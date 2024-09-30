<?php

require_once __DIR__ . '/../config/Database.php';

class Prescription
{
    private $conn;
    private $table = 'prescriptions';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getPrescriptions()
    {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        try {
            $query = "INSERT INTO " . $this->table . "(ref, doctor, prescription, created_at) VALUES(:ref, :doc, :presc, :created)";
            $stmt = $this->conn->prepare($query);

            $created = date('Y-m-d H:i:s');

            $stmt->bindParam(":ref", $data["ref"]);
            $stmt->bindParam(":doc", $data["doc"]);
            $stmt->bindParam(":presc", $data["presc"]);
            $stmt->bindParam(":created", $created);

            if ($stmt->execute()) {
                return true;
            } else {
                // If execution fails, throw an exception with the error details
                $error = $stmt->errorInfo();
                throw new Exception($error[2]);
            }
        } catch (PDOException $e) {
            // Handle PDO-specific exceptions
            throw new Exception($e->getMessage());
        } catch (Exception $e) {
            // Handle general exceptions
            throw new Exception($e->getMessage());
        }
    }

    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}