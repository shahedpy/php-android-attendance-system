<?php

namespace App\Models;

use App\Core\Database;

class Attendance
{
    public function getByClassAndDate(int $classId, string $date): array
    {
        $db = Database::connection();
        $stmt = $db->prepare(
            'SELECT a.*, s.name AS student_name, s.roll_number
             FROM attendance a
             JOIN students s ON a.student_id = s.id
             WHERE a.class_id = ? AND a.date = ?
             ORDER BY s.roll_number ASC'
        );
        if (!$stmt) return [];
        $stmt->bind_param('is', $classId, $date);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getByStudent(int $studentId, string $startDate, string $endDate): array
    {
        $db = Database::connection();
        $stmt = $db->prepare(
            'SELECT * FROM attendance
             WHERE student_id = ? AND date BETWEEN ? AND ?
             ORDER BY date DESC'
        );
        if (!$stmt) return [];
        $stmt->bind_param('iss', $studentId, $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getStudentSummary(int $studentId, string $startDate, string $endDate): array
    {
        $db = Database::connection();
        $stmt = $db->prepare(
            'SELECT status, COUNT(*) as count FROM attendance
             WHERE student_id = ? AND date BETWEEN ? AND ?
             GROUP BY status'
        );
        $summary = ['present' => 0, 'absent' => 0, 'late' => 0, 'total' => 0];
        if (!$stmt) return $summary;
        $stmt->bind_param('iss', $studentId, $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $summary[$row['status']] = (int) $row['count'];
            $summary['total'] += (int) $row['count'];
        }
        return $summary;
    }

    public function mark(int $studentId, int $classId, string $date, string $status, string $markedBy): bool
    {
        $db = Database::connection();
        $stmt = $db->prepare(
            'INSERT INTO attendance (student_id, class_id, date, status, marked_by)
             VALUES (?, ?, ?, ?, ?)
             ON DUPLICATE KEY UPDATE status = ?, marked_by = ?'
        );
        if (!$stmt) return false;
        $stmt->bind_param('iisssss', $studentId, $classId, $date, $status, $markedBy, $status, $markedBy);
        return $stmt->execute();
    }

    public function getReportByClass(int $classId, string $startDate, string $endDate): array
    {
        $db = Database::connection();
        $stmt = $db->prepare(
            'SELECT s.id, s.name, s.roll_number,
                SUM(CASE WHEN a.status = "present" THEN 1 ELSE 0 END) as present_count,
                SUM(CASE WHEN a.status = "absent"  THEN 1 ELSE 0 END) as absent_count,
                SUM(CASE WHEN a.status = "late"    THEN 1 ELSE 0 END) as late_count,
                COUNT(a.id) as total_days
             FROM students s
             LEFT JOIN attendance a ON s.id = a.student_id AND a.date BETWEEN ? AND ?
             WHERE s.class_id = ?
             GROUP BY s.id, s.name, s.roll_number
             ORDER BY s.roll_number ASC'
        );
        if (!$stmt) return [];
        $stmt->bind_param('ssi', $startDate, $endDate, $classId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
}
