<?php

namespace App\Controllers;

use App\Models\Shout;
use App\Auth;

class ChatController
{
    public static function index(): void
    {
        header("Content-Type: application/json");
        echo json_encode(Shout::getRecent(20));
        exit;
    }

    public static function post(): void
    {
        header("Content-Type: application/json");
        if (!Auth::check()) {
            echo json_encode(['error' => 'Bitte melde dich an, um in den Fan-Chat zu schreiben.']);
            exit;
        }

        $user = Auth::user();
        $message = $_POST['message'] ?? '';
        $res = Shout::add($user['id'], $message);

        echo json_encode($res);
        exit;
    }
}
