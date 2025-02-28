<?php
namespace app\Controllers;

abstract class BaseController {
    protected function loadModel($model) {
        $modelPath = "../app/models/$model.php";
        $modelClass = "app\\models\\$model";

        if (file_exists($modelPath)) {
            require_once $modelPath;
            if (class_exists($modelClass)) {
                return new $modelClass();
            }
            throw new \Exception("Class $modelClass not found in file");
        }
        throw new \Exception("Model $model not found");
    }

    protected function renderView($view, $data = []) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // إضافة بيانات المستخدم والقائمة النشطة
        $data['currentUser'] = [
            'id' => $_SESSION['user_id'] ?? null,
            'username' => $_SESSION['username'] ?? null,
            'role' => $_SESSION['role'] ?? null,
            'full_name' => $_SESSION['full_name'] ?? null,
            'branch_id' => $_SESSION['branch_id'] ?? null,
            'branch_name' => $_SESSION['branch_name'] ?? null
        ];

        extract($data);

        // تخزين محتوى الصفحة
        ob_start();
        
        // تحديد مسار الملف
        $viewFile = $this->resolveViewPath($view);
        
        if (!file_exists($viewFile)) {
            throw new \Exception("View $view not found at: $viewFile");
        }

        require_once $viewFile;
        $content = ob_get_clean();

        // التحقق من وجود layout مخصص
        $layoutFile = "../app/views/layouts/layout.php";
        
        if (file_exists($layoutFile)) {
            require_once $layoutFile;
        } else {
            echo $content;
        }
    }

    private function resolveViewPath($view) {
        $view = str_replace('../views/', '', $view);
        
        // تحديد المسار حسب نوع الصفحة
        if (strpos($view, 'users/') === 0 || 
            strpos($view, 'Suppliers/') === 0 || 
            strpos($view, 'dashboard/') === 0) {
            return '../app/views/' . $view . '.php';
        } else {
            return '../app/views/materials/' . ucfirst($view) . '.php';
        }
    }

    protected function jsonResponse($data, $statusCode = 200) {
        try {
            header('Content-Type: application/json');
            http_response_code($statusCode);
            echo json_encode($data);
            exit;
        } catch (\Exception $e) {
            error_log("JSON RESPONSE ERROR: " . $e->getMessage());
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'خطأ في تنسيق البيانات']);
            exit;
        }
    }

    protected function getRequestData() {
        return json_decode(file_get_contents('php://input'), true) ?? [];
    }

    protected function redirect($controller, $action = 'index', $params = []) {
        $url = "/MaterailManegmentT/public/index.php?controller=$controller&action=$action";
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $url .= "&$key=$value";
            }
        }
        header("Location: $url");
        exit;
    }
}