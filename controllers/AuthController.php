<?php

require_once __DIR__ . "/../models/Auth.php";
require_once __DIR__ . "/../utils/sendmail.php";

class UserController
{
    private $auth;

    public function __construct($db)
    {
        $this->auth = new Auth($db);
    }

    public function createAccount($data)
    {
        $validationResult = $this->auth->validate($data);
        if ($validationResult['status'] === 'error') {
            return $validationResult;
        }

        if ($this->auth->verifyUser($data['email'])) {
            return ["status" => "error", "message" => "Email already exists."];
        }

        if ($this->auth->createAccount($data)) {
            return ["status" => "success", "message" => "Account created successfully."];
        } else {
            return ["status" => "error", "message" => "Failed to create account."];
        }
    }

    public function login($data)
    {
        return $this->auth->login($data);
    }

    public function deleteAccount($id)
    {
        return $this->auth->deleteAccount($id) ?
            ["status" => "success", "message" => "Account deleted successfully."] :
            ["status" => "error", "message" => "Failed to delete account."];
    }

    public function getUser($id)
    {
        $user = $this->auth->getUser($id);
        if ($user) {
            return ["status" => "success", "data" => $user];
        } else {
            return ["status" => "error", "message" => "User not found."];
        }
    }

    public function updateAccount($id, $data)
    {
        $data['updated'] = date('Y-m-d H:i:s');
        return $this->auth->updateAccount($id, $data) ?
            ["status" => "success", "message" => "Account updated successfully."] :
            ["status" => "error", "message" => "Failed to update account."];
    }

    public function resetPassword($data)
    {
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return ["status" => "error", "message" => "Invalid email address"];
        }

        $token = $this->auth->generateResetToken($data);
        if (!$token) {
            return ["status" => "error", "message" => "Failed to generate reset token."];
        }

        $mailBody = "<html><body>
        <h2>Password Reset Request</h2>
        <p>Dear user,</p>
        <p>You have requested to reset your password. Use the token: $token</p>
        <p>This token will expire in 30 minutes.</p></body></html>";

        if (!sendMail($data['email'], "Password Reset Token", $mailBody)) {
            return ["status" => "error", "message" => "Failed to send email."];
        }

        return ["status" => "success", "message" => "Token sent to your email."];
    }

    public function getToken($token)
    {

        // Validate the email address
        if (empty($token)) {
            http_response_code(400); // Bad Request
            echo json_encode(array("message" => "Invalid TOKEN"));
            return;
        }

        return $this->auth->getToken($token);
    }

    public function verifyUser($data)
    {
        return $this->auth->verifyUser($data) ?
            ["status" => "success", "message" => "User exists."] :
            ["status" => "error", "message" => "User not found."];
    }

    public function updatePassword($data)
    {
        return $this->auth->updatePassword($data) ?
            ["status" => "success", "message" => "Update successful."] :
            ["status" => "error", "message" => "failed to update."];
    }

}
