<?php

namespace App\Models;

use App\Database;

class Reaction
{
    public static function toggle(string $itemType, int $itemId, int $userId, string $reactionType = 'heart'): bool
    {
        $db = Database::getConnection();
        
        $stmtCheck = $db->prepare("
            SELECT id FROM reactions 
            WHERE item_type = ? AND item_id = ? AND user_id = ? AND reaction_type = ?
        ");
        $stmtCheck->execute([$itemType, $itemId, $userId, $reactionType]);
        $existing = $stmtCheck->fetch();

        if ($existing) {
            $stmtDel = $db->prepare("DELETE FROM reactions WHERE id = ?");
            $stmtDel->execute([$existing['id']]);
            return false; // Removed
        } else {
            $stmtIns = $db->prepare("
                INSERT INTO reactions (item_type, item_id, user_id, reaction_type, created_at)
                VALUES (?, ?, ?, ?, NOW())
            ");
            $stmtIns->execute([$itemType, $itemId, $userId, $reactionType]);
            return true; // Added
        }
    }

    public static function getCounts(string $itemType, int $itemId): array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT reaction_type, COUNT(*) as count 
            FROM reactions 
            WHERE item_type = ? AND item_id = ?
            GROUP BY reaction_type
        ");
        $stmt->execute([$itemType, $itemId]);
        return $stmt->fetchAll();
    }
}
