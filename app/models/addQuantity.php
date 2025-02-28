<?php
namespace app\models;
use PDO;
use config\database;

class AddQuantity {
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




    
    public function addQuantity($material_id, $quantity) {
        try {
            $this->pdo->beginTransaction();
    
            $stmt = $this->pdo->prepare("
                UPDATE materials 
                SET quantity = quantity + ?, 
                    updated_at = NOW() 
                WHERE id = ?
            ");
            
            $stmt->execute([$quantity, $material_id]);
            
            // نتحقق من عدد الصفوف المتأثرة
            if ($stmt->rowCount() > 0) {
                $this->pdo->commit();
                return true;
            }
            
            $this->pdo->rollBack();
            return false;
            
        } catch (\Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            return false;
        }
    }







/*
    public function addQuantity($material_id, $quantity) {
        try {
            $this->pdo->beginTransaction();

            // تحديث الكمية
            $stmt = $this->pdo->prepare("
                UPDATE materials 
                SET quantity = quantity + ?, 
                    updated_at = NOW() 
                WHERE id = ?
            ");
            
            $success = $stmt->execute([$quantity, $material_id]);
            
            if ($success) {
                $this->pdo->commit();
                return true;
            }
            
            $this->pdo->rollBack();
            return false;
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    */
    // ... باقي الدوال من النموذج السابق (getSuppliers, getBranches, getSizes) ...
}