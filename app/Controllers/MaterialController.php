<?php

namespace app\Controllers;
use app\models\Material;
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Material.php';

class MaterialController extends BaseController {
    private $materialModel;

    public function __construct() {
        $this->materialModel = $this->loadModel('Material');
    }

    public function index() {
        $materials = $this->materialModel->getLast20Materials();
        $this->renderView('AddMaterial', ['materials' => $materials]);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $success = $this->materialModel->createMaterial($_POST);
            
            if ($success) {
                echo json_encode(["status" => "success", "message" => "تمت إضافة المادة بنجاح"]);
            } else {
                echo json_encode(["status" => "error", "message" => "حدث خطأ أثناء الإضافة"]);
            }
            exit;
        }
    }

    public function getMaterials() {
        $materials = $this->materialModel->getAllMaterials();
        echo json_encode($materials);
        exit;
    }

    public function getSupplier() {
        $Suppliers = $this->materialModel->getSuppliers();
        echo json_encode($Suppliers);
        exit;
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $success = $this->materialModel->updateMaterial($_POST['id'], $_POST);
            
            if ($success) {
                echo json_encode(["status" => "success", "message" => "تم تحديث المادة بنجاح"]);
            } else {
                echo json_encode(["status" => "error", "message" => "حدث خطأ أثناء التحديث"]);
            }
            exit;
        }
    }


  



    public function delete() {
        if (isset($_GET['id'])) {
            $success = $this->materialModel->deleteMaterial($_GET['id']);
            
            if ($success) {
                echo json_encode(["status" => "success", "message" => "تم حذف المادة بنجاح"]);
            } else {
                echo json_encode(["status" => "error", "message" => "حدث خطأ أثناء الحذف"]);
            }
            exit;
        }
    }
}