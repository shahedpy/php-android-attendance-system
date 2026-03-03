<?php
require_once('../app/bootstrap.php');

use App\Controllers\ParentController;

$controller = new ParentController();
$controller->dashboard();