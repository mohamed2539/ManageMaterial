<?php

namespace app\core;

class Session {
    private $flashMessages = [];

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // تحميل رسائل الفلاش من الجلسة
        $this->flashMessages = $_SESSION['flash_messages'] ?? [];
        unset($_SESSION['flash_messages']);
    }

    public function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public function get($key, $default = null) {
        return $_SESSION[$key] ?? $default;
    }

    public function remove($key) {
        unset($_SESSION[$key]);
    }

    public function destroy() {
        session_destroy();
    }

    public function setFlash($key, $message) {
        $this->flashMessages[$key] = $message;
        $_SESSION['flash_messages'] = $this->flashMessages;
    }

    public function getFlash($key) {
        $message = $this->flashMessages[$key] ?? null;
        unset($this->flashMessages[$key]);
        return $message;
    }

    public function hasFlash($key) {
        return isset($this->flashMessages[$key]);
    }

    public function getAllFlash() {
        $messages = $this->flashMessages;
        $this->flashMessages = [];
        return $messages;
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public function getUserId() {
        return $_SESSION['user_id'] ?? null;
    }

    public function getUserRole() {
        return $_SESSION['user_role'] ?? null;
    }
} 