<?php

namespace app\Controllers;
use app\models\Branch;
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Branch.php'; // تحميل الموديل يدويًا قبل التبديل إلى Autoloading



class BranchController extends BaseController {

    private $branchModel;

    public function __construct() {
        $this->branchModel = $this->loadModel('Branch'); // استخدام الطريقة الموحدة
    }

    public function index() {
        $branches = $this->branchModel->getLast20Branches();

        $this->renderView('AddBranch', ['branches' => $branches]);
    }




    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $success = $this->branchModel->createBranch($_POST);

            if ($success) {
                echo json_encode(["status" => "success", "message" => "تمت إضافة الفرع بنجاح"]);
            } else {
                echo json_encode(["status" => "error", "message" => "حدث خطأ أثناء الإضافة"]);
            }
            exit; // مهم جدًا حتى لا يُحمّل أي HTML غير مقصود
        }
    }


    public function getBranches() {
        $branches = $this->branchModel->getAllBranches();
        echo json_encode($branches);
        exit;
    }

    public function delete() {
        if (isset($_GET['id'])) {
            $success = $this->branchModel->deleteBranch($_GET['id']);

            if ($success) {
                echo json_encode(["status" => "success", "message" => "تم حذف الفرع بنجاح"]);
            } else {
                echo json_encode(["status" => "error", "message" => "حدث خطأ أثناء الحذف"]);
            }
            exit;
        }
    }

/*    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $success = $this->branchModel->updateBranch($_POST);

            if ($success) {
                echo json_encode(["status" => "success", "message" => "تم تعديل بيانات الفرع بنجاح"]);
            } else {
                echo json_encode(["status" => "error", "message" => "حدث خطأ أثناء التعديل"]);
            }
            exit;
        }
    }*/



    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');

            // ✅ تحقق من أن جميع البيانات موجودة
            if (!isset($_POST['id'], $_POST['name'], $_POST['address'], $_POST['phone'], $_POST['email'], $_POST['manager_name'], $_POST['notes'])) {
                echo json_encode(["status" => "error", "message" => "❌ بيانات غير مكتملة"]);
                exit;
            }

            $id = $_POST['id'];
            $data = [
                'name' => $_POST['name'],
                'address' => $_POST['address'],
                'phone' => $_POST['phone'],
                'email' => $_POST['email'],
                'manager_name' => $_POST['manager_name'],
                'notes' => $_POST['notes']
            ];

            // ✅ استدعاء `updateBranch()`
            $isUpdated = $this->branchModel->updateBranch($id, $data);

            if ($isUpdated) {
                echo json_encode(["status" => "success", "message" => "✅ تم تحديث الفرع بنجاح"]);
            } else {
                echo json_encode(["status" => "error", "message" => "❌ فشل تحديث الفرع"]);
            }

            exit;
        }
    }




    public function listBranches() {
        $branches = $this->branchModel->getAllBranches();
        $this->jsonResponse($branches);
    }
}
