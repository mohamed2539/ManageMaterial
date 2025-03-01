<?php

use app\core\Router;
use app\Controllers\AuthController;
use app\Controllers\MaterialController;
use app\Controllers\BranchController;
use app\Controllers\DashboardController;

$router = Router::getInstance();

// مسارات المصادقة
$router->get('/login', AuthController::class, 'index');
$router->post('/login', AuthController::class, 'login');
$router->get('/logout', AuthController::class, 'logout');
$router->post('/register', AuthController::class, 'register');
$router->post('/reset-password', AuthController::class, 'resetPassword');

// مسارات المواد
$router->get('/materials', MaterialController::class, 'index');
$router->get('/materials/create', MaterialController::class, 'create');
$router->post('/materials/store', MaterialController::class, 'store');
$router->get('/materials/getMaterials', MaterialController::class, 'getMaterials');
$router->get('/materials/getBranches', MaterialController::class, 'getBranches');
$router->get('/materials/getSupplier', MaterialController::class, 'getSupplier');
$router->get('/materials/{id}', MaterialController::class, 'show');
$router->get('/materials/{id}/edit', MaterialController::class, 'edit');
$router->put('/materials/{id}', MaterialController::class, 'update');
$router->delete('/materials/{id}', MaterialController::class, 'delete');
$router->post('/materials/issue', MaterialController::class, 'issueItem');

// مسارات الفروع
$router->get('/branches', BranchController::class, 'index');
$router->get('/branches/{id}', BranchController::class, 'show');
$router->post('/branches', BranchController::class, 'store');
$router->put('/branches/{id}', BranchController::class, 'update');
$router->delete('/branches/{id}', BranchController::class, 'delete');

// مسارات لوحة التحكم
$router->get('/', DashboardController::class, 'index');
$router->get('/dashboard', DashboardController::class, 'index');
$router->get('/dashboard/statistics', DashboardController::class, 'statistics');

return $router; 