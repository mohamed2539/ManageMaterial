<?php

use app\Controllers\BranchController;
use app\Controllers\SupplierController; // ✅ إضافة SupplierController

require_once '../vendor/autoload.php'; // ✅ تحميل الـ Autoload
require_once '../config/config.php';   // ✅ تحميل الإعدادات

$controllerName = isset($_GET['controller']) ? ucfirst($_GET['controller']) . 'Controller' : 'SupplierController'; // ✅ الافتراضي الآن هو SupplierController
$action = $_GET['action'] ?? 'index';

$controllerClass = "app\\Controllers\\$controllerName"; // ✅ Namespace صحيح

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
