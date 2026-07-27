<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\AdminController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('category.show');

Route::get('/topic/create', [TopicController::class, 'createForm'])->name('topics.create')->middleware('auth');
Route::post('/topic/create', [TopicController::class, 'store'])->name('topics.store')->middleware('auth');
Route::get('/topic/{id}', [TopicController::class, 'show'])->name('topics.show');
Route::post('/topic/{id}/reply', [TopicController::class, 'reply'])->name('topics.reply')->middleware('auth');
Route::post('/topic/{id}/react', [TopicController::class, 'react'])->name('topics.react');
Route::post('/topic/{id}/vote-poll', [TopicController::class, 'votePoll'])->name('topics.votePoll')->middleware('auth');
Route::post('/topic/{id}/delete', [TopicController::class, 'destroy'])->name('topics.destroy')->middleware('auth');

Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'processLogin']);
Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/register', [AuthController::class, 'processRegister']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/user/{username}', [ProfileController::class, 'show'])->name('user.profile');
Route::post('/user/update', [ProfileController::class, 'update'])->name('user.update')->middleware('auth');

Route::get('/api/chat', [ChatController::class, 'index']);
Route::post('/api/chat', [ChatController::class, 'post']);

// Admin Routes with Auth & Spatie Role Check
Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin');
    Route::get('/admin/audit', [AdminController::class, 'auditLogs'])->name('admin.audit');
    Route::get('/admin/trash', [AdminController::class, 'trash'])->name('admin.trash');
    Route::post('/admin/topic/{id}/restore', [AdminController::class, 'restoreTopic'])->name('admin.topic.restore');
    Route::post('/admin/topic/{id}/force-delete', [AdminController::class, 'forceDeleteTopic'])->name('admin.topic.forceDelete');
    Route::post('/admin/category/create', [AdminController::class, 'createCategory'])->name('admin.category.create');
});
