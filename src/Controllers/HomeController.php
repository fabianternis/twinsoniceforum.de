<?php

namespace App\Controllers;

use App\Models\Category;
use App\Models\Topic;
use App\Models\Shout;
use App\SubdomainHandler;
use App\Auth;

class HomeController
{
    public static function index(): void
    {
        $categories = Category::getAll();
        $subdomainConfig = SubdomainHandler::getSubdomainConfig();

        $selectedCatId = null;
        if ($subdomainConfig && isset($subdomainConfig['category_slug'])) {
            $catMatch = Category::findBySlug($subdomainConfig['category_slug']);
            if ($catMatch) {
                $selectedCatId = (int)$catMatch['id'];
            }
        }

        $latestTopics = Topic::getLatest(20, $selectedCatId);
        $recentShouts = Shout::getRecent(15);
        $user = Auth::user();

        require __DIR__ . '/../../views/home.php';
    }
}
