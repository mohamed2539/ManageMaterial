<?php

namespace config;
use PDO;
use PDOException;
class database {
    private  $host = "localhost";
    private  $dbname = "materialmanagementt";
    private  $username = "root";
    private  $password = "";
    private  $pdo = null;

    public function __construct() {
        try {
            $this->pdo = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            die("خطأ في الاتصال بقاعدة البيانات: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->pdo;
    }
}


