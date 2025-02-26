<?php

namespace app\Controllers;
use app\models\User;
use app\models\Branch;
use app\middleware\AuthMiddleware;

class UserController extends BaseController {
    private $userModel;
    private $branchModel;

    public function __construct() {
        $this->userModel = new User();
        $this->branchModel = new Branch();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $user = $this->userModel->getUserByUsername($username);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = $user;
                header("Location: /dashboard.php");
                exit;
            } else {
                $error = "Invalid credentials";
                require 'views/auth/login.php';
            }
        } else {
            require 'views/auth/login.php';
        }
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $fullName = $_POST['full_name'];
            $branchId = $_POST['branch_id'];
            $role = $_POST['role'];

            if ($this->userModel->registerUser($username, $password, $fullName, $branchId, $role)) {
                header("Location: /login.php");
                exit;
            } else {
                $error = "Registration failed";
                require 'views/auth/register.php';
            }
        } else {
            require 'views/auth/register.php';
        }
    }
    /*========================================Temp Code====================================================*/
    /*========================================Temp Code====================================================*/
    /*========================================Temp Code====================================================*/







    public function index() {
        $users = $this->userModel->getAllUsers();
        $this->renderView('users/index', ['users' => $users]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['status' => 'error', 'message' => 'طريقة طلب غير صحيحة']);
        }
    
        $data = [
            'username' => $_POST['username'],
            'password' => $_POST['password'],
            'full_name' => $_POST['full_name'],
            'branch_id' => $_POST['branch_id'],
            'role' => $_POST['role'],
            'status' => 'active'
        ];
    
        if ($this->userModel->createUser($data)) {
            $this->jsonResponse(['status' => 'success', 'message' => 'تم إضافة المستخدم بنجاح']);
        } else {
            $this->jsonResponse(['status' => 'error', 'message' => 'حدث خطأ أثناء إضافة المستخدم']);
        }
    }

    public function edit($id) {
        $user = $this->userModel->getUserById($id);
        if ($user) {
            $this->jsonResponse($user);
        } else {
            $this->jsonResponse(['status' => 'error', 'message' => 'المستخدم غير موجود']);
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['status' => 'error', 'message' => 'طريقة طلب غير صحيحة']);
        }

        $id = $_POST['id'];
        $data = [
            'username' => $_POST['username'],
            'full_name' => $_POST['full_name'],
            'branch_id' => $_POST['branch_id'],
            'role' => $_POST['role'],
            'status' => $_POST['status']
        ];

        if (!empty($_POST['password'])) {
            $data['password'] = $_POST['password'];
        }

        if ($this->userModel->updateUser($id, $data)) {
            $this->jsonResponse(['status' => 'success', 'message' => 'تم تحديث بيانات المستخدم بنجاح']);
        } else {
            $this->jsonResponse(['status' => 'error', 'message' => 'حدث خطأ أثناء تحديث البيانات']);
        }
    }

    public function getBranches() {
        try {
            $branches = $this->branchModel->getAllBranches();
            $this->jsonResponse($branches);
        } catch (\Exception $e) {
            $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }
}