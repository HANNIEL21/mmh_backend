<?php

class Auth
{
    private $conn;
    private $table = "users";

    public function __construct($db)
    {
        $this->conn = $db;
    }
    public function validate($data)
    {
        $requiredFields = ['firstname', 'lastname', 'email', 'password', 'phone', 'role'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                return ["status" => "error", "message" => "Field '$field' is required."];
            }
        }
        return ["status" => "success", "message" => "Validation successful"];
    }

    public function verifyUser($data)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $data['email']);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false; 
    }
    public function createAccount($data)
    {
        $query = "INSERT INTO " . $this->table . " 
            (firstname, lastname, email, password, phone, role, created_at, updated_at) 
            VALUES (:firstname, :lastname, :email, :password, :phone, :role, :created, :updated)";
        $stmt = $this->conn->prepare($query);
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

        $stmt->bindParam(":firstname", $data['firstname']);
        $stmt->bindParam(":lastname", $data['lastname']);
        $stmt->bindParam(":email", $data['email']);
        $stmt->bindParam(":password", $hashedPassword);
        $stmt->bindParam(":phone", $data['phone']);
        $stmt->bindParam(":role", $data['role']);
        $stmt->bindParam(":created", $data['created']);
        $stmt->bindParam(":updated", $data['updated']);

        return $stmt->execute();
    }
    public function login($data)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $data['email']);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($data['password'], $user['password'])) {
            unset($user['password']);
            return ["status" => "success", "data" => $user];
        }

        return ["status" => "error", "message" => $user ? "Invalid password." : "User not found."];
    }
    public function deleteAccount($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
    public function getUser($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function updateAccount($id, $data)
    {
        $query = "UPDATE " . $this->table . " SET firstname = :firstname, lastname = :lastname, email = :email, 
                  phone = :phone, role = :role, updated_at = :updated WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":firstname", $data['firstname']);
        $stmt->bindParam(":lastname", $data['lastname']);
        $stmt->bindParam(":email", $data['email']);
        $stmt->bindParam(":phone", $data['phone']);
        $stmt->bindParam(":role", $data['role']);
        $stmt->bindParam(":updated", $data['updated']);
        $stmt->bindParam(":id", $id);

        return $stmt->execute();
    }

    public function updatePassword($data)
    {
        $query = "UPDATE " . $this->table . " SET password = :password, updated_at = :updated WHERE email = :email";
        $stmt = $this->conn->prepare($query);

        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

        $stmt->bindParam(":email", $data['email']);
        $stmt->bindParam(":password", $hashedPassword);
        $stmt->bindParam(":updated", $data['updated']);

        return $stmt->execute();
    }

    public function generateResetToken($data)
    {
        $token = random_int(100000, 999999);
        $tokenHash = hash('sha256', $token);
        $expiry = date("Y-m-d H:i:s", time() + 60 * 30);

        $query = "UPDATE " . $this->table . " SET hashed_token = :hash, hashed_token_expires_at = :expiry WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':hash', $tokenHash);
        $stmt->bindParam(':expiry', $expiry);

        return $stmt->execute() ? $token : false;
    }

    public function getToken($token)
    {
        $hashedToken = hash('sha256', $token);

        $query = "SELECT * FROM " . $this->table . " WHERE hashed_token = :token";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':token', $hashedToken);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result;
        } else {
            return false;
        }
    }


}
