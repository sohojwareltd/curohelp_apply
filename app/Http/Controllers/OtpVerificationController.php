<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Mail\OtpVerification;

class OtpVerificationController extends Controller
{
    /**
     * Send OTP to email
     */
    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email address',
                'errors' => $validator->errors()
            ], 422);
        }

        $email = $request->email;
        
        // Generate 6-digit OTP
        $otp = random_int(100000, 999999);
        
        // Store OTP in cache for 10 minutes
        $cacheKey = 'otp_' . md5($email);
        Cache::put($cacheKey, [
            'otp' => $otp,
            'attempts' => 0,
            'created_at' => now()
        ], now()->addMinutes(10));

        try {
            // Send OTP email
            Mail::to($email)->send(new OtpVerification($otp));

            return response()->json([
                'success' => true,
                'message' => 'OTP sent to your email address. Please check your inbox.',
                'expires_in' => 600 // 10 minutes in seconds
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp' => 'required|digits:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid input',
                'errors' => $validator->errors()
            ], 422);
        }

        $email = $request->email;
        $inputOtp = $request->otp;
        $cacheKey = 'otp_' . md5($email);

        // Get stored OTP data
        $otpData = Cache::get($cacheKey);

        if (!$otpData) {
            return response()->json([
                'success' => false,
                'message' => 'OTP expired or not found. Please request a new one.'
            ], 404);
        }

        // Check maximum attempts (3 attempts)
        if ($otpData['attempts'] >= 3) {
            Cache::forget($cacheKey);
            return response()->json([
                'success' => false,
                'message' => 'Maximum verification attempts exceeded. Please request a new OTP.'
            ], 429);
        }

        // Verify OTP
        if ($otpData['otp'] == $inputOtp) {
            // OTP is correct - mark email as verified
            Cache::put('email_verified_' . md5($email), true, now()->addHours(24));
            Cache::forget($cacheKey);

            return response()->json([
                'success' => true,
                'message' => 'Email verified successfully!'
            ]);
        } else {
            // Increment attempts
            $otpData['attempts']++;
            Cache::put($cacheKey, $otpData, now()->addMinutes(10));

            $attemptsLeft = 3 - $otpData['attempts'];
            return response()->json([
                'success' => false,
                'message' => "Invalid OTP. You have {$attemptsLeft} attempt(s) remaining.",
                'attempts_left' => $attemptsLeft
            ], 401);
        }
    }

    /**
     * Check if email is verified
     */
    public function checkVerification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'verified' => false
            ], 422);
        }

        $email = $request->email;
        $isVerified = Cache::get('email_verified_' . md5($email), false);

        return response()->json([
            'success' => true,
            'verified' => $isVerified
        ]);
    }

    /**
     * Resend OTP
     */
    public function resendOtp(Request $request)
    {
        // Check rate limiting - allow resend after 60 seconds
        $email = $request->email;
        $rateLimitKey = 'otp_resend_' . md5($email);
        
        if (Cache::has($rateLimitKey)) {
            $waitTime = Cache::get($rateLimitKey) - now()->timestamp;
            return response()->json([
                'success' => false,
                'message' => "Please wait {$waitTime} seconds before requesting another OTP."
            ], 429);
        }

        // Set rate limit for 60 seconds
        Cache::put($rateLimitKey, now()->addSeconds(60)->timestamp, now()->addSeconds(60));

        // Use the same send OTP logic
        return $this->sendOtp($request);
    }
}
