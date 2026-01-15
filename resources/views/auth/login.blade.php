@extends('layouts.site')

@section('content')
<div class="shell" style="padding: 60px 24px;">
    <div style="max-width: 420px; margin: 0 auto;">
        <div style="text-align: center; margin-bottom: 40px;">
            <h1 style="font-size: 32px; font-weight: 800; margin: 0 0 8px; color: #0c0c0c;">Login</h1>
            <p style="color: #999; margin: 0; font-size: 15px;">Sign in to your account to continue</p>
        </div>

        <div style="background: #fff; border: 1px solid #e5e5e0; border-radius: 18px; padding: 32px; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.06);">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div style="display: grid; gap: 20px;">
                    <!-- Email Field -->
                    <div style="display: grid; gap: 8px;">
                        <label for="email" style="font-weight: 600; letter-spacing: 0.01em; color: #0c0c0c; font-size: 14px;">
                            {{ __('Email Address') }}
                        </label>
                        <input 
                            id="email" 
                            type="email" 
                            class="@error('email') is-invalid @enderror"
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            autocomplete="email" 
                            autofocus
                            style="padding: 12px 14px; border-radius: 12px; border: 1px solid #e5e5e0; background: #fff; font-family: 'Cabin', sans-serif; font-size: 15px; transition: all 200ms ease;"
                            onblur="this.style.borderColor = '#e5e5e0'"
                            onfocus="this.style.borderColor = '#dbb88e'; this.style.boxShadow = '0 0 0 3px rgba(219, 184, 142, 0.1)'"
                        >
                        @error('email')
                            <span style="color: #dc2626; font-size: 13px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div style="display: grid; gap: 8px;">
                        <label for="password" style="font-weight: 600; letter-spacing: 0.01em; color: #0c0c0c; font-size: 14px;">
                            {{ __('Password') }}
                        </label>
                        <input 
                            id="password" 
                            type="password" 
                            class="@error('password') is-invalid @enderror"
                            name="password" 
                            required 
                            autocomplete="current-password"
                            style="padding: 12px 14px; border-radius: 12px; border: 1px solid #e5e5e0; background: #fff; font-family: 'Cabin', sans-serif; font-size: 15px; transition: all 200ms ease;"
                            onblur="this.style.borderColor = '#e5e5e0'"
                            onfocus="this.style.borderColor = '#dbb88e'; this.style.boxShadow = '0 0 0 3px rgba(219, 184, 142, 0.1)'"
                        >
                        @error('password')
                            <span style="color: #dc2626; font-size: 13px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input 
                            type="checkbox" 
                            name="remember" 
                            id="remember" 
                            {{ old('remember') ? 'checked' : '' }}
                            style="width: 18px; height: 18px; cursor: pointer; accent-color: #dbb88e;"
                        >
                        <label for="remember" style="cursor: pointer; font-size: 14px; color: #666; margin: 0;">
                            {{ __('Remember Me') }}
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        style="padding: 12px 20px; border-radius: 8px; border: none; background: linear-gradient(135deg, #dbb88e 0%, #c4a074 100%); color: #fff; font-weight: 700; font-size: 14px; cursor: pointer; transition: all 350ms cubic-bezier(0.68, -0.55, 0.265, 1.55); box-shadow: 0 6px 20px rgba(219, 184, 142, 0.4); letter-spacing: 0.01em;"
                        onmouseover="this.style.transform = 'translateY(-4px) scale(1.03)'; this.style.boxShadow = '0 12px 32px rgba(219, 184, 142, 0.6)'"
                        onmouseout="this.style.transform = 'translateY(0)'; this.style.boxShadow = '0 6px 20px rgba(219, 184, 142, 0.4)'"
                    >
                        {{ __('Login') }}
                    </button>
                </div>

                <!-- Forgot Password Link -->
                @if (Route::has('password.request'))
                    <div style="text-align: center; margin-top: 20px;">
                        <a href="{{ route('password.request') }}" style="color: #dbb88e; text-decoration: none; font-size: 14px; font-weight: 600; transition: color 200ms ease;" onmouseover="this.style.color = '#c4a074'" onmouseout="this.style.color = '#dbb88e'">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    </div>
                @endif
            </form>
        </div>

        <!-- Sign Up Link -->
        <div style="text-align: center; margin-top: 24px;">
            <p style="color: #666; font-size: 14px; margin: 0;">
                Don't have an account? 
                <a href="{{ route('apply') }}" style="color: #dbb88e; text-decoration: none; font-weight: 600; transition: color 200ms ease;" onmouseover="this.style.color = '#c4a074'" onmouseout="this.style.color = '#dbb88e'">
                    Apply Now
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
