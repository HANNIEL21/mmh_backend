<?php

require_once '../models/Patient.php';

class PatientsController
{
    private Patient $patientModel;

    public function __construct(PDO $db)
    {
        $this->patientModel = new Patient($db);
    }

    public function getPatients(): array
    {
        return $this->patientModel->getPatients();
    }

    public function getPatient(int $id)
    {
        return $this->patientModel->getById($id);
    }

    public function createPatient(array $data): array
    {
        $result = $this->patientModel->create($data);

        // Success case: return newly created data
        if (isset($result['id'])) {
            return array("status" => "success", "message" => "Patient created successfully", "data" => $result);
        }

        // Error: Email already exists
        if (isset($result['error']) && $result['error'] === 1) {
            return array("status" => "error", "message" => "Email already exists");
        }

        // Error: Failed to create patient
        if (isset($result['error']) && $result['error'] === 2) {
            return array("status" => "error", "message" => "Failed to create patient");
        }

        // General error fallback
        return array("status" => "error", "message" => "An unexpected error occurred");
    }



    public function updatePatient(int $id, array $data): bool
    {
        return $this->patientModel->update($id, $data);
    }

    public function deletePatient($id): bool
    {
        return $this->patientModel->delete($id);
    }
}