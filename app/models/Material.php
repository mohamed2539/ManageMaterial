<?php

namespace app\models;
use PDO;
use config\database;

class Material {
    private $pdo;

    public function __construct() {
        $this->pdo = (new database())->getConnection();
    }

    public function getAllMaterials() {
        $stmt = $this->pdo->query("SELECT * FROM materials");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLast20Materials() {
        $stmt = $this->pdo->query("SELECT * FROM materials ORDER BY id DESC LIMIT 20");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function generateCode($name, $size) {
        // توليد كود فريد من 3 أحرف/أرقام
        $prefix = strtoupper(substr($name, 0, 2)); // أول حرفين من الاسم
        $random = substr(str_shuffle("0123456789"), 0, 1); // رقم عشوائي
        return $prefix . $random;
    }




    public function createMaterial($data) {
        $code = $this->generateCode($data['name'], $data['size']);
        $supplier_id = !empty($data['supplier_id']) ? $data['supplier_id'] : null;
        
        // نستخدم استعلام أبسط مؤقتاً بدون last_updated_by
        $stmt = $this->pdo->prepare("INSERT INTO materials 
            (name, size, unit, quantity, branch_id, code, supplier_id, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
        
        return $stmt->execute([
            $data['name'],
            $data['size'] ?? null,
            $data['unit'] ?? null,
            $data['quantity'],
            $data['branch_id'],
            $code,
            $supplier_id
        ]);
    }









    public function getSuppliers() {
        $stmt = $this->pdo->query("SELECT id, name FROM suppliers");
        $suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($suppliers);
        exit;
    }

    public function updateMaterial($id, $data) {
        $last_updated_by = 1; // قيمة افتراضية
        
        $stmt = $this->pdo->prepare("UPDATE materials 
                                   SET name = ?, size = ?, unit = ?, quantity = ?, 
                                       branch_id = ?, supplier_id = ?, last_updated_by = ? 
                                   WHERE id = ?");

        return $stmt->execute([
            $data['name'],
            $data['size'],
            $data['unit'],
            $data['quantity'],
            $data['branch_id'],
            $data['supplier_id'] ?? null,
            $last_updated_by,
            $id
        ]);
    }

    public function deleteMaterial($id) {
        $stmt = $this->pdo->prepare("DELETE FROM materials WHERE id = ?");
        return $stmt->execute([$id]);
    }




// في ملف Material.php
public function searchMaterials($name, $supplier_id) {
    $stmt = $this->pdo->prepare("SELECT * FROM materials WHERE name LIKE ? AND (supplier_id = ? OR ? = '')");
    $stmt->execute(["%$name%", $supplier_id, $supplier_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}









}