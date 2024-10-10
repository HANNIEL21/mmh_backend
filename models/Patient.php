<?php

require_once __DIR__ . "/../config/Database.php";

class Patient
{
    private $conn;

    private $table = "users";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getPatients()
    {
        $query = "SELECT * FROM " . $this->table . " WHERE role = 'PATIENT'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        // Check if email already exists
        $emailQuery = "SELECT * FROM " . $this->table . " WHERE email = :email";
        $emailStmt = $this->conn->prepare($emailQuery);
        $emailStmt->bindParam(':email', $data['email']);
        $emailStmt->execute();
        $emailCount = $emailStmt->rowCount();

        if ($emailCount > 0) {
            return array('error' => 1, 'message' => 'Email already exists');
        }

        // Insert query
        $query = "INSERT INTO " . $this->table . " 
    (firstname, lastname, age, email, phone, gender, religion, marital_status, address, role, occupation, fnnok, lnnok, pnnok, anok, created_at, updated_at) 
    VALUES 
    (:firstname, :lastname, :age, :email, :phone, :gender, :religion, :marital_status, :address, :role, :occupation, :fnnok, :lnnok, :pnnok, :anok, :created, :updated)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':firstname', $data['firstname']);
        $stmt->bindParam(':lastname', $data['lastname']);
        $stmt->bindParam(':age', $data['age']);
        $stmt->bindParam(':phone', $data['phone']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':gender', $data['gender']);
        $stmt->bindParam(':religion', $data['religion']);
        $stmt->bindParam(':occupation', $data['occupation']);
        $stmt->bindParam(':role', $data['role']);
        $stmt->bindParam(':marital_status', $data['marital_status']);
        $stmt->bindParam(':address', $data['address']);
        $stmt->bindParam(':fnnok', $data['fnnok']);
        $stmt->bindParam(':lnnok', $data['lnnok']);
        $stmt->bindParam(':anok', $data['anok']);
        $stmt->bindParam(':pnnok', $data['pnnok']);
        $stmt->bindParam(':created', $data['created_at']);
        $stmt->bindParam(':updated', $data['updated_at']);

        // Execute the query and check if insertion is successful
        if ($stmt->execute()) {
            $lastInsertId = $this->conn->lastInsertId();

            // Fetch the newly created patient
            $selectQuery = "SELECT * FROM " . $this->table . " WHERE id = :id";
            $selectStmt = $this->conn->prepare($selectQuery);
            $selectStmt->bindParam(':id', $lastInsertId);

            if ($selectStmt->execute()) {
                return $selectStmt->fetch(PDO::FETCH_ASSOC); // Return the created data
            } else {
                return array('error' => 2, 'message' => 'Failed to fetch created patient');
            }
        } else {
            return array('error' => 2, 'message' => 'Failed to create patient');
        }
    }

    public function update($id, $data)
    {
        $query = "UPDATE " . $this->table . " SET 
        firstname = :firstname, 
        lastname = :lastname, 
        age = :age, 
        ref = :ref, 
        status = :status, 
        phone = :phone, 
        email = :email, 
        gender = :gender, 
        address = :address, 
        religion = :religion, 
        occupation = :occupation, 
        marital_status = :marital_status, 
        fnnok = :fnnok, 
        lnnok = :lnnok, 
        pnnok = :pnnok, 
        anok = :anok, 
        updated_at = :updated 
        WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':firstname', $data['firstname']);
        $stmt->bindParam(':lastname', $data['lastname']);
        $stmt->bindParam(':age', $data['age']);
        $stmt->bindParam(':ref', $data['ref']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':phone', $data['phone']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':gender', $data['gender']);
        $stmt->bindParam(':address', $data['address']);
        $stmt->bindParam(':religion', $data['religion']);
        $stmt->bindParam(':occupation', $data['occupation']);
        $stmt->bindParam(':marital_status', $data['marital_status']);
        $stmt->bindParam(':fnnok', $data['fnnok']);
        $stmt->bindParam(':lnnok', $data['lnnok']);
        $stmt->bindParam(':anok', $data['anok']);
        $stmt->bindParam(':pnnok', $data['pnnok']);
        $stmt->bindParam(':updated', $data['updated_at']);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}