<?php
require_once __DIR__ . "/../config/ErrorHandler.php";
set_exception_handler(['ErrorHandler', 'handleException']);

include_once __DIR__ . '/../config/Database.php';
include_once __DIR__ . '/../config/Headers.php';
include_once __DIR__ . '/../controllers/AuthController.php';



$database = new Database();
$db = $database->getConnection();
$controller = new UserController($db);
$method = $_SERVER['REQUEST_METHOD'];


switch ($method) {
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        
        if (isset($_GET['action'])) {
            $action = $_GET['action'];

            if ($action == 'create') {
                $response = $controller->createAccount($data);
            } elseif ($action == 'login') {
                $response = $controller->login($data);
            } elseif ($action == 'reset') {
                $response = $controller->resetPassword($data);
            }elseif ($action == 'verify') {
                $response = $controller->verifyUser($data);
            }elseif ($action == 'updatePassword') {
                $response = $controller->updatePassword($data);
            } else {
                $response = ["status" => "error", "message" => "Invalid action specified."];
            }
        } else {
            $response = ["status" => "error", "message" => "No action specified."];
        } 
        break;
    case 'GET':
        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($_GET['id'])) {
            $response = $controller->getUser($_GET['id']);
        } elseif (isset($_GET['token'])) {
            $response = $controller->getToken($_GET['token']);
        }elseif (isset($_GET['action']) == 'verify') {
            $response = $controller->verifyUser($data);
        }
        break;
    case 'DELETE':
        if (isset($_GET['id'])) {
            $response = $controller->deleteAccount($_GET['id']);
        }
        break;
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($_GET['action'])) {
            $action = $_GET['action'];
            if ($action == 'password') {
                $response = $controller->updatePassword($data);
            }
        }
        break;
    default:
        $response = ["status" => "error", "message" => "Invalid request method."];
        break;
}

echo json_encode($response);