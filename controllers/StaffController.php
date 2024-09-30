<?php

require_once '../models/Staff.php';

class StaffController
{
    private $staffModel;

    public function __construct($db)
    {
        $this->staffModel = new Staff($db);
    }

    public function getStaff($id)
    {
        return $this->staffModel->getStaff($id);
    }

    public function getStaffs()
    {
        return $this->staffModel->getStaffs();
    }

    public function createStaff($data)
    {
        return $this->staffModel->create($data);
    }

    public function updateStaff($id, $data)
    {
        return $this->staffModel->update($id, $data);
    }

    public function deleteStaff($id)
    {
        return $this->staffModel->delete($id);
    }
}