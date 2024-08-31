<?php
namespace care\models;
use care\core\Model;
use DateTime;
use Exception;
use PDO;
use PDOException;

class AdminModel extends Model
{
    /**
     * @throws Exception
     */
    public function __construct() {
        parent::__construct();
    }
    public function login($email, $password): bool
    {
        // Hash the password before comparing (assuming you're storing hashed passwords)
        $hashedPassword = md5($password);

        $sql = 'SELECT * FROM tbl_admin WHERE email = :email AND password = :password';
        $params = [
            ':email' => $email,
            ':password' => $hashedPassword
        ];

        $result = $this->db->fetchOne($sql, $params);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Fetch all doctors.
     *
     * @return array|false The list of doctors as an associative array, or false on failure.
     * @throws Exception
     */
    public function getAllDoctors()
    {
        $sql = 'SELECT * FROM tbl_doctor';

        try {
            // Fetch all doctor records
            $doctors = $this->db->fetchAll($sql);

            // Check if the result is empty
            if ($doctors === false) {
                throw new Exception('Failed to retrieve doctors.');
            }

            return $doctors;
        } catch (Exception $e) {
            // Log error and rethrow exception
            error_log("Error fetching all doctors: " . $e->getMessage());
            throw new Exception("An error occurred while fetching the doctors. Please try again later.");
        }
    }
    public function doesDoctorExist($doctorId): bool
    {
        // Ensure $doctorId is an integer
        $doctorId = (int)$doctorId;

        // Prepare the SQL statement to check existence
        $sql = "SELECT COUNT(*) FROM tbl_doctor WHERE id = :doctorId";

        try {
            // Prepare the statement
            $stmt = $this->db->prepare($sql);

            // Bind the parameter
            $stmt->bindParam(':doctorId', $doctorId, PDO::PARAM_INT);

            // Execute the statement
            $stmt->execute();

            // Fetch the count
            $count = $stmt->fetchColumn();

            // Return true if doctor exists, false otherwise
            return $count > 0;
        } catch (PDOException $e) {
            // Handle exceptions (e.g., log the error)
            error_log("Error checking doctor existence: " . $e->getMessage());
            return false;
        }
    }
    public function deleteDoctor($doctorId): bool
    {
        // Ensure $doctorId is an integer to prevent SQL injection
        $doctorId = (int)$doctorId;

        // Prepare the SQL statement to delete the doctor
        $sql = "DELETE FROM tbl_doctor WHERE id = :doctorId";

        try {
            // Prepare the statement
            $stmt = $this->db->prepare($sql);

            // Bind the parameter
            $stmt->bindParam(':doctorId', $doctorId, PDO::PARAM_INT);

            // Execute the statement
            if ($stmt->execute()) {
                // Optionally, handle deletion of associated files (e.g., avatar)
                // Fetch the current avatar path
                $currentAvatar = $this->getDoctorAvatar($doctorId);
                if ($currentAvatar && file_exists($currentAvatar)) {
                    unlink($currentAvatar); // Delete the file
                }

                return true; // Deletion successful
            } else {
                return false; // Deletion failed
            }
        } catch (PDOException $e) {
            // Handle exceptions (e.g., log the error)
            error_log("Error deleting doctor: " . $e->getMessage());
            return false; // Deletion failed
        }
    }
    private function getDoctorAvatar($doctorId): ?string
    {
        // Prepare the SQL statement to get the avatar path
        $sql = "SELECT avatar FROM tbl_doctor WHERE id = :doctorId";

        try {
            // Prepare the statement
            $stmt = $this->db->prepare($sql);

            // Bind the parameter
            $stmt->bindParam(':doctorId', $doctorId, PDO::PARAM_INT);

            // Execute the statement
            $stmt->execute();

            // Fetch the result
            $doctor = $stmt->fetch(PDO::FETCH_ASSOC);

            // Return the avatar path or null if not found
            return $doctor ? $doctor['avatar'] : null;
        } catch (PDOException $e) {
            // Handle exceptions (e.g., log the error)
            error_log("Error retrieving doctor avatar: " . $e->getMessage());
            return null;
        }
    }

    public function getDoctorById(int $doctor_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM tbl_doctor WHERE id = ?");
        $stmt->execute([$doctor_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getEducationByDoctorId(int $doctor_id)
    {

        $query = "SELECT * FROM tbl_education WHERE doctor_id = :doctor_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':doctor_id', $doctor_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

       if($result>0){
           return $result;
       } else {
           error_log("Error retrieving education: " . $stmt->errorInfo());
       }
    }

    public function getExperienceByDoctorId(int $id)
    {

        $query = "SELECT * FROM tbl_experience WHERE doctor_id = :doctor_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':doctor_id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? $result : [];
    }
    function validateDoctorData($data) {
        $errors = [];

        // Check if required fields are present
        $requiredFields = ['full_name', 'date_of_birth', 'gender', 'address', 'state', 'country', 'postal_code', 'phone_no','password'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . ' is required.';
            }
        }

        // Validate date_of_birth format (assuming YYYY-MM-DD)
        if (!empty($data['date_of_birth']) && !DateTime::createFromFormat('Y-m-d', $data['date_of_birth'])) {
            $errors['date_of_birth'] = 'Invalid date format. Use YYYY-MM-DD.';
        }

        // Validate phone number format (e.g., only digits and optional +)
        if (!empty($data['phone_no']) && !preg_match('/^\+?\d+$/', $data['phone_no'])) {
            $errors['phone_no'] = 'Invalid phone number format.';
        }

        // Other validations as necessary
        return $errors;
    }

    public function uploadAvatar($file)
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


        return $response;
    }


    public function updateDoctor(array $doctorData) {
        $sql = "UPDATE tbl_doctor SET 
                full_name = :full_name,
                date_of_birth = :date_of_birth,
                gender = :gender,
                address = :address,
                state = :state,
                country = :country,
                postal_code = :postal_code,
                phone_no = :phone_no,
                password = :password,
                avatar = :avatar,
                status=:status
            WHERE id = :doctor_id";

        $stmt = $this->db->prepare($sql);
        if (!$stmt->execute([
            ':full_name' => $doctorData['full_name'],
            ':date_of_birth' => $doctorData['date_of_birth'],
            ':gender' => $doctorData['gender'],
            ':address' => $doctorData['address'],
            ':state' => $doctorData['state'],
            ':country' => $doctorData['country'],
            ':postal_code' => $doctorData['postal_code'],
            ':phone_no' => $doctorData['phone_no'],
            ':password' =>  password_hash($doctorData['password'], PASSWORD_DEFAULT),
            ':avatar' => $doctorData['avatar'] ?? null,
            ':status' => 1,
            ':doctor_id' => $doctorData['doctor_id']
        ])) {
            $errorInfo = $stmt->errorInfo();
            echo "Error updating doctor: ". $errorInfo[2];
            return false;
        }
        return true;
    }






    /**
     * @throws Exception
     */
    public function updateEducation(array $educationData)
    {
        $education = $this->getEducationById($educationData['id']);
        if (!$education) {
            throw new Exception('The education record with the given id does not exist.');
        }
        $sql = "UPDATE tbl_education SET 
            institution = :institution,
            subject = :subject,
            starting_date = :starting_date,
            complete_date = :complete_date,
            degree = :degree,
            grade = :grade
        WHERE id = :id";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':institution' => $educationData['institution'],
                ':subject' => $educationData['subject'],
                ':starting_date' => $educationData['starting_date'],
                ':complete_date' => $educationData['complete_date'],
                ':degree' => $educationData['degree'],
                ':grade' => $educationData['grade'],
                ':id' => $educationData['id']
            ]);



            return true;
        } catch (PDOException $e) {
            error_log("Error updating education: " . $e->getMessage());
            throw new Exception("An error occurred while updating the education. Please try again later.");
        }
    }


    /**
     * @throws Exception
     */
    public function updateExperience(array $experienceData)
    {

        $experience = $this->getExpByID($experienceData['id']);
        if (!$experience) {
            throw new Exception('The Experience record with the given id does not exist.');
        }
        $sql = "UPDATE tbl_experience SET 
                company_name = :company_name,
                location = :company_location,
                job_position = :job_position,
                period_from = :period_from,
                period_to = :period_to
            WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':company_name' => $experienceData['company_name'],
            ':company_location' => $experienceData['company_location'],
            ':job_position' => $experienceData['job_position'],
            ':period_from' => $experienceData['period_from'],
            ':period_to' => $experienceData['period_to'],
            ':id' => $experienceData['id']
        ]);
        return true;
    }

    private function getEducationById($id)
    {
        $query = "SELECT * FROM tbl_education WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if($result>0){
            return $result;
        } else {
            error_log("Error retrieving education: " . $stmt->errorInfo());
        }
    }

    private function getExpByID($id)
    {
        $query = "SELECT * FROM tbl_experience WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if($result>0){
            return $result;
        } else {
            error_log("Error retrieving Experience: " . $stmt->errorInfo());
        }
    }
    public function createSchedule($doctorId, $availableDays, $hospitalName, $fee, $startTime, $endTime, $message, $status) {
        // Prepare SQL statement
        $sql = "INSERT INTO tbl_schedule (doctor_id, available_days, hospital_name, fees, start_time, end_time, message, status)
                VALUES (:doctor_id, :available_days, :hospital_name, :fee, :start_time, :end_time, :message, :status)";

        // Prepare the statement
        $stmt = $this->db->prepare($sql);

        // Convert the array of available days to a string
        $availableDaysStr = implode(',', $availableDays);

        // Bind parameters
        $stmt->bindParam(':doctor_id', $doctorId, PDO::PARAM_INT);
        $stmt->bindParam(':available_days', $availableDaysStr, PDO::PARAM_STR);
        $stmt->bindParam(':hospital_name', $hospitalName, PDO::PARAM_STR);
        $stmt->bindParam(':fee', $fee, PDO::PARAM_INT);
        $stmt->bindParam(':start_time', $startTime, PDO::PARAM_STR);
        $stmt->bindParam(':end_time', $endTime, PDO::PARAM_STR);
        $stmt->bindParam(':message', $message, PDO::PARAM_STR);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);

        // Execute the statement
        return $stmt->execute();
    }

    /**
     * @throws Exception
     */
    public function getAllPatient()
    {
        $sql = 'SELECT * FROM tbl_patient';

        try {
            // Fetch all doctor records
            $patient = $this->db->fetchAll($sql);

            // Check if the result is empty
            if ($patient === false) {
                throw new Exception('Failed to retrieve Patient.');
            }

            return $patient;
        } catch (Exception $e) {
            // Log error and rethrow exception
            error_log("Error fetching all patient: " . $e->getMessage());
            throw new Exception("An error occurred while fetching the patient. Please try again later.");
        }
    }
    public function deletePatientByID($id): bool
    {
        // Ensure $doctorId is an integer to prevent SQL injection
        $patientID = (int)$id;

        // Prepare the SQL statement to delete the doctor
        $sql = "DELETE FROM tbl_patient WHERE id = :patientID";

        try {
            // Prepare the statement
            $stmt = $this->db->prepare($sql);

            // Bind the parameter
            $stmt->bindParam(':patientID', $patientID, PDO::PARAM_INT);

            // Execute the statement
            if ($stmt->execute()) {



                return true; // Deletion successful
            } else {
                return false; // Deletion failed
            }
        } catch (PDOException $e) {
            // Handle exceptions (e.g., log the error)
            error_log("Error deleting patient: " . $e->getMessage());
            return false; // Deletion failed
        }
    }
    public function getAllAppointment()
    {

        $query = "SELECT 
                a.id, a.patient_id, a.doctor_id, a.day, a.start_time, a.end_time,
                a.name, a.phone, a.problem, a.hospital_name, a.fees, a.meeting_link, a.status,
                p.name AS patient_name,
                d.full_name AS doctor_name
            FROM tbl_appointment a
            JOIN tbl_patient p ON a.patient_id = p.id
            JOIN tbl_doctor d ON a.doctor_id = d.id
        ";
        $appointments = $this->db->fetchAll($query);

        // Fetch all results as an associative array


        return $appointments;
    }
    public function deleteappointmentByID($id): bool
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

    public function getTotalDoctors() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM tbl_doctor");
        return $stmt->fetch()['total'];
    }

    public function getTotalPatients() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM tbl_patient");
        return $stmt->fetch()['total'];
    }

    public function getTotalAppointments() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM tbl_appointment");
        return $stmt->fetch()['total'];
    }
    public function getAppointments() {
        $query = "
            SELECT 
                a.id, 
                a.day, 
                a.start_time, 
                a.end_time, 
                a.problem, 
                a.meeting_link, 
                a.appointment_status,
                a.hospital_name,
                d.full_name AS doctor_name, 
                p.name AS patient_name
            FROM tbl_appointment a
            JOIN tbl_doctor d ON a.doctor_id = d.id
            JOIN tbl_patient p ON a.patient_id = p.id
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @throws Exception
     */
    public function getAllSchedules() {
        $sql = "SELECT 
                s.id, s.doctor_id, s.available_days, s.hospital_name, s.fees, s.start_time, s.end_time, s.message, s.status,
                d.full_name AS doctor_name
            FROM tbl_schedule s
            JOIN tbl_doctor d ON s.doctor_id = d.id";

        try {
            $schedules = $this->db->fetchAll($sql);
            if ($schedules === false) {
                throw new Exception('Failed to retrieve schedules.');
            }
            return $schedules;
        } catch (Exception $e) {
            error_log("Error fetching schedules: " . $e->getMessage());
            throw new Exception("An error occurred while fetching the schedules. Please try again later.");
        }
    }

    /**
     * @throws Exception
     */
    public function getScheduleById($id)
    {
        $sql = "SELECT * FROM tbl_schedule WHERE id = :id";
        $params = [':id' => $id];

        try {
            $result = $this->db->fetchOne($sql, $params);
            return $result ? $result : null; // Return result or null if not found
        } catch (Exception $e) {
            error_log("Error in AdminModel::getScheduleById - " . $e->getMessage());
            throw new Exception("Error retrieving Schedule.");
        }
    }

    public function deleteScheduleByID($id)
    {
        $sql = "DELETE FROM tbl_schedule WHERE id = :id";

        try {
            // Prepare the statement
            $stmt = $this->db->prepare($sql);

            // Bind the parameter
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            // Execute the statement
            if ($stmt->execute()) {
                return true;
            } else {
                return false; // Deletion failed
            }
        } catch (PDOException $e) {
            // Handle exceptions (e.g., log the error)
            error_log("Error deleting sch: " . $e->getMessage());
            return false; // Deletion failed
        }

    }

    public function getAllPayments()
    {

        $query = "
            SELECT 
                p.id,
                p.appointment_id, 
                p.doctor_id, 
                p.patient_id, 
                p.payment_screenshot, 
                p.status, 
                p.amount, 
                p.created_at, 
                pt.name AS patient_name
            FROM 
                tbl_payment p
            JOIN 
                tbl_patient pt ON p.patient_id = pt.id
        ";

        $result = $this->db->query($query);
        return $result->fetchAll(PDO::FETCH_ASSOC);

    }

}
