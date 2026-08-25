<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\LeadController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\StaffController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\CertificateController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Certificate Company CRM - API Routes
|--------------------------------------------------------------------------
*/

// Health check
Route::get('/health', fn() => response()->json([
    'status'  => 'ok',
    'app'     => config('app.name'),
    'version' => '1.0.0',
    'time'    => now()->toDateTimeString(),
]));

// Authentication (public)
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);
});

// Protected routes (require Sanctum token)
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me',      [AuthController::class, 'me']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // ─── STAFF ────────────────────────────────────────────────────────
    Route::apiResource('staff', StaffController::class);

    // ─── LEADS ────────────────────────────────────────────────────────
    Route::get('/leads/stats', [LeadController::class, 'stats']);   // must be before resource
    Route::post('/leads/{lead}/certify', [LeadController::class, 'certify']);
    Route::apiResource('leads', LeadController::class);

    // ─── CERTIFICATES ──────────────────────────────────────────────────
    Route::get('/certificates', [CertificateController::class, 'index']);

    // ─── PROJECTS & TASKS (from Task Manager POC) ─────────────────────
    Route::apiResource('projects', ProjectController::class);
    Route::get('/projects/{project}/tasks',  [TaskController::class, 'index']);
    Route::post('/projects/{project}/tasks', [TaskController::class, 'store']);
    Route::get('/tasks/{task}',    [TaskController::class, 'show']);
    Route::put('/tasks/{task}',    [TaskController::class, 'update']);
    Route::patch('/tasks/{task}',  [TaskController::class, 'update']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
});
