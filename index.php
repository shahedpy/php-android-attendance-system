<?php
require_once('app/bootstrap.php');

use App\Core\Auth;

if (Auth::check()) {
    $role = (string) ($_SESSION['role'] ?? 'admin');
    header('Location: ' . ($role === 'parent' ? 'parent/' : 'admin/'));
    exit();
}

header('Location: login/');
exit();