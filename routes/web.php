<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return Auth::check() ? redirect()->route('posts.index') : redirect()->route('login');
});

Route::middleware('auth')->group(function () {
        // Profile Routes
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [ProfileController::class, 'edit'])->name('edit');
            Route::patch('/', [ProfileController::class, 'update'])->name('update');
            Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
            Route::get('{user}', [ProfileController::class, 'show'])->name('show');
        });

        // Post Routes
        Route::prefix('posts')->name('posts.')->group(function () {
            Route::get('/', [PostController::class, 'index'])->name('index');
            Route::get('create', [PostController::class, 'create'])->name('create');
            Route::post('/', [PostController::class, 'store'])->name('store');
            Route::get('{post}', [PostController::class, 'show'])->name('show');
            Route::get('{post}/edit', [PostController::class, 'edit'])->name('edit');
            Route::put('{post}', [PostController::class, 'update'])->name('update');
            Route::delete('{post}', [PostController::class, 'destroy'])->name('destroy');
            Route::post('{post}/comments', [CommentController::class, 'store'])->name('comments.store');
        });

        // Comment Routes
        Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

require __DIR__.'/auth.php';
