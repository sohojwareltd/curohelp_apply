@extends('layouts.site')

@section('content')
<div class="apply-container" style="text-align: center; padding: 60px 20px;">
    <div style="max-width: 600px; margin: 0 auto;">
        <div style="font-size: 80px; margin-bottom: 20px; animation: pulse 1.5s ease-in-out;">✓</div>
        
        <h1 style="font-size: 42px; font-weight: 800; color: #0c0c0c; margin: 20px 0;">Thank You!</h1>
        
        <p style="font-size: 18px; color: var(--muted); line-height: 1.6; margin-bottom: 30px;">
            Your application has been successfully submitted. We've received all your information and will review it carefully.
        </p>
        
        <div style="background: #f0fdf4; border: 1px solid #22c55e; border-radius: 12px; padding: 20px; margin-bottom: 40px;">
            <p style="color: #166534; margin: 0;">
                <strong>✓ Confirmation email sent</strong><br>
                Check your inbox for a confirmation message with your application details.
            </p>
        </div>
        
        <div style="background: #f5f5f5; border-radius: 12px; padding: 24px; text-align: left; margin-bottom: 30px;">
            <h3 style="font-weight: 600; color: #0c0c0c; margin: 0 0 16px;">What happens next?</h3>
            <ul style="margin: 0; padding-left: 24px; color: #555;">
                <li style="margin-bottom: 12px;">Our team will review your application within 2-3 business days</li>
                <li style="margin-bottom: 12px;">If there are any questions, we'll contact you at the email or phone provided</li>
                <li style="margin-bottom: 12px;">Suitable opportunities will be matched to your preferences</li>
                <li>You'll receive updates via email about matching roles</li>
            </ul>
        </div>
        
        <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
            <a href="https://curohelp.com" class="btn" style="text-decoration: none; display: inline-block;">
                Back to Home
            </a>
            {{-- <a href="{{ route('login') }}" class="btn outline" style="text-decoration: none; display: inline-block;">
                Sign In
            </a> --}}
        </div>
    </div>
</div>

<style>
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
            color: #22c55e;
        }
        50% {
            transform: scale(1.1);
            color: #16a34a;
        }
    }
    
    @media (max-width: 768px) {
        .apply-container {
            padding: 40px 20px;
        }
        
        h1 {
            font-size: 32px !important;
        }
    }
</style>
@endsection
