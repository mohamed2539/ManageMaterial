<?php

use app\Controllers\BranchController;

require_once '../vendor/autoload.php'; // ✅ استخدم Autoload لتحميل الكلاسات تلقائيًا
require_once '../config/config.php';   // ✅ تحميل الإعدادات

$controllerName = isset($_GET['controller']) ? ucfirst($_GET['controller']) . 'Controller' : 'BranchController';
$action = $_GET['action'] ?? 'index';

$controllerClass = "app\\Controllers\\$controllerName"; // ✅ استخدام الـ Namespace الصحيح

if (class_exists($controllerClass)) {
    $controller = new $controllerClass();
    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        die("❌ العملية '$action' غير موجودة في المتحكم '$controllerClass'");
    }
} else {
    die("❌ المتحكم '$controllerClass' غير موجود - تأكد من اسم الـ controller في الرابط و الـ namespace");
}
