<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\PublicResumeController;

// Guest routes (not logged in)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    Route::get('/signup', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/signup', [RegisterController::class, 'register']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/', [ResumeController::class, 'index'])->name('home');
    Route::get('/edit-resume', [ResumeController::class, 'edit'])->name('resume.edit');
    Route::post('/edit-resume', [ResumeController::class, 'update'])->name('resume.update');
    
    // Delete routes for profile picture and CV PDF
    Route::delete('/profile-picture', [ResumeController::class, 'deleteProfilePicture'])->name('profile.picture.delete');
    Route::delete('/cv-pdf', [ResumeController::class, 'deleteCvPdf'])->name('cv.pdf.delete');
    
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
});

// Public resume view (no authentication required)
Route::get('/public/{slug}', [PublicResumeController::class, 'show'])->name('resume.public');