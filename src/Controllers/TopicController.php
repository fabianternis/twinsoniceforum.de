<?php

namespace App\Controllers;

use App\Models\Topic;
use App\Models\Post;
use App\Models\Poll;
use App\Models\Category;
use App\Models\Reaction;
use App\Auth;

class TopicController
{
    public static function show(int $id): void
    {
        $topic = Topic::findById($id);
        if (!$topic) {
            header("HTTP/1.0 404 Not Found");
            echo "Topic non-existent.";
            return;
        }

        Topic::incrementViews($id);
        $posts = Post::getByTopic($id);
        $poll = Poll::getByTopic($id);
        $reactions = Reaction::getCounts('topic', $id);
        $user = Auth::user();

        require __DIR__ . '/../../views/topic.php';
    }

    public static function createForm(): void
    {
        if (!Auth::check()) {
            header("Location: /login?redirect=/topic/create");
            exit;
        }

        $categories = Category::getAll();
        $user = Auth::user();
        $error = null;

        require __DIR__ . '/../../views/topic_create.php';
    }

    public static function store(): void
    {
        if (!Auth::check()) {
            header("Location: /login");
            exit;
        }

        $categoryId = (int)($_POST['category_id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $user = Auth::user();

        if ($categoryId <= 0 || empty($title) || empty($content)) {
            $error = "Bitte fülle alle Pflichtfelder aus (Kategorie, Titel & Inhalt).";
            $categories = Category::getAll();
            require __DIR__ . '/../../views/topic_create.php';
            return;
        }

        $topicId = Topic::create($categoryId, $user['id'], $title, $content);
        header("Location: /topic/" . $topicId);
        exit;
    }

    public static function reply(int $topicId): void
    {
        if (!Auth::check()) {
            header("Location: /login");
            exit;
        }

        $content = trim($_POST['content'] ?? '');
        if (!empty($content)) {
            $user = Auth::user();
            Post::create($topicId, $user['id'], $content);
        }

        header("Location: /topic/" . $topicId);
        exit;
    }

    public static function react(int $topicId): void
    {
        if (!Auth::check()) {
            header("Content-Type: application/json");
            echo json_encode(['error' => 'Anmeldung erforderlich']);
            exit;
        }

        $user = Auth::user();
        $type = $_POST['type'] ?? 'heart';
        $added = Reaction::toggle('topic', $topicId, $user['id'], $type);
        $counts = Reaction::getCounts('topic', $topicId);

        header("Content-Type: application/json");
        echo json_encode(['success' => true, 'added' => $added, 'counts' => $counts]);
        exit;
    }

    public static function votePoll(int $topicId): void
    {
        if (!Auth::check()) {
            header("Location: /login");
            exit;
        }

        $pollId = (int)($_POST['poll_id'] ?? 0);
        $optionId = (int)($_POST['option_id'] ?? 0);
        $user = Auth::user();

        if ($pollId > 0 && $optionId > 0) {
            Poll::vote($pollId, $optionId, $user['id']);
        }

        header("Location: /topic/" . $topicId);
        exit;
    }
}
