<?php

namespace app\models;
use PDO;
use config\database;

class Inventory {
    private $pdo;

    public function __construct() {
        $this->pdo = (new database())->getConnection();
    }

    public function getLowStockAlerts() {
        $stmt = $this->pdo->query("
            SELECT m.*, b.name as branch_name
            FROM materials m
            JOIN branches b ON m.branch_id = b.id
            WHERE m.quantity <= m.min_quantity
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOverStockAlerts() {
        $stmt = $this->pdo->query("
            SELECT m.*, b.name as branch_name
            FROM materials m
            JOIN branches b ON m.branch_id = b.id
            WHERE m.quantity >= m.max_quantity
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getInventoryLogs() {
        $stmt = $this->pdo->query("
            SELECT l.*, m.name as material_name, u.username
            FROM inventory_logs l
            JOIN materials m ON l.material_id = m.id
            JOIN users u ON l.user_id = u.id
            ORDER BY l.created_at DESC
            LIMIT 100
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}