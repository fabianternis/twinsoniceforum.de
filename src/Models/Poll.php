<?php

namespace App\Models;

use App\Database;

class Poll
{
    public static function getByTopic(int $topicId): ?array
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM polls WHERE topic_id = ?");
        $stmt->execute([$topicId]);
        $poll = $stmt->fetch();

        if (!$poll) {
            return null;
        }

        $stmtOpts = $db->prepare("SELECT * FROM poll_options WHERE poll_id = ? ORDER BY id ASC");
        $stmtOpts->execute([$poll['id']]);
        $poll['options'] = $stmtOpts->fetchAll();

        // Calculate total votes
        $total = 0;
        foreach ($poll['options'] as $opt) {
            $total += (int)$opt['votes'];
        }
        $poll['total_votes'] = $total;

        return $poll;
    }

    public static function vote(int $pollId, int $optionId, int $userId): bool|string
    {
        $db = Database::getConnection();

        // Check if user already voted
        $stmtCheck = $db->prepare("SELECT id FROM poll_votes WHERE poll_id = ? AND user_id = ?");
        $stmtCheck->execute([$pollId, $userId]);
        if ($stmtCheck->fetch()) {
            return "Du hast bereits für diese Umfrage abgestimmt!";
        }

        // Record vote
        $stmtIns = $db->prepare("INSERT INTO poll_votes (poll_id, option_id, user_id, created_at) VALUES (?, ?, ?, NOW())");
        $stmtIns->execute([$pollId, $optionId, $userId]);

        // Increment count
        $stmtUpd = $db->prepare("UPDATE poll_options SET votes = votes + 1 WHERE id = ?");
        $stmtUpd->execute([$optionId]);

        return true;
    }
}
