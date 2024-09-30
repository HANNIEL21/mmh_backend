<?php

require_once __DIR__ . "/../config/ErrorHandler.php";
set_exception_handler(['ErrorHandler', 'handleException']);


require_once __DIR__ . "/../config/Headers.php";
require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../controllers/PatientsController.php";

$database = new Database();
$db = $database->getConnection();
$patientController = new PatientsController($db);
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case "GET":
        if (isset($_GET['id'])) {
            $result = $patientController->getPatient($_GET['id']);
        } else {
            $result = $patientController->getPatients();
        }
        echo json_encode(["data" => $result]);
        break;
    case "POST":
        $data = json_decode(file_get_contents("php://input"), true);
        $result = $patientController->createPatient($data);

        if ($result['status'] === 'success') {
            http_response_code(201); // Status code for successful creation
            echo json_encode($result);
        } else {
            http_response_code(400); // Status code for client-side error
            echo json_encode($result);
        }
        break;
    case "PUT":
        if (isset($_GET['id'])) {
            $data = json_decode(file_get_contents("php://input"), true);
            $result = $patientController->updatePatient($_GET['id'], $data);
            if ($result) {
                http_response_code(200);
                echo json_encode(array("status" => "success", "message" => "Patient updated successfully"));
            } else {
                http_response_code(400);
                echo json_encode(array("status" => "error", "message" => "Failed to update patient"));
            }
        } else {
            http_response_code(404);
            echo json_encode(array("status" => "error", "message" => "Missing ID"));
        }
        break;
    case "DELETE":
        if (isset($_GET['id'])) {
            $data = json_decode(file_get_contents("php://input"), true);
            $result = $patientController->deletePatient($_GET['id']);
            if ($result) {
                http_response_code(200);
                echo json_encode(array("status" => "success", "message" => "Patient deleted successfully"));
            } else {
                http_response_code(400);
                echo json_encode(array("status" => "error", "message" => "Failed to delete Patient"));
            }
        } else {
            http_response_code(404);
            echo json_encode(array("status" => "error", "message" => "Missing ID"));
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(array("status" => "error", "message" => "Method not allowed"));
        break;

}

