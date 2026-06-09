<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerVideoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [CustomerVideoController::class, 'index'])->name('dashboard');
    Route::post('/video/{id}/request', [CustomerVideoController::class, 'requestAccess'])->name('video.request');
    Route::get('/video/{id}/watch', [CustomerVideoController::class, 'watch'])->name('video.watch');
});


require __DIR__ . '/auth.php';
