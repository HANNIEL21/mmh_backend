<?php

require_once __DIR__ . "/../models/Prescription.php";

class PrescriptionController
{
    private $prescriptionModel;

    public function __construct($db)
    {
        $this->prescriptionModel = new Prescription($db);
    }

    public function getAllPrescription()
    {
        return $this->prescriptionModel->getPrescriptions();
    }

    public function getPrescriptionById($id)
    {
        return $this->prescriptionModel->getById($id);
    }

    public function createPrescription($data)
    {
        return $this->prescriptionModel->create($data);
    }

    public function deletePrescription($id)
    {
        return $this->prescriptionModel->delete($id);
    }
}