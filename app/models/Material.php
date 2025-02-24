<?php
namespace app\models; // All lowercase

use app\config\database; // Updated to lowercase

class Material {
    private $db;

    public function __construct() {
        $this->db = Database::connect(); // Use the updated namespace
    }

    public function addMaterial($data) {
        $query = "INSERT INTO materials (name, size, unit, quantity, branch_id, code) 
                  VALUES (:name, :size, :unit, :quantity, :branch_id, :code)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }

    public function getBranches() {
        $query = "SELECT id, name FROM branches";
        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }
}