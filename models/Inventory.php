<?php

class Inventory
{
    private $conn;

    private $table = "inventory";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getItem($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getItems()
    {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $query = "INSERT INTO " . $this->table . " (product, price, unit, category, quantity, expiry, created_at, updated_at) VALUES (:product, :price, :unit, :category, :quantity, :expiry, :created_at, :updated_at)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':product', $data['product']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':category', $data['category']);
        $stmt->bindParam(':quantity', $data['quantity']);
        $stmt->bindParam(':unit', $data['unit']);
        $stmt->bindParam(':expiry', $data['expiry']);
        $stmt->bindParam(':created_at', $data['created_at']);
        $stmt->bindParam(':updated_at', $data['updated_at']);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function update($id, $data)
    {
        $query = "UPDATE " . $this->table . " SET product= :product, price= :price, unit= :unit, expiry= :expiry, category= :category, quantity= :quantity, updated_at= :update WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':product', $data['product']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':unit', $data['unit']);
        $stmt->bindParam(':expiry', $data['expiry']);
        $stmt->bindParam(':category', $data['category']);
        $stmt->bindParam(':quantity', $data['quantity']);
        $stmt->bindParam(':update', $data['updated_at']);

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