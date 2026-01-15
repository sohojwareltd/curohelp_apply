<?php

use App\Http\Controllers\CandidateApplicationController;
use App\Http\Controllers\FileUploadController;
use Illuminate\Support\Facades\Route;

// Public application submission endpoints served by this app; backend forwards to portal.
Route::middleware('api')->group(function (): void {
	Route::post('/apply/submit', [CandidateApplicationController::class, 'submit'])->name('api.apply.submit');
	Route::post('/apply/upload', [FileUploadController::class, 'upload'])->name('api.apply.upload');

	// CORS preflight catch-all for API
	Route::options('/{any}', function () {
		return response()->noContent();
	})->where('any', '.*');
});


