<?php

namespace app\Controllers;
use app\models\Inventory;

class InventoryController extends BaseController {
    private $inventoryModel;

    public function __construct() {
        $this->inventoryModel = new Inventory();
    }

    public function stockAlert() {
        $data = [
            'lowStock' => $this->inventoryModel->getLowStockAlerts(),
            'overStock' => $this->inventoryModel->getOverStockAlerts()
        ];
        $this->renderView('inventory/stockAlert', $data);
    }

    public function inventoryLog() {
        $data = [
            'logs' => $this->inventoryModel->getInventoryLogs()
        ];
        $this->renderView('inventory/inventory-log', $data);
    }

    // AJAX endpoints
    public function getLatestAlerts() {
        $alerts = $this->inventoryModel->getLatestAlerts();
        $this->jsonResponse($alerts);
    }
}