<?php
// Initialize the error and exception handlers
require_once __DIR__ . "/../config/ErrorHandler.php"; 
set_exception_handler(['ErrorHandler', 'handleException']);

require_once __DIR__ . "/../config/Headers.php";
require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../controllers/PrescriptionController.php";


$database = new Database();
$db = $database->getConnection();
$prescriptionController = new PrescriptionController($db);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $result = $prescriptionController->getPrescriptionById($_GET['id']);
        } else {
            $result = $prescriptionController->getAllPrescription();
        }
        echo json_encode($result);
        break;
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $result = $prescriptionController->createPrescription($data);
        if ($result) {
            http_response_code(201);
            echo json_encode(array("status" => "success", "message" => "Prescription created successfully"));
        } else {
            http_response_code(400);
            echo json_encode(array("status" => "error", "message" => "Failed to create prescription"));
        }
        break;
    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"), true);
        $result = $prescriptionController->deletePrescription($data['id']);
        if ($result) {
            http_response_code(200);
            echo json_encode(array("status" => "success", "message" => "Prescription deleted successfully"));
        } else {
            http_response_code(400);
            echo json_encode(array("status" => "error", "message" => "Failed to delete prescription"));
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(array("status" => "error", "message" => "Method not allowed"));
        break;
}
