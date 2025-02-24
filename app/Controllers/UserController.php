<?php

namespace app\Controllers;

class UserController extends BaseController {
    public function listUsers() {
        $userModel = $this->loadModel('User');
        $users = $userModel->getAllUsers();
        $this->jsonResponse($users);
    }

    public function addUser() {
        $data = $this->getRequestData();
        $userModel = $this->loadModel('User');
        $result = $userModel->createUser($data);
        $this->jsonResponse(['success' => $result]);
    }
}
