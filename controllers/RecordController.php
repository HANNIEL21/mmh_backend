<?php

require_once __DIR__."/../models/Record.php";

class RecordController {
    private $recordModel;
    public function __construct($db){
        $this->recordModel = new Record($db);
    }   

    public function getAllRecords(){
        return $this->recordModel->getRecords();
    }

    public function getRecord($id){
        return $this->recordModel->getRecord($id);
    }

    public function createRecord($data){
        return $this->recordModel->createRecord($data);
    }

    public function deleteRecord($id){
        return $this->recordModel->deleteRecord($id);
    }

}