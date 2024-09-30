<?php

require_once "../models/Calendar.php";


class CalendarController {
    private $model;

    public function __construct($db){
        $this->model = new CalenderModel($db);
    }

    public function getEvent($id){
        return $this->model->getEvent($id);
    }

    public function getEvents(){
        return $this->model->getEvents();
    }

    public function create($data){
        return $this->model->createEvent($data);
    }

    public function update($id, $data){
        return $this->model->updateEvent($id, $data);
    }

    public function delete($id) {
        return $this->model->deleteEvent($id);
    }
}