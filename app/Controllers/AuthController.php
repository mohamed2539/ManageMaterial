<?php

namespace app\Controllers;
use app\models\User;
use app\utils\Database;
use app\utils\Session;
use app\utils\Logger;

class AuthController extends BaseController {
    private $userModel;
    private $db;
    private $session;

    public function __construct() {
        $this->userModel = new User();
        $this->db = Database::getInstance();
        $this->session = new Session();
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

    public function login($email, $password) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ? AND is_active = 1");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // تحديث آخر تسجيل دخول
                $this->db->prepare("UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE id = ?")->execute([$user['id']]);
                
                // تخزين بيانات المستخدم في الجلسة
                $this->session->set('user_id', $user['id']);
                $this->session->set('user_role', $user['role']);
                $this->session->set('branch_id', $user['branch_id']);

                return ['success' => true, 'message' => 'تم تسجيل الدخول بنجاح'];
            }

            return ['success' => false, 'message' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة'];
        } catch (Exception $e) {
            Logger::error('Login error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'حدث خطأ أثناء تسجيل الدخول'];
        }
    }

    protected function redirectBasedOnRole($role, $branchId) {
        switch ($role) {
            case 'admin':
            case 'main_branch':
                header("Location: /MaterailManegmentT/app/views/materials/issueItem.php");
                exit;
            default:
                header("Location: /MaterailManegmentT/app/views/materials/viewAll.php?branch_id=" . $branchId);
                exit;
        }
    }

    public function logout() {
        $this->session->destroy();
        return ['success' => true, 'message' => 'تم تسجيل الخروج بنجاح'];
    }

    public function register($userData) {
        try {
            // التحقق من عدم وجود المستخدم
            $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$userData['email']]);
            if ($stmt->fetch()) {
                return ['success' => false, 'message' => 'البريد الإلكتروني مسجل مسبقاً'];
            }

            // تشفير كلمة المرور
            $hashedPassword = password_hash($userData['password'], PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (username, email, password, full_name, role, branch_id) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $userData['username'],
                $userData['email'],
                $hashedPassword,
                $userData['full_name'],
                $userData['role'] ?? 'user',
                $userData['branch_id'] ?? null
            ]);

            return ['success' => true, 'message' => 'تم إنشاء الحساب بنجاح'];
        } catch (Exception $e) {
            Logger::error('Registration error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'حدث خطأ أثناء إنشاء الحساب'];
        }
    }

    public function resetPassword($email) {
        try {
            $token = bin2hex(random_bytes(32));
            $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $stmt = $this->db->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
            $stmt->execute([$token, $expiry, $email]);

            if ($stmt->rowCount() > 0) {
                // إرسال بريد إلكتروني برابط إعادة تعيين كلمة المرور
                $resetLink = "https://your-domain.com/reset-password?token=" . $token;
                // TODO: implement email sending
                return ['success' => true, 'message' => 'تم إرسال رابط إعادة تعيين كلمة المرور'];
            }

            return ['success' => false, 'message' => 'البريد الإلكتروني غير مسجل'];
        } catch (Exception $e) {
            Logger::error('Password reset error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'حدث خطأ أثناء إعادة تعيين كلمة المرور'];
        }
    }
}