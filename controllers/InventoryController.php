<?php

require_once "../models/Inventory.php";

class InventoryController {
    private $model;

    public function __construct($db){
        $this->model = new Inventory($db);
    }

    public function getItem($id){
        return $this->model->getItem($id);
    }

    public function getItems(){
        return $this->model->getItems();
    }

    public function createItem($data){
        return $this->model->create($data);
    }

    public function updateItem($id, $data){
        return $this->model->update($id, $data);
    }

    public function deleteItem($id){
        return $this->model->delete($id);
    }
}