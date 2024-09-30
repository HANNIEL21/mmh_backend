<?php

class DrugCategory
{
    private $conn;

    private $table = "drug_category";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll()
    {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOne($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $query = "INSERT INTO " . $this->table ."(category, created_at, updated_at) VALUES (:category, :created, :updated)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":category", $data['category']);
        $stmt->bindParam(":created", $data['created_at']);
        $stmt->bindParam(":updated", $data['updated_at']);
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    public function update($id, $data)
    {
        $query = "UPDATE " . $this->table . " SET category = :category WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":category", $data['category']);
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt= $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

}