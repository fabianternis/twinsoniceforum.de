<?php

namespace App;

class Auth
{
    public static function init(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function check(): bool
    {
        self::init();
        return isset($_SESSION['user_id']);
    }

    public static function user(): ?array
    {
        self::init();
        if (!self::check()) {
            return null;
        }

        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT id, username, email, avatar_url, bio, rank_badge, role, created_at FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();

        if (!$user) {
            self::logout();
            return null;
        }

        return $user;
    }

    public static function login(string $usernameOrEmail, string $password): bool|string
    {
        self::init();
        $db = Database::getConnection();

        $stmt = $db->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$usernameOrEmail, $usernameOrEmail]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return "Ungültiger Benutzername oder Passwort.";
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        return true;
    }

    public static function register(string $username, string $email, string $password, string $bio = ''): bool|string
    {
        self::init();
        $username = trim($username);
        $email = trim(strtolower($email));

        if (strlen($username) < 3) {
            return "Der Benutzername muss mindestens 3 Zeichen lang sein.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Bitte gib eine gültige E-Mail-Adresse ein.";
        }

        if (strlen($password) < 6) {
            return "Das Passwort muss mindestens 6 Zeichen lang sein.";
        }

        $db = Database::getConnection();

        // Check if username or email taken
        $stmt = $db->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            return "Benutzername oder E-Mail-Adresse ist bereits vergeben.";
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $avatarUrl = 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=250&q=80';

        $stmtInsert = $db->prepare("INSERT INTO users (username, email, password_hash, avatar_url, bio, rank_badge, role) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmtInsert->execute([$username, $email, $hash, $avatarUrl, $bio, 'Ice Fan', 'member']);

        $userId = $db->lastInsertId();
        $_SESSION['user_id'] = $userId;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'member';

        return true;
    }

    public static function logout(): void
    {
        self::init();
        unset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['role']);
        session_destroy();
    }

    public static function isAdmin(): bool
    {
        $user = self::user();
        return $user && $user['role'] === 'admin';
    }
}
