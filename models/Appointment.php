<?php

class Appointment
{
    private $conn;
    private $table = "appointments";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAppointment($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAppointments()
    {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function generateCode()
    {
        // Generate a unique 6-digit booking code
        return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }


    public function create($data)
    {
        // Generate a unique 6-digit booking code
        $bookingCode = $this->generateCode();

        $query = "INSERT INTO " . $this->table . " (firstname, lastname, email, phone, type, date, time, note, booking_number) 
                  VALUES (:firstname, :lastname, :email, :phone, :type, :date, :time, :note, :code)";

        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':firstname', $data['firstname']);
        $stmt->bindParam(':lastname', $data['lastname']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':phone', $data['phone']);
        $stmt->bindParam(':type', $data['type']);
        $stmt->bindParam(':date', $data['date']);
        $stmt->bindParam(':time', $data['time']);
        $stmt->bindParam(':note', $data['note']);
        $stmt->bindParam(':code', $bookingCode);

        // Execute the query
        if ($stmt->execute()) {
            // Get the last inserted ID
            $lastInsertId = $this->conn->lastInsertId();

            // Fetch the newly created data
            $selectQuery = "SELECT * FROM " . $this->table . " WHERE id = :id";
            $selectStmt = $this->conn->prepare($selectQuery);
            $selectStmt->bindParam(':id', $lastInsertId);

            if ($selectStmt->execute()) {
                // Return the created data
                return $selectStmt->fetch(PDO::FETCH_ASSOC);
            } else {
                return false; // If fetching fails
            }
        } else {
            return false; // If insert fails
        }
    }



    public function update($id, $data)
    {
        $query = "UPDATE " . $this->table . " SET firstname = :firstname, lastname = :lastname, email = :email, phone = :phone, type = :type, date = :date, time = :time, note = :note WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":firstname", $data["firstname"]);
        $stmt->bindParam(":lastname", $data["lastname"]);
        $stmt->bindParam(":type", $data["type"]);
        $stmt->bindParam(":phone", $data["phone"]);
        $stmt->bindParam(":email", $data["email"]);
        $stmt->bindParam(":date", $data["date"]);
        $stmt->bindParam(":time", $data["time"]);
        $stmt->bindParam(":note", $data["note"]);

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