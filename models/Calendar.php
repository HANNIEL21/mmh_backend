<?php

class CalenderModel
{

    private $conn;

    private $table = "calendar";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getEvents()
    {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEvent($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createEvent($data)
    {
        $query = "INSERT INTO " . $this->table . " SET event= :event, time = :time, date = :date, created_at = :created, updated_at = :updated";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":event", $data["event"]);
        $stmt->bindParam(":time", $data["time"]);
        $stmt->bindParam(":date", $data["date"]);
        $stmt->bindParam(":created", $data["created_at"]);
        $stmt->bindParam(":updated", $data["updated_at"]);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    public function updateEvent($id, $data)
    {
        $query = "UPDATE  " . $this->table . " SET event= :event, time = :time, date = :date, updated_at = :updated WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":event", $data["event"]);
        $stmt->bindParam(":time", $data["time"]);
        $stmt->bindParam(":date", $data["date"]);
        $stmt->bindParam(":updated", $data["updated_at"]);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteEvent($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        if($stmt->execute()){
            return true;
        } else {
            return false;
        }
    }
}