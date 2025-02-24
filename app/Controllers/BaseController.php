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
        extract($data);
        // تحويل الاسم إلى UpperCamelCase لجعل البحث متوافقًا مع الأسماء الحقيقية
        $viewFile = '../app/views/materials/' . ucfirst($view) . '.php';

        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            throw new \Exception("View $view not found");
        }
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

    protected function redirect($url) {
        header("Location: $url");
        exit;
    }
}
