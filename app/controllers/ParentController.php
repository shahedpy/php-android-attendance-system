<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\View;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\Feedback;

class ParentController
{
    public function __construct()
    {
        Auth::requireRole('parent');
    }

    private function baseData(string $active, string $title): array
    {
        return [
            'active'   => $active,
            'title'    => $title,
            'username' => (string) ($_SESSION['username'] ?? ''),
            'success'  => $this->flash('success'),
            'error'    => $this->flash('error'),
        ];
    }

    private function flash(string $key): string
    {
        $val = (string) ($_SESSION['flash_' . $key] ?? '');
        unset($_SESSION['flash_' . $key]);
        return $val;
    }

    private function setFlash(string $key, string $value): void
    {
        $_SESSION['flash_' . $key] = $value;
    }

    // ── Dashboard ───────────────────────────────────────

    public function dashboard(): void
    {
        $username = (string) ($_SESSION['username'] ?? '');
        $data = $this->baseData('dashboard', 'Dashboard');
        $data['students'] = (new Student())->getByParent($username);
        View::renderWithLayout('parent', 'parent/dashboard', $data);
    }

    // ── Feedback ────────────────────────────────────────

    public function feedback(): void
    {
        $username = (string) ($_SESSION['username'] ?? '');
        $data = $this->baseData('feedback', 'Add Feedback');
        $data['feedbacks'] = (new Feedback())->getByParent($username);
        View::renderWithLayout('parent', 'parent/feedback', $data);
    }

    public function submitFeedback(array $req): void
    {
        $username = (string) ($_SESSION['username'] ?? '');
        $subject  = trim($req['subject'] ?? '');
        $message  = trim($req['message'] ?? '');

        if ($subject === '' || $message === '') {
            $this->setFlash('error', 'Subject and message are required.');
        } elseif ((new Feedback())->create($username, $subject, $message)) {
            $this->setFlash('success', 'Feedback submitted successfully.');
        } else {
            $this->setFlash('error', 'Failed to submit feedback.');
        }
        header('Location: feedback.php');
        exit();
    }

    // ── Report ──────────────────────────────────────────

    public function report(array $params = []): void
    {
        $username  = (string) ($_SESSION['username'] ?? '');
        $students  = (new Student())->getByParent($username);
        $studentId = !empty($params['student_id']) ? (int) $params['student_id'] : null;
        $startDate = $params['start_date'] ?? date('Y-m-01');
        $endDate   = $params['end_date']   ?? date('Y-m-d');

        $data = $this->baseData('report', 'Student Report');
        $data['students']        = $students;
        $data['selectedStudent'] = $studentId;
        $data['startDate']       = $startDate;
        $data['endDate']         = $endDate;
        $data['records']         = [];
        $data['summary']         = ['present' => 0, 'absent' => 0, 'late' => 0, 'total' => 0];

        if ($studentId) {
            $attendanceModel = new Attendance();
            $data['records'] = $attendanceModel->getByStudent($studentId, $startDate, $endDate);
            $data['summary'] = $attendanceModel->getStudentSummary($studentId, $startDate, $endDate);
        }

        View::renderWithLayout('parent', 'parent/report', $data);
    }
}
