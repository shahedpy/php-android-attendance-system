<?php
require_once('../app/bootstrap.php');

use App\Controllers\AdminController;

$controller = new AdminController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'add';
    if ($action === 'delete') {
        $controller->deleteClass((int) ($_POST['id'] ?? 0));
    } else {
        $controller->addClass($_POST);
    }
} else {
    $controller->classes();
}