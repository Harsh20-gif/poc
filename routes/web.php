<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CertificationController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\LeadInteractionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Sales & Admin Routes
    Route::middleware('role:admin,sales')->group(function () {
        Route::get('/leads', [LeadController::class, 'index'])->name('leads.index');
        Route::get('/leads/create', [LeadController::class, 'create'])->name('leads.create');
        Route::post('/leads', [LeadController::class, 'store'])->name('leads.store');
        Route::get('/leads/import', [LeadController::class, 'showImportForm'])->name('leads.import.form');
        Route::post('/leads/import', [LeadController::class, 'import'])->name('leads.import');
        Route::get('/leads/{lead}', [LeadController::class, 'show'])->name('leads.show');
        Route::get('/leads/{lead}/edit', [LeadController::class, 'edit'])->name('leads.edit');
        Route::put('/leads/{lead}', [LeadController::class, 'update'])->name('leads.update');
        Route::post('/leads/{lead}/deactivate', [LeadController::class, 'deactivate'])->name('leads.deactivate');
        Route::post('/leads/{lead}/reactivate', [LeadController::class, 'reactivate'])->name('leads.reactivate');
        Route::post('/leads/{lead}/convert', [LeadController::class, 'convert'])->name('leads.convert');
        
        Route::post('/leads/{lead}/interactions', [LeadInteractionController::class, 'store'])->name('interactions.store');
        
        Route::get('/renewals', [CertificationController::class, 'renewals'])->name('renewals.index');
    });

    // All Authenticated users can view clients
    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
    Route::get('/clients/{client}', [ClientController::class, 'show'])->name('clients.show');
    
    // Verifier & Admin Routes
    Route::middleware('role:admin,verifier')->group(function () {
        Route::put('/clients/{client}/status', [ClientController::class, 'updateStatus'])->name('clients.update_status');
        Route::post('/documents/{document}/verify', [DocumentController::class, 'verify'])->name('documents.verify');
        Route::post('/documents/{document}/reject', [DocumentController::class, 'reject'])->name('documents.reject');
    });
    
    // Anyone can upload docs, issue certs (since sales/admin/verifier can interact with client page appropriately based on UI)
    Route::post('/clients/{client}/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::post('/clients/{client}/certifications', [CertificationController::class, 'store'])->name('certifications.store');
});
