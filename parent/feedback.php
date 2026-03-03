<?php
require_once('../app/bootstrap.php');

use App\Controllers\ParentController;

$controller = new ParentController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->submitFeedback($_POST);
} else {
    $controller->feedback();
}