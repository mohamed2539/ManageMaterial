<?php

namespace app\Controllers;

class AddQuantityController extends BaseController {
    public function addQuantity() {
        $data = $this->getRequestData();
        $materialModel = $this->loadModel('Material');
        $result = $materialModel->addQuantity($data);
        $this->jsonResponse(['success' => $result]);
    }
}
