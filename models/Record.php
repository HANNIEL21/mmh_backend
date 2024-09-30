<?php

require_once __DIR__ . "/../config/Database.php";

class Record
{
    private $conn;
    private $table = "records";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getRecords()
    {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRecord($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createRecord($data)
    {
        $query = "INSERT INTO " . $this->table . " SET ref = :ref, staff = :staff, type = :type, complain = :complain, hoc = :hoc, course = :course, cause = :cause, care = :care, complication = :complication, pmh = :pmh, sr = :sr, pd = :pd, fsh = :fsh, remark = :remark, created_at = :created, updated_at = :updated";

        $stmt = $this->conn->prepare($query);

        $createdAt = date('Y-m-d H:i:s');
        $updatedAt = date('Y-m-d H:i:s');

        $stmt->bindParam(':ref', $data["ref"]);
        $stmt->bindParam(':staff', $data["staff"]);
        $stmt->bindParam(':type', $data["type"]);
        $stmt->bindParam(':complain', $data["complain"]);
        $stmt->bindParam(':hoc', $data["hoc"]);
        $stmt->bindParam(':course', $data["course"]);
        $stmt->bindParam(':cause', $data["cause"]);
        $stmt->bindParam(':care', $data["care"]);
        $stmt->bindParam(':complication', $data["complication"]);
        $stmt->bindParam(':pmh', $data["pmh"]);
        $stmt->bindParam(':sr', $data["sr"]);
        $stmt->bindParam(':pd', $data["pd"]);
        $stmt->bindParam(':fsh', $data["fsh"]);
        $stmt->bindParam(':remark', $data["remark"]);
        $stmt->bindParam(':created', $createdAt);
        $stmt->bindParam(':updated', $updatedAt);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteRecord($id)
    {
        // First, check if the row exists
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $result = $stmt->fetch();

        if ($result['count'] > 0) {
            // Row exists, proceed with deletion
            $query = "DELETE FROM " . $this->table . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } else {
            echo json_encode(["status"=>"error", "message"=>"Item with ID $id does not exist."]);
            return false;
        }
    }

}