<?php

namespace App\Models;

use App\Database;

class Topic
{
    public static function getLatest(int $limit = 20, ?int $categoryId = null): array
    {
        $db = Database::getConnection();
        $sql = "
            SELECT t.*, 
                   u.username, u.avatar_url, u.rank_badge,
                   c.name as category_name, c.slug as category_slug, c.icon as category_icon, c.badge_color,
                   (SELECT COUNT(*) FROM reactions r WHERE r.item_type = 'topic' AND r.item_id = t.id) as reaction_count
            FROM topics t
            JOIN users u ON t.user_id = u.id
            JOIN categories c ON t.category_id = c.id
        ";
        
        $params = [];
        if ($categoryId !== null) {
            $sql .= " WHERE t.category_id = ? ";
            $params[] = $categoryId;
        }

        $sql .= " ORDER BY t.is_pinned DESC, t.updated_at DESC LIMIT " . (int)$limit;

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function findById(int $id): ?array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT t.*, 
                   u.username, u.avatar_url, u.rank_badge, u.bio, u.role as user_role,
                   c.name as category_name, c.slug as category_slug, c.icon as category_icon, c.badge_color
            FROM topics t
            JOIN users u ON t.user_id = u.id
            JOIN categories c ON t.category_id = c.id
            WHERE t.id = ?
        ");
        $stmt->execute([$id]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public static function incrementViews(int $id): void
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE topics SET views = views + 1 WHERE id = ?");
        $stmt->execute([$id]);
    }

    public static function create(int $categoryId, int $userId, string $title, string $content): int
    {
        $db = Database::getConnection();
        $slug = self::slugify($title);

        $stmt = $db->prepare("
            INSERT INTO topics (category_id, user_id, title, slug, content, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, NOW(), NOW())
        ");
        $stmt->execute([$categoryId, $userId, $title, $slug, $content]);
        return (int)$db->lastInsertId();
    }

    public static function search(string $query): array
    {
        $db = Database::getConnection();
        $like = '%' . $query . '%';
        $stmt = $db->prepare("
            SELECT t.*, 
                   u.username, u.avatar_url,
                   c.name as category_name, c.icon as category_icon
            FROM topics t
            JOIN users u ON t.user_id = u.id
            JOIN categories c ON t.category_id = c.id
            WHERE t.title LIKE ? OR t.content LIKE ?
            ORDER BY t.created_at DESC
            LIMIT 15
        ");
        $stmt->execute([$like, $like]);
        return $stmt->fetchAll();
    }

    private static function slugify(string $text): string
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower($text);
        return empty($text) ? 'n-a' : $text;
    }
}
