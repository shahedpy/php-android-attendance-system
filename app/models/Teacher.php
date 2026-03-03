<?php

namespace App\Models;

use App\Core\Database;

class Teacher
{
    public function getAll(): array
    {
        $db = Database::connection();
        $result = $db->query(
            'SELECT t.*, c.name AS class_name
             FROM teachers t
             LEFT JOIN classes c ON t.class_id = c.id
             ORDER BY t.name ASC'
        );
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getById(int $id): ?array
    {
        $db = Database::connection();
        $stmt = $db->prepare(
            'SELECT t.*, c.name AS class_name
             FROM teachers t
             LEFT JOIN classes c ON t.class_id = c.id
             WHERE t.id = ?'
        );
        if (!$stmt) return null;
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return ($result && $result->num_rows === 1) ? $result->fetch_assoc() : null;
    }

    public function create(string $name, string $username, string $email, string $phone, ?int $classId): bool
    {
        $db = Database::connection();
        if ($classId !== null) {
            $stmt = $db->prepare('INSERT INTO teachers (name, username, email, phone, class_id) VALUES (?, ?, ?, ?, ?)');
            if (!$stmt) return false;
            $stmt->bind_param('ssssi', $name, $username, $email, $phone, $classId);
        } else {
            $stmt = $db->prepare('INSERT INTO teachers (name, username, email, phone) VALUES (?, ?, ?, ?)');
            if (!$stmt) return false;
            $stmt->bind_param('ssss', $name, $username, $email, $phone);
        }
        return $stmt->execute();
    }

    public function update(int $id, string $name, string $email, string $phone, ?int $classId): bool
    {
        $db = Database::connection();
        if ($classId !== null) {
            $stmt = $db->prepare('UPDATE teachers SET name = ?, email = ?, phone = ?, class_id = ? WHERE id = ?');
            if (!$stmt) return false;
            $stmt->bind_param('sssii', $name, $email, $phone, $classId, $id);
        } else {
            $stmt = $db->prepare('UPDATE teachers SET name = ?, email = ?, phone = ?, class_id = NULL WHERE id = ?');
            if (!$stmt) return false;
            $stmt->bind_param('sssi', $name, $email, $phone, $id);
        }
        return $stmt->execute();
    }

    public function delete(int $id): bool
    {
        $db = Database::connection();
        $stmt = $db->prepare('DELETE FROM teachers WHERE id = ?');
        if (!$stmt) return false;
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public function getByUsername(string $username): ?array
    {
        $db = Database::connection();
        $stmt = $db->prepare(
            'SELECT t.*, c.name AS class_name
             FROM teachers t
             LEFT JOIN classes c ON t.class_id = c.id
             WHERE t.username = ?'
        );
        if (!$stmt) return null;
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return ($result && $result->num_rows === 1) ? $result->fetch_assoc() : null;
    }
}
