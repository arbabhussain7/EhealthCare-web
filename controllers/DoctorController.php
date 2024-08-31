<?php

namespace care\controllers;
use care\core\Controller;
use care\core\View;
use Exception;


class  DoctorController extends Controller
{
    public function __construct()
    {
        parent::__construct(); 

        session_start(); 

    }
    private function isAuthenticated(): bool
    {
        // Check if the user is logged in and has an admin role
        return isset($_SESSION['doctor_email']) && isset($_SESSION['doctor_role']) && $_SESSION['doctor_role'] === 'doctor';
    }
    public function index()
    {
        try {
            if (!$this->isAuthenticated()) {
                // Redirect to login page if not authenticated
                header('Location: login');
                exit();
            }

            $doctorModel = $this->loadModel("DoctorModel");

            // Initialize error message
            $errorMessage = null;

            // Fetch appointments for the authenticated doctor
            $appointments = $doctorModel->getAppointmentsByDoctorId($_SESSION["doctor_id"]);

            // Check if an ID is provided and is numeric
            if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
                $id = intval($_GET["id"]); // Sanitize ID

                // Retrieve the appointment by ID
                $appointment = $doctorModel->getAppointmentById($id);

                if ($appointment) {
                    // Check if the appointment's current status is 'waiting'
                    if ($appointment['appointment_status'] === 'waiting') {
                        // Update the status to 'started'
                        $updateSuccess = $doctorModel->updateAppointmentStatus($id, 'started');

                        if (!$updateSuccess) {
                            // Set error message if update fails
                            $errorMessage = "Failed to update appointment status from waiting to started.";
                        } else {
                            header('Location: index');
                            exit();
                        }
                    } else if($appointment['appointment_status'] === 'started') {

                        $updateSuccess =   $doctorModel->updateAppointmentStatus($id, 'completed');
                        if (!$updateSuccess) {
                            // Set error message if update fails
                            $errorMessage = "Failed to update appointment status from started to completed.";
                        } else {
                            header('Location: index');
                            exit();
                        }
                    }
                } else {
                    // Set error message if the appointment does not exist
                    $errorMessage = "No appointment found with ID: " . htmlspecialchars($id);
                }
            }

            // Render the view with appointments and error message
            View::render('home/doctor-home', [
                'title' => 'Doctor Dashboard',
                'some_data' => 'Welcome to Health Care portal',
                'appointments' => $appointments, // Pass appointments to the view
                'error' => $errorMessage // Pass error message to the view
            ]);
        } catch (Exception $e) {
            // Log the error and set user-friendly error message
            error_log("Error in DoctorController::index - " . $e->getMessage());
            $errorMessage = "An error occurred while loading the dashboard. Please try again later.";

            // Render the view with error message
            View::render('home/doctor-home', [
                'title' => 'Doctor Dashboard',
                'some_data' => 'Welcome to Health Care portal',
                'appointments' => [], // Pass empty appointments in case of error
                'error' => $errorMessage // Pass error message to the view
            ]);
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
    public function doctorLogin()
    {
        try {
            if ($this->isAuthenticated()) {
                // Redirect to login page if not authenticated
                header('Location: index');
                exit();
            }

            $error = '';
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['doctorLogin'])) {
                $email = trim($_POST['doctorEmail']);
                $password = trim($_POST['doctorPassword']);
                $doctorModel = $this->loadModel("DoctorModel");
                if (filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($password)) {
                    $doctor = $doctorModel->authenticDoctor($email, $password);
if($doctor){
    $_SESSION['doctor_id'] = $doctor['id'];
    $_SESSION['doctor_specialty'] = $doctor['specialty'];
    $_SESSION['doctor_name'] = $doctor['full_name'];
    $_SESSION['doctor_email'] = $doctor['email'];
    $_SESSION['doctor_role'] = 'doctor';

    // Redirect to a secure page
    header('Location: index');
    exit();
}else {
    $error = 'Invalid email or password';
}
                } else {
                    $error = 'Please enter a valid email and password';




                }



                }


                View::render('home/doctor-login', [
                'title' => 'Doctor Login',
                'some_data' => 'Welcome to Health Care portal',
                    'errors'=>$error
            ]);
        } catch (Exception $e){
            error_log("Error in DoctorController::index - " . $e->getMessage());
            echo "An error occurred while loading the dashboard. Please try again later.";
        }
    }
}