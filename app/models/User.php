<?php

namespace app\models;
use PDO;
use config\database;

class User {
    private $db;

    public function __construct() {
        $this->pdo = (new database())->getConnection();
    }

    public function getUserByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function registerUser($username, $password, $fullName, $branchId, $role) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO users (username, password, full_name, branch_id, role, status, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        return $stmt->execute([$username, $hashedPassword, $fullName, $branchId, $role, 'active']);
    }

/*========================================Temp Code====================================================*/
    public function findByUsername($username) {
        $stmt = $this->pdo->prepare("
            SELECT users.*, branches.name as branch_name 
            FROM users 
            LEFT JOIN branches ON users.branch_id = branches.id 
            WHERE users.username = ?
        ");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function verifyPassword($inputPassword, $hashedPassword) {
        if (strlen($hashedPassword) == 32) {
            return md5($inputPassword) === $hashedPassword;
        }
        return password_verify($inputPassword, $hashedPassword);
    }

    public function getAllUsers() {
        $stmt = $this->pdo->query("
            SELECT users.*, branches.name as branch_name 
            FROM users 
            LEFT JOIN branches ON users.branch_id = branches.id
            ORDER BY users.id DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function createUser($data) {
        try {
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            
            $stmt = $this->pdo->prepare("
                INSERT INTO users (
                    username, password, full_name, branch_id, 
                    role, status, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, NOW())
            ");
        
            return $stmt->execute([
                $data['username'],
                $hashedPassword,
                $data['full_name'],
                $data['branch_id'],
                $data['role'],
                $data['status']
            ]);
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
    
    public function getUserById($id) {
        $stmt = $this->pdo->prepare("
            SELECT users.*, branches.name as branch_name 
            FROM users 
            LEFT JOIN branches ON users.branch_id = branches.id 
            WHERE users.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function updateUser($id, $data) {
        try {
            $sql = "UPDATE users SET 
                    username = ?, 
                    full_name = ?, 
                    branch_id = ?, 
                    role = ?, 
                    status = ?";
            
            $params = [
                $data['username'],
                $data['full_name'],
                $data['branch_id'],
                $data['role'],
                $data['status']
            ];
        
            if (!empty($data['password'])) {
                $sql .= ", password = ?";
                $params[] = password_hash($data['password'], PASSWORD_DEFAULT);
            }
        
            $sql .= " WHERE id = ?";
            $params[] = $id;
        
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}