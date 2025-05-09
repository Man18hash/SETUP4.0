<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\FundedProjectsController;
use App\Http\Controllers\RefundsController;
use App\Http\Controllers\BeneficiariesController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    // Projects Routes
    Route::get('/projects', [ProjectsController::class, 'index'])->name('projects');
    Route::post('/projects', [ProjectsController::class, 'store'])->name('projects.store');
});

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Projects routes (additional actions)
    Route::get('/projects/{project}/edit', [ProjectsController::class, 'edit'])->name('projects.edit');
    Route::patch('/projects/{project}', [ProjectsController::class, 'update'])->name('projects.update');
    
    // Funded Projects
    Route::get('/funded-projects', [FundedProjectsController::class, 'index'])->name('funded-projects');

    // NEW routes for Refund Status & Attachments
    Route::patch('/funded-projects/{project}/refund-status', [FundedProjectsController::class, 'updateRefundStatus'])
         ->name('funded-projects.updateRefundStatus');
    Route::get('/funded-projects/{project}/manage-attachments', [FundedProjectsController::class, 'manageAttachments'])
         ->name('funded-projects.manageAttachments');
    Route::post('/funded-projects/{project}/attachments', [FundedProjectsController::class, 'storeAttachment'])
         ->name('funded-projects.storeAttachment');

    Route::get('/refunds', [RefundsController::class, 'index'])->name('refunds');
    
    // Beneficiaries routes
    Route::get('/beneficiaries', [BeneficiariesController::class, 'index'])->name('beneficiaries');
    Route::post('/beneficiaries', [BeneficiariesController::class, 'store'])->name('beneficiaries.store');
    
    // SetupController
    Route::get('/setup', [SetupController::class, 'index'])->name('setup');
    Route::get('/project-plans', [SetupController::class, 'index'])->name('project-plans.index');
    Route::post('/project-plans', [SetupController::class, 'store'])->name('project-plans.store');
    Route::patch('/project-plans/{id}', [SetupController::class, 'update'])->name('project-plans.update');
    Route::delete('/project-plans/{id}', [SetupController::class, 'destroy'])->name('project-plans.destroy');
    
    Route::get('/users', [UsersController::class, 'index'])->name('users');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Attachment Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/project-attachments', [\App\Http\Controllers\ProjectAttachmentController::class, 'store'])
         ->name('project_attachments.store');
    Route::get('/project-attachments/{attachment}/download', [\App\Http\Controllers\ProjectAttachmentController::class, 'download'])
         ->name('project_attachments.download');
});

require __DIR__.'/auth.php';
use App\Http\Controllers\ReleasedProjectsController;

Route::get('/released-projects', [ReleasedProjectsController::class, 'index'])->name('released-projects');



Route::get('/released-projects', [ReleasedProjectsController::class, 'index'])->name('released-projects');
Route::patch('/released-projects/{id}/refund-status', [ReleasedProjectsController::class, 'updateRefundStatus']);
Route::post('/released-projects/{id}/attachments', [ReleasedProjectsController::class, 'storeAttachment']);
Route::delete('/released-projects/{id}', [ReleasedProjectsController::class, 'destroy']);

// Subsidiary ledger route (assuming you have a blade view for this)
Route::get('/subsidiary-ledger/{id}', function($id) {
    // Here you can fetch and pass the necessary data for the subsidiary ledger.
    return view('subsidiary-ledger', ['project_id' => $id]);
})->name('subsidiary-ledger');

use App\Http\Controllers\SubsidiaryLedgerController;

Route::get('/subsidiary-ledger/{id}', [SubsidiaryLedgerController::class, 'show'])
     ->name('subsidiary-ledger');
