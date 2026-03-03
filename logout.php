<?php
require_once('app/bootstrap.php');

use App\Controllers\LoginController;

$controller = new LoginController();
$controller->logout();
