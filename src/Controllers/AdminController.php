<?php

namespace App\Controllers;

use App\Database;
use App\Auth;

class AdminController
{
    public static function index(): void
    {
        if (!Auth::isAdmin()) {
            header("Location: /");
            exit;
        }

        $db = Database::getConnection();
        
        // General Statistics
        $usersCount = (int)$db->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $topicsCount = (int)$db->query("SELECT COUNT(*) FROM topics")->fetchColumn();
        $postsCount = (int)$db->query("SELECT COUNT(*) FROM posts")->fetchColumn();

        $recentUsers = $db->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 10")->fetchAll();
        $categories = $db->query("SELECT * FROM categories ORDER BY display_order ASC")->fetchAll();

        $currentUser = Auth::user();

        require __DIR__ . '/../../views/admin.php';
    }

    public static function createCategory(): void
    {
        if (!Auth::isAdmin()) {
            header("Location: /");
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $icon = trim($_POST['icon'] ?? '⛸️');
        $color = trim($_POST['badge_color'] ?? 'cyan');

        if (!empty($name) && !empty($slug)) {
            $db = Database::getConnection();
            $stmt = $db->prepare("INSERT INTO categories (name, slug, description, icon, badge_color) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $slug, $description, $icon, $color]);
        }

        header("Location: /admin");
        exit;
    }
}
