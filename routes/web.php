<?php

use App\Http\Controllers\AdminWebController;
use App\Http\Controllers\CollegeAdminWebController;
use App\Http\Controllers\StudentWebController;
use App\Http\Controllers\WebAuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public & Auth Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->isAdmin()) return redirect()->route('admin.dashboard');
        if ($user->isCollegeAdmin()) return redirect()->route('college_admin.dashboard');
        return redirect()->route('student.dashboard');
    }
    return redirect()->route('login');
});

Route::get('/login', [WebAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [WebAuthController::class, 'login'])->name('login.post');
Route::get('/register', [WebAuthController::class, 'showRegister'])->name('register');
Route::post('/register', [WebAuthController::class, 'register'])->name('register.post');
Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| System Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:ADMIN'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminWebController::class, 'dashboard'])->name('dashboard');
    Route::get('/colleges', [AdminWebController::class, 'colleges'])->name('colleges');
    Route::post('/colleges', [AdminWebController::class, 'storeCollege'])->name('colleges.store');
    Route::post('/colleges/{id}', [AdminWebController::class, 'updateCollege'])->name('colleges.update');
    Route::post('/colleges/{collegeId}/courses', [AdminWebController::class, 'storeCourse'])->name('courses.store');
    
    Route::get('/students', [AdminWebController::class, 'students'])->name('students');
    
    Route::get('/applications', [AdminWebController::class, 'applications'])->name('applications');
    Route::post('/applications/{id}/approve', [AdminWebController::class, 'approveApplication'])->name('applications.approve');
    Route::post('/applications/{id}/reject', [AdminWebController::class, 'rejectApplication'])->name('applications.reject');
    Route::post('/applications/{id}/ai-summary', [\App\Http\Controllers\AISummaryController::class, 'generate'])->name('applications.ai-summary');
    
    Route::get('/audit-logs', [AdminWebController::class, 'auditLogs'])->name('audit-logs');
});

/*
|--------------------------------------------------------------------------
| College Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:COLLEGE_ADMIN'])->prefix('college-admin')->name('college_admin.')->group(function () {
    Route::get('/dashboard', [CollegeAdminWebController::class, 'dashboard'])->name('dashboard');
    Route::get('/applications', [CollegeAdminWebController::class, 'applications'])->name('applications');
    Route::post('/applications/{id}/approve', [CollegeAdminWebController::class, 'approveApplication'])->name('applications.approve');
    Route::post('/applications/{id}/reject', [CollegeAdminWebController::class, 'rejectApplication'])->name('applications.reject');
    Route::post('/applications/{id}/ai-summary', [\App\Http\Controllers\AISummaryController::class, 'generate'])->name('applications.ai-summary');
});

/*
|--------------------------------------------------------------------------
| Student Portal Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:STUDENT'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentWebController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [StudentWebController::class, 'profile'])->name('profile');
    Route::post('/profile', [StudentWebController::class, 'updateProfile'])->name('profile.update');
    Route::get('/apply', [StudentWebController::class, 'showApplyForm'])->name('apply');
    Route::post('/apply', [StudentWebController::class, 'submitApplication'])->name('apply.post');
});
