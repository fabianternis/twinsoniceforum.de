<?php

namespace App\Models;

use App\Database;

class Post
{
    public static function getByTopic(int $topicId): array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT p.*, 
                   u.username, u.avatar_url, u.rank_badge, u.role as user_role
            FROM posts p
            JOIN users u ON p.user_id = u.id
            WHERE p.topic_id = ?
            ORDER BY p.created_at ASC
        ");
        $stmt->execute([$topicId]);
        return $stmt->fetchAll();
    }

    public static function create(int $topicId, int $userId, string $content, ?int $parentId = null): int
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            INSERT INTO posts (topic_id, user_id, parent_id, content, created_at, updated_at)
            VALUES (?, ?, ?, ?, NOW(), NOW())
        ");
        $stmt->execute([$topicId, $userId, $parentId, $content]);
        $postId = (int)$db->lastInsertId();

        // Update topic reply count & updated_at timestamp
        $stmtUpdate = $db->prepare("
            UPDATE topics 
            SET replies_count = replies_count + 1, updated_at = NOW() 
            WHERE id = ?
        ");
        $stmtUpdate->execute([$topicId]);

        return $postId;
    }
}
