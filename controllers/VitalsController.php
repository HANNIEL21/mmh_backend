<?php

require_once "../models/Vitals.php";

class VitalsController{
    private $model;

    public function __construct($db){
        $this->model = new Vitals($db);
    }

    public function getVitals($id){
        return $this->model->getVital($id);
    }

    public function createVital($data){
        return $this->model->create($data);
    }

    public function updateVitals($id, $data){
        return $this->model->update($id, $data);
    }

    public function deleteVitals($id){
        return $this->model->delete($id);
    }
}