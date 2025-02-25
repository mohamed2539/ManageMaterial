<?php

namespace app\Controllers;
use app\models\User;

class AuthController extends BaseController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index() {
        if (isset($_SESSION['user_id'])) {
            $this->redirectBasedOnRole($_SESSION['role'], $_SESSION['branch_id']);
        }
        
        $this->renderView('users/login');
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('auth', 'index');
            return;
        }
    
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
    
        $user = $this->userModel->findByUsername($username);
    
        if (!$user || !$this->userModel->verifyPassword($password, $user['password'])) {
            $this->renderView('users/login', ['error' => 'اسم المستخدم أو كلمة المرور غير صحيحة']);
            return;
        }
    
        if ($user['status'] !== 'active') {
            $this->renderView('users/login', ['error' => 'هذا الحساب غير نشط']);
            return;
        }
    
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['branch_id'] = $user['branch_id'];
        $_SESSION['branch_name'] = $user['branch_name'];
    
        $this->redirectBasedOnRole($user['role'], $user['branch_id']);
    }

    protected function redirectBasedOnRole($role, $branchId) {
        switch ($role) {
            case 'admin':
            case 'main_branch':
                $this->redirect('material', 'viewAll');
                break;
            default:
                $this->redirect('material', 'viewAll', ['branch_id' => $branchId]);
                break;
        }
    }

    public function logout() {
        session_destroy();
        $this->redirect('auth', 'index');
    }
}