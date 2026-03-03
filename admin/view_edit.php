<?php
require_once('../app/bootstrap.php');

use App\Controllers\AdminController;

$controller = new AdminController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->updateRecord($_POST);
} else {
    $controller->viewEdit($_GET);
}