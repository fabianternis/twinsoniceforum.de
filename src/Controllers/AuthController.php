<?php

namespace App\Controllers;

use App\Auth;

class AuthController
{
    public static function loginForm(): void
    {
        if (Auth::check()) {
            header("Location: /");
            exit;
        }

        $error = null;
        require __DIR__ . '/../../views/login.php';
    }

    public static function processLogin(): void
    {
        $userOrEmail = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        $result = Auth::login($userOrEmail, $password);
        if ($result === true) {
            $redirect = $_POST['redirect'] ?? '/';
            header("Location: " . $redirect);
            exit;
        }

        $error = is_string($result) ? $result : "Fehler beim Anmelden.";
        require __DIR__ . '/../../views/login.php';
    }

    public static function registerForm(): void
    {
        if (Auth::check()) {
            header("Location: /");
            exit;
        }

        $error = null;
        require __DIR__ . '/../../views/register.php';
    }

    public static function processRegister(): void
    {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $bio = trim($_POST['bio'] ?? '');

        $result = Auth::register($username, $email, $password, $bio);
        if ($result === true) {
            header("Location: /");
            exit;
        }

        $error = is_string($result) ? $result : "Fehler bei der Registrierung.";
        require __DIR__ . '/../../views/register.php';
    }

    public static function logout(): void
    {
        Auth::logout();
        header("Location: /");
        exit;
    }
}
