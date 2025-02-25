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
        
        $data['currentUser'] = [
            'id' => $_SESSION['user_id'] ?? null,
            'username' => $_SESSION['username'] ?? null,
            'role' => $_SESSION['role'] ?? null,
            'full_name' => $_SESSION['full_name'] ?? null
        ];
    
        extract($data);
    
        // تنظيف المسار من أي بادئات غير ضرورية
        $view = str_replace('../views/', '', $view);
        
        // تحديد المسار الصحيح بناءً على نوع الصفحة
        if (strpos($view, 'users/') === 0) {
            $viewFile = '../app/views/' . $view . '.php';
        } else if (strpos($view, 'Suppliers/') === 0) {
            $viewFile = '../app/views/' . $view . '.php';
        } else {
            // للصفحات الأخرى (المواد)
            $viewFile = '../app/views/materials/' . ucfirst($view) . '.php';
        }
    
        if (!file_exists($viewFile)) {
            throw new \Exception("View $view not found at: $viewFile");
        }
    
        require_once $viewFile;
    }

    protected function jsonResponse($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
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