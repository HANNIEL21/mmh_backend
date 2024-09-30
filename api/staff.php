<?php

require_once __DIR__ . "/../config/ErrorHandler.php";
set_exception_handler(['ErrorHandler', 'handleException']);

require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../config/Headers.php";
require_once __DIR__ . "/../controllers/StaffController.php";

$database = new Database();
$db = $database->getConnection();
$controller = new StaffController($db);
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case "GET":
        if (isset($_GET['id'])) {
            $result = $controller->getStaff($_GET['id']);
        } else {
            $result = $controller->getStaffs();
        }
        echo json_encode(["data" => $result]);
        break;
    case "POST":
        $data = json_decode(file_get_contents("php://input"), true);
        $result = $controller->createStaff($data);
        if ($result) {
            http_response_code(201);
            echo json_encode(array("status" => "success", "message" => "Staff account created", "data"=>$result));
        } else {
            http_response_code(400);
            echo json_encode(array("status" => "error", "message" => "Failed to create account"));
        }
        break;
    case "PUT":
        if (isset($_GET['id'])) {
            $data = json_decode(file_get_contents("php://input"), true);
            $result = $controller->updateStaff($_GET['id'], $data);
            if ($result) {
                http_response_code(201);
                echo json_encode(array("status" => "success", "message" => "Staff updated successfully"));
            } else {
                http_response_code(400);
                echo json_encode(array("status" => "error", "message" => "Failed to update staff"));
            }
        } else {
            http_response_code(404);
            echo json_encode(array("status" => "error", "message" => "MISSING ID"));
        }
        break;
    case "DELETE":
        if (isset($_GET['id'])) {
            $data = json_decode(file_get_contents("php://input"), true);
            $result = $controller->deleteStaff($_GET['id']);
            if ($result) {
                http_response_code(200);
                echo json_encode(array("status" => "success", "message" => "Staff deleted successfully"));
            } else {
                http_response_code(400);
                echo json_encode(array("status" => "error", "message" => "Failed to delete staff"));
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
