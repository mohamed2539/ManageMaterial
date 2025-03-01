<?php

namespace app\core;

class Router {
    private static $instance = null;
    private $routes = [];
    private $currentController = null;
    private $currentAction = null;
    private $params = [];

    // Private constructor to prevent direct creation
    private function __construct() {}

    // Private clone method to prevent cloning
    private function __clone() {}

    // Public wakeup method to prevent unserializing
    public function __wakeup() {}

    /**
     * Get the singleton instance of the Router
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Register a GET route
     */
    public function get($path, $controller, $action) {
        $this->addRoute('GET', $path, $controller, $action);
        return $this;
    }

    /**
     * Register a POST route
     */
    public function post($path, $controller, $action) {
        $this->addRoute('POST', $path, $controller, $action);
        return $this;
    }

    /**
     * Register a PUT route
     */
    public function put($path, $controller, $action) {
        $this->addRoute('PUT', $path, $controller, $action);
        return $this;
    }

    /**
     * Register a DELETE route
     */
    public function delete($path, $controller, $action) {
        $this->addRoute('DELETE', $path, $controller, $action);
        return $this;
    }

    /**
     * Add a route to the routing table
     */
    private function addRoute($method, $path, $controller, $action) {
        // Remove trailing slashes
        $path = rtrim($path, '/');
        
        $this->routes[$method][$path] = [
            'controller' => $controller,
            'action' => $action
        ];
    }

    /**
     * Dispatch the route
     */
    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = $this->parseUrl();
        
        // Debug information
        error_log("Request Method: " . $method);
        error_log("Raw URL: " . $_SERVER['REQUEST_URI']);
        error_log("Parsed URL segments: " . print_r($url, true));

        // If URL is empty, use dashboard as default
        if (empty($url[0])) {
            $url[0] = 'dashboard';
        }

        // Convert plural to singular for controller name
        $controllerSegment = rtrim($url[0], 's');
        $controllerName = ucfirst($controllerSegment) . 'Controller';
        $controllerClass = "app\\Controllers\\" . $controllerName;

        error_log("Looking for controller: " . $controllerClass);

        // Check if controller exists
        if (class_exists($controllerClass)) {
            try {
                $this->currentController = new $controllerClass();
                unset($url[0]);

                // Determine the action
                $this->currentAction = isset($url[1]) ? $url[1] : 'index';
                if (isset($url[1])) {
                    unset($url[1]);
                }

                // Check if method exists
                if (!method_exists($this->currentController, $this->currentAction)) {
                    throw new \Exception("Method {$this->currentAction} not found in controller {$controllerClass}");
                }

                // Get remaining parameters
                $this->params = $url ? array_values($url) : [];

                error_log("Calling {$controllerClass}::{$this->currentAction}");
                call_user_func_array([$this->currentController, $this->currentAction], $this->params);

            } catch (\Exception $e) {
                error_log("Controller error: " . $e->getMessage());
                $this->error($e->getMessage());
            }
        } else {
            error_log("Controller not found: " . $controllerClass);
            $this->error("Controller not found: " . $controllerClass . "\nAvailable controllers in app\\Controllers\\: " . 
                        print_r(get_declared_classes(), true));
        }
    }

    /**
     * Parse the URL into segments
     */
    private function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }

    /**
     * Handle errors
     */
    private function error($message) {
        header("HTTP/1.0 404 Not Found");
        echo "Error: " . $message;
    }

    /**
     * Get all registered routes (for debugging)
     */
    public function getRoutes() {
        return $this->routes;
    }
} 