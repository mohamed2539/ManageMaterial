<?php

namespace app\core;

class Controller {
    protected $view;
    protected $session;

    public function __construct() {
        $this->session = new Session();
    }

    protected function render($view, $data = []) {
        // استخراج البيانات لتكون متاحة في ملف العرض
        extract($data);

        // تحديد مسار ملف العرض
        $viewPath = __DIR__ . '/../views/' . $view . '.php';
        
        // التحقق من وجود ملف العرض
        if (!file_exists($viewPath)) {
            throw new \Exception("View file not found: {$view}");
        }

        // بدء تخزين المخرجات
        ob_start();
        
        // تضمين ملف العرض
        include $viewPath;
        
        // إرجاع المحتوى وتنظيف المخرج
        return ob_get_clean();
    }

    protected function redirect($url) {
        header("Location: " . $url);
        exit();
    }

    protected function json($data) {
        header('Content-Type: application/json');
        return json_encode($data);
    }

    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function isPut() {
        return $_SERVER['REQUEST_METHOD'] === 'PUT';
    }

    protected function isDelete() {
        return $_SERVER['REQUEST_METHOD'] === 'DELETE';
    }

    protected function getRequestMethod() {
        return $_SERVER['REQUEST_METHOD'];
    }

    protected function setFlash($key, $message) {
        $this->session->setFlash($key, $message);
    }

    protected function getFlash($key) {
        return $this->session->getFlash($key);
    }

    protected function notFound() {
        header("HTTP/1.0 404 Not Found");
        return $this->render('errors/404');
    }

    protected function forbidden() {
        header("HTTP/1.0 403 Forbidden");
        return $this->render('errors/403');
    }

    protected function unauthorized() {
        header("HTTP/1.0 401 Unauthorized");
        return $this->render('errors/401');
    }

    protected function getPostData() {
        return $_POST;
    }

    protected function getQueryParams() {
        return $_GET;
    }

    protected function validateRequired($data, $fields) {
        $errors = [];
        foreach ($fields as $field) {
            if (!isset($data[$field]) || empty(trim($data[$field]))) {
                $errors[$field] = "الحقل {$field} مطلوب";
            }
        }
        return $errors;
    }
}