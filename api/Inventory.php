<?php
require_once __DIR__ . "/../config/ErrorHandler.php";
set_exception_handler(['ErrorHandler', 'handleException']);

require_once __DIR__ . "/../config/Headers.php";
require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../controllers/InventoryController.php";

$database = new Database();
$db = $database->getConnection();
$controller = new InventoryController($db);
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case "GET":
        if (isset($_GET['id'])) {
            $result = $controller->getItem($_GET['id']);
        } else {
            $result = $controller->getItems();
        }
        echo json_encode(["data" => $result]);
        break;
    case "POST":
        $data = json_decode(file_get_contents("php://input"), true);
        $result = $controller->createItem($data);
        if ($result) {
            http_response_code(201);
            echo json_encode(array("status" => "success", "message" => "Product created successfully"));
        } else {
            http_response_code(400);
            echo json_encode(array("status" => "error", "message" => "Failed to create product"));
        }
        break;
    case "PUT":
        if (isset($_GET['id'])) {
            $data = json_decode(file_get_contents("php://input"), true);
            $result = $controller->updateItem($_GET['id'], $data);
            if ($result) {
                http_response_code(200);
                echo json_encode(array("status" => "success", "message" => "Product updated successfully"));
            } else {
                http_response_code(400);
                echo json_encode(array("status" => "error", "message" => "Failed to update product"));
            }
        } else {
            http_response_code(404);
            echo json_encode(array("status" => "error", "message" => "MISSING ID"));
        }
        break;
    case "DELETE":
        if (isset($_GET['id'])) {
            $data = json_decode(file_get_contents("php://input"), true);
            $result = $controller->deleteItem($_GET['id']);
            if ($result) {
                http_response_code(200);
                echo json_encode(array("status" => "success", "message" => "Product deleted successfully"));
            } else {
                http_response_code(400);
                echo json_encode(array("status" => "error", "message" => "Failed to delete product"));
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