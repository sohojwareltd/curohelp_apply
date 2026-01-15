<?php

use App\Http\Controllers\CandidateApplicationController;
use App\Http\Controllers\FileUploadController;
use Illuminate\Support\Facades\Route;

// Public landing page; all other web routes removed per request.
Route::get('/', [CandidateApplicationController::class, 'show'])->name('apply');
Route::post('/upload', [FileUploadController::class, 'upload'])->name('upload');
Route::get('/apply/success', [CandidateApplicationController::class, 'success'])->name('apply.success');
Route::post('/submit', [CandidateApplicationController::class, 'submit'])->name('submit');
