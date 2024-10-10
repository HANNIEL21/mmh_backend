<?php

require_once __DIR__ ."/../models/Appointment.php";

class AppointmentController{
    private Appointment $model;

    public function __construct($db){
        $this->model = new Appointment($db);
    }

    public function getAppointment($id){
        return $this->model->getAppointment($id);
    }
    public function getAppointmentRef($id){
        return $this->model->getAppointmentRef($id);
    }

    public function getAppointments(){
        return $this->model->getAppointments();
    }

    public function createAppointment($data){
        return $this->model->create($data);
    }

    public function deleteAppopintment($id){
        return$this->model->delete($id);
    }

    public function updateAppointment($id, $data){
        return $this->model->update($id, $data);
    }

}