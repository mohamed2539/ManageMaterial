<?php

namespace app\Controllers;

use app\models\Supplier;
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Supplier.php';

class SupplierController extends BaseController {
    private $supplierModel;

    public function __construct() {
        $this->supplierModel = $this->loadModel('Supplier');
    }

    public function index() {
        $suppliers = $this->supplierModel->getLast20Suppliers();
        $this->renderView('Suppliers/addSuppliers');
        //$this->renderView('Suppliers/addSuppliers', ['suppliers' => $suppliers]);
        //$this->renderView('../views/Suppliers/addSuppliers', ['suppliers' => $suppliers]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // ✅ التحقق من القيم المطلوبة قبل الإرسال
            if (empty($_POST['name']) || empty($_POST['phone']) || empty($_POST['email']) || empty($_POST['address'])) {
                echo json_encode(["status" => "error", "message" => "يجب ملء جميع الحقول المطلوبة"]);
                exit;
            }

            $_POST['created_by'] = 'admin'; // ✅ فقط هنا، وليس في جافاسكريبت
            $success = $this->supplierModel->createSupplier($_POST);

            echo json_encode([
                "status" => $success ? "success" : "error",
                "message" => $success ? "تمت إضافة المورد بنجاح" : "حدث خطأ أثناء الإضافة"
            ]);
            exit;
        }
    }


    public function getSuppliers() {
        echo json_encode($this->supplierModel->getAllSuppliers());
        exit;
    }

    public function delete() {
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $success = $this->supplierModel->deleteSupplier($_GET['id']);
            echo json_encode(["status" => $success ? "success" : "error", "message" => $success ? "تم حذف المورد بنجاح" : "حدث خطأ أثناء الحذف"]);
        } else {
            echo json_encode(["status" => "error", "message" => "❌ معرف المورد غير صحيح"]);
        }
        exit;
    }

    public function update() {
        file_put_contents("debug.log", print_r($_POST, true));
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $success = $this->supplierModel->updateSupplier($_POST);

            echo json_encode([
                "status" => $success ? "success" : "error",
                "message" => $success ? "تم تحديث بيانات المورد بنجاح" : "لم يتم التعديل، ربما لا يوجد تغيير"
            ]);
            exit;
        }
    }


    public function listSuppliers() {
        $this->jsonResponse($this->supplierModel->getAllSuppliers());
    }
}
