<?php

require_once __DIR__ . "/../config/ErrorHandler.php";
set_exception_handler(['ErrorHandler', 'handleException']);

require_once __DIR__ . "/../config/Headers.php";
require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../controllers/StatsController.php";

// Create an instance of the Database class and get the connection
$database = new Database();
$db = $database->getConnection();
$controller = new StatsController($db);
$method = $_SERVER['REQUEST_METHOD'];


$patient = $controller->getPatients();
$doctor = $controller->getDoctor();
$staff = $controller->getStaffs();
$inPatient = $controller->getInpatient();
$patientActivity = $controller->getPatientActivity();


if ($method === "GET") {
    $data = array(
        "patients" => $patient,
        "doctors" => $doctor,
        "staffs" => $staff,
        "inpatients" => $inPatient,
        "newpatients" => $patientActivity,
    );
    echo json_encode($data);
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Method not allowed"));

}