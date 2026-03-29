<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;



Route::get('/', \App\Livewire\ExecutiveDashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/dashboard', \App\Livewire\ExecutiveDashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// نستخدم middleware 'signed' لكي لا يتم التلاعب بالرابط أو تغييره
Route::get('/magic-upload/{project}/{partner}', \App\Livewire\PartnerMagicUpload::class)
    ->name('magic.upload')
    ->middleware('signed');

Route::get('/client-portal/{project}', \App\Livewire\ClientPortal::class)
->name('client.portal')
->middleware('signed'); // لضمان عدم دخول أي شخص للرابط وتغيير الـ ID

Route::get('/apply/{project}', \App\Livewire\PublicJobPage::class)->name('job.apply');

// مسارات النظام محمية ومتاحة فقط للموظفين المسجلين
Route::middleware(['auth'])->group(function () {
    
    // ضع كل المسارات الخاصة بك هنا
    // Route::get('/', function () { return view('dashboard'); }); 
    Route::get('/projects', \App\Livewire\ProjectsList::class)->name('projects');
    Route::get('/clients', \App\Livewire\ClientsList::class)->name('clients');
    Route::get('/project/{id}/pipeline', \App\Livewire\ProjectPipeline::class)->name('project.pipeline');
    Route::get('/partners', \App\Livewire\PartnersManager::class)->name('partners');
    Route::get('/collections', \App\Livewire\FinancialCollections::class)
    ->middleware(['auth', 'can:is-admin']) // تمت إضافة الحماية هنا
    ->name('collections');
    Route::get('/invoice/{id}', [\App\Http\Controllers\InvoiceController::class, 'show'])->name('invoice.show');
});

require __DIR__.'/auth.php';
