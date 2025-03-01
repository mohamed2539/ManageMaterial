<?php

namespace app\core;

class BaseController {
    protected function render($view, $data = []) {
        // Extract data to make it available in view
        extract($data);
        
        // Get the view file path
        $viewFile = __DIR__ . "/../views/{$view}.php";
        
        // Check if view exists
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            throw new \Exception("View {$view} not found");
        }
    }

    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function redirect($url) {
        header("Location: {$url}");
        exit;
    }
} 