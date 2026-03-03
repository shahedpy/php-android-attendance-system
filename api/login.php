<?php
require_once('../app/bootstrap.php');

use App\Controllers\LoginController;
use App\Models\User;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../login/');
    exit();
}

// JSON API request (from Android app)
$isApi = !empty($_POST['api'])
    || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);

if ($isApi) {
    header('Content-Type: application/json');

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $userModel = new User();
    $user = $userModel->findByUsername($username);

    if (!$user || !$userModel->passwordMatches($user, $password)) {
        echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
        exit();
    }

    if (!$userModel->isActive($user)) {
        echo json_encode(['success' => false, 'message' => 'Account is inactive']);
        exit();
    }

    echo json_encode([
        'success'  => true,
        'username' => $username,
        'role'     => $userModel->detectRole($user),
    ]);
    exit();
}

// Web form login
$controller = new LoginController();
$controller->login($_POST);
