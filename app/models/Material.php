<?php

namespace app\models;

use PDO;
use PDOException;
use Exception;

class Material {
    private $pdo;

    public function __construct() {
        $host = 'localhost';
        $db   = 'materialmanagementt';
        $user = 'root';
        $pass = '';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        try {
            $this->pdo = new PDO($dsn, $user, $pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new Exception("Connection failed: " . $e->getMessage());
        }
    }

    public function getAllMaterials() {
        try {
            $stmt = $this->pdo->query("
                SELECT m.*, b.name as branch_name, s.name as supplier_name 
                FROM materials m 
                LEFT JOIN branches b ON m.branch_id = b.id 
                LEFT JOIN suppliers s ON m.supplier_id = s.id
                WHERE m.deleted_at IS NULL
                ORDER BY m.id DESC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function createMaterial($data) {
        try {
            // Check if code already exists
            $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM materials WHERE code = ? AND deleted_at IS NULL");
            $stmt->execute([$data['code']]);
            if ($stmt->fetchColumn() > 0) {
                return [
                    'status' => 'error',
                    'message' => 'هذا الكود موجود بالفعل'
                ];
            }
            
            $stmt = $this->pdo->prepare("
                INSERT INTO materials (code, name, size, unit, quantity, branch_id, supplier_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            
            $success = $stmt->execute([
                $data['code'],
                $data['name'],
                $data['size'] ?? '',
                $data['unit'] ?? '',
                $data['quantity'],
                $data['branch_id'],
                !empty($data['supplier_id']) ? $data['supplier_id'] : null
            ]);

            if ($success) {
                return [
                    'status' => 'success',
                    'message' => 'تمت إضافة المادة بنجاح'
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'فشل في إضافة المادة'
                ];
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [
                'status' => 'error',
                'message' => 'حدث خطأ أثناء إضافة المادة'
            ];
        }
    }

    private function generateUniqueCode() {
        // Get the current year
        $year = date('Y');
        
        // Get the last code from the database for this year
        $stmt = $this->pdo->prepare("
            SELECT code 
            FROM materials 
            WHERE code LIKE :yearPrefix 
            ORDER BY code DESC 
            LIMIT 1
        ");
        $yearPrefix = "M-{$year}-";
        $stmt->execute([':yearPrefix' => $yearPrefix . '%']);
        $lastCode = $stmt->fetchColumn();
        
        if ($lastCode) {
            // Extract the number from the last code and increment it
            $lastNumber = intval(substr($lastCode, -4));
            $newNumber = $lastNumber + 1;
        } else {
            // If no codes exist for this year, start with 1
            $newNumber = 1;
        }
        
        // Format the new code with leading zeros
        return sprintf("M-%s-%04d", $year, $newNumber);
    }

    public function updateMaterial($data) {
        try {
            // التحقق من وجود البيانات المطلوبة
            if (!isset($data['id']) || !isset($data['name']) || !isset($data['quantity']) || !isset($data['branch_id'])) {
                throw new Exception("البيانات المطلوبة غير مكتملة");
            }

            // Check if the new code exists for other materials
            if (isset($data['code'])) {
                $stmt = $this->pdo->prepare("
                    SELECT COUNT(*) 
                    FROM materials 
                    WHERE code = ? AND id != ? AND deleted_at IS NULL
                ");
                $stmt->execute([$data['code'], $data['id']]);
                if ($stmt->fetchColumn() > 0) {
                    return [
                        'status' => 'error',
                        'message' => 'هذا الكود موجود بالفعل'
                    ];
                }
            }

            $sql = "UPDATE materials SET 
                    code = :code,
                    name = :name,
                    size = :size,
                    unit = :unit,
                    quantity = :quantity,
                    branch_id = :branch_id,
                    supplier_id = :supplier_id,
                    updated_at = NOW()
                    WHERE id = :id AND deleted_at IS NULL";

            $stmt = $this->pdo->prepare($sql);
            
            $params = [
                ':id' => $data['id'],
                ':code' => $data['code'],
                ':name' => $data['name'],
                ':size' => $data['size'] ?? '',
                ':unit' => $data['unit'] ?? '',
                ':quantity' => $data['quantity'],
                ':branch_id' => $data['branch_id'],
                ':supplier_id' => empty($data['supplier_id']) ? null : $data['supplier_id']
            ];

            $success = $stmt->execute($params);

            if ($success) {
                return [
                    'status' => 'success',
                    'message' => 'تم تحديث المادة بنجاح'
                ];
            } else {
                throw new Exception("فشل تحديث المادة");
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            return [
                'status' => 'error',
                'message' => 'حدث خطأ أثناء تحديث المادة: ' . $e->getMessage()
            ];
        }
    }

    public function deleteMaterial($id) {
        try {
            $stmt = $this->pdo->prepare("UPDATE materials SET deleted_at = NOW() WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}