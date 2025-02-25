<?php

namespace app\models;
use PDO;
use config\database;

class Dashboard {
    private $pdo;

    public function __construct() {
        try {
            $this->pdo = (new database())->getConnection();
        } catch (\PDOException $e) {
            error_log('Database Connection Error: ' . $e->getMessage());
            throw new \Exception('خطأ في الاتصال بقاعدة البيانات');
        }
    }

    public function getTotalMaterials() {
        try {
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM materials");
            return $stmt->fetchColumn() ?? 0;
        } catch (\PDOException $e) {
            error_log('getTotalMaterials Error: ' . $e->getMessage());
            return 0;
        }
    }

    public function getTotalBranches() {
        try {
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM branches");
            return $stmt->fetchColumn() ?? 0;
        } catch (\PDOException $e) {
            error_log('getTotalBranches Error: ' . $e->getMessage());
            return 0;
        }
    }

    public function getTotalSuppliers() {
        try {
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM suppliers");
            return $stmt->fetchColumn() ?? 0;
        } catch (\PDOException $e) {
            error_log('getTotalSuppliers Error: ' . $e->getMessage());
            return 0;
        }
    }

    public function getTotalActiveUsers() {
        try {
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM users WHERE status = 'active'");
            return $stmt->fetchColumn() ?? 0;
        } catch (\PDOException $e) {
            error_log('getTotalActiveUsers Error: ' . $e->getMessage());
            return 0;
        }
    }

    public function getRecentActivities($limit = 10) {
        try {
            $sql = "
                SELECT 
                    'dispense' as type,
                    d.created_at,
                    m.name as material_name,
                    d.quantity,
                    u.username as user_name,
                    b.name as branch_name
                FROM dispense_materials d
                JOIN materials m ON d.material_id = m.id
                JOIN users u ON d.user_id = u.id
                JOIN branches b ON m.branch_id = b.id
                UNION ALL
                SELECT 
                    'addition' as type,
                    mq.created_at,
                    m.name as material_name,
                    mq.quantity,
                    u.username as user_name,
                    b.name as branch_name
                FROM material_quantities mq
                JOIN materials m ON mq.material_id = m.id
                JOIN users u ON mq.user_id = u.id
                JOIN branches b ON m.branch_id = b.id
                ORDER BY created_at DESC
                LIMIT :limit
            ";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?? [];
        } catch (\PDOException $e) {
            error_log('getRecentActivities Error: ' . $e->getMessage());
            return [];
        }
    }

    public function getLowStockItems() {
        try {
            $sql = "
                SELECT 
                    m.*,
                    b.name as branch_name
                FROM materials m
                JOIN branches b ON m.branch_id = b.id
                WHERE m.quantity <= m.min_quantity
            ";
            
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?? [];
        } catch (\PDOException $e) {
            error_log('getLowStockItems Error: ' . $e->getMessage());
            return [];
        }
    }
}