<?php
// models/LiveSearch.php
class LiveSearch {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function searchMaterials($query) {
        $stmt = $this->db->prepare("SELECT id, code, name, quantity FROM materials WHERE name LIKE ? OR code LIKE ? LIMIT 10");
        $searchTerm = "%" . $query . "%";
        $stmt->execute([$searchTerm, $searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}