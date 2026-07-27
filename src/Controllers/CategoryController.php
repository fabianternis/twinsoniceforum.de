<?php

namespace App\Controllers;

use App\Models\Category;
use App\Models\Topic;
use App\Auth;

class CategoryController
{
    public static function show(string $slug): void
    {
        $category = Category::findBySlug($slug);
        if (!$category) {
            header("HTTP/1.0 404 Not Found");
            echo "Category not found.";
            return;
        }

        $topics = Topic::getLatest(30, (int)$category['id']);
        $user = Auth::user();

        require __DIR__ . '/../../views/category.php';
    }
}
