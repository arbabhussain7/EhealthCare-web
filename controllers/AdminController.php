<?php
namespace care\controllers;
use care\core\View;

use care\core\Controller;
use care\models\AdminModel;
use care\models\DoctorModel;
use Exception;
class AdminController extends Controller
{
    private $doctorModel;
    public function __construct()
    {
        parent::__construct(); // Call the parent constructor

        session_start(); // Start the session on every request
//        $this->doctorModel = new AdminModel();

    }
    private function isAuthenticated(): bool
    {
        // Check if the user is logged in and has an admin role
        return isset($_SESSION['admin_email']) && isset($_SESSION['admin_role']) && $_SESSION['admin_role'] === 'admin';
    }
    public function index()
    {
        try {
            if (!$this->isAuthenticated()) {
                // Redirect to login page if not authenticated
                header('Location: login');
                exit();
            }
            $model = $this->loadModel('AdminModel');
            $data = [
                'totalDoctors' => $model->getTotalDoctors(),
                'totalPatients' => $model->getTotalPatients(),
                'totalAppointments' => $model->getTotalAppointments(),
                'appointments' => $model->getAppointments()
            ];
            View::render('home/admin-home', [
                'title' => 'Admin Dashboard',
                'some_data' => 'Welcome to Health Care portal',
                'data' => $data
            ],"admin");
        } catch (Exception $e){
            error_log("Error in AdminController::index - " . $e->getMessage());
            echo "An error occurred while loading the dashboard. Please try again later.";
        }

    }


    public function adminLogin()
    {
        try {
            if ($this->isAuthenticated()) {
                // Redirect to dashboard if already logged in
                header('Location: dashboard');
                exit();
            }


            $errors = [];
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adminLogin'])) {

                $adminEmail = $_POST['adminEmail'] ?? '';
                $adminPassword = $_POST['adminPassword'] ?? '';
                // Validate input
                if (empty($adminEmail) || !filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
                    $errors['adminEmail'] = 'Valid email address is required.';
                }
                if (empty($adminPassword)) {
                    $errors['adminPassword'] = 'Password is required.';
                }

                if (empty($errors)) {


                    try {

                        $model = $this->loadModel('AdminModel');
                        if ($model->login($adminEmail, $adminPassword)) {
                            $_SESSION['admin_email'] = $adminEmail;
                            $_SESSION['admin_role'] = 'admin'; // Assuming 'admin' role, set based on your implementation
                            header('Location: dashboard');
                            exit();
                        } else {
                            $errors['general'] = 'Invalid email or password.';
                        }
                    } catch (Exception $e) {
                        error_log("Error in AdminController::adminLogin - " . $e->getMessage());
                        $errors['general'] = 'An error occurred during login. Please try again later.';
                    }
                }
            }

            View::render('home/admin-login', [
                'title' => 'Admin Login',
                'some_data' => 'Please log in to access the admin dashboard',
                'errors' => $errors ?? null
            ],"admin");
        } catch (Exception $e){
            error_log("Error in AdminController::adminLogin - " . $e->getMessage());
            echo "An error occurred while loading the login page. Please try again later.";
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

    public function showDoctor()
    {
        try {

            $this->doctorModel = new AdminModel();

            if (!$this->isAuthenticated()) {
                // Redirect to login page if not authenticated
                header('Location: login');
                exit();
            }
            $doctors = $this->doctorModel->getAllDoctors();
            try {
                if (isset($_GET['doctor_id']) && is_numeric($_GET['doctor_id'])) {
                    $doctor_id = intval($_GET['doctor_id']);
                    if ($this->doctorModel->doesDoctorExist($doctor_id)) {
                        // Proceed with deletion
                        if ($this->doctorModel->deleteDoctor($doctor_id)) {
                            if ($this->doctorModel->deleteDoctor($doctor_id)) {
                                echo "<script>alert('Doctor successfully deleted.');</script>";
                           header('Location: dashboard');
                            } else {
                                echo "<script>alert('Error deleting doctor.');</script>";
                            }
                        } else {
                            echo "<script>alert('Doctor not found.');</script>";
                        }
                    }
                }
            } catch (Exception $e) {
                error_log("Error in AdminController::showDoctor - " . $e->getMessage());
                throw new Exception(" Doctor not found.".$e->getMessage());

            }

            // Pass data to the view
            View::render('home/admin-doctors', [
                'title' => 'Doctors List',
                'doctors' => $doctors,  // Include the doctors data in the view
                'some_data' => 'Welcome to Health Care portal'
            ], "admin");
        } catch (Exception $e){
            error_log("Error in AdminController::showDoctor - " . $e->getMessage());
            echo "An error occurred while loading the dashboard. Please try again later.";
        }
    }

    public function doctorProfile()
    {
        try {
            if (!$this->isAuthenticated()) {
                // Redirect to login page if not authenticated
                header('Location: login');
                exit();
            }
            $this->doctorModel = new AdminModel();

            $doctor = null;
            $education = null;
            $experience = null;
            $error_message = null;
            if(isset($_GET['id']) && !empty($_GET['id'])) {
                $doctor_id = intval($_GET['id']);

                if ($this->doctorModel->doesDoctorExist($doctor_id)) {
                    $doctor = $this->doctorModel->getDoctorById($doctor_id);
                 echo   $education = $this->doctorModel->getEducationByDoctorId($doctor_id);
                    $experience = $this->doctorModel->getExperienceByDoctorId($doctor_id);
                } else {
                    // Handle the case where the doctor does not exist
                    $error_message = "Doctor not found.";
                }
            }


            View::render('home/doctor-profile', [
                'title' => 'Doctor Profile',
                'doctor' => $doctor,
                'education' => $education,
                'experience' => $experience,
                'error_message' => $error_message,
                'some_data' => 'Welcome to Health Care portal'
            ], "admin");
        } catch (Exception $e){
            error_log("Error in AdminController::doctorProfile - " . $e->getMessage());
            echo "An error occurred while loading the dashboard. Please try again later.";
        }

    }

    public function updateDoctorProfile()
    {
        try {
            if (!$this->isAuthenticated()) {
                // Redirect to login page if not authenticated
                header('Location: login');
                exit();
            }

            $this->doctorModel = new AdminModel();
            $doctor = null;
            $education = null;
            $experience = null;
            $errors = [];
            $error_message = null;

            if (isset($_GET['id']) && !empty($_GET['id'])) {
                $doctor_id = intval($_GET['id']);

                if ($this->doctorModel->doesDoctorExist($doctor_id)) {
                    $doctor = $this->doctorModel->getDoctorById($doctor_id);
                    $education = $this->doctorModel->getEducationByDoctorId($doctor_id);
                    $experience = $this->doctorModel->getExperienceByDoctorId($doctor_id);
                   // $errors["general"] =print_r($experience);
                    $experienceId = $experience["id"];
                    $eduId = $education["id"];
$oldAvatar = $doctor["avatar"];

                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateDoctor'])) {

                        $errors = [];

                        if (!empty($_FILES['avatar']['name'])) {
                            $uploadResult = $this->doctorModel->uploadAvatar($_FILES['avatar']);
                            if (isset($uploadResult['error']) && !empty($uploadResult['error'])) {
                                $errors['avatar'] = $uploadResult['error'];
                                error_log("Avatar upload error: " . $uploadResult['error']);
                            } else {
                                $imageResult = $uploadResult['path'];
                                error_log("Avatar uploaded successfully: " . $imageResult);
                            }
                        } else {
                            $imageResult = $oldAvatar;
                            error_log("Using previous avatar: " . $imageResult);
                        }
                        $doctorData = [
                            'full_name' => $_POST['full_name'] ?? '',
                            'date_of_birth' => $_POST['date_of_birth'] ?? '',
                            'gender' => $_POST['gender'] ?? '',
                            'password' => $_POST['password'] ?? '',
                            'address' => $_POST['address'] ?? '',
                            'state' => $_POST['state'] ?? '',
                            'country' => $_POST['country'] ?? '',
                            'postal_code' => $_POST['postal_code'] ?? '',
                            'phone_no' => $_POST['phone_no'] ?? '',
                            'avatar' => $imageResult ?? '',
                            'doctor_id' => $doctor_id ?? '' // Assuming doctor_id is hidden in the form
                        ];
                       // error_log(var_dump($doctorData));
                       // error_log("Existing values: " . print_r($doctorData, true));


                        // Collect education data
                        $educationData = [
                            'institution' => $_POST['institution'] ?? '',
                            'subject' => $_POST['subject'] ?? '',
                            'starting_date' => $_POST['starting_date'] ?? '',
                            'complete_date' => $_POST['complete_date'] ?? '',
                            'degree' => $_POST['degree'] ?? '',
                            'grade' => $_POST['grade'] ?? '',
                            'id' => $eduId ?? ''
                        ];

                        // Collect experience data
                        $experienceData = [
                            'company_name' => $_POST['company_name'] ?? '',
                            'company_location' => $_POST['company_location'] ?? '',
                            'job_position' => $_POST['job_position'] ?? '',
                            'period_from' => $_POST['period_from'] ?? '',
                            'period_to' => $_POST['period_to'] ?? '',
                            'id' => $experienceId ?? '' // Assuming doctor_id is hidden in the form
                        ];

                        // Validate data
                        $errors = array_merge(
                            $this->validateEducationData($educationData),
                            $this->validateExperienceData($experienceData)
                        );

                        if (empty($errors)) {
                            $doctorUpdateSuccess = $this->doctorModel->updateDoctor($doctorData);
                            $educationUpdateSuccess = $this->doctorModel->updateEducation($educationData);
                            $experienceUpdateSuccess = $this->doctorModel->updateExperience($experienceData);

                            if ($doctorUpdateSuccess && $educationUpdateSuccess && $experienceUpdateSuccess) {
                                // Redirect or show success message
                                header('Location: doctors.php');
                                exit();
                            } else {
                                $errors['general'] = 'There was a problem updating the doctor profile.';
                            }
                        }
                    }
                } else {
                    $error_message = "Doctor not found.";
                }

                // Pass data to the view
                View::render('home/update-doc-profile', [
                    'title' => 'Update Doctor Profile',
                    'doctor' => $doctor,
                    'education' => $education,
                    'experience' => $experience,
                    'errors' => $errors,
                    'error_message' => $error_message,
                    'some_data' => 'Update the doctor profile'
                ], "admin");

            } else {
                // Handle case where doctor ID is not provided
                echo "Invalid request. Doctor ID is missing.";
            }



        }
        catch (Exception $e) {
                error_log("Error in AdminController::updateDoctorProfile - " . $e->getMessage());
                echo "An error occurred while updating the profile. Please try again later.";

        }
    }



    private function validateEducationData($data)
    {
        $errors = [];
        if (empty($data['institution'])) {
            $errors['institution'] = 'Institution is required.';
        }
        if (empty($data['subject'])) {
            $errors['subject'] = 'Subject is required.';
        }
        if (empty($data['starting_date'])) {
            $errors['starting_date'] = 'Starting date is required.';
        }
        if (empty($data['complete_date'])) {
            $errors['complete_date'] = 'Complete date is required.';
        }
        if (empty($data['degree'])) {
            $errors['degree'] = 'Degree is required.';
        }
        if (empty($data['grade'])) {
            $errors['grade'] = 'Grade is required.';
        }
        return $errors;
    }

    private function validateExperienceData($data)
    {
        $errors = [];
        if (empty($data['company_name'])) {
            $errors['company_name'] = 'Company name is required.';
        }
        if (empty($data['company_location'])) {
            $errors['company_location'] = 'Company location is required.';
        }
        if (empty($data['job_position'])) {
            $errors['job_position'] = 'Job position is required.';
        }
        if (empty($data['period_from'])) {
            $errors['period_from'] = 'Period from is required.';
        }
        if (empty($data['period_to'])) {
            $errors['period_to'] = 'Period to is required.';
        }
        return $errors;
    }

    public function addDoctorSchedule()
    {
        try {
            if (!$this->isAuthenticated()) {
                // Redirect to login page if not authenticated
                header('Location: login');
                exit();
            }
            $model = $this->loadModel('AdminModel');
            $doctors =$model->getAllDoctors();
            $errors = [];
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['createSchedule'])) {
                $doctorId = isset($_POST['doctor_id']) ? trim($_POST['doctor_id']) : '';
                $availableDays = isset($_POST['available_days']) ? $_POST['available_days'] : [];
                $hospitalName = isset($_POST['hospital_name']) ? trim($_POST['hospital_name']) : '';
                $fee = isset($_POST['fee']) ? trim($_POST['fee']) : '';
                $startTime = isset($_POST['start_time']) ? trim($_POST['start_time']) : '';
                $endTime = isset($_POST['end_time']) ? trim($_POST['end_time']) : '';
                $message = isset($_POST['message']) ? trim($_POST['message']) : '';
                $status = isset($_POST['status']) ? trim($_POST['status']) : '';
                if (empty($doctorId)) {
                    $errors['doctor_id'] = 'Doctor ID is required.';
                }
                if (empty($availableDays)) {
                    $errors['available_days'] = 'At least one available day must be selected.';
                }
                if (empty($hospitalName)) {
                    $errors['hospital_name'] = 'Hospital name is required.';
                }
                if (empty($fee) || !is_numeric($fee)) {
                    $errors['fee'] = 'Valid doctor fee is required.';
                }
                if (empty($startTime) || !preg_match('/^\d{2}:\d{2}$/', $startTime)) {
                    $errors['start_time'] = 'Valid start time is required.';
                }
                if (empty($endTime) || !preg_match('/^\d{2}:\d{2}$/', $endTime)) {
                    $errors['end_time'] = 'Valid end time is required.';
                }
                if (empty($status)) {
                    $errors['status'] = 'Schedule status is required.';
                }
                if (empty($errors)) {
                    $result = $model->createSchedule($doctorId, $availableDays, $hospitalName, $fee, $startTime, $endTime, $message, $status);

                    if ($result) {
                        // Redirect or show success message
                        header('Location: index');
                        exit();
                    } else {
                        $errors['general'] = 'An error occurred while creating the schedule. Please try again.';
                    }
                }
            }


            View::render('home/add-schedule', [
                'title' => 'Add Doctor Schedule',
                'some_data' => 'Welcome to Health Care portal',
                'doctors' => $doctors,
                'errors' => $errors
            ],"admin");
        } catch (Exception $e){
            error_log("Error in AdminController::index - " . $e->getMessage());
            echo "An error occurred while loading the dashboard. Please try again later.";
        }
    }

    public function showPatients()
    {

        try {
            if (!$this->isAuthenticated()) {
                // Redirect to login page if not authenticated
                header('Location: login');
                exit();
            }
            $errors[]=null;
            $adminModel = $this->loadModel('AdminModel');
$patient = $adminModel->getAllPatient();
if(isset($_GET["id"]) && !empty($_GET["id"])){
    $id = intval($_GET["id"]);
    $deletePatient = $adminModel->deletePatientByID($id);
if($deletePatient){
    header('Location: patients');
    exit();
}
}

            View::render('home/admin-patient', [
                'title' => 'Show Patient',
                'some_data' => 'Welcome to Health Care portal',
                'patients' => $patient,
                'errors' => $errors
            ],"admin");
        } catch (Exception $e){
            error_log("Error in AdminController::index - " . $e->getMessage());
            echo "An error occurred while loading the dashboard. Please try again later.";
        }
    }

    public function showAppointments()
    {
        try {
            if (!$this->isAuthenticated()) {
                // Redirect to login page if not authenticated
                header('Location: login');
                exit();
            }
            $errors[]=null;
            $adminModel = $this->loadModel('AdminModel');
            $appointments= $adminModel->getAllAppointment();
            if(isset($_GET["id"]) && !empty($_GET["id"])){
                $id = intval($_GET["id"]);
                $deleteApp = $adminModel->deleteappointmentByID($id);
                if($deleteApp){
                    header('Location: appointments');
                    exit();
                }
            }

            View::render('home/admin-appointment', [
                'title' => 'Show appointment',
                'some_data' => 'Welcome to Health Care portal',
                'appointments' => $appointments,

            ],"admin");
        } catch (Exception $e){
            error_log("Error in AdminController::index - " . $e->getMessage());
            echo "An error occurred while loading the dashboard. Please try again later.";
        }
    }

    public function showSchedule()
    {
        try {
            if (!$this->isAuthenticated()) {
                // Redirect to login page if not authenticated
                header('Location: login');
                exit();
            }
            $model = $this->loadModel('AdminModel');
            $schedules = $model->getAllSchedules();
            if(isset($_GET['id'])){
                $id = intval($_GET["id"]);
                $sch = $model->getScheduleById($id);
                if($sch){
                    $deleteSchedule = $model->deleteScheduleByID($id);
                    if($deleteSchedule){
                        header('Location: schedule');
                        exit();
                    } else {
                        View::render('home/admin-schedule', [
                            'title' => 'Admin Dashboard',
                            'some_data' => 'Welcome to Health Care portal',
                            'error' => "Something went wrong. Please try again later.",
                        ], "admin");

                    }
                }
            }
            View::render('home/admin-schedule', [
                'title' => 'Admin Dashboard',
                'some_data' => 'Welcome to Health Care portal',
                'data' => $schedules,
            ], "admin");


        } catch (Exception $e){
            error_log("Error in AdminController::index - " . $e->getMessage());
            echo "An error occurred while loading the dashboard. Please try again later.";
        }
    }

    public function showPayment()
    {
        try {
            if (!$this->isAuthenticated()) {
                // Redirect to login page if not authenticated
                header('Location: login');
                exit();
            }

            // Load the model
            $model = $this->loadModel('AdminModel');

            // Fetch payments
            $payments = $model->getAllPayments();

            // Render the view with payment data
            View::render('home/admin-payment', [
                'title' => 'Admin Dashboard',
                'some_data' => 'Welcome to Health Care portal',
                'payments' => $payments
            ], "admin");

        } catch (Exception $e) {
            error_log("Error in AdminController::showPayments - " . $e->getMessage());
            echo "An error occurred while loading the payments. Please try again later.";
        }
    }

}