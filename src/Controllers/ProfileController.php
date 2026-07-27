<?php

namespace App\Controllers;

use App\Database;
use App\Auth;

class ProfileController
{
    public static function show(string $username): void
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $profileUser = $stmt->fetch();

        if (!$profileUser) {
            header("HTTP/1.0 404 Not Found");
            echo "Benutzer nicht gefunden.";
            return;
        }

        // Get user statistics
        $stmtTopics = $db->prepare("
            SELECT t.*, c.name as category_name, c.icon as category_icon, c.badge_color 
            FROM topics t 
            JOIN categories c ON t.category_id = c.id 
            WHERE t.user_id = ? 
            ORDER BY t.created_at DESC 
            LIMIT 10
        ");
        $stmtTopics->execute([$profileUser['id']]);
        $userTopics = $stmtTopics->fetchAll();

        $stmtPostCount = $db->prepare("SELECT COUNT(*) FROM posts WHERE user_id = ?");
        $stmtPostCount->execute([$profileUser['id']]);
        $postCount = (int)$stmtPostCount->fetchColumn();

        $currentUser = Auth::user();

        require __DIR__ . '/../../views/profile.php';
    }

    public static function update(): void
    {
        if (!Auth::check()) {
            header("Location: /login");
            exit;
        }

        $user = Auth::user();
        $avatarUrl = trim($_POST['avatar_url'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        $rankBadge = trim($_POST['rank_badge'] ?? '');

        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE users SET avatar_url = ?, bio = ?, rank_badge = ? WHERE id = ?");
        $stmt->execute([
            empty($avatarUrl) ? $user['avatar_url'] : $avatarUrl,
            $bio,
            empty($rankBadge) ? $user['rank_badge'] : $rankBadge,
            $user['id']
        ]);

        header("Location: /user/" . urlencode($user['username']));
        exit;
    }
}
