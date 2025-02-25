<?php

namespace app\middleware;

class AuthMiddleware {
    public static function isAuthenticated() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            header('Location: /MaterailManegmentT/public/index.php?controller=auth&action=index');
            exit;
        }
    }

    public static function isAdmin() {
        self::isAuthenticated();
        if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'main_branch') {
            header('Location: /MaterailManegmentT/public/index.php?controller=error&action=forbidden');
            exit;
        }
    }

    public static function canAccessBranch($branchId) {
        self::isAuthenticated();
        return $_SESSION['role'] === 'admin' || 
               $_SESSION['role'] === 'main_branch' || 
               $_SESSION['branch_id'] == $branchId;
    }
}