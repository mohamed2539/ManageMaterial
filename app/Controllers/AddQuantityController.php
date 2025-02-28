<?php
namespace app\Controllers;
use app\models\addQuantity;
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/AddQuantity.php';

class AddQuantityController extends BaseController {
    private $addQuantityModel;

    public function __construct() {
        $this->addQuantityModel = $this->loadModel('AddQuantity');
    }

    public function index() {
        $this->renderView('addQuantity/index');
    }

    public function liveSearch() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $searchTerm = $_POST['searchTerm'] ?? '';
            $supplier_id = $_POST['supplier_id'] ?? '';
            $branch_id = $_POST['branch_id'] ?? '';
            $size = $_POST['size'] ?? '';
            
            $results = $this->addQuantityModel->searchMaterials($searchTerm, $supplier_id, $branch_id, $size);
            echo json_encode($results);
            exit;
        }
    }



    public function updateQuantity() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $material_id = $_POST['material_id'] ?? '';
                $quantity = $_POST['quantity'] ?? 0;
                
                if (empty($material_id) || empty($quantity)) {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'البيانات غير مكتملة'
                    ]);
                    exit;
                }
                
                $success = $this->addQuantityModel->addQuantity($material_id, $quantity);
                
                if ($success) {
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'تم تحديث الكمية بنجاح'
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'حدث خطأ أثناء تحديث الكمية'
                    ]);
                }
                exit;
            } catch (\Exception $e) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'حدث خطأ غير متوقع'
                ]);
                exit;
            }
        }
    }


    /*
    public function updateQuantity() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $material_id = $_POST['material_id'] ?? '';
            $quantity = $_POST['quantity'] ?? 0;
            
            $success = $this->addQuantityModel->addQuantity($material_id, $quantity);
            
            echo json_encode([
                'status' => $success ? 'success' : 'error',
                'message' => $success ? 'تم تحديث الكمية بنجاح' : 'حدث خطأ أثناء تحديث الكمية'
            ]);
            exit;
        }
    }

    */

    public function getFilters() {
        $filters = [
            'suppliers' => $this->addQuantityModel->getSuppliers(),
            'branches' => $this->addQuantityModel->getBranches(),
            'sizes' => $this->addQuantityModel->getSizes()
        ];
        echo json_encode($filters);
        exit;
    }
}