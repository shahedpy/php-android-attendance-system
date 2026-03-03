<?php
require_once('../app/bootstrap.php');

use App\Models\User;
use App\Models\Attendance;
use App\Models\Student;

header('Content-Type: application/json');

// Authenticate via POST params or HTTP Basic Auth
$username = trim($_POST['username'] ?? $_SERVER['PHP_AUTH_USER'] ?? '');
$password = $_POST['password'] ?? $_SERVER['PHP_AUTH_PW'] ?? '';

$userModel = new User();
$user = $userModel->findByUsername($username);

if (!$user || !$userModel->passwordMatches($user, $password) || !$userModel->isActive($user)) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$role = $userModel->detectRole($user);
if ($role !== 'teacher' && $role !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Forbidden']);
    exit();
}

$attendanceModel = new Attendance();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mark attendance
    $classId = (int) ($_POST['class_id'] ?? 0);
    $date    = $_POST['date'] ?? date('Y-m-d');
    $records = $_POST['attendance'] ?? [];

    if ($classId === 0 || empty($records)) {
        echo json_encode(['success' => false, 'message' => 'class_id and attendance[] required']);
        exit();
    }

    $marked = 0;
    foreach ($records as $record) {
        $studentId = (int) ($record['student_id'] ?? 0);
        $status    = $record['status'] ?? 'present';
        if ($studentId > 0 && in_array($status, ['present', 'absent', 'late'], true)) {
            if ($attendanceModel->mark($studentId, $classId, $date, $status, $username)) {
                $marked++;
            }
        }
    }

    echo json_encode(['success' => true, 'marked' => $marked]);
} else {
    // Get attendance for a class/date
    $classId = (int) ($_GET['class_id'] ?? 0);
    $date    = $_GET['date'] ?? date('Y-m-d');

    if ($classId === 0) {
        echo json_encode(['success' => false, 'message' => 'class_id required']);
        exit();
    }

    $data = $attendanceModel->getByClassAndDate($classId, $date);
    echo json_encode(['success' => true, 'data' => $data]);
}
