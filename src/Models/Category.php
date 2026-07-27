<?php

namespace App\Models;

use App\Database;

class Category
{
    public static function getAll(): array
    {
        $db = Database::getConnection();
        $stmt = $db->query("
            SELECT c.*, 
                   COUNT(t.id) as topic_count,
                   COALESCE(SUM(t.replies_count), 0) as total_replies
            FROM categories c
            LEFT JOIN topics t ON c.id = t.category_id
            GROUP BY c.id
            ORDER BY c.display_order ASC
        ");
        return $stmt->fetchAll();
    }

    public static function findBySlug(string $slug): ?array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM categories WHERE slug = ?");
        $stmt->execute([$slug]);
        $res = $stmt->fetch();
        return $res ?: null;
    }
}
