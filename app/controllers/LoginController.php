<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\View;
use App\Models\User;

class LoginController
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function show(): void
    {
        if (Auth::check()) {
            Auth::redirectByRole((string) ($_SESSION['role'] ?? 'admin'));
        }

        $errorMessage = (string) ($_SESSION['error_message'] ?? '');
        unset($_SESSION['error_message']);

        View::render('auth/login', ['errorMessage' => $errorMessage]);
    }

    public function login(array $request): void
    {
        $username = trim((string) ($request['username'] ?? ''));
        $password = (string) ($request['password'] ?? '');

        if ($username === '' || $password === '') {
            $_SESSION['error_message'] = 'Please enter username and password.';
            header('Location: ../login/');
            exit();
        }

        $user = $this->userModel->findByUsername($username);

        if (!$user || !$this->userModel->passwordMatches($user, $password)) {
            $_SESSION['error_message'] = 'Username or Password Error!';
            header('Location: ../login/');
            exit();
        }

        if (!$this->userModel->isActive($user)) {
            $_SESSION['error_message'] = 'Your account is inactive';
            header('Location: ../login/');
            exit();
        }

        $role = $this->userModel->detectRole($user);
        Auth::login($username, $role);
        Auth::redirectByRole($role);
    }

    public function logout(): void
    {
        Auth::logout();
        header('Location: login/');
        exit();
    }
}
