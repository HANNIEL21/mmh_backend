<?php
// Initialize the error and exception handlers
require_once __DIR__ . "/../config/ErrorHandler.php";
set_exception_handler(['ErrorHandler', 'handleException']);

require_once __DIR__ . "/../config/Headers.php";
require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../controllers/RecordController.php";


$database = new Database();
$db = $database->getConnection();
$recordController = new RecordController($db);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $result = $recordController->getRecord($_GET['id']);
        } else {
            $result = $recordController->getAllRecords();
        }
        echo json_encode($result);
        break;
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $result = $recordController->createRecord($data);
        if ($result) {
            http_response_code(201);
            echo json_encode(array("status" => "success", "message" => "Record created successfully"));
        } else {
            http_response_code(400);
            echo json_encode(array("status" => "error", "message" => "Failed to create record"));
        }
        break;
    case 'DELETE':
        if (isset($_GET["id"])) {
            $data = json_decode(file_get_contents("php://input"), true);
            $result = $recordController->deleteRecord($_GET["id"]);
            if ($result) {
                http_response_code(200);
                echo json_encode(array("status" => "success", "message" => "Record deleted successfully " .$_GET["id"]));
            } else {
                http_response_code(400);
                echo json_encode(array("status" => "error", "message" => "Failed to delete record"));
            }
        } else {
            http_response_code(404);
            echo json_encode(array("status" => "error", "message" => "MISSING ID"));
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(array("status" => "error", "message" => "Method not allowed"));
        break;
}
