<?php
namespace app\Controllers;
use app\models\Search;
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/Search.php';

class SearchController extends BaseController {
    private $searchModel;

    public function __construct() {
        $this->searchModel = $this->loadModel('Search');
    }

    public function index() {
        $this->renderView('search/index');
    }

    public function liveSearch() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $searchTerm = $_POST['searchTerm'] ?? '';
            $supplier_id = $_POST['supplier_id'] ?? '';
            $branch_id = $_POST['branch_id'] ?? '';
            $size = $_POST['size'] ?? '';
            
            $results = $this->searchModel->searchMaterials($searchTerm, $supplier_id, $branch_id, $size);
            echo json_encode($results);
            exit;
        }
    }

    public function getFilters() {
        $filters = [
            'suppliers' => $this->searchModel->getSuppliers(),
            'branches' => $this->searchModel->getBranches(),
            'sizes' => $this->searchModel->getSizes()
        ];
        echo json_encode($filters);
        exit;
    }
}