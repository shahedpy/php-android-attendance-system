<?php

namespace App\Models;

use App\Core\Database;

class Feedback
{
    public function getAll(): array
    {
        $db = Database::connection();
        $result = $db->query('SELECT * FROM feedback ORDER BY created_at DESC');
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getByParent(string $parentUsername): array
    {
        $db = Database::connection();
        $stmt = $db->prepare('SELECT * FROM feedback WHERE parent_username = ? ORDER BY created_at DESC');
        if (!$stmt) return [];
        $stmt->bind_param('s', $parentUsername);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function create(string $parentUsername, string $subject, string $message): bool
    {
        $db = Database::connection();
        $stmt = $db->prepare('INSERT INTO feedback (parent_username, subject, message) VALUES (?, ?, ?)');
        if (!$stmt) return false;
        $stmt->bind_param('sss', $parentUsername, $subject, $message);
        return $stmt->execute();
    }
}
