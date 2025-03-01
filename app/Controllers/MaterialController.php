<?php

namespace app\Controllers;

use app\core\BaseController;
use app\models\Material;
use app\models\Branch;
use app\models\Supplier;

class MaterialController extends BaseController {
    private $materialModel;
    private $branchModel;
    private $supplierModel;

    public function __construct() {
        $this->materialModel = new Material();
        $this->branchModel = new Branch();
        $this->supplierModel = new Supplier();
    }

    public function index() {
        $materials = $this->materialModel->getAllMaterials();
        $this->render('materials/index', ['materials' => $materials]);
    }

    public function getMaterials() {
        $materials = $this->materialModel->getAllMaterials();
        echo json_encode($materials);
        exit;
    }

    public function getBranches() {
        $branches = $this->branchModel->getAllBranches();
        echo json_encode($branches);
        exit;
    }

    public function getSupplier() {
        $suppliers = $this->supplierModel->getAllSuppliers();
        echo json_encode($suppliers);
        exit;
    }

    public function create() {
        $this->render('materials/addMaterial');
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $success = $this->materialModel->createMaterial($_POST);
            
            if ($success) {
                echo json_encode([
                    "status" => "success",
                    "message" => "تمت إضافة المادة بنجاح"
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "حدث خطأ أثناء إضافة المادة"
                ]);
            }
            exit;
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['id'])) {
                echo json_encode([
                    "status" => "error",
                    "message" => "معرف المادة مطلوب"
                ]);
                exit;
            }

            $success = $this->materialModel->updateMaterial($_POST['id'], $_POST);
            
            if ($success) {
                echo json_encode([
                    "status" => "success",
                    "message" => "تم تحديث المادة بنجاح"
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "حدث خطأ أثناء تحديث المادة"
                ]);
            }
            exit;
        }
    }

    public function delete() {
        if (isset($_GET['id'])) {
            $success = $this->materialModel->deleteMaterial($_GET['id']);
            
            if ($success) {
                echo json_encode([
                    "status" => "success",
                    "message" => "تم حذف المادة بنجاح"
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "حدث خطأ أثناء حذف المادة"
                ]);
            }
            exit;
        }
    }
}