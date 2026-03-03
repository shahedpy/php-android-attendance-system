<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\View;
use App\Models\ClassModel;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\Feedback;
use App\Models\User;

class AdminController
{
    public function __construct()
    {
        Auth::requireRole('admin');
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

    // ── Teacher Management ──────────────────────────────

    public function teachers(): void
    {
        $data = $this->baseData('teacher', 'Add Teacher');
        $data['teachers'] = (new Teacher())->getAll();
        $data['classes']  = (new ClassModel())->getAll();
        View::renderWithLayout('admin', 'admin/teachers', $data);
    }

    public function addTeacher(array $req): void
    {
        $name     = trim($req['name'] ?? '');
        $username = trim($req['username'] ?? '');
        $email    = trim($req['email'] ?? '');
        $phone    = trim($req['phone'] ?? '');
        $classId  = !empty($req['class_id']) ? (int) $req['class_id'] : null;

        if ($name === '' || $username === '') {
            $this->setFlash('error', 'Name and username are required.');
        } else {
            $teacherModel = new Teacher();
            if ($teacherModel->create($name, $username, $email, $phone, $classId)) {
                (new User())->create($username, 'teacher123', 'teacher');
                $this->setFlash('success', 'Teacher added successfully. Default password: teacher123');
            } else {
                $this->setFlash('error', 'Failed to add teacher. Username may already exist.');
            }
        }
        header('Location: index.php');
        exit();
    }

    public function deleteTeacher(int $id): void
    {
        $teacher = (new Teacher())->getById($id);
        if ($teacher && (new Teacher())->delete($id)) {
            (new User())->deleteByUsername($teacher['username']);
            $this->setFlash('success', 'Teacher deleted.');
        } else {
            $this->setFlash('error', 'Failed to delete teacher.');
        }
        header('Location: index.php');
        exit();
    }

    // ── Class Management ────────────────────────────────

    public function classes(): void
    {
        $data = $this->baseData('class', 'Add Class');
        $data['classes'] = (new ClassModel())->getAll();
        View::renderWithLayout('admin', 'admin/classes', $data);
    }

    public function addClass(array $req): void
    {
        $name = trim($req['name'] ?? '');
        if ($name === '') {
            $this->setFlash('error', 'Class name is required.');
        } elseif ((new ClassModel())->create($name)) {
            $this->setFlash('success', 'Class added successfully.');
        } else {
            $this->setFlash('error', 'Failed to add class. Name may already exist.');
        }
        header('Location: class.php');
        exit();
    }

    public function deleteClass(int $id): void
    {
        if ((new ClassModel())->delete($id)) {
            $this->setFlash('success', 'Class deleted.');
        } else {
            $this->setFlash('error', 'Failed to delete class.');
        }
        header('Location: class.php');
        exit();
    }

    // ── Student Management ──────────────────────────────

    public function students(): void
    {
        $data = $this->baseData('student', 'Add Student');
        $data['students'] = (new Student())->getAll();
        $data['classes']  = (new ClassModel())->getAll();
        View::renderWithLayout('admin', 'admin/students', $data);
    }

    public function addStudent(array $req): void
    {
        $name     = trim($req['name'] ?? '');
        $roll     = trim($req['roll_number'] ?? '');
        $classId  = (int) ($req['class_id'] ?? 0);
        $parent   = trim($req['parent_username'] ?? '');

        if ($name === '' || $roll === '' || $classId === 0) {
            $this->setFlash('error', 'Name, roll number, and class are required.');
        } elseif ((new Student())->create($name, $roll, $classId, $parent)) {
            $this->setFlash('success', 'Student added successfully.');
        } else {
            $this->setFlash('error', 'Failed to add student.');
        }
        header('Location: student.php');
        exit();
    }

    public function deleteStudent(int $id): void
    {
        if ((new Student())->delete($id)) {
            $this->setFlash('success', 'Student deleted.');
        } else {
            $this->setFlash('error', 'Failed to delete student.');
        }
        header('Location: student.php');
        exit();
    }

    // ── View / Edit ─────────────────────────────────────

    public function viewEdit(array $params = []): void
    {
        $type   = $params['type'] ?? 'teachers';
        $editId = isset($params['edit']) ? (int) $params['edit'] : null;

        $data = $this->baseData('view', 'View/Edit Details');
        $data['currentType'] = $type;
        $data['editId']      = $editId;
        $data['editRecord']  = null;
        $data['classes']     = (new ClassModel())->getAll();

        switch ($type) {
            case 'classes':
                $data['records'] = (new ClassModel())->getAll();
                if ($editId) $data['editRecord'] = (new ClassModel())->getById($editId);
                break;
            case 'students':
                $data['records'] = (new Student())->getAll();
                if ($editId) $data['editRecord'] = (new Student())->getById($editId);
                break;
            default:
                $type = 'teachers';
                $data['currentType'] = $type;
                $data['records'] = (new Teacher())->getAll();
                if ($editId) $data['editRecord'] = (new Teacher())->getById($editId);
        }
        View::renderWithLayout('admin', 'admin/view_edit', $data);
    }

    public function updateRecord(array $req): void
    {
        $type = $req['type'] ?? '';
        $id   = (int) ($req['id'] ?? 0);

        if ($id === 0) {
            $this->setFlash('error', 'Invalid record.');
            header('Location: view_edit.php');
            exit();
        }

        $ok = false;
        switch ($type) {
            case 'teachers':
                $ok = (new Teacher())->update(
                    $id,
                    trim($req['name'] ?? ''),
                    trim($req['email'] ?? ''),
                    trim($req['phone'] ?? ''),
                    !empty($req['class_id']) ? (int) $req['class_id'] : null
                );
                break;
            case 'classes':
                $ok = (new ClassModel())->update($id, trim($req['name'] ?? ''));
                break;
            case 'students':
                $ok = (new Student())->update(
                    $id,
                    trim($req['name'] ?? ''),
                    trim($req['roll_number'] ?? ''),
                    (int) ($req['class_id'] ?? 0),
                    trim($req['parent_username'] ?? '')
                );
                break;
        }

        $this->setFlash($ok ? 'success' : 'error', $ok ? 'Record updated.' : 'Failed to update record.');
        header('Location: view_edit.php?type=' . urlencode($type));
        exit();
    }

    // ── Reports ─────────────────────────────────────────

    public function reports(array $params = []): void
    {
        $classId   = !empty($params['class_id']) ? (int) $params['class_id'] : null;
        $startDate = $params['start_date'] ?? date('Y-m-01');
        $endDate   = $params['end_date']   ?? date('Y-m-d');

        $data = $this->baseData('reports', 'Reports');
        $data['classes']       = (new ClassModel())->getAll();
        $data['selectedClass'] = $classId;
        $data['startDate']     = $startDate;
        $data['endDate']       = $endDate;
        $data['report']        = [];

        if ($classId) {
            $data['report'] = (new Attendance())->getReportByClass($classId, $startDate, $endDate);
        }

        View::renderWithLayout('admin', 'admin/reports', $data);
    }

    // ── Feedback ────────────────────────────────────────

    public function feedback(): void
    {
        $data = $this->baseData('feedback', 'View Feedback');
        $data['feedbacks'] = (new Feedback())->getAll();
        View::renderWithLayout('admin', 'admin/feedback', $data);
    }
}
