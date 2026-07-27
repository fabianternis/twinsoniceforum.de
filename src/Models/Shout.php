<?php

namespace App\Models;

use App\Database;

class Shout
{
    public static function getRecent(int $limit = 15): array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT s.*, u.username, u.avatar_url, u.rank_badge
            FROM shouts s
            JOIN users u ON s.user_id = u.id
            ORDER BY s.created_at DESC
            LIMIT " . (int)$limit
        );
        $stmt->execute();
        return array_reverse($stmt->fetchAll());
    }

    public static function add(int $userId, string $message): array
    {
        $db = Database::getConnection();
        $message = trim(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));
        if (empty($message)) {
            return ['error' => 'Nachricht darf nicht leer sein.'];
        }

        $stmt = $db->prepare("INSERT INTO shouts (user_id, message, created_at) VALUES (?, ?, NOW())");
        $stmt->execute([$userId, $message]);
        $id = $db->lastInsertId();

        $stmtFetch = $db->prepare("
            SELECT s.*, u.username, u.avatar_url, u.rank_badge
            FROM shouts s
            JOIN users u ON s.user_id = u.id
            WHERE s.id = ?
        ");
        $stmtFetch->execute([$id]);
        return $stmtFetch->fetch();
    }
}
