<?php

namespace App\Models;

use App\Core\Database;

class Student
{
    public function getAll(): array
    {
        $db = Database::connection();
        $result = $db->query(
            'SELECT s.*, c.name AS class_name
             FROM students s
             LEFT JOIN classes c ON s.class_id = c.id
             ORDER BY s.name ASC'
        );
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getById(int $id): ?array
    {
        $db = Database::connection();
        $stmt = $db->prepare(
            'SELECT s.*, c.name AS class_name
             FROM students s
             LEFT JOIN classes c ON s.class_id = c.id
             WHERE s.id = ?'
        );
        if (!$stmt) return null;
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return ($result && $result->num_rows === 1) ? $result->fetch_assoc() : null;
    }

    public function getByClassId(int $classId): array
    {
        $db = Database::connection();
        $stmt = $db->prepare('SELECT * FROM students WHERE class_id = ? ORDER BY roll_number ASC');
        if (!$stmt) return [];
        $stmt->bind_param('i', $classId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getByParent(string $parentUsername): array
    {
        $db = Database::connection();
        $stmt = $db->prepare(
            'SELECT s.*, c.name AS class_name
             FROM students s
             LEFT JOIN classes c ON s.class_id = c.id
             WHERE s.parent_username = ?
             ORDER BY s.name ASC'
        );
        if (!$stmt) return [];
        $stmt->bind_param('s', $parentUsername);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function create(string $name, string $rollNumber, int $classId, string $parentUsername): bool
    {
        $db = Database::connection();
        $stmt = $db->prepare('INSERT INTO students (name, roll_number, class_id, parent_username) VALUES (?, ?, ?, ?)');
        if (!$stmt) return false;
        $stmt->bind_param('ssis', $name, $rollNumber, $classId, $parentUsername);
        return $stmt->execute();
    }

    public function update(int $id, string $name, string $rollNumber, int $classId, string $parentUsername): bool
    {
        $db = Database::connection();
        $stmt = $db->prepare('UPDATE students SET name = ?, roll_number = ?, class_id = ?, parent_username = ? WHERE id = ?');
        if (!$stmt) return false;
        $stmt->bind_param('ssisi', $name, $rollNumber, $classId, $parentUsername, $id);
        return $stmt->execute();
    }

    public function delete(int $id): bool
    {
        $db = Database::connection();
        $stmt = $db->prepare('DELETE FROM students WHERE id = ?');
        if (!$stmt) return false;
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}
