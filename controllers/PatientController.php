<?php
namespace care\controllers;

use care\core\Controller;
use care\core\View;
use Exception;

class PatientController extends Controller
{
    public function __construct()
    {
        parent::__construct(); // Call the parent constructor

        session_start(); // Start the session on every request


    }

    private function isAuthenticated(): bool
    {
        return isset($_SESSION['patient_email']) && isset($_SESSION['patient_role']) && $_SESSION['patient_role'] === 'patient';
    }
    public function index()
    {
        try {



            if (!$this->isAuthenticated()) {
                // Redirect to dashboard if already logged in
                header('Location: login');
                exit();
            }

            $patientModel = $this->loadModel("PatientModel");

            // Initialize error message
            $errorMessage = null;

            // Fetch appointments for the authenticated doctor
            $appointments = $patientModel->getAppointmentsByPatientId($_SESSION["patient_id"]);

            if(isset($_GET["id"]) && is_numeric($_GET["id"])){
                $id = $_GET["id"];
                $appointment = $patientModel->getAppointmentById($id);
                if($appointment){
                    $deleteAppt = $patientModel->deleteAppointmentById($id);
                    if(!$deleteAppt){
                        $errorMessage = "Failed to Delete appointment.";

                    } else {
                        header('Location: home');
                        exit();
                    }
                }
            }

            View::render('home/patient-home', [
                'title' => 'Patient Dashboard',
                'some_data' => 'Welcome to Health Care portal',
                'appointments' => $appointments,
                'error' => $errorMessage,
            ]);
        } catch (Exception $e){
            error_log("Error in PatientController::index - " . $e->getMessage());
            echo "An error occurred while loading the home page. Please try again later.";
        }

    }

}