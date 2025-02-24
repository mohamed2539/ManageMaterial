<?php
require_once "../core/Database.php";

class Dispense {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function dispenseMaterial($material_id, $quantity, $user_id, $branch_id) {
        try {
            $this->db->beginTransaction();

            // تقليل الكمية في جدول المواد
            $updateQuery = "UPDATE materials SET quantity = quantity - ? WHERE id = ? AND branch_id = ?";
            $this->db->query($updateQuery, [$quantity, $material_id, $branch_id]);

            // تسجيل العملية في جدول الصرف
            $insertQuery = "INSERT INTO dispense_records (material_id, quantity, user_id, branch_id, dispense_date) VALUES (?, ?, ?, ?, NOW())";
            $this->db->query($insertQuery, [$material_id, $quantity, $user_id, $branch_id]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }
}
