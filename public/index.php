<?php

// تعيين عرض الأخطاء
error_reporting(E_ALL);
ini_set('display_errors', 1);

// تحديد المسار الأساسي
define('ROOT_PATH', dirname(__DIR__));

// تحميل الأوتولودر
require_once ROOT_PATH . '/vendor/autoload.php';

// تسجيل الأوتولودر المخصص
spl_autoload_register(function ($class) {
    $file = ROOT_PATH . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// التحقق من وجود ملف .env
if (file_exists(ROOT_PATH . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(ROOT_PATH);
    $dotenv->load();
} else {
    die('ملف .env غير موجود. يرجى نسخ .env.example إلى .env');
}

// تعيين المنطقة الزمنية
date_default_timezone_set('Africa/Cairo');

// بدء الجلسة
session_start();

// تهيئة الراوتر وتنفيذ الطلب
$router = app\core\Router::getInstance();
$router->dispatch();
