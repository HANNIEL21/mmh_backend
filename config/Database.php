<?php

class Database
{
    private $config;
    public $conn;

    public function __construct()
    {
        $this->config = parse_ini_file(__DIR__ . "/config.ini", true);
    }

    public function getConnection()
    {
        $this->conn = null;

        try {
            $dsn = "mysql:host={$this->config['database']['host']};dbname={$this->config['database']['database']}";
            $this->conn = new PDO($dsn, $this->config['database']['username'], $this->config['database']['password']);
        } catch (PDOException $e) {
            ErrorHandler::handleException($e);
        }

        return $this->conn;
    }
}