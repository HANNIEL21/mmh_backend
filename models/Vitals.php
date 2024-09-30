<?php

class Vitals{
    private $conn;
    private $table_name = "vitals";

    public function __construct($db){
        $this->conn = $db;
    }

    public function getVital($id){
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO :: FETCH_ASSOC);
    }

    public function create($data){
        $query = "INSERT INTO " . $this->table_name . " SET user_id = :uID, temperature = :temp, pulse = :pulse, weight = :weight, height = :height, respiration = :resp, bp = :bp, created_at = :created, updated_at = :updated";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':uID', $data['user_id']);
        $stmt->bindParam(':temp', $data['temperature']);
        $stmt->bindParam(':pulse', $data['pulse']);
        $stmt->bindParam(':weight', $data['weight']);
        $stmt->bindParam(':height', $data['height']);
        $stmt->bindParam(':resp', $data['respiration']);
        $stmt->bindParam(':bp', $data['bp']);
        $stmt->bindParam(':created', $data['created_at']);
        $stmt->bindParam(':updated', $data['updated_at']);

        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    public function update($id, $data){
        $query = "UPDATE " . $this->table_name . " SET user_id = :uID, temperature = :temp, pulse = :pulse, weight = :weight, height = :height, respiration = :resp, bp = :bp, updated_at = :updated WHERE user_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':uID', $data['user_id']);
        $stmt->bindParam(':temp', $data['temperature']);
        $stmt->bindParam(':pulse', $data['pulse']);
        $stmt->bindParam(':weight', $data['weight']);
        $stmt->bindParam(':height', $data['height']);
        $stmt->bindParam(':resp', $data['respiration']);
        $stmt->bindParam(':bp', $data['bp']);
        $stmt->bindParam(':updated', $data['updated_at']);
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    public function delete($id){
        $query = "DELETE FROM " . $this->table_name . " WHERE user_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }
}