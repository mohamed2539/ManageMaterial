<?php

namespace app\Controllers;

class MaterialController extends BaseController {
    public function listMaterials() {
        $materialModel = $this->loadModel('Material');
        $materials = $materialModel->getAllMaterials();
        $this->jsonResponse($materials);
    }

    public function addMaterial() {
        $data = $this->getRequestData();
        $materialModel = $this->loadModel('Material');
        $result = $materialModel->createMaterial($data);
        $this->jsonResponse(['success' => $result]);
    }
}
