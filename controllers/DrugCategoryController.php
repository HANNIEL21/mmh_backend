<?php

require_once "../models/DrugCategory.php";

class DrugCategoryController
{
    private $model;

    public function __construct($db)
    {
        $this->model = new DrugCategory($db);
    }

    public function getAll()
    {
        return $this->model->getAll();
    }

    public function getOne($id)
    {
        return $this->model->getOne($id);
    }

    public function create($data)
    {
        return $this-> model->create($data);
    }

    public function update($id, $data)
    {
        return $this->model->update($id, $data);
    }

    public function delete($id)
    {
        return $this->model->delete($id);
    }
}