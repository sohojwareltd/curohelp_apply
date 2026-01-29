<?php

use App\Http\Controllers\ApplyPageController;
use App\Http\Controllers\OtpVerificationController;
use Rinvex\Country\CountryLoader;
use Illuminate\Support\Facades\Route;

// Public landing page; all other web routes removed per request.
Route::get('/', [ApplyPageController::class, 'show'])->name('apply');

Route::get('/apply/success', [ApplyPageController::class, 'success'])->name('apply.success');

// Phone country codes
Route::get('/countries/phone-codes', function () {
    $countries = collect(CountryLoader::countries())->map(function ($country) {
        return [
            'code' => $country['iso_3166_1_alpha2'],
            'name' => $country['name'],
            'calling_code' => isset($country['calling_code']) ? '+' . $country['calling_code'] : null,
            'emoji' => $country['emoji'] ?? '🌍',
        ];
    })->filter(fn($c) => $c['calling_code'] !== null)
        ->sortBy(fn($c) => $c['name'] === 'United Kingdom' ? '0' : '1' . $c['name'])
        ->values();

    return response()->json($countries);
})->name('countries.phone-codes');

// OTP Verification Routes
Route::prefix('otp')->group(function () {
    Route::post('/send', [OtpVerificationController::class, 'sendOtp'])->name('otp.send');
    Route::post('/verify', [OtpVerificationController::class, 'verifyOtp'])->name('otp.verify');
    Route::post('/resend', [OtpVerificationController::class, 'resendOtp'])->name('otp.resend');
    Route::post('/check', [OtpVerificationController::class, 'checkVerification'])->name('otp.check');
});

route::get('test', function () {

    return phpinfo();
});
