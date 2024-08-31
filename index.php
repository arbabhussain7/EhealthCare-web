<?php
// Enable error reporting
//use care\core\Router;
//
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
//ini_set('log_errors', 1);
//ini_set('error_log', __DIR__ . '/logs/error.log');
//define('ROOT', __DIR__ . '/care');
//// Autoload core classes
//spl_autoload_register(/**
// * @throws Exception
// */ function ($class) {
//    $prefix = 'care\\';
//    $base_dir = __DIR__ . '/care/';
//
//    // Check if class belongs to namespace
//    $len = strlen($prefix);
//    if (strncmp($prefix, $class, $len) !== 0) {
//        return;
//    }
//
//    // Replace namespace prefix with base directory
//    $relative_class = substr($class, $len);
//    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
//
//    // Include the class file
//    if (file_exists($file)) {
//        require_once $file;
//    } else {
//        throw new Exception("Class $class not found.");
//    }
//});
//
//$router = new Router();
//
//// Define routes
//$router->add('index', function() {
//    $controller = new HomeController();
//    $controller->index();
//});
//$router->add('doctor-registration', function() {
//    $controller = new HomeController();
//    $controller->doctorRegistration();
//});
//
//// Define default route
//$router->setDefault(function() {
//    $controller = new HomeController();
//    $controller->index(); // This will render your homepage layout
//});
//
//$uri = trim($_SERVER['REQUEST_URI'], '/');
//
//// Remove the leading 'care' segment
//$uri = preg_replace('/^care\//', '', $uri);
//
//
//
//$router->dispatch($uri);


// Enable error reporting
use care\controllers\DoctorController;

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/error.log');
define('ROOT', __DIR__ . '/care');
require "config/config.php";

// Autoload core classes
//spl_autoload_register(/**
// * @throws Exception
// */ function ($class) {
//    // Define the namespace prefix and base directory
//    $prefix = 'care\\';
//    $base_dir = __DIR__ . '/care/';
//
//    // Log the class being autoloaded
//    error_log("Attempting to autoload class: $class");
//
//    // Check if the class belongs to the namespace
//    $len = strlen($prefix);
//    if (strncmp($prefix, $class, $len) !== 0) {
//        return;
//    }
//
//    // Replace namespace prefix with base directory
//    $relative_class = substr($class, $len);
//    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
//
//    // Log the file path being checked
//    error_log("Checking file path: $file");
//
//    // Include the class file if it exists
//    if (file_exists($file)) {
//        require_once $file;
//        error_log("Class file loaded: $file");
//    } else {
//        error_log("Class file not found: $file");
//        throw new Exception("Class $class not found.");
//    }
//});


require_once __DIR__ . '/vendor/autoload.php';




$router = new \care\core\Router();

// Define routes
$router->add('', function() {
    $controller = new \care\controllers\HomeController();
    $controller->index();
});
$router->add('index', function() {
    $controller = new \care\controllers\HomeController();
    $controller->index();
});
$router->add('doctor-registration', function() {
    $controller = new \care\controllers\HomeController();
    $controller->doctorRegistration();
});
$router->add('admin/login', function() {
    $controller = new \care\controllers\AdminController();
    $controller->adminLogin();
});
$router->add('admin/dashboard', function() {
    $controller = new \care\controllers\AdminController();
    $controller->index();
});
$router->add('admin/index', function() {
    $controller = new \care\controllers\AdminController();
    $controller->index();
});
$router->add('admin/doctors', function() {
    $controller = new \care\controllers\AdminController();
    $controller->showDoctor();
});
$router->add('admin/doctors.php', function() {
    $controller = new \care\controllers\AdminController();
    $controller->showDoctor();
});
$router->add('admin/patients', function() {
    $controller = new \care\controllers\AdminController();
    $controller->showPatients();
});
$router->add('admin/patients.php', function() {
    $controller = new \care\controllers\AdminController();
    $controller->showPatients();
});
$router->add('admin/appointments', function() {
    $controller = new \care\controllers\AdminController();
    $controller->showAppointments();
});
$router->add('admin/appointments.php', function() {
    $controller = new \care\controllers\AdminController();
    $controller->showAppointments();
});
$router->add('admin/logout', function() {
    $controller = new \care\controllers\AdminController();
    $controller->logout();
});
$router->add('admin/doctor-profile', function() {
    $controller = new \care\controllers\AdminController();
    $controller->doctorProfile();
});
$router->add('admin/doctor-profile.php', function() {
    $controller = new \care\controllers\AdminController();
    $controller->doctorProfile();
});
$router->add('admin/edit-doctor-profile', function() {
    $controller = new \care\controllers\AdminController();
    $controller->updateDoctorProfile();
});
$router->add('admin/edit-doctor-profile.php', function() {
    $controller = new \care\controllers\AdminController();
    $controller->updateDoctorProfile();
});
$router->add('admin/add-schedule', function() {
    $controller = new \care\controllers\AdminController();
    $controller->addDoctorSchedule();
});
$router->add('admin/add-schedule.php', function() {
    $controller = new \care\controllers\AdminController();
    $controller->addDoctorSchedule();
});
$router->add('admin/schedule', function() {
    $controller = new \care\controllers\AdminController();
    $controller->showSchedule();
});
$router->add('admin/schedule.php', function() {
    $controller = new \care\controllers\AdminController();
    $controller->showSchedule();
});
$router->add('admin/payment', function() {
    $controller = new \care\controllers\AdminController();
    $controller->showPayment();
});
$router->add('admin/payment.php', function() {
    $controller = new \care\controllers\AdminController();
    $controller->showPayment();
});
$router->add('patient/register', function() {
    $controller = new \care\controllers\HomeController();
    $controller->patientRegistration();
});
$router->add('patient/register.php', function() {
    $controller = new \care\controllers\HomeController();
    $controller->patientRegistration();
});
$router->add('patient/login.php', function() {
    $controller = new \care\controllers\HomeController();
    $controller->loginPatient();
});
$router->add('patient/login', function() {
    $controller = new \care\controllers\HomeController();
    $controller->loginPatient();
});
$router->add('patient/dashboard', function() {
    $controller = new \care\controllers\HomeController();
    $controller->patientDashboard();
});
$router->add('patient/dashboard.php', function() {
    $controller = new \care\controllers\HomeController();
    $controller->patientDashboard();
});
$router->add('patient/index', function() {
    $controller = new \care\controllers\HomeController();
    $controller->patientDashboard();
});
$router->add('patient/index.php', function() {
    $controller = new \care\controllers\HomeController();
    $controller->patientDashboard();
});
$router->add('patient/home', function() {
    $controller = new \care\controllers\PatientController();
    $controller->index();
});
$router->add('patient/home.php', function() {
    $controller = new \care\controllers\PatientController();
    $controller->index();
});
$router->add('patient/logout.php', function() {
    $controller = new \care\controllers\HomeController();
    $controller->logout();
});
$router->add('patient/logout', function() {
    $controller = new \care\controllers\HomeController();
    $controller->logout();
});
$router->add('patient/doctor-list', function() {
    $controller = new \care\controllers\HomeController();
    $controller->showDocList();
});
$router->add('patient/doctor-list.php', function() {
    $controller = new \care\controllers\HomeController();
    $controller->showDocList();
});
$router->add('patient/doctor-detail.php', function() {
    $controller = new \care\controllers\HomeController();
    $controller->showDocDetail();
});
$router->add('patient/doctor-confirmation.php', function() {
    $controller = new \care\controllers\HomeController();
    $controller->doctorConfirmation();
});
$router->add('patient/waiting.php', function() {
    $controller = new \care\controllers\HomeController();
    $controller->waitingRoom();
});
$router->add('patient/check_appointment_status.php', function() {
    $controller = new \care\controllers\HomeController();
    $controller->checkAppointmentStatus();
});
$router->add('doctor/login', function() {
    $controller = new DoctorController();
    $controller->doctorLogin();
});
$router->add('doctor/login.php', function() {
    $controller = new DoctorController();
    $controller->doctorLogin();
});
$router->add('doctor/index', function() {
    $controller = new DoctorController();
    $controller->index();
});
$router->add('doctor/index.php', function() {
    $controller = new DoctorController();
    $controller->index();
});
$router->add('doctor/logout.php', function() {
    $controller = new DoctorController();
    $controller->logout();
});
$router->add('doctor/logout', function() {
    $controller = new DoctorController();
    $controller->logout();
});
$router->setNotFound(function() {
    header("HTTP/1.0 404 Not Found");
   require "views/404.php";
});

$uri = isset($_GET['url']) ? trim($_GET['url'], '/') : '';
$router->dispatch($uri);
