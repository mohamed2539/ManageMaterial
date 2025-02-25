<?php

namespace app\models;
use PDO;
use config\database;
class Supplier {
    private $pdo;

    public function __construct() {
        $this->pdo = (new database())->getConnection(); // ✅ تصحيح استدعاء الكلاس
    }

    public function getLast20Suppliers() {
        $stmt = $this->pdo->prepare("SELECT * FROM suppliers ORDER BY created_at DESC LIMIT 20");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    public function getAllSuppliers() {
        $stmt = $this->pdo->prepare("SELECT * FROM suppliers ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createSupplier($data) {
        $query = "INSERT INTO suppliers (name, phone, email, address, created_by, created_at) 
              VALUES (:name , :phone, :email, :address, :created_by, NOW())";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'address' => $data['address'],
            'created_by' => 'admin' // الافتراضي حتى يتم إضافة نظام تسجيل الدخول
        ]);

        return $stmt->rowCount() > 0; // ✅ يعيد true إذا تم إدخال صف جديد
    }


    public function deleteSupplier($id) {
        $stmt = $this->pdo->prepare("DELETE FROM suppliers WHERE id = ?");
        $stmt->execute([$id]);

        return $stmt->rowCount() > 0; // ✅ إرجاع true إذا تم الحذف بنجاح
    }

    public function updateSupplier($data) {
        if (!isset($data['id'], $data['name'], $data['phone'], $data['email'], $data['address'])) {
            return false; // ✅ رفض الطلب إذا كانت البيانات غير مكتملة
        }

        $query = "UPDATE suppliers SET name = :name, phone = :phone, email = :email, address = :address, updated_at = NOW() WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            'id' => $data['id'],
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'address' => $data['address']
        ]);

        return $stmt->rowCount() > 0;
    }

}
