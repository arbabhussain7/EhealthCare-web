<?php
namespace care\controllers;
use care\core\View;

use care\core\Controller;
use Exception;
use Google_Client;
use Google_Service_Calendar;

class HomeController extends Controller {
    public function __construct()
    {
        parent::__construct(); // Call the parent constructor

        session_start(); // Start the session on every request


    }

    private function isAuthenticated(): bool
    {
        return isset($_SESSION['patient_email']) && isset($_SESSION['patient_role']) && $_SESSION['patient_role'] === 'patient';
    }
    public function index() {


        try {
            View::render('home/index', [
                'title' => 'Home Page',
                'some_data' => 'Welcome to Health Care portal'
            ]);
        } catch (Exception $e){
            error_log("Error in HomeController::index - " . $e->getMessage());
           echo "An error occurred while loading the home page. Please try again later.";
        }
    }

    /**
     * @throws Exception
     */
    public function doctorRegistration()
    {
        try {
            $model = $this->loadModel('DoctorModel');


            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $errors = [];
                $data = [
                    'full_name' => $_POST['full_name'] ?? '',
                    'phone_no' => $_POST['phone_no'] ?? '',
                    'email' => $_POST['email'] ?? '',
                    'city' => $_POST['city'] ?? '',
                    'pmc_no' => $_POST['pmc_no'] ?? '',
                    'specialty' => $_POST['specialty'] ?? '',
                    'gender' => $_POST['gender'] ?? '',
                ];

                // Validate form data
                if (empty($data['full_name'])) $errors['full_name'] = 'Full name is required';
                if (empty($data['phone_no'])) $errors['phone_no'] = 'Phone number is required';
                if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Valid email is required';
                if (empty($data['city'])) $errors['city'] = 'City is required';
                if (empty($data['pmc_no'])) $errors['pmc_no'] = 'PMC No. is required';
                if (empty($data['specialty'])) $errors['specialty'] = 'Specialty is required';
                if (empty($data['gender'])) $errors['gender'] = 'Gender is required';

                // If no errors, attempt to register doctor
                if (empty($errors)) {
                    try {
                        if ($model->registerDoctor($data)) {
                            // Redirect or display success message
                            header('Location: index');
                            exit();
                        } else {
                            $errors['general'] = 'Failed to register doctor. Please try again.';
                        }
                    } catch (Exception $e) {
                        // Handle specific exceptions for duplicates
                        if ($e->getMessage() === 'Email already exists.') {
                            $errors['email'] = 'This email is already registered.';
                        } elseif ($e->getMessage() === 'PMC No. already exists.') {
                            $errors['pmc_no'] = 'This PMC No. is already registered.';
                        } else {
                            $errors['general'] = 'An error occurred while processing your request. Please try again later.';
                        }
                        error_log("Error in HomeController::doctorRegistration - " . $e->getMessage());
                    }
                }

                // Render the registration view with errors
                View::render('home/doctor-registration', [
                    'title' => 'Doctor Registration',
                    'some_data' => 'Doctor Registration',
                    'errors' => $errors,
                    'data' => $data
                ]);

            } else {
                View::render('home/doctor-registration', [
                    'title' => 'Doctor Registration',
                    'some_data' => 'Doctor Registration'
                ]);
            }
        } catch (Exception $e) {
            error_log("Error loading DoctorModel: " . $e->getMessage());
            // You can also log the stack trace if you want
            // error_log($e->getTraceAsString());
            // ...
        }
    }


    public function patientRegistration()
    {
        try {

            $patientModel  =$this->loadModel('PatientModel');
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registerPatient'])) {
                $data = [
                    'name' => $_POST['name'] ?? '',
                    'username' => $_POST['username'] ?? '',
                    'phone' => $_POST['phone'] ?? '',
                    'email' => $_POST['email'] ?? '',
                    'password' => $_POST['password'] ?? ''

                ];
                $errors = $patientModel->validate($data);
                if ($patientModel->checkDuplicate($data['email'], $data['username'])) {
                    $errors['duplicate'] = 'Email or Username already exists';
                }
                if (empty($errors)) {
                    // Create the patient
                    error_log("Before creating patient");
                    if ($patientModel->createPatient($data)) {
                        error_log("Patient created successfully");
                        header('Location: login');
                        exit;
                    } else {
                        error_log("Error creating patient");
                        $errors['general'] = 'Error saving data';
                    }
                }
                View::render('home/patient-register', [
                    'title' => 'Patient Registration',
                    'some_data' => 'Welcome to Health Care portal',
                    'errors' => $errors,
                    'formData' => $data
                ]);


            }else {
                // Initial page load
                View::render('home/patient-register', [
                    'title' => 'Patient Registration',
                    'some_data' => 'Welcome to Health Care portal'
                ]);
            }

        } catch (Exception $e){
            error_log("Error in HomeController::patientRegistration - " . $e->getMessage());
            echo "An error occurred while processing your request. Please try again later.";
        }

    }

    /**
     * @throws Exception
     */
    public function loginPatient()
    {
        try{
//            if ($this->isAuthenticated()) {
//                // Redirect to dashboard if already logged in
//                header('Location: dashboard');
//                exit();
//            }
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['patientLogin'])) {
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $patientModel = $this->loadModel('PatientModel');

            if (filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($password)) {
                $patient = $patientModel->authenticPatient($email, $password);
                if ($patient) {
                  // session_start();
                    $_SESSION['patient_id'] = $patient['id'];
                    $_SESSION['patient_phone'] = $patient['phone'];
                    $_SESSION['patient_name'] = $patient['name'];
                    $_SESSION['patient_email'] = $patient['email'];
                    $_SESSION['patient_role'] = 'patient';

                    // Redirect to a secure page
                    header('Location: dashboard');
                    exit();
                } else {
                    $error = 'Invalid email or password';
                }
            } else {
                $error = 'Please enter a valid email and password';
            }
        }

        View::render('home/patient-login', [
            'title' => 'Patient Login',
            'some_data' => 'Welcome to Health Care portal',
            'errors'=>$error
        ]);
    }catch (Exception $e){
            error_log("Error in HomeController::loginPatient - " . $e->getMessage());
            throw  new Exception($e->getMessage());
        }
    }

    public function patientDashboard()
    {
        try {
            if (!$this->isAuthenticated()) {
                // Redirect to dashboard if already logged in
                header('Location: login');
                exit();
            }
            View::render('home/patient-dashboard', [
                'title' => 'Patient Dashboard',
                'some_data' => 'Welcome to Health Care portal'
            ]);
        } catch (Exception $e){
            error_log("Error in HomeController::index - " . $e->getMessage());
            echo "An error occurred while loading the home page. Please try again later.";
        }
    }
    public function logout()
    {
        // Clear session data and log out the user
        session_unset();
        session_destroy();
        header('Location: login');
        exit();
    }

    public function showDocList()
    {
        try {
            if (!$this->isAuthenticated()) {
                // Redirect to dashboard if already logged in
                header('Location: login');
                exit();
            }
            $patientModel = $this->loadModel('PatientModel');
            $specialties = $patientModel->getSpecialtiesWithDoctorCount();
            $data = [
                'title' => 'Patient Dashboard',
                'some_data' => 'Welcome to Health Care portal',
                'specialties' => $specialties
            ];
            if (empty($specialties)) {
                $data['error'] = 'No doctors found in the database.';
            }
            View::render('home/doctor-list', $data);
        } catch (Exception $e){
            error_log("Error in HomeController::index - " . $e->getMessage());
            echo "An error occurred while loading the home page. Please try again later.";
        }
    }

    public function showDocDetail()
    {
        try {
            // Check if the user is authenticated
            if (!$this->isAuthenticated()) {
                // Redirect to login if not authenticated
                header('Location: login');
                exit();
            }

            // Initialize specialty and search term
            $specialty = '';
            $search = '';

            if (isset($_GET['specialty']) && !empty($_GET['specialty'])) {
                $specialty = urldecode($_GET['specialty']);
            }

            if (isset($_GET['search']) && !empty($_GET['search'])) {
                $search = urldecode($_GET['search']);
            }

            $patientModel = $this->loadModel('PatientModel');

            // Get doctors based on specialty
            $doctors = [];
            if (!empty($specialty)) {
                $doctors = $patientModel->getDoctorsBySpecialty($specialty);
            }

            // Filter doctors by search term if provided
            if (!empty($search)) {
                $doctors = array_filter($doctors, function($doctor) use ($search) {
                    return stripos($doctor['full_name'], $search) !== false;
                });
            }

            // Process each doctor to include education and experience
            foreach ($doctors as &$doctor) {
                $doctor['education'] = $patientModel->getDoctorEducation($doctor['id']);
                $doctor['experience'] = $patientModel->getDoctorExperience($doctor['id']);
                $doctor['total_experience'] = $patientModel->calculateExperience($doctor['experience']);
                $doctor['schedule'] = $patientModel->getScheduleByDoctorId($doctor['id']); // Fetch schedule
            }

            // Prepare data for the view
            $data = [
                'title' => 'Doctors By Specialty',
                'some_data' => 'Welcome to Health Care portal',
                'doctors' => $doctors,
                'specialty' => $specialty
            ];

            // Handle case where no doctors are found
            if (empty($doctors)) {
                $data['error'] = 'No doctors found for the search term.';
            }

            // Render the view with the prepared data
            View::render('home/doctor-detail', $data);
        } catch (Exception $e) {
            // Log and display an error message
            error_log("Error in HomeController::showDocDetail - " . $e->getMessage());
            echo "An error occurred while loading the doctor details. Please try again later.";
        }
    }

    public function doctorConfirmation()
    {
        try {
            if (!$this->isAuthenticated()) {
                // Redirect to login if not authenticated
                header('Location: login');
                exit();
            }

            $errors = [];
            $doctor = null;
            $education = null;
            $experience = null;
            $schedule = null;
            $totalExperience = null;
            $imageResult = '';

            if (isset($_GET["id"]) && !empty($_GET["id"])) {
                $doctorId = intval(trim($_GET["id"]));

                $patientModel = $this->loadModel('PatientModel');

                // Fetch doctor details and schedule
                $schedule = $patientModel->getScheduleByDoctorId($doctorId);
                $doctor = $patientModel->getDoctorById($doctorId);
                $education = $patientModel->getDoctorEducation($doctorId);
                $experience = $patientModel->getDoctorExperience($doctorId);
                $totalExperience = $patientModel->calculateExperience($experience);
                $pendingCount = $patientModel->countPendingAppointments($doctorId);

                // Register appointment
                if (isset($_POST["bookAppt"]) && !empty($_POST["bookAppt"])) {
                    $patientName = trim($_POST["patientName"]);
                    $patientPhone = trim($_POST["patientPhone"]);
                    $patientProblem = trim($_POST["patientProblem"]);
                    $day = trim($_POST["availableDays"]); // Changed to match form input name
                    $hospitalName = trim($_POST["hospitalName"]);
                    $startTime = trim($_POST["startTime"]);
                    $endTime = trim($_POST["endTime"]);
                    $fees = trim($_POST["fees"]);
                    $patientId = $_SESSION['patient_id'];

                    // Validate input
                    if (empty($patientName)) {
                        $errors[] = "Patient Name is required.";
                    }
                    if (empty($patientPhone)) {
                        $errors[] = "Patient Phone No. is required.";
                    }

                    // Check for existing appointment
                    if (empty($errors)) {
                        if ($patientModel->checkExistingAppointment($patientId, $doctorId, $day)) {
                            $errors[] = "An appointment with the same doctor on this day already exists.";
                        }
                    }

                    // Handle file upload
                    if (!empty($_FILES['paymentProof']['name'])) {
                        $uploadResult = $patientModel->uploadScreenshot($_FILES['paymentProof']);
                        if (isset($uploadResult['error']) && !empty($uploadResult['error'])) {
                            $errors[] = $uploadResult['error'];
                        } else {
                            $imageResult = $uploadResult['path'];
                        }
                    }

                    // If no errors, proceed with data insertion
                    if (empty($errors)) {
                        $nextAvailableTime = $this->calculateNextAvailableTime($pendingCount, $startTime, $endTime);
                        if (isset($nextAvailableTime['error'])) {
                            // Add error message from calculateNextAvailableTime
                            $errors[] = $nextAvailableTime['error'];
                        } else {
                            try {
                                $appointmentId = $patientModel->createAppointment($doctorId, $patientId, $day, $hospitalName, $nextAvailableTime['start'], $nextAvailableTime['end'], $fees, $patientProblem);

                                if ($appointmentId) {
                                    // Create payment record
                                    if ($patientModel->createPayment($appointmentId, $doctorId, $patientId, $imageResult, $fees)) {
                                        // Update appointment status
                                        header('Location: waiting.php?appointment_id=' . $appointmentId);

                                    } else {
                                        $errors[] = "Failed to create payment record.";
                                    }
                                } else {
                                    $errors[] = "Failed to create appointment.";
                                }
                            } catch (Exception $e) {
                                $errors[] = "An error occurred while creating the appointment. Please try again.";
                            }
                        }
                    }
                }

                if ($doctor) {
                    View::render('home/doctor-confirmation', [
                        'title' => 'Doctor Confirmation',
                        'doctor' => $doctor,
                        'education' => $education,
                        'experience' => $experience,
                        'totalExperience' => $totalExperience,
                        'schedule' => $schedule,
                        'errors' => $errors
                    ]);
                } else {
                    // Redirect to doctor list if doctor is not found
                    header('Location: doctor-list');
                    exit();
                }
            } else {
                // Redirect to doctor list if no ID is provided
                header('Location: doctor-list');
                exit();
            }
        } catch (Exception $e) {
            error_log("Error in HomeController::doctorConfirmation - " . $e->getMessage());
            echo "An error occurred while processing your request. Please try again later.";
        }
    }


    /**
     * @throws Exception
     */
    public function waitingRoom()
    {

        try {


            if (!$this->isAuthenticated()) {
                // Redirect to login if not authenticated
                header('Location: login');
                exit();
            }
            $patientModel = $this->loadModel('PatientModel');
            $data[] = null;
            if (isset($_GET['state'])) {
                $state = json_decode(base64_decode($_GET['state']), true);
                $appointmentId = isset($state['appointment_id']) ? $state['appointment_id'] : null;
                error_log("Decoded appointment_id: " . $appointmentId);
                $appointmentId = intval($appointmentId);
                $patientId = $_SESSION['patient_id'];
                $appointment = $patientModel->getAppointmentByIdAndPatientId($appointmentId, $patientId);
                $doctorId = $appointment["doctor_id"];
                $doctor = $patientModel->getDoctorById($doctorId);
                if ($appointment) {
                    $eventData = array(
                        'summary' => 'Virtual Consultation with ' . $doctor["full_name"],
                        'location' => 'Online Meeting',
                        'description' => 'Virtual consultation with ',
                        'start' => date('c', strtotime($appointment['start_time'])), // start time from appointment
                        'end' => date('c', strtotime($appointment['end_time'])), // end time from appointment
                        'attendees' => array(
                            array('email' => $_SESSION['patient_email']),
                            array('email' => $doctor["email"]),
                        ),
                        'conferenceData' => array(
                            'createRequest' => array(
                                'requestId' => uniqid(),
                                'conferenceSolutionKey' => array(
                                    'type' => 'hangoutsMeet'
                                )
                            )
                        )

                    );

                    // Call the createGoogleMeetLink function and pass the meeting link to the view
                    $meetLink = $patientModel->createGoogleMeetLink($eventData, $appointmentId);
                    if ($meetLink) {
                        $data['meet_link'] = $meetLink;
                        $patientModel->updateAppointmentWithMeetLink($appointmentId, $meetLink);
                    } else {
                        $data['error'] = 'Unable to create a meet link.';
                    }
                    $data['appointment'] = $appointment;
                    if ($appointment['status'] == 1) {
                        $data['status_message'] = 'Your appointment is now active.';
                    } else {
                        $data['status_message'] = 'Your appointment is not yet active. Please wait until your number is called.';
                    }
                } else {
                    $data['error'] = 'Invalid appointment ID.';
                }
            } else {

                if (isset($_GET["appointment_id"]) && !empty($_GET["appointment_id"])) {
                    $appointmentId = intval($_GET['appointment_id']);
                    $patientId = $_SESSION['patient_id'];
                    $appointment = $patientModel->getAppointmentByIdAndPatientId($appointmentId, $patientId);
                    $doctorId = $appointment["doctor_id"];
                    $doctor = $patientModel->getDoctorById($doctorId);
                    if ($appointment) {
                        $eventData = array(
                            'summary' => 'Virtual Consultation with ' . $doctor["full_name"],
                            'location' => 'Online Meeting',
                            'description' => 'Virtual consultation with ',
                            'start' => date('c', strtotime($appointment['start_time'])), // start time from appointment
                            'end' => date('c', strtotime($appointment['end_time'])), // end time from appointment
                            'attendees' => array(
                                array('email' => $_SESSION['patient_email']),
                                array('email' => $doctor["email"]),
                            ),
                            'organizer' => array(
                                'email' => $doctor["email"],
                            ),
                            'conferenceData' => array(
                                'createRequest' => array(
                                    'requestId' => uniqid(),
                                    'conferenceSolutionKey' => array(
                                        'type' => 'hangoutsMeet'
                                    )
                                )
                            )

                        );

                        // Call the createGoogleMeetLink function and pass the meeting link to the view
                        $meetLink = $patientModel->createGoogleMeetLink($eventData, $appointmentId);
                        if ($meetLink) {
                            $data['meet_link'] = $meetLink;
                            $patientModel->updateAppointmentWithMeetLink($appointmentId, $meetLink);
                        } else {
                            $data['error'] = 'Unable to create a meet link.';
                        }
                        $data['appointment'] = $appointment;
                        if ($appointment['status'] == 1) {
                            $data['status_message'] = 'Your appointment is now active.';
                        } else {
                            $data['status_message'] = 'Your appointment is not yet active. Please wait until your number is called.';
                        }
                    } else {
                        $data['error'] = 'Invalid appointment ID.';
                    }
                } else {
                    $data['error'] = 'No appointment ID provided.';
                }
}


//            View::render('home/waiting-room', [
//                'title' => 'Home Page',
//                'some_data' => 'Welcome to Health Care portal',
//                'data' => $data
//            ]);

            header('Location: home');
            exit();
        } catch (Exception $e){
            error_log("Error in HomeController::waitingRoom - " . $e->getMessage());
            $data['error'] = 'Unknown error occurred while processing your request. Please try again later.';
            View::render('home/waiting-room', [
                'title' => 'Waiting Room',
                'data' => $data
            ]);
            // echo "An error occurred while processing your request. Please try again later.";
        }
    }


    /**
     * @throws Exception
     */
    public function checkAppointmentStatus()
    {
        try {
            if (!$this->isAuthenticated()) {
                // Redirect to login if not authenticated
                header('Location: login');
                exit();
            }
            header('Content-Type: application/json');
            if (isset($_GET['appointment_id']) && !empty($_GET['appointment_id'])) {

                $appointmentId = intval($_GET['appointment_id']);
                $patientModel = $this->loadModel('PatientModel');
                try {
                     $patientId = $_SESSION["patient_id"];

                    $appointment = $patientModel->getAppointmentByIdAndPatientId($appointmentId,$patientId);

                    if ($appointment) {
                        echo json_encode(['status' => $appointment['status']]);
                    } else {
                        echo json_encode(['status' => 0]); // Assuming 0 means invalid or not found
                    }
                }
                catch (Exception $e) {
                        error_log("Error in check_appointment_status.php - " . $e->getMessage());
                        echo json_encode(['status' => 0]);

                }

            } else {
        echo json_encode(['status' => 0]);
    }
        } catch (Exception $e){
            error_log("Error in HomeController::waitingRoom - " . $e->getMessage());
            throw  new Exception($e->getMessage());

        }
    }
    private function calculateNextAvailableTime($pendingCount, $startTime, $endTime) {
        $appointmentDuration = 1200; // 20 minutes in seconds

        $startTimestamp = strtotime($startTime); // User-provided start time
        $endTimestamp = strtotime($endTime);     // User-provided end time

        if ($startTimestamp === false || $endTimestamp === false || $startTimestamp >= $endTimestamp) {
            // Invalid times or end time before start time
            return ['error' => 'Invalid start or end time.'];
        }

        // Calculate the time of the next available slot
        $nextAvailableTime = $startTimestamp + ($pendingCount * $appointmentDuration);

        if ($nextAvailableTime >= $endTimestamp) {
            // If the calculated time is beyond the provided end time
            return ['error' => 'No available appointment slots within the specified time range.'];
        }

        return [
            'start' => date("H:i:s", $nextAvailableTime),
            'end' => date("H:i:s", $nextAvailableTime + $appointmentDuration)
        ];
    }

}
