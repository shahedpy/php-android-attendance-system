<?php

namespace App\Models;

use App\Core\Database;

class ClassModel
{
    public function getAll(): array
    {
        $db = Database::connection();
        $result = $db->query('SELECT * FROM classes ORDER BY name ASC');
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getById(int $id): ?array
    {
        $db = Database::connection();
        $stmt = $db->prepare('SELECT * FROM classes WHERE id = ?');
        if (!$stmt) return null;
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return ($result && $result->num_rows === 1) ? $result->fetch_assoc() : null;
    }

    public function create(string $name): bool
    {
        $db = Database::connection();
        $stmt = $db->prepare('INSERT INTO classes (name) VALUES (?)');
        if (!$stmt) return false;
        $stmt->bind_param('s', $name);
        return $stmt->execute();
    }

    public function update(int $id, string $name): bool
    {
        $db = Database::connection();
        $stmt = $db->prepare('UPDATE classes SET name = ? WHERE id = ?');
        if (!$stmt) return false;
        $stmt->bind_param('si', $name, $id);
        return $stmt->execute();
    }

    public function delete(int $id): bool
    {
        $db = Database::connection();
        $stmt = $db->prepare('DELETE FROM classes WHERE id = ?');
        if (!$stmt) return false;
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}
