<?php
namespace care\models;
use care\core\Model;
use DateTime;
use Exception;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use PDO;
use PDOException;

class PatientModel extends Model {
    public function __construct() {
        parent::__construct();
    }

    public function validate($data) {
        $errors = [];

        if (empty($data['name'])) {
            $errors['name'] = 'Name is required';
        }

        if (empty($data['username'])) {
            $errors['username'] = 'Username is required';
        }

        if (empty($data['phone'])) {
            $errors['phone'] = 'Phone is required';
        }

        if (empty($data['email'])) {
            $errors['email'] = 'Email is required';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format';
        }

        if (empty($data['password'])) {
            $errors['password'] = 'Password is required';
        }



        return $errors;
    }

    public function checkDuplicate($email, $username) {
        $sql = "SELECT COUNT(*) FROM tbl_patient WHERE email = :email OR username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email, ':username' => $username]);
        return $stmt->fetchColumn() > 0;
    }
    public function createPatient($data) {
        $sql = "INSERT INTO tbl_patient (name, username, phone, email, password) 
                VALUES (:name, :username, :phone, :email, :password)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':name' => $data['name'],
            ':username' => $data['username'],
            ':phone' => $data['phone'],
            ':email' => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_DEFAULT)

        ]);

    }

    public function authenticPatient($email, $password)
    {
        $stmt = $this->db->prepare('SELECT * FROM tbl_patient WHERE email = ?');
        $stmt->execute([$email]);
        $patient = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify password
        if ($patient && password_verify($password, $patient['password'])) {
            return $patient;
        }

        return false;

    }
    public function getSpecialtiesWithDoctorCount() {
        $sql = "SELECT id, specialty, avatar, COUNT(*) AS doctor_count 
                FROM tbl_doctor 
                GROUP BY specialty, avatar,id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDoctorsBySpecialty($specialty) {
        $sql = "SELECT * FROM tbl_doctor WHERE specialty = :specialty";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':specialty' => $specialty]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getDoctorExperience($doctor_id) {
        $sql = "SELECT * FROM tbl_experience WHERE doctor_id = :doctor_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':doctor_id' => $doctor_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDoctorEducation($doctor_id) {
        $sql = "SELECT * FROM tbl_education WHERE doctor_id = :doctor_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':doctor_id' => $doctor_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @throws \Exception
     */
    public function calculateExperience($experience)
    {
        $totalExperience = 0;

        foreach ($experience as $exp) {
            $periodFrom = new DateTime($exp['period_from']);
            $periodTo = new DateTime($exp['period_to']);
            $interval = $periodFrom->diff($periodTo);
            $years = $interval->y;
            $totalExperience += $years;
        }

        return $totalExperience;
    }

    public function getScheduleByDoctorId($doctorId) {
        $query = "SELECT * FROM tbl_schedule WHERE doctor_id = :doctor_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':doctor_id', $doctorId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDoctorById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM tbl_doctor WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);

    }

    /**
     * @throws \Google\Service\Exception
     */
    public function createGoogleMeetLink($eventData, $appointmentId) {
        $client = new Google_Client();
        $client->setClientId('1038568461771-d07ah3gare7rt4hbh1qb11ib5kda4cbu.apps.googleusercontent.com');
        $client->setClientSecret('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');
        $client->setRedirectUri('http://localhost/care/patient/waiting.php');
        $client->addScope(Google_Service_Calendar::CALENDAR);

        // Create and encode the state parameter with the appointment ID
        $stateData = ['appointment_id' => $appointmentId];
        $state = base64_encode(json_encode($stateData));
        error_log("Set state: " . $state);

        // Construct the authorization URL manually for debugging
        $baseAuthUrl = 'https://accounts.google.com/o/oauth2/v2/auth';
        $params = [
            'response_type' => 'code',
            'access_type' => 'online',
            'client_id' => '1038568461771-d07ah3gare7rt4hbh1qb11ib5kda4cbu.apps.googleusercontent.com',
            'redirect_uri' => 'http://localhost/care/patient/waiting.php',
            'scope' => 'https://www.googleapis.com/auth/calendar',
            'approval_prompt' => 'auto',
            'state' => $state,  // Don't URL encode here; http_build_query will handle it
        ];

        $authUrl = $baseAuthUrl . '?' . http_build_query($params);
        error_log("Auth URL: " . $authUrl);

        // Check if we have an access token in the session
        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
            $client->setAccessToken($_SESSION['access_token']);
        } else {
            if (!isset($_GET['code'])) {
                // Redirect to Google for authorization
                header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
                exit();
            } else {
                // Exchange the authorization code for an access token
                $client->fetchAccessTokenWithAuthCode($_GET['code']);
                $_SESSION['access_token'] = $client->getAccessToken();
            }
        }

        $service = new Google_Service_Calendar($client);

        // Debug log to inspect eventData['attendees']
        error_log("Debug: Attendees Data - " . print_r($eventData['attendees'], true));

        // Prepare attendees array
        $attendees = [];
        foreach ($eventData['attendees'] as $email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $attendees[] = ['email' => $email];
            }
        }
// Add the organizer to the attendees array
        if (isset($eventData['organizer'])) {
            $attendees[] = ['email' => $eventData['organizer']['email'], 'organizer' => true];
        }
        // Debug log to inspect the final attendees array
        error_log("Debug: Prepared Attendees - " . print_r($attendees, true));

        // Create the event object
        $event = new Google_Service_Calendar_Event(array(
            'summary' => $eventData['summary'],
            'location' => 'Online Meeting',
            'description' => $eventData['description'],
            'start' => array(
                'dateTime' => $eventData['start'],
                'timeZone' => 'America/Los_Angeles',
            ),
            'end' => array(
                'dateTime' => $eventData['end'],
                'timeZone' => 'America/Los_Angeles',
            ),
            'conferenceData' => array(
                'createRequest' => array(
                    'requestId' => uniqid(),
                    'conferenceSolutionKey' => array(
                        'type' => 'hangoutsMeet',
                    ),
                ),
            ),
            'attendees' => $attendees,
            'reminders' => array(
                'useDefault' => TRUE,
            ),
        ));

        $calendarId = 'primary';

        try {
            // Insert the event into the calendar
            $event = $service->events->insert($calendarId, $event, array(
                'conferenceDataVersion' => 1,
            ));
            // Return the Hangout link for the meeting
            $meetLink = $event->getHangoutLink();
            return $meetLink;
        } catch (Exception $e) {
            // Log any errors during event insertion
            error_log("Error Inserting Event: " . $e->getMessage());
            return null;
        }
    }

    /**
     * @throws Exception
     */
    public function checkExistingAppointment($patientId, $doctorId, $day) {
        // Prepare the SQL query
        $query = "SELECT COUNT(*) AS count FROM tbl_appointment WHERE patient_id = :patientId AND doctor_id = :doctorId AND day = :day";
        $stmt = $this->db->prepare($query);

        // Check if the statement was prepared successfully
        if ($stmt === false) {
            throw new Exception('Failed to prepare SQL query:');
        }

        // Bind the parameters
        $stmt->bindValue(':patientId', $patientId, PDO::PARAM_INT);
        $stmt->bindValue(':doctorId', $doctorId, PDO::PARAM_INT);
        $stmt->bindValue(':day', $day, PDO::PARAM_STR);

        // Execute the query
        if (!$stmt->execute()) {
            throw new Exception('Failed to execute SQL query: ' . implode(' ', $stmt->errorInfo()));
        }

        // Fetch the result
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $count = $row ? $row['count'] : 0;

        // Return whether the count is greater than 0
        return $count > 0;
    }




    public function createAppointment($doctorId, $patientId, $day, $hospitalName, $startTime, $endTime, $fees, $problem) {
        $query = "INSERT INTO tbl_appointment (doctor_id, patient_id, day, hospital_name, start_time, end_time, fees, problem, status)
              VALUES (:doctorId, :patientId, :day, :hospitalName, :startTime, :endTime, :fees, :problem, 0)";
        $stmt = $this->db->prepare($query);

        $stmt->bindValue(':doctorId', $doctorId, PDO::PARAM_INT);
        $stmt->bindValue(':patientId', $patientId, PDO::PARAM_INT);
        $stmt->bindValue(':day', $day, PDO::PARAM_STR);
        $stmt->bindValue(':hospitalName', $hospitalName, PDO::PARAM_STR);
        $stmt->bindValue(':startTime', $startTime, PDO::PARAM_STR);
        $stmt->bindValue(':endTime', $endTime, PDO::PARAM_STR);
        $stmt->bindValue(':fees', $fees, PDO::PARAM_INT);
        $stmt->bindValue(':problem', $problem, PDO::PARAM_STR);

        $stmt->execute();
        return $this->db->lastInsertId();
    }

    public function createPayment($appointmentId, $doctorId, $patientId, $imageResult, $fees)
    {
        // Example implementation
        try {
            $query = "INSERT INTO tbl_payment (appointment_id, doctor_id, patient_id, payment_screenshot, amount, status) VALUES (:appointment_id, :doctor_id, :patient_id, :payment_screenshot, :amount, :status)";
            $stmt = $this->db->prepare($query);

            // Bind values to the parameters
            $stmt->bindValue(':appointment_id', $appointmentId, PDO::PARAM_INT);
            $stmt->bindValue(':doctor_id', $doctorId, PDO::PARAM_INT);
            $stmt->bindValue(':patient_id', $patientId, PDO::PARAM_INT);
            $stmt->bindValue(':payment_screenshot', $imageResult, PDO::PARAM_STR);
            $stmt->bindValue(':amount', $fees, PDO::PARAM_STR); // Use PDO::PARAM_STR for monetary values
            $stmt->bindValue(':status', 1, PDO::PARAM_INT); // Set status to 1


            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error creating payment: " . $e->getMessage());
            return false;
        }
    }

    /**
     * @throws Exception
     */
    public function updateAppointmentStatus($appointmentId)
    {
        $query = "UPDATE tbl_appointment SET status = 1 WHERE id = :id";
        $stmt = $this->db->prepare($query);

        if ($stmt === false) {
            $errorInfo = $this->db->errorInfo($query);
            error_log('Failed to prepare SQL query: ' . implode(' ', $errorInfo));
            throw new Exception('Failed to prepare SQL query: ' . implode(' ', $errorInfo));
        }

        $stmt->bindParam(':id', $appointmentId, PDO::PARAM_INT);

        if (!$stmt->execute()) {
            $errorInfo = $stmt->errorInfo();
            error_log('Failed to execute SQL query: ' . implode(' ', $errorInfo));
            throw new Exception('Failed to execute SQL query: ' . implode(' ', $errorInfo));
        }

        // Check if any rows were affected
        if ($stmt->rowCount() > 0) {
            return true; // Successfully updated
        } else {
            error_log('No rows affected. Appointment might already be updated.');
            return false; // No changes made, possibly already updated
        }
    }



    public function uploadScreenshot($file)
    {
        $uploadDir = 'public/uploads/'; // Directory where files will be uploaded
        $allowedFileTypes = ['jpg', 'jpeg', 'png', 'gif']; // Allowed file extensions
        $maxFileSize = 5 * 1024 * 1024; // Maximum file size (5 MB)

        // Initialize response array
        $response = [
            'success' => false,
            'path' => '',
            'error' => ''
        ];

        // Check if the file was uploaded
        if (isset($file['tmp_name']) && !empty($file['tmp_name'])) {
            $fileTmpPath = $file['tmp_name'];
            $fileName = basename($file['name']);
            $fileSize = $file['size'];
            $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            // Check file size
            if ($fileSize > $maxFileSize) {
                $response['error'] = 'File size exceeds the maximum limit of 5 MB.';
                return $response;
            }

            // Check file extension
            if (!in_array($fileType, $allowedFileTypes)) {
                $response['error'] = 'Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.';
                return $response;
            }

            // Generate a unique file name to avoid conflicts
            $newFileName = uniqid() . '.' . $fileType;
            $uploadPath = $uploadDir . $newFileName;

            // Move the file to the target directory
            if (move_uploaded_file($fileTmpPath, $uploadPath)) {
                $response['success'] = true;
                $response['path'] = $uploadPath;
                error_log("File uploaded successfully: " . $uploadPath);
            } else {
                $response['error'] = 'Failed to move uploaded file.';
            }
        } else {
            $response['error'] = 'No file was uploaded.';
        }

        // Debugging information
        error_log("Upload response: " . print_r($response, true));

        return $response;
    }

    /**
     * @throws Exception
     */
    public function getAppointmentByIdAndPatientId($appointmentId, $patientId)
    {
        $query = "SELECT * FROM tbl_appointment WHERE id = :id AND patient_id  = :patient_id";
        $stmt = $this->db->prepare($query);

        if ($stmt === false) {
            throw new Exception('Failed to prepare SQL query: ' . implode(' ', $this->db->errorInfo()));
        }

        // Bind parameters
        $stmt->bindParam(':id', $appointmentId, PDO::PARAM_INT);
        $stmt->bindParam(':patient_id', $patientId, PDO::PARAM_INT);

        if (!$stmt->execute()) {
            throw new Exception('Failed to execute SQL query: ' . implode(' ', $stmt->errorInfo()));
        }

        $appointment = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($appointment === false) {
            throw new Exception('Appointment not found for the given patient.');
        }

        $stmt->closeCursor();

        return $appointment;
    }
    public function getNextWaitingAppointment()
    {
        $query = "SELECT * FROM tbl_appointment WHERE status = 0 ORDER BY scheduled_time ASC LIMIT 1";
        $statement = $this->db->prepare($query);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function updateAppointmentWithMeetLink($appointmentId, $meetLink)
    {
        $query = "UPDATE tbl_appointment SET status = 1, meeting_link = :meet_link WHERE id = :id";
        $statement = $this->db->prepare($query);
        $statement->bindParam(':meet_link', $meetLink);
        $statement->bindParam(':id', $appointmentId);
        return $statement->execute();
    }
    public function countPendingAppointments($doctorId) {
        $sql = "SELECT COUNT(*) as count FROM tbl_appointment WHERE doctor_id = ? AND (appointment_status = 'waiting' OR appointment_status = 'started')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$doctorId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    public function getAppointmentsByPatientId(int $patientId): array {
        $sql = "SELECT a.id, p.name AS patient_name, d.full_name AS doctor_name, 
            a.status, a.start_time, a.end_time, a.meeting_link, a.appointment_status
            FROM tbl_appointment AS a
            JOIN tbl_patient AS p ON a.patient_id = p.id
            JOIN tbl_doctor AS d ON a.doctor_id = d.id
            WHERE a.patient_id = :patient_id";
        $params = [':patient_id' => $patientId];
        return $this->db->fetchAll($sql, $params);
    }
    public function getAppointmentById($id)
    {
        $sql = "SELECT * FROM tbl_appointment WHERE id = :id";
        $params = [':id' => $id];

        try {
            $result = $this->db->fetchOne($sql, $params);
            return $result ? $result : null; // Return result or null if not found
        } catch (Exception $e) {
            error_log("Error in DoctorModel::getAppointmentById - " . $e->getMessage());
            throw new Exception("Error retrieving appointment.");
        }
    }
    public function deleteAppointmentByID($id): bool
    {
        $appointmentID = (int)$id;

        // Prepare the SQL statement to delete the doctor
        $sql = "DELETE FROM tbl_appointment WHERE id = :appointmentID";

        try {
            // Prepare the statement
            $stmt = $this->db->prepare($sql);

            // Bind the parameter
            $stmt->bindParam(':appointmentID', $appointmentID, PDO::PARAM_INT);

            // Execute the statement
            if ($stmt->execute()) {
                return true;
            } else {
                return false; // Deletion failed
            }
        } catch (PDOException $e) {
            // Handle exceptions (e.g., log the error)
            error_log("Error deleting appointment: " . $e->getMessage());
            return false; // Deletion failed
        }
    }
}