<?php

namespace app\Controllers;

class LoginController extends BaseController {
    public function showLoginForm() {
        $this->renderView('login');
    }

    public function authenticate() {
        $data = $this->getRequestData();

        // مثال بسيط للتحقق من تسجيل الدخول
        if ($data['username'] === 'admin' && $data['password'] === 'password') {
            $_SESSION['user'] = $data['username'];
            $this->redirect('dashboard.php');
        } else {
            $this->jsonResponse(['error' => 'Invalid credentials'], 401);
        }
    }

    public function logout() {
        session_destroy();
        $this->redirect('index.php');
    }
}
