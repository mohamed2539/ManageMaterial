<?php
namespace app\models;
use PDO;
use config\database;

class Search {
    private $pdo;

    public function __construct() {
        $this->pdo = (new database())->getConnection();
    }

    public function searchMaterials($searchTerm, $supplier_id = '', $branch_id = '', $size = '') {
        $sql = "SELECT m.*, s.name as supplier_name, b.name as branch_name 
                FROM materials m 
                LEFT JOIN suppliers s ON m.supplier_id = s.id 
                LEFT JOIN branches b ON m.branch_id = b.id 
                WHERE 1=1";
        $params = [];

        if (!empty($searchTerm)) {
            $sql .= " AND m.name LIKE ?";
            $params[] = "%$searchTerm%";
        }

        if (!empty($supplier_id)) {
            $sql .= " AND m.supplier_id = ?";
            $params[] = $supplier_id;
        }

        if (!empty($branch_id)) {
            $sql .= " AND m.branch_id = ?";
            $params[] = $branch_id;
        }

        if (!empty($size)) {
            $sql .= " AND m.size = ?";
            $params[] = $size;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSuppliers() {
        $stmt = $this->pdo->query("SELECT id, name FROM suppliers");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBranches() {
        $stmt = $this->pdo->query("SELECT id, name FROM branches");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSizes() {
        $stmt = $this->pdo->query("SELECT DISTINCT size FROM materials WHERE size IS NOT NULL");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}