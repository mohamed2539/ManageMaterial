<?php

namespace app\Controllers;

class DispenseMaterialController extends BaseController {
    public function dispense() {
        $data = $this->getRequestData();
        $dispenseModel = $this->loadModel('DispenseMaterial');
        $result = $dispenseModel->dispense($data);
        $this->jsonResponse(['success' => $result]);
    }
}
