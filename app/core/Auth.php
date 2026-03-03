<?php

namespace App\Core;

class Auth
{
    public static function check(): bool
    {
        return !empty($_SESSION['username']);
    }

    public static function user(): ?array
    {
        if (!self::check()) {
            return null;
        }

        return [
            'username' => (string) ($_SESSION['username'] ?? ''),
            'role' => (string) ($_SESSION['role'] ?? 'admin'),
        ];
    }

    public static function login(string $username, string $role): void
    {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;
    }

    public static function logout(): void
    {
        session_unset();
        session_destroy();
    }

    public static function requireRole(string $role): void
    {
        if (!self::check()) {
            header('Location: ../login/');
            exit();
        }

        if ((string) ($_SESSION['role'] ?? '') !== $role) {
            self::redirectByRole((string) ($_SESSION['role'] ?? 'admin'));
        }
    }

    public static function redirectByRole(string $role): void
    {
        if ($role === 'parent') {
            header('Location: ../parent/');
            exit();
        }

        header('Location: ../admin/');
        exit();
    }
}
