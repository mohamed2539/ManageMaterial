<?php

namespace app\models;
use PDO;
use config\database;

class Branch {
    private $pdo;

    public function __construct() {
        $this->pdo = (new database())->getConnection(); // ✅ تصحيح استدعاء الكلاس
    }

    public function getAllBranches() {
        $stmt = $this->pdo->query("SELECT * FROM branches");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLast20Branches() {
        $stmt = $this->pdo->query("SELECT * FROM branches  LIMIT 20");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createBranch($data) {
        $stmt = $this->pdo->prepare("INSERT INTO branches (name, address, phone, email, manager_name, notes) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['name'],
            $data['address'],
            $data['phone'],
            $data['email'],
            $data['manager_name'],
            $data['notes']
        ]);
    }


    public function deleteBranch($id) {
        $stmt = $this->pdo->prepare("DELETE FROM branches WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function updateBranch($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE branches SET name = ?, address = ?, phone = ?, email = ?, manager_name = ?, notes = ? WHERE id = ?");

        if (!$stmt->execute([
            $data['name'],
            $data['address'],
            $data['phone'],
            $data['email'],
            $data['manager_name'],
            $data['notes'],
            $id
        ])) {
            var_dump($stmt->errorInfo()); // ✅ طباعة أي أخطاء SQL
            exit;
        }

        return true;
    }

}
