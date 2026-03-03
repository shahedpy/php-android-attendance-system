<?php
require_once('../app/bootstrap.php');

use App\Controllers\AdminController;

$controller = new AdminController();
$controller->feedback();