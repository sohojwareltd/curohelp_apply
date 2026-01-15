<?php

use App\Http\Controllers\ApplyPageController;
use Illuminate\Support\Facades\Route;

// Public landing page; all other web routes removed per request.
Route::get('/', [ApplyPageController::class, 'show'])->name('apply');

Route::get('/apply/success', [ApplyPageController::class, 'success'])->name('apply.success');

