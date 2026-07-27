<?php

declare(strict_types=1);

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../src/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

use App\Router;
use App\Config;
use App\DatabaseInitializer;
use App\Auth;

// Initialize config & session
Config::load();
Auth::init();

// Ensure DB tables exist
DatabaseInitializer::run();

// Define Routes
Router::get('/', [\App\Controllers\HomeController::class, 'index']);

Router::get('/category/{slug}', [\App\Controllers\CategoryController::class, 'show']);

Router::get('/topic/create', [\App\Controllers\TopicController::class, 'createForm']);
Router::post('/topic/create', [\App\Controllers\TopicController::class, 'store']);
Router::get('/topic/{id}', [\App\Controllers\TopicController::class, 'show']);
Router::post('/topic/{id}/reply', [\App\Controllers\TopicController::class, 'reply']);
Router::post('/topic/{id}/react', [\App\Controllers\TopicController::class, 'react']);
Router::post('/topic/{id}/vote-poll', [\App\Controllers\TopicController::class, 'votePoll']);

Router::get('/login', [\App\Controllers\AuthController::class, 'loginForm']);
Router::post('/login', [\App\Controllers\AuthController::class, 'processLogin']);
Router::get('/register', [\App\Controllers\AuthController::class, 'registerForm']);
Router::post('/register', [\App\Controllers\AuthController::class, 'processRegister']);
Router::get('/logout', [\App\Controllers\AuthController::class, 'logout']);

Router::get('/user/{username}', [\App\Controllers\ProfileController::class, 'show']);
Router::post('/user/update', [\App\Controllers\ProfileController::class, 'update']);

Router::get('/api/chat', [\App\Controllers\ChatController::class, 'index']);
Router::post('/api/chat', [\App\Controllers\ChatController::class, 'post']);

Router::get('/admin', [\App\Controllers\AdminController::class, 'index']);
Router::post('/admin/category/create', [\App\Controllers\AdminController::class, 'createCategory']);

// Dispatch Request
Router::dispatch();
