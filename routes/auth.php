<?php
require_once __DIR__ . '/../controllers/AuthController.php';

$auth = new AuthController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    switch ($_GET['action']) {
        case 'register':
            $auth->register($data);
            break;
        case 'login':
            $auth->login($data);
            break;
        case 'logout':
            $auth->logout(getallheaders());
            break;
    }
}
