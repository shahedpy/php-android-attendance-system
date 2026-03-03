<?php
require_once('../app/bootstrap.php');

use App\Models\User;
use App\Models\Student;
use App\Models\ClassModel;

header('Content-Type: application/json');

// Authenticate via query params or HTTP Basic Auth
$username = trim($_GET['username'] ?? $_SERVER['PHP_AUTH_USER'] ?? '');
$password = $_GET['password'] ?? $_SERVER['PHP_AUTH_PW'] ?? '';

$userModel = new User();
$user = $userModel->findByUsername($username);

if (!$user || !$userModel->passwordMatches($user, $password) || !$userModel->isActive($user)) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$classId = isset($_GET['class_id']) ? (int) $_GET['class_id'] : null;

if ($classId) {
    // Get students for a specific class
    $students = (new Student())->getByClassId($classId);
    echo json_encode(['success' => true, 'data' => $students]);
} else {
    // Get all classes (useful for teacher app to pick a class)
    $classes = (new ClassModel())->getAll();
    echo json_encode(['success' => true, 'data' => $classes]);
}
