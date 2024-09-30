<?php

class StatsController{
    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

    // Get the number of patients
    public function getPatients(): int {
        $query = "SELECT COUNT(*) AS patient_count FROM users WHERE role = 'PATIENT'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch the result as an associative array
        return $result['patient_count']; // Return the patient count
    }

    // Get the number of doctors
    public function getDoctor(): int {
        $query = "SELECT COUNT(*) AS doctor_count FROM users WHERE role = 'DOCTOR'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['doctor_count'];
    }

    // Get the number of staff members
    public function getStaffs(): int {
        $query = "SELECT COUNT(*) AS staff_count FROM users WHERE role NOT IN ('STAFF', 'DOCTOR')";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['staff_count'];
    }

    // Get the number of inpatients
    public function getInpatient(): int {
        $query = "SELECT COUNT(*) AS inpatient_count FROM users WHERE role = 'INPATIENT'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['inpatient_count'];
    }

    public function getPatientActivity(): array {
        // Fetch all patients from the users table where role is PATIENT
        $query = "
            SELECT 
                MONTH(created_at) AS month, 
                COUNT(*) AS total_patients 
            FROM 
                users 
            WHERE 
                role = 'PATIENT' 
            GROUP BY 
                MONTH(created_at) 
            ORDER BY 
                MONTH(created_at) ASC
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Format the result to match your data structure
        $activities = [];
        foreach ($result as $row) {
            $activities[] = [
                'month' => date("F", mktime(0, 0, 0, $row['month'], 10)), // Get the full month name
                'patients' => (int)$row['total_patients'],
            ];
        }
    
        return $activities; // Return the formatted array
    }
    
}
