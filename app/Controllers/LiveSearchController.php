<?php

namespace app\Controllers;

class LiveSearchController extends BaseController {
    public function search() {
        $query = $_GET['query'] ?? '';
        $searchModel = $this->loadModel('Search');
        $results = $searchModel->search($query);
        $this->jsonResponse($results);
    }
}
