<?php

namespace App\Models;

use App\Core\Database;

class User
{
    public function findByUsername(string $username): ?array
    {
        $db = Database::connection();
        $stmt = $db->prepare('SELECT * FROM users WHERE username = ? LIMIT 1');

        if (!$stmt) {
            return null;
        }

        $stmt->bind_param('s', $username);

        if (!$stmt->execute()) {
            return null;
        }

        $result = $stmt->get_result();

        if (!$result || $result->num_rows !== 1) {
            return null;
        }

        return $result->fetch_assoc() ?: null;
    }

    public function detectRole(array $user): string
    {
        foreach (['role', 'user_role', 'type', 'user_type'] as $key) {
            if (!empty($user[$key])) {
                return strtolower(trim((string) $user[$key]));
            }
        }

        return 'admin';
    }

    public function isActive(array $user): bool
    {
        if (!isset($user['active'])) {
            return true;
        }

        return (int) $user['active'] === 1;
    }

    public function passwordMatches(array $user, string $password): bool
    {
        return (string) ($user['password'] ?? '') === md5($password);
    }

    public function create(string $username, string $password, string $role): bool
    {
        $db = Database::connection();
        $hashed = md5($password);
        $stmt = $db->prepare('INSERT IGNORE INTO users (username, password, role, active) VALUES (?, ?, ?, 1)');
        if (!$stmt) return false;
        $stmt->bind_param('sss', $username, $hashed, $role);
        return $stmt->execute();
    }

    public function deleteByUsername(string $username): bool
    {
        $db = Database::connection();
        $stmt = $db->prepare('DELETE FROM users WHERE username = ? AND role != "admin"');
        if (!$stmt) return false;
        $stmt->bind_param('s', $username);
        return $stmt->execute();
    }
}
