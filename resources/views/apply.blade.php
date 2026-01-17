@extends('layouts.site')

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<style>
    /* FilePond Custom Styling */
    .filepond--root {
        font-family: "Cabin", sans-serif;
    }

    .filepond--drop-label {
        color: #999;
    }

    .filepond--panel-root {
        background-color: #f8f8f5;
        border: 2px dashed #e5e5e0;
        border-radius: 8px;
    }

    .filepond--item {
        width: calc(50% - 6px);
    }

    .filepond--item-panel {
        background-color: #fff;
        border: 1px solid #e5e5e0;
        border-radius: 6px;
    }

    .filepond--item-panel:hover {
        border-color: #dbb88e;
        box-shadow: 0 2px 8px rgba(219, 184, 142, 0.2);
    }

    .filepond--processing-complete-indicator {
        color: #22c55e;
    }

    .filepond--file-status-main {
        color: #0c0c0c;
        font-weight: 600;
    }

    .filepond--file-status-sub {
        color: #999;
        font-size: 12px;
    }

    .filepond--button {
        cursor: pointer;
        color: #dbb88e;
        background: transparent;
        border: none;
        padding: 0;
        font-weight: 600;
        transition: color 150ms ease;
    }

    .filepond--button:hover {
        color: #c4a074;
        text-decoration: underline;
    }

    .filepond--root:focus {
        outline: none;
    }

    .filepond--credits {
        display: none;
    }

    .image-preview {
        margin-top: 12px;
        display: none;
    }

    .image-preview img {
        max-width: 200px;
        max-height: 200px;
        border-radius: 8px;
        border: 1px solid #e5e5e0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .apply-container {
        max-width: 1100px;
        margin: 0 auto;
        padding: 0px 20px 20px 0;
    }

    .apply-brand {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
        margin-top: 35px;
    }

    .apply-brand img {
        height: 60px;
        width: auto;
        display: block;
    }

    .apply-header {
        margin-bottom: 40px;
        text-align: center;
    }

    .apply-header h1 {
        font-size: 42px;
        font-weight: 800;
        margin: 16px 0 8px;
        color: #0c0c0c;
    }

    .apply-header p {
        font-size: 16px;
        color: var(--muted);
        margin: 0;
    }

    .progress-section {
        margin-bottom: 40px;
    }

    .progress-bar {
        height: 4px;
        background: #e5e5e5;
        border-radius: 2px;
        overflow: hidden;
        margin: 20px 0;
    }

    .progress-bar__fill {
        height: 100%;
        background: linear-gradient(90deg, #dbb88e, #c9a982);
        transition: width 0.3s ease;
        border-radius: 2px;
    }

    .stepper-compact {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
        gap: 12px;
        margin-bottom: 24px;
    }

    .stepper-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
        padding: 0;
        background: transparent;
        border-radius: 0;
        font-size: 12px;
        font-weight: 500;
        color: #666;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .stepper-item.is-active {
        background: transparent;
        color: #fff;
    }

    .stepper-dot {
        width: 20px;
        height: 20px;
        background: currentColor;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        ;
        font-size: 10px;
        font-weight: 700;
    }

    /* Enhanced Stepper Styles */
    .progress-section {
        margin-bottom: 28px;
    }

    .progress-bar {
        display: none;
    }

    .progress-bar__fill {
        height: 0;
    }

    .stepper-container {
        position: relative;
        padding: 14px 0 6px;
    }

    .stepper-line {
        position: absolute;
        top: 30px;
        left: 5%;
        right: 5%;
        height: 2px;
        background-image: repeating-linear-gradient(90deg,
                #d1d1d1 0px,
                #d1d1d1 8px,
                transparent 8px,
                transparent 16px);
        z-index: 0;
    }

    .stepper-line-active {
        position: absolute;
        top: 30px;
        left: 5%;
        height: 2px;
        background-image: repeating-linear-gradient(90deg,
                #d6b27d 0px,
                #d6b27d 8px,
                transparent 8px,
                transparent 16px);
        z-index: 0;
        transition: width 0.4s ease;
    }

    .stepper-items {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        position: relative;
        z-index: 1;
    }

    .stepper-item[data-step] {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        transition: all 0.25s ease;
        flex: 1;
    }

    .stepper-item[data-step]:hover .stepper-circle {
        transform: scale(1.04);
    }

    .stepper-circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: white;
        border: 2px solid #e6d9c4;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        color: #a58a5d;
        transition: all 0.25s ease;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
    }

    .stepper-item:not(.active):not(.completed) .stepper-circle {
        background: #f5f5f5;
        border-color: #d1d1d1;
        color: #999;
    }

    .stepper-item:not(.active):not(.completed) .stepper-title,
    .stepper-item:not(.active):not(.completed) .stepper-subtitle {
        color: #999;
    }

    .stepper-item.active .stepper-circle {
        background: linear-gradient(135deg, #dbb88e, #cfa56d);
        border-color: #d3ac76;
        color: #fff;
        box-shadow: 0 3px 12px rgba(219, 184, 142, 0.28);
        transform: scale(1.07);
    }

    .stepper-item.completed .stepper-circle {
        background: linear-gradient(135deg, #dbb88e, #cfa56d);
        border-color: #d3ac76;
        color: #fff;
        box-shadow: 0 2px 8px rgba(219, 184, 142, 0.28);
    }

    .stepper-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
        text-align: center;
    }

    .stepper-title {
        font-size: 11px;
        font-weight: 700;
        color: #0c0c0c;
        display: block;
    }

    .stepper-subtitle {
        font-size: 9px;
        color: #888;
        display: block;
    }

    .stepper-item.active .stepper-title {
        color: #d3ac76;
    }

    .stepper-item.completed .stepper-title {
        color: #d3ac76;
    }

    .form-section {
        background: #fff;
        border: 1px solid #e5e5e5;
        border-radius: 12px;
        padding: 32px;
        margin-bottom: 24px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .form-section-title {
        font-size: 20px;
        font-weight: 700;
        color: #0c0c0c;
        margin: 0 0 8px;
    }

    .form-section-desc {
        font-size: 14px;
        color: var(--muted);
        margin: 0 0 24px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 24px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group label {
        font-size: 14px;
        font-weight: 600;
        color: #0c0c0c;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        padding: 12px 14px;
        border: 1px solid #d1d1d1;
        border-radius: 8px;
        font-size: 14px;
        font-family: inherit;
        transition: all 0.2s ease;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #dbb88e;
        box-shadow: 0 0 0 3px rgba(219, 184, 142, 0.1);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 100px;
    }

    .checkbox-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .checkbox-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px;
        background: #f9f9f9;
        border: 1px solid #e5e5e5;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 14px;
    }

    .checkbox-item:hover {
        background: #f0f0f0;
        border-color: #dbb88e;
    }

    .checkbox-item input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: #dbb88e;
    }

    .checkbox-item span {
        color: #0c0c0c;
        font-weight: 600;
        pointer-events: none;
    }

    .checkbox-item:hover span {
        color: #0c0c0c;
    }

    /* Select2 Custom Styling to match form inputs */
    .select2-container--default .select2-selection--single {
        padding: 0;
        border: 1px solid #d1d1d1;
        border-radius: 8px;
        height: auto;
        background-color: #fff;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        padding: 12px 14px;
        font-size: 14px;
        color: #0c0c0c;
        line-height: 1.5;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: auto;
        top: 50%;
        right: 12px;
        transform: translateY(-50%);
        width: auto;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: #999 transparent transparent;
    }

    .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
        border-color: transparent transparent #999;
    }

    .select2-container--default .select2-selection--single:focus {
        outline: none;
        border-color: #dbb88e;
        box-shadow: 0 0 0 3px rgba(219, 184, 142, 0.1);
    }

    .select2-container--default .select2-dropdown {
        border: 1px solid #d1d1d1;
        border-radius: 8px;
        margin-top: 4px;
    }

    .select2-container--default .select2-dropdown .select2-search__field {
        padding: 12px 14px;
        border: none;
        border-bottom: 1px solid #e5e5e5;
        border-radius: 8px 8px 0 0;
        font-size: 14px;
    }

    .select2-container--default .select2-results__option {
        padding: 12px 14px;
        font-size: 14px;
        color: #0c0c0c;
    }

    .select2-container--default .select2-results__option--highlighted {
        background-color: #f0f0f0;
        color: #0c0c0c;
    }

    .select2-container--default .select2-results__option[aria-selected="true"] {
        background-color: #f9f9f9;
        color: #0c0c0c;
    }

    .select2-container--default .select2-results__option[aria-selected="true"]--highlighted {
        background-color: #dbb88e;
        color: #fff;
    }

    .error-box {
        background: #fef2f2;
        border: 1px solid #f97316;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 24px;
    }

    .error-box strong {
        color: #991b1b;
        display: block;
        margin-bottom: 8px;
    }

    .error-box ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .error-box li {
        color: #7c2d12;
        font-size: 14px;
        margin-bottom: 4px;
    }

    .upload-note {
        font-size: 12px;
        color: #d6b27d;
        margin-top: 6px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .review-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 16px;
    }

    .review-card {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 12px;
        padding: 24px;
        position: relative;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        display: flex;
        flex-direction: column;
    }

    .review-card:hover {
        border-color: #dbb88e;
        box-shadow: 0 8px 20px rgba(219, 184, 142, 0.15);
        transform: translateY(-4px);
    }

    .review-card h3 {
        font-size: 16px;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0 0 16px;
        padding: 0;
        border: none;
        letter-spacing: -0.3px;
    }

    .review-card p {
        font-size: 14px;
        line-height: 1.6;
        color: #555;
        margin: 8px 0;
    }

    .review-card ul {
        margin: 8px 0;
        padding-left: 0;
    }

    .review-card li {
        font-size: 14px;
        line-height: 1.6;
        color: #555;
        margin: 4px 0;
    }

    .review-card .muted {
        color: #888;
        font-size: 13px;
        margin-top: 4px;
    }

    .step-nav {
        display: flex;
        gap: 16px;
        justify-content: space-between;
        margin-top: 40px;
    }

    .btn {
        padding: 12px 32px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-block;
    }

    .btn {
        background: linear-gradient(135deg, #dbb88e, #c9a982);
        border: 1px solid #c9a982;
        color: #fff;
        box-shadow: 0 6px 14px rgba(219, 184, 142, 0.24);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(219, 184, 142, 0.32);
        color: #ffffff !important;
    }

    .btn.outline {
        background: #fff;
        color: #0c0c0c;
        border: 1px solid #dbb88e;
        box-shadow: inset 0 0 0 1px rgba(219, 184, 142, 0.2);
    }

    .btn.outline:hover {
        background: #fff7ed;
        border-color: #c79b63;
        box-shadow: 0 8px 18px rgba(219, 184, 142, 0.22);
        color: #0c0c0c;
    }

    .success-message {
        background: #f0fdf4;
        border: 1px solid #22c55e;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 24px;
        color: #166534;
    }

    .image-preview {
        margin-top: 12px;
        display: none;
    }

    .image-preview img {
        max-width: 100%;
        max-height: 200px;
        border-radius: 8px;
        border: 2px solid #e5e5e5;
    }

    .review-card {
        transition: all 0.2s ease;
    }

    .review-card:hover {
        border-color: #dbb88e;
        box-shadow: 0 4px 12px rgba(219, 184, 142, 0.12);
    }

    .input-danger {
        border-color: #dc2626 !important;
        background-color: #fee2e2 !important;
    }

    .input-danger:focus {
        border-color: #991b1b !important;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .apply-container {
            padding: 24px 16px;
        }

        .form-section {
            padding: 20px;
        }

        .form-grid {
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 16px;
        }

        .stepper-items {
            gap: 8px;
        }

        .stepper-circle {
            width: 32px;
            height: 32px;
            font-size: 13px;
        }

        .stepper-title {
            font-size: 10px;
        }

        .stepper-subtitle {
            display: none;
        }

        .step-nav {
            flex-wrap: wrap;
            gap: 12px;
        }

        /* Inline option rows wrap nicely */
        .form-group>div {
            flex-wrap: wrap;
        }
    }

    @media (max-width: 480px) {
        .apply-header h1 {
            font-size: 28px;
        }

        .stepper-circle {
            width: 28px;
            height: 28px;
            font-size: 12px;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

@section('content')
    @php
        $stepTitles = [
            1 => 'Personal details',
            2 => 'Roles wanted',
            3 => 'Preferences',
            4 => 'Locations',
            5 => 'Remuneration',
            6 => 'Profile',
            7 => 'Uploads',
            8 => 'Review & submit',
        ];
        $totalSteps = count($stepTitles);
        $get = fn(int $stepKey, string $key, $default = null) => $state[$stepKey][$key] ?? $default;
    @endphp

    <div class="apply-container">
        <div class="apply-brand">
            <a href="https://curohelp.com">
                <img src="https://curohelp.com/wp-content/uploads/2025/08/png.png" alt="Logo">
                {{-- <img src="{{ asset('images/logo.png') }}" alt="Logo"> --}}
            </a>
        </div>
        <div class="apply-header">
            <div class="pill">Step {{ $step }} of {{ $totalSteps }}</div>
            <h1>{{ $stepTitles[$step] }}</h1>
            {{-- <p>Minimal, guided flow. Data is written only after the final submit.</p> --}}
        </div>

        <div class="progress-section">
            <div class="stepper-container">
                <div class="stepper-line"></div>
                <div class="stepper-line-active" style="width: {{ (($step - 1) / ($totalSteps - 1)) * 90 }}%;"></div>
                <div class="stepper-items">
                    @foreach ($stepTitles as $index => $title)
                        @if ($step > $index)
                            <a href="{{ route('apply', ['step' => $index, 'candidate' => $candidateUuid]) }}" 
                               class="stepper-item {{ $step === $index ? 'active' : ($step > $index ? 'completed' : '') }}"
                               style="text-decoration: none; color: inherit; cursor: pointer;">
                                <div class="stepper-circle">
                                    @if ($step > $index)
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M16.667 5.833L8.333 14.167L3.333 9.167" stroke="white" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    @else
                                        {{ $index }}
                                    @endif
                                </div>
                                <div class="stepper-label">
                                    <span class="stepper-title">{{ $title }}</span>
                                    @php
                                        $subtitles = [
                                            1 => 'Your details',
                                            2 => 'Desired roles',
                                            3 => 'Work preferences',
                                            4 => 'Areas you can work',
                                            5 => 'Pay & hours',
                                            6 => 'Your background',
                                            7 => 'Your documents',
                                            8 => 'Confirm & submit',
                                        ];
                                    @endphp
                                    <span class="stepper-subtitle">{{ $subtitles[$index] ?? '' }}</span>
                                </div>
                            </a>
                        @else
                            <div class="stepper-item {{ $step === $index ? 'active' : ($step > $index ? 'completed' : '') }}"
                                style="cursor: {{ $step > $index ? 'pointer' : 'default' }}; opacity: {{ $step > $index ? '1' : '0.6' }};">
                                <div class="stepper-circle">
                                    @if ($step > $index)
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M16.667 5.833L8.333 14.167L3.333 9.167" stroke="white" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    @else
                                        {{ $index }}
                                    @endif
                                </div>
                                <div class="stepper-label">
                                    <span class="stepper-title">{{ $title }}</span>
                                    @php
                                        $subtitles = [
                                            1 => 'Your details',
                                            2 => 'Desired roles',
                                            3 => 'Work preferences',
                                            4 => 'Areas you can work',
                                            5 => 'Pay & hours',
                                            6 => 'Your background',
                                            7 => 'Your documents',
                                            8 => 'Confirm & submit',
                                        ];
                                    @endphp
                                    <span class="stepper-subtitle">{{ $subtitles[$index] ?? '' }}</span>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <form id="applicationForm" method="POST" action="{{ $portalApiUrl }}/api/apply/submit" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="step" value="{{ $step }}">
            <input type="hidden" name="navigate" id="navigate" value="">
            <input type="hidden" name="goto_step" id="goto_step" value="">
            <input type="hidden" name="candidate" value="{{ $candidateUuid }}">
            @if (session('success'))
                <div class="success-message">{{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="error-box">
                    <strong>Fix these before continuing:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="form-section">
                @if ($step === 1)
                    <h2 class="form-section-title">Your Information</h2>
                    <p class="form-section-desc">Tell us your basic details so we can contact you.</p>
                    <div class="row g-4">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="first_name">First Name *</label>
                                <input id="first_name" name="first_name" type="text"
                                    value="{{ old('first_name', $get(1, 'first_name', $get(1, 'full_name'))) }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="last_name">Last Name *</label>
                                <input id="last_name" name="last_name" type="text"
                                    value="{{ old('last_name', $get(1, 'last_name')) }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="email">Email *</label>
                                <input id="email" name="email" type="email"
                                    value="{{ old('email', $get(1, 'email')) }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="phone">Phone *</label>
                                <input id="phone" name="phone" type="tel"
                                    value="{{ old('phone', $get(1, 'phone')) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="postcode">Postcode</label>
                                <input id="postcode" name="postcode" type="text"
                                    value="{{ old('postcode', $get(1, 'postcode')) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nearest_city">Nearest City/Town</label>
                                <input id="nearest_city" name="nearest_city" type="text"
                                    value="{{ old('nearest_city', $get(1, 'nearest_city')) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="country">Country</label>
                                <select id="country" name="country" class="country-select ">
                                    <option value="">-- Select Country --</option>
                                    @foreach ($countries->sortBy(fn($c) => $c->name === 'United Kingdom' ? '0' : '1' . $c->name) as $country)
                                        <option value="{{ $country->name }}"
                                            data-name="{{ strtolower($country->name) }}"
                                            {{ old('country', $get(1, 'country')) === $country->name ? 'selected' : '' }}>
                                            {{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label>Additional</label>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="checkbox-item" style="margin: 0;">
                                            <input type="checkbox" name="driving_licence" value="1"
                                                {{ old('driving_licence', $get(1, 'driving_licence')) ? 'checked' : '' }}>
                                            <span>Driving Licence</span>
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="checkbox-item" style="margin: 0;">
                                            <input type="checkbox" name="own_car" value="1"
                                                {{ old('own_car', $get(1, 'own_car')) ? 'checked' : '' }}>
                                            <span>Own Car</span>
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="checkbox-item" style="margin: 0;">
                                            <input type="checkbox" name="valid_passport" value="1"
                                                {{ old('valid_passport', $get(1, 'valid_passport')) ? 'checked' : '' }}>
                                            <span>Valid Passport</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($step === 2)
                    <h2 class="form-section-title">Roles You Want</h2>
                    <p class="form-section-desc">Select all positions you're interested in.</p>
                    <div class="row g-4">
                        @foreach ($roles->sortBy(fn ($role) => strtolower($role->name)) as $role)
                            <div class="col-md-3">
                                <label class="checkbox-item">
                                    <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                        {{ in_array($role->id, old('roles', $get(2, 'roles', []))) ? 'checked' : '' }}>
                                    <span>{{ $role->name }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if ($step === 3)
                    <h2 class="form-section-title">Your Preferences</h2>
                    <p class="form-section-desc">Help us understand what you're looking for in a role.</p>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="pets">Pets</label>
                                <select id="pets" name="pets_preference">
                                    <option value="">No preference</option>
                                    <option value="comfortable_with_pets" @selected(old('pets_preference', $get(3, 'pets_preference')) === 'comfortable_with_pets')>Comfortable with
                                        pets
                                    </option>
                                    <option value="no_pets" @selected(old('pets_preference', $get(3, 'pets_preference')) === 'no_pets')>Prefer no pets</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="smokers">Smokers</label>
                                <select id="smokers" name="smokers_preference">
                                    <option value="">No preference</option>
                                    <option value="comfortable_with_smokers" @selected(old('smokers_preference', $get(3, 'smokers_preference')) === 'comfortable_with_smokers')>Comfortable
                                    </option>
                                    <option value="no_smokers" @selected(old('smokers_preference', $get(3, 'smokers_preference')) === 'no_smokers')>Prefer no smokers</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="living">Live-in / Live-out</label>
                                <select id="living" name="living_arrangement">
                                    <option value="either" @selected(old('living_arrangement', $get(3, 'living_arrangement')) === 'either')>Either</option>
                                    <option value="live_in" @selected(old('living_arrangement', $get(3, 'living_arrangement')) === 'live_in')>Live-in</option>
                                    <option value="live_out" @selected(old('living_arrangement', $get(3, 'living_arrangement')) === 'live_out')>Live-out</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="unwanted">What You DON'T Want</label>
                                <textarea id="unwanted" name="unwanted" placeholder="e.g., No heavy lifting, no overnight travel">{{ old('unwanted', $get(3, 'unwanted')) }}</textarea>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($step === 4)
                    <h2 class="form-section-title">Where Can You Work?</h2>
                    <p class="form-section-desc">Select all locations you are available to work in. Use the UK or
                        International sections below.</p>
                    @php
                        $ukLocations = $locations->where('type', 'uk');
                        $intlLocations = $locations->where('type', 'international');
                        $selectedLocs = old('locations', $get(4, 'locations', []));
                    @endphp
                    <div class="form-group" style="margin-bottom: 12px;">
                        <input type="text" id="locationSearch" placeholder="Search locations..."
                            style="width:100%; padding:10px 12px; border:1px solid #d1d1d1; border-radius:8px;">
                    </div>
                    <div class="surface" style="margin-bottom:16px;">
                        <div style="display:flex; align-items:center; justify-content:space-between; gap:12px;">
                            <div style="display:flex; align-items:center; gap:8px;">
                                <strong>UK</strong>
                                <button type="button" class="btn outline" id="selectAllUk"
                                    style="padding:6px 10px; font-size:12px;">Select All</button>
                            </div>
                            <button type="button" class="btn outline" id="toggleUk"
                                style="padding:6px 10px; font-size:12px; display:flex; align-items:center; gap:6px; border:none; background:none; color:#666;">
                                <span class="toggle-arrow"
                                    style="transition: transform 0.3s ease; font-size:16px;">▼</span>
                            </button>
                        </div>
                        <div class="row g-4" id="ukGrid" style="margin-top:12px;">
                            @foreach ($ukLocations as $loc)
                                <div class="col-md-3">
                                    <label class="checkbox-item" data-group="uk"
                                        data-name="{{ strtolower($loc->name) }}">
                                        <input type="checkbox" name="locations[]" value="{{ $loc->id }}"
                                            {{ in_array($loc->id, $selectedLocs) ? 'checked' : '' }}>
                                        <span>{{ $loc->name }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="surface">
                        <div style="display:flex; align-items:center; justify-content:space-between; gap:12px;">
                            <div style="display:flex; align-items:center; gap:8px;">
                                <strong>International</strong>
                                <button type="button" class="btn outline" id="selectAllIntl"
                                    style="padding:6px 10px; font-size:12px;">Select All</button>
                            </div>
                            <button type="button" class="btn outline" id="toggleIntl"
                                style="padding:6px 10px; font-size:12px; display:flex; align-items:center; gap:6px; border:none; background:none; color:#666;">
                                <span class="toggle-arrow"
                                    style="transition: transform 0.3s ease; transform: rotate(180deg); font-size:16px;">▼</span>
                            </button>
                        </div>
                        <div class="row g-4" id="intlGrid" style="margin-top:12px; display:none;">
                            @foreach ($intlLocations as $loc)
                                <div class="col-md-3">
                                    <label class="checkbox-item" data-group="intl"
                                        data-name="{{ strtolower($loc->name) }}">
                                        <input type="checkbox" name="locations[]" value="{{ $loc->id }}"
                                            {{ in_array($loc->id, $selectedLocs) ? 'checked' : '' }}>
                                        <span>{{ $loc->name }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($step === 5)
                    <h2 class="form-section-title">Remuneration & Hours</h2>
                    <p class="form-section-desc">Let us know your salary expectations and availability.</p>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="employment">Employment Type *</label>
                                <select id="employment" name="employment_type">
                                    <option value="">Select employment type</option>
                                    @foreach ($employmentTypes as $type)
                                        <option value="{{ $type }}" @selected(old('employment_type', $get(5, 'employment_type')) === $type)>
                                            {{ ucwords(str_replace('_', ' ', $type)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="annual">Annual Salary (£)</label>
                                <input id="annual" type="text" name="salary_expectation_annual"
                                    value="{{ old('salary_expectation_annual', $get(5, 'salary_expectation_annual')) }}"
                                    placeholder="e.g., 45000">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="hourly">Hourly Rate (£)</label>
                                <input id="hourly" type="text" name="salary_expectation_hourly"
                                    value="{{ old('salary_expectation_hourly', $get(5, 'salary_expectation_hourly')) }}"
                                    placeholder="e.g., 25.00">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Working Hours Preference</label>
                                <div class="row g-4" data-name="working-hours-container">
                                    <div class="col-md-3">
                                        <label class="checkbox-item">
                                            <input type="checkbox" name="working_hours[]" value="rota">
                                            <span>Rota</span>
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="checkbox-item">
                                            <input type="checkbox" name="working_hours[]" value="day">
                                            <span>Day</span>
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="checkbox-item">
                                            <input type="checkbox" name="working_hours[]" value="night">
                                            <span>Night</span>
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="checkbox-item">
                                            <input type="checkbox" name="working_hours[]" value="full_time">
                                            <span>Full Time</span>
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="checkbox-item">
                                            <input type="checkbox" name="working_hours[]" value="part_time">
                                            <span>Part Time</span>
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="checkbox-item">
                                            <input type="checkbox" name="working_hours[]" value="sessional">
                                            <span>Sessional</span>
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="checkbox-item">
                                            <input type="checkbox" name="working_hours[]" value="temporary">
                                            <span>Temporary</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($step === 6)
                    <h2 class="form-section-title">Your Profile</h2>
                    <p class="form-section-desc">This is what our clients will see … This is your story to tell … Make it a
                        compelling one.</p>
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="overview">Overview</label>
                                <textarea id="overview" name="overview"
                                    placeholder="This is your quick pitch … What you do, why your good at it and why you love it.">{{ old('overview', $get(6, 'overview')) }}</textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="skills">Key Skills</label>
                                <textarea id="skills" name="skills" placeholder="List your main capabilities and strengths">{{ old('skills', $get(6, 'skills')) }}</textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="qualifications">Qualifications</label>
                                <textarea id="qualifications" name="qualifications" placeholder="Degrees, certifications, and relevant training">{{ old('qualifications', $get(6, 'qualifications')) }}</textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="training">Training Completed</label>
                                <textarea id="training" name="training" placeholder="Any specialized training or courses">{{ old('training', $get(6, 'training')) }}</textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="duties">Duties Performed</label>
                                <textarea id="duties" name="duties_performed" placeholder="Main responsibilities in your roles">{{ old('duties_performed', $get(6, 'duties_performed')) }}</textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="qualities">Personal Qualities</label>
                                <textarea id="qualities" name="personal_qualities" placeholder="Your strengths and character traits">{{ old('personal_qualities', $get(6, 'personal_qualities')) }}</textarea>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($step === 7)
                    <h2 class="form-section-title">Upload Documents</h2>
                    <p class="form-section-desc">Upload your photo, CV, certificates, and introduction video (no more than
                        2 minutes).</p>
                    @php
                        $fileFields = [
                            'photo' => ['label' => 'Photo', 'types' => 'image/*', 'preview' => true],
                            'intro_video' => [
                                'label' => 'Introduction Video (⏱️ No more than 2 minutes)',
                                'types' => 'video/mp4,video/quicktime',
                            ],
                            'cv' => [
                                'label' => 'CV / Resume',
                                'types' =>
                                    'application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            ],
                            'certificates' => [
                                'label' => 'Certificates',
                                'types' =>
                                    'application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            ],
                            'training_documents' => [
                                'label' => 'Training Documents',
                                'types' =>
                                    'application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            ],
                            'security_checks' => [
                                'label' => 'Security Checks',
                                'types' =>
                                    'application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            ],
                        ];
                    @endphp
                    <div class="row g-4">
                        @foreach ($fileFields as $name => $meta)
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="{{ $name }}">{{ $meta['label'] }}</label>
                                    <input id="{{ $name }}" class="filepond" type="file"
                                        name="{{ $name }}" accept="{{ $meta['types'] }}"
                                        onchange="previewImage(this, '{{ $name }}_preview')">
                                    @if (isset($meta['note']))
                                        <p class="upload-note">{{ $meta['note'] }}</p>
                                    @endif
                                    @if (isset($state[7][$name]))
                                        <p style="font-size: 12px; color: #22c55e; margin-top: 4px;">✓ Uploaded</p>
                                    @endif
                                    @if (isset($meta['preview']) && $meta['preview'])
                                        @php
                                            $existingUrl = null;
                                            if ($name === 'photo' && isset($state[7]['photo'])) {
                                                $photoData = $state[7]['photo'];
                                                // Handle both string paths (legacy) and array data (new format)
                                                $photoPath = is_array($photoData)
                                                    ? $photoData['path'] ?? null
                                                    : $photoData;
                                                if ($photoPath) {
                                                    try {
                                                        $existingUrl = \Illuminate\Support\Facades\Storage::disk(
                                                            'public',
                                                        )->url($photoPath);
                                                    } catch (\Throwable $e) {
                                                        $existingUrl = asset('storage/' . ltrim($photoPath, '/'));
                                                    }
                                                }
                                            }
                                        @endphp
                                        <div id="{{ $name }}_preview" class="image-preview">
                                            @if ($existingUrl)
                                                <img src="{{ $existingUrl }}" alt="Preview">
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if ($step === 8)
                    <h2 class="form-section-title">Review Your Application</h2>
                    <p class="form-section-desc">Please review all information before submitting. Click any section to edit.</p>
                    <div class="review-grid">
                        <div>
                            <a href="{{ route('apply', ['step' => 1, 'candidate' => $candidateUuid]) }}" class="review-card"
                                style="text-decoration: none; color: inherit;">
                                <h3>Personal Details</h3>
                                <p><strong>First name:</strong>
                                    {{ $get(1, 'first_name', $get(1, 'full_name')) ?: 'Not provided' }}</p>
                                <p><strong>Last name:</strong> {{ $get(1, 'last_name') ?: 'Not provided' }}</p>
                                <p><strong>Email:</strong> {{ $get(1, 'email') ?: 'Not provided' }}</p>
                                <p><strong>Phone:</strong> {{ $get(1, 'phone') ?: 'Not provided' }}</p>
                                <p><strong>Nearest city/town:</strong> {{ $get(1, 'nearest_city') ?: 'Not provided' }}</p>
                                <p><strong>Postcode:</strong> {{ $get(1, 'postcode') ?: 'Not provided' }}</p>
                                <p><strong>Country:</strong> {{ $get(1, 'country') ?: 'Not provided' }}</p>
                                <p class="muted"><strong>Driving licence:</strong>
                                    {{ $get(1, 'driving_licence') ? 'Yes' : 'No' }}</p>
                                <p class="muted"><strong>Own car:</strong> {{ $get(1, 'own_car') ? 'Yes' : 'No' }}</p>
                                <p class="muted"><strong>Valid passport:</strong>
                                    {{ $get(1, 'valid_passport') ? 'Yes' : 'No' }}</p>
                            </a>
                        </div>

                        <div>
                            <a href="{{ route('apply', ['step' => 2, 'candidate' => $candidateUuid]) }}" class="review-card"
                                style="text-decoration: none; color: inherit;">
                                <h3>Roles Interested In</h3>
                                @if ($get(2, 'roles', []))
                                    <ul style="list-style: none; padding: 0; margin: 0;">
                                        @foreach ($roles->whereIn('id', $get(2, 'roles', [])) as $role)
                                            <li style="padding: 4px 0;">• {{ $role->name }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="muted">No roles selected</p>
                                @endif
                            </a>
                        </div>

                        <div>
                            <a href="{{ route('apply', ['step' => 3, 'candidate' => $candidateUuid]) }}" class="review-card"
                                style="text-decoration: none; color: inherit;">
                                <h3>Preferences</h3>
                                <p><strong>Pets:</strong>
                                    {{ ucwords(str_replace('_', ' ', $get(3, 'pets_preference', 'No preference'))) }}</p>
                                <p><strong>Smokers:</strong>
                                    {{ ucwords(str_replace('_', ' ', $get(3, 'smokers_preference', 'No preference'))) }}
                                </p>
                                <p><strong>Living:</strong>
                                    {{ ucwords(str_replace('_', ' ', $get(3, 'living_arrangement', 'either'))) }}</p>
                                <p><strong>Avoid:</strong> {{ $get(3, 'unwanted') ?: 'Not provided' }}</p>
                            </a>
                        </div>

                        <div>
                            <a href="{{ route('apply', ['step' => 4, 'candidate' => $candidateUuid]) }}" class="review-card"
                                style="text-decoration: none; color: inherit;">
                                <h3>Locations</h3>
                                @php
                                    $selected = $get(4, 'locations', []);
                                    $selectedNames = $selected
                                        ? $locations->whereIn('id', $selected)->pluck('name')->toArray()
                                        : [];
                                @endphp
                                @if (count($selectedNames))
                                    <p class="muted">{{ implode(', ', $selectedNames) }}</p>
                                @else
                                    <p class="muted">No locations selected</p>
                                @endif
                            </a>
                        </div>

                        <div>
                            <a href="{{ route('apply', ['step' => 5, 'candidate' => $candidateUuid]) }}" class="review-card"
                                style="text-decoration: none; color: inherit;">
                                <h3>Remuneration</h3>
                                <p><strong>Type:</strong>
                                    {{ ucwords(str_replace('_', ' ', $get(5, 'employment_type', 'Not specified'))) }}</p>
                                <p><strong>Annual:</strong>
                                    {{ $get(5, 'salary_expectation_annual') ? '£' . number_format($get(5, 'salary_expectation_annual')) : 'Not provided' }}
                                </p>
                                <p><strong>Hourly:</strong>
                                    {{ $get(5, 'salary_expectation_hourly') ? '£' . $get(5, 'salary_expectation_hourly') : 'Not provided' }}
                                </p>
                                <p class="muted"><strong>Working hours:</strong>
                                    @php
                                        $hours = $get(5, 'working_hours', []);
                                    @endphp
                                    {{ count($hours) ? implode(', ', array_map(fn($h) => ucwords(str_replace('_', ' ', $h)), $hours)) : 'None selected' }}
                                </p>
                            </a>
                        </div>

                        <div>
                            <a href="{{ route('apply', ['step' => 6, 'candidate' => $candidateUuid]) }}" class="review-card"
                                style="text-decoration: none; color: inherit;">
                                <h3>Experience & Profile</h3>
                                <p><strong>Overview:</strong>
                                    {{ $get(6, 'overview') ? \Illuminate\Support\Str::limit($get(6, 'overview'), 120) : 'Not provided' }}
                                </p>
                                <p class="muted"><strong>Skills:</strong>
                                    {{ $get(6, 'skills') ? \Illuminate\Support\Str::limit($get(6, 'skills'), 100) : 'Not provided' }}
                                </p>
                                <p class="muted"><strong>Qualifications:</strong>
                                    {{ $get(6, 'qualifications') ? \Illuminate\Support\Str::limit($get(6, 'qualifications'), 100) : 'Not provided' }}
                                </p>
                                <p class="muted"><strong>Training completed:</strong>
                                    {{ $get(6, 'training') ? \Illuminate\Support\Str::limit($get(6, 'training'), 100) : 'Not provided' }}
                                </p>
                                <p class="muted"><strong>Duties performed:</strong>
                                    {{ $get(6, 'duties_performed') ? \Illuminate\Support\Str::limit($get(6, 'duties_performed'), 100) : 'Not provided' }}
                                </p>
                                <p class="muted"><strong>Personal qualities:</strong>
                                    {{ $get(6, 'personal_qualities') ? \Illuminate\Support\Str::limit($get(6, 'personal_qualities'), 100) : 'Not provided' }}
                                </p>
                            </a>
                        </div>

                        <div>
                            <a href="{{ route('apply', ['step' => 7, 'candidate' => $candidateUuid]) }}" class="review-card"
                                style="text-decoration: none; color: inherit;">
                                <h3>Documents</h3>
                                <div id="step8-documents-container">
                                    <p class="muted" style="font-size: 12px;">Loading documents...</p>
                                </div>
                            </a>
                        </div>
                    </div>
                @endif

            </div>
            <div class="step-nav">
                @if ($step > 1)
                    <a href="{{ route('apply', ['step' => $step - 1, 'candidate' => $candidateUuid]) }}" class="btn outline">Back</a>
                @else
                    <a class="btn outline" href="{{ url('/') }}">Back</a>
                @endif
                @if ($step < count($stepTitles))
                    <button id="submitBtn" class="btn" type="submit">Save & Next</button>
                @else
                    <button class="btn" type="submit">Submit application</button>
                @endif
            </div>

        </form>
    </div>

    <script>
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            const file = input.files[0];

            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview">';
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '';
                preview.style.display = 'none';
            }
        }

        // Initialize FilePond after all scripts load
        window.addEventListener('load', function() {
            // Call Portal API directly - use dynamic URL from controller
            var API_BASE = '{{ $portalApiUrl }}/api/apply';
            var WITH_CREDENTIALS = false;
            
            // Load options (roles, locations, countries) from API
            fetch(API_BASE + '/options', {
                method: 'GET',
                headers: { 'Accept': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                console.log('Options loaded:', data);
                
                // Populate roles (step 2)
                if (data.roles && data.roles.length > 0) {
                    const rolesContainer = document.querySelector('.row.g-4');
                    if (rolesContainer && rolesContainer.closest('.form-section')?.querySelector('h2')?.textContent.includes('Roles')) {
                        rolesContainer.innerHTML = '';
                        data.roles.forEach(role => {
                            const roleHtml = `
                                <div class="col-md-3">
                                    <label class="checkbox-item">
                                        <input type="checkbox" name="roles[]" value="${role.id}">
                                        <span>${role.name}</span>
                                    </label>
                                </div>
                            `;
                            rolesContainer.insertAdjacentHTML('beforeend', roleHtml);
                        });
                    }
                }
                
                // Populate locations (step 4)
                if (data.locations && data.locations.length > 0) {
                    const ukGrid = document.getElementById('ukGrid');
                    const intlGrid = document.getElementById('intlGrid');
                    
                    if (ukGrid) {
                        ukGrid.innerHTML = '';
                        data.locations.filter(loc => loc.type === 'uk').forEach(loc => {
                            const locHtml = `
                                <div class="col-md-3">
                                    <label class="checkbox-item" data-group="uk" data-name="${loc.name.toLowerCase()}">
                                        <input type="checkbox" name="locations[]" value="${loc.id}">
                                        <span>${loc.name}</span>
                                    </label>
                                </div>
                            `;
                            ukGrid.insertAdjacentHTML('beforeend', locHtml);
                        });
                    }
                    
                    if (intlGrid) {
                        intlGrid.innerHTML = '';
                        data.locations.filter(loc => loc.type === 'international').forEach(loc => {
                            const locHtml = `
                                <div class="col-md-3">
                                    <label class="checkbox-item" data-group="intl" data-name="${loc.name.toLowerCase()}">
                                        <input type="checkbox" name="locations[]" value="${loc.id}">
                                        <span>${loc.name}</span>
                                    </label>
                                </div>
                            `;
                            intlGrid.insertAdjacentHTML('beforeend', locHtml);
                        });
                    }
                    
                    // Setup search filter after locations loaded
                    const locationSearch = document.getElementById('locationSearch');
                    if (locationSearch) {
                        const items = [
                            ...(ukGrid ? ukGrid.querySelectorAll('[data-name]') : []),
                            ...(intlGrid ? intlGrid.querySelectorAll('[data-name]') : []),
                        ];
                        locationSearch.addEventListener('input', function(e) {
                            const q = e.target.value.trim().toLowerCase();
                            items.forEach(el => {
                                const name = el.getAttribute('data-name') || '';
                                el.style.display = name.includes(q) ? '' : 'none';
                            });
                        });
                    }
                }
                
                // Populate countries (step 1)
                if (data.countries && data.countries.length > 0) {
                    const countrySelect = document.querySelector('select[name="country"]');
                    if (countrySelect) {
                        // Keep the empty option
                        const emptyOption = countrySelect.querySelector('option[value=""]');
                        countrySelect.innerHTML = emptyOption ? emptyOption.outerHTML : '<option value="">-- Select Country --</option>';
                        
                        // Sort countries: UK first, then alphabetically
                        const sorted = data.countries.sort((a, b) => {
                            if (a.name === 'United Kingdom') return -1;
                            if (b.name === 'United Kingdom') return 1;
                            return a.name.localeCompare(b.name);
                        });
                        
                        sorted.forEach(country => {
                            const option = document.createElement('option');
                            option.value = country.name;
                            option.textContent = country.name;
                            countrySelect.appendChild(option);
                        });
                        
                        // Reinitialize Select2 after jQuery is loaded (it's loaded later in the page)
                        setTimeout(() => {
                            if (window.jQuery && window.jQuery.fn.select2) {
                                try {
                                    window.jQuery(countrySelect).select2('destroy');
                                } catch(e) {}
                                window.jQuery(countrySelect).select2({
                                    placeholder: '-- Select Country --',
                                    allowClear: true,
                                    width: '100%'
                                });
                            }
                        }, 500);
                    }
                }
                
                // Populate working hours options (step 5) - Static options
                const workingHoursOptions = ['Rota', 'Day', 'Night', 'Full Time', 'Part Time', 'Seasonal', 'Temporary'];
                const workingHoursContainer = document.querySelector('[data-name="working-hours-container"]');
                
                if (workingHoursContainer) {
                    workingHoursContainer.innerHTML = '';
                    workingHoursOptions.forEach(option => {
                        const value = option.toLowerCase().replace(/\s+/g, '_');
                        const hoursHtml = `
                            <div class="col-md-3">
                                <label class="checkbox-item">
                                    <input type="checkbox" name="working_hours[]" value="${value}">
                                    <span>${option}</span>
                                </label>
                            </div>
                        `;
                        workingHoursContainer.insertAdjacentHTML('beforeend', hoursHtml);
                    });
                }
                
                // Load previously saved data for ALL steps
                const candidateUuid = document.querySelector('input[name="candidate"]')?.value;
                window.currentStep = document.querySelector('input[name="step"]')?.value;
                console.log('Current step:', window.currentStep, 'Candidate:', candidateUuid);
                
                if (candidateUuid) {
                    // Wait a bit for dynamic content to render, then load state
                    setTimeout(() => {
                        fetch(API_BASE + '/state?candidate=' + candidateUuid, {
                        method: 'GET',
                        headers: { 'Accept': 'application/json' }
                    })
                    .then(response => {
                        console.log('State response status:', response.status);
                        return response.json();
                    })
                    .then(stateData => {
                        console.log('Full state response:', stateData);
                        
                        // Data is nested under "state" property in the response
                        const applicationState = stateData.state || {};
                        const step7Data = applicationState[7] || applicationState["7"] || {};
                        console.log('Step 7 data:', step7Data);
                        console.log('Step 7 data keys:', Object.keys(step7Data));
                        
                        const fileFields = ['photo', 'intro_video', 'cv', 'certificates', 'training_documents', 'security_checks'];
                        
                        fileFields.forEach(fieldName => {
                            const fileData = step7Data[fieldName];
                            console.log(`Checking ${fieldName}:`, fileData);
                            
                            if (fileData) {
                                // Extract path and name from object or use string directly
                                const filePath = typeof fileData === 'object' ? fileData.path : fileData;
                                const fileName = typeof fileData === 'object' ? fileData.original_name : fieldName;
                                console.log(`${fieldName} path:`, filePath);
                                
                                if (filePath) {
                                    // Set data attribute on the input for form submission
                                    const input = document.getElementById(fieldName);
                                    if (input) {
                                        input.setAttribute('data-file-path', filePath);
                                        console.log(`Set data-file-path on #${fieldName}`);
                                    }
                                    
                                    // Load image preview if it's the photo field
                                    if (fieldName === 'photo') {
                                        let previewDiv = document.getElementById('photo_preview');
                                        const photoInput = document.getElementById('photo');
                                        
                                        // Create preview div if it doesn't exist
                                        if (!previewDiv) {
                                            console.log('Creating preview div...');
                                            previewDiv = document.createElement('div');
                                            previewDiv.id = 'photo_preview';
                                            previewDiv.className = 'image-preview';
                                            if (photoInput && photoInput.parentElement) {
                                                photoInput.parentElement.appendChild(previewDiv);
                                            }
                                        }
                                        
                                        if (previewDiv) {
                                            const imageUrl = '{{ $portalApiUrl }}/storage/' + filePath;
                                            console.log('Loading existing image from:', imageUrl);
                                            handlePreviewImage(photoInput, previewDiv, imageUrl);
                                        }
                                    } else {
                                        // For non-image files (cv, certificates, etc), show as downloadable link
                                        const fileUrl = '{{ $portalApiUrl }}/storage/' + filePath;
                                        const input = document.getElementById(fieldName);
                                        if (input && input.parentElement) {
                                            // Remove old link if exists
                                            let oldLink = input.nextElementSibling;
                                            while (oldLink && oldLink.className === 'file-download-link') {
                                                oldLink.remove();
                                                oldLink = input.nextElementSibling;
                                            }
                                            
                                            // Create new link div
                                            const linkDiv = document.createElement('div');
                                            linkDiv.className = 'file-download-link';
                                            linkDiv.style.marginTop = '8px';
                                            linkDiv.innerHTML = `
                                                <a href="${fileUrl}" target="_blank" style="
                                                    display: inline-flex;
                                                    align-items: center;
                                                    gap: 8px;
                                                    padding: 8px 12px;
                                                    background: #f0f0f0;
                                                    border-radius: 6px;
                                                    text-decoration: none;
                                                    color: #0066cc;
                                                    font-size: 14px;
                                                    border: 1px solid #ddd;
                                                ">
                                                    <span style="font-size: 16px;">📄</span>
                                                    <span>${fileName}</span>
                                                    <span style="font-size: 12px; color: #666;">(Click to view)</span>
                                                </a>
                                            `;
                                            input.parentElement.insertBefore(linkDiv, input.nextElementSibling);
                                            console.log(`File link created for ${fieldName}: ${fileUrl}`);
                                        }
                                    }
                                }
                            }
                        });
                        
                        // Populate form fields for steps 1-6
                        if (window.currentStep <= 6) {
                            // Step 1 - Personal Details
                            if (applicationState[1]) {
                                const step1 = applicationState[1];
                                document.querySelector('input[name="first_name"]')?.setAttribute('value', step1.first_name || '');
                                document.querySelector('input[name="last_name"]')?.setAttribute('value', step1.last_name || '');
                                document.querySelector('input[name="email"]')?.setAttribute('value', step1.email || '');
                                document.querySelector('input[name="phone"]')?.setAttribute('value', step1.phone || '');
                                document.querySelector('input[name="nearest_city"]')?.setAttribute('value', step1.nearest_city || '');
                                document.querySelector('input[name="postcode"]')?.setAttribute('value', step1.postcode || '');
                                
                                // Restore country using Select2
                                if (step1.country) {
                                    const countrySelect = document.querySelector('select[name="country"]');
                                    if (countrySelect) {
                                        if (window.jQuery && window.jQuery.fn.select2) {
                                            window.jQuery(countrySelect).val(step1.country).trigger('change');
                                        } else {
                                            countrySelect.value = step1.country;
                                        }
                                    }
                                }
                                
                                if (step1.driving_licence) document.querySelector('input[name="driving_licence"]')?.setAttribute('checked', 'checked');
                                if (step1.own_car) document.querySelector('input[name="own_car"]')?.setAttribute('checked', 'checked');
                                if (step1.valid_passport) document.querySelector('input[name="valid_passport"]')?.setAttribute('checked', 'checked');
                            }
                            
                            // Step 2 - Roles
                            if (applicationState[2] && applicationState[2].roles) {
                                applicationState[2].roles.forEach(roleId => {
                                    const checkbox = document.querySelector(`input[name="roles[]"][value="${roleId}"]`);
                                    if (checkbox) checkbox.checked = true;
                                });
                            }
                            
                            // Step 3 - Preferences
                            if (applicationState[3]) {
                                const step3 = applicationState[3];
                                
                                // Restore select dropdowns
                                if (step3.pets_preference) {
                                    const petsSelect = document.querySelector('select[name="pets_preference"]');
                                    if (petsSelect) petsSelect.value = step3.pets_preference;
                                }
                                
                                if (step3.smokers_preference) {
                                    const smokersSelect = document.querySelector('select[name="smokers_preference"]');
                                    if (smokersSelect) smokersSelect.value = step3.smokers_preference;
                                }
                                
                                if (step3.living_arrangement) {
                                    const livingSelect = document.querySelector('select[name="living_arrangement"]');
                                    if (livingSelect) livingSelect.value = step3.living_arrangement;
                                }
                                
                                // Restore textarea
                                const unwantedTextarea = document.querySelector('textarea[name="unwanted"]');
                                if (unwantedTextarea && step3.unwanted) {
                                    unwantedTextarea.value = step3.unwanted;
                                }
                            }
                            
                            // Step 4 - Locations
                            if (applicationState[4] && applicationState[4].locations) {
                                applicationState[4].locations.forEach(locId => {
                                    const checkbox = document.querySelector(`input[name="locations[]"][value="${locId}"]`);
                                    if (checkbox) checkbox.checked = true;
                                });
                            }
                            
                            // Step 5 - Remuneration
                            if (applicationState[5]) {
                                const step5 = applicationState[5];
                                if (step5.employment_type) {
                                    document.querySelector(`input[name="employment_type"][value="${step5.employment_type}"]`)?.setAttribute('checked', 'checked');
                                }
                                document.querySelector('input[name="salary_expectation_annual"]')?.setAttribute('value', step5.salary_expectation_annual || '');
                                document.querySelector('input[name="salary_expectation_hourly"]')?.setAttribute('value', step5.salary_expectation_hourly || '');
                                if (step5.working_hours && Array.isArray(step5.working_hours)) {
                                    step5.working_hours.forEach(hours => {
                                        const checkbox = document.querySelector(`input[name="working_hours[]"][value="${hours}"]`);
                                        if (checkbox) checkbox.checked = true;
                                    });
                                }
                            }
                            
                            // Step 6 - Experience & Profile
                            if (applicationState[6]) {
                                const step6 = applicationState[6];
                                
                                // Use .value instead of setAttribute for textarea
                                const overviewTextarea = document.querySelector('textarea[name="overview"]');
                                if (overviewTextarea && step6.overview) overviewTextarea.value = step6.overview;
                                
                                const skillsTextarea = document.querySelector('textarea[name="skills"]');
                                if (skillsTextarea && step6.skills) skillsTextarea.value = step6.skills;
                                
                                const qualificationsTextarea = document.querySelector('textarea[name="qualifications"]');
                                if (qualificationsTextarea && step6.qualifications) qualificationsTextarea.value = step6.qualifications;
                                
                                const trainingTextarea = document.querySelector('textarea[name="training"]');
                                if (trainingTextarea && step6.training) trainingTextarea.value = step6.training;
                                
                                const dutiesTextarea = document.querySelector('textarea[name="duties_performed"]');
                                if (dutiesTextarea && step6.duties_performed) dutiesTextarea.value = step6.duties_performed;
                                
                                const qualitiesTextarea = document.querySelector('textarea[name="personal_qualities"]');
                                if (qualitiesTextarea && step6.personal_qualities) qualitiesTextarea.value = step6.personal_qualities;
                            }
                        }
                        
                        // Also populate step 8 Documents section if currently on step 8
                        if (window.currentStep == 8) {
                            // Load all roles and locations for mapping IDs to names
                            const allRoles = {};
                            const allLocations = {};
                            
                            if (data.roles) {
                                data.roles.forEach(role => {
                                    allRoles[role.id] = role.name;
                                });
                            }
                            
                            if (data.locations) {
                                data.locations.forEach(loc => {
                                    allLocations[loc.id] = loc.name;
                                });
                            }
                            
                            // Get all review cards
                            const reviewCards = document.querySelectorAll('.review-card');
                            
                            // Find cards by their h3 text content
                            let personalCard, rolesCard, prefsCard, locsCard, remuCard, expCard;
                            
                            reviewCards.forEach(card => {
                                const h3 = card.querySelector('h3');
                                if (!h3) return;
                                const title = h3.textContent.trim();
                                
                                if (title === 'Personal Details') personalCard = card;
                                else if (title === 'Roles Interested In') rolesCard = card;
                                else if (title === 'Preferences') prefsCard = card;
                                else if (title === 'Locations') locsCard = card;
                                else if (title === 'Remuneration') remuCard = card;
                                else if (title === 'Experience & Profile') expCard = card;
                            });
                            
                            // Populate Personal Details
                            if (personalCard) {
                                const personalHtml = `
                                    <h3>Personal Details</h3>
                                    <p><strong>First name:</strong> ${applicationState[1]?.first_name || 'Not provided'}</p>
                                    <p><strong>Last name:</strong> ${applicationState[1]?.last_name || 'Not provided'}</p>
                                    <p><strong>Email:</strong> ${applicationState[1]?.email || 'Not provided'}</p>
                                    <p><strong>Phone:</strong> ${applicationState[1]?.phone || 'Not provided'}</p>
                                    <p><strong>Nearest city/town:</strong> ${applicationState[1]?.nearest_city || 'Not provided'}</p>
                                    <p><strong>Postcode:</strong> ${applicationState[1]?.postcode || 'Not provided'}</p>
                                    <p><strong>Country:</strong> ${applicationState[1]?.country || 'Not provided'}</p>
                                    <p class="muted"><strong>Driving licence:</strong> ${applicationState[1]?.driving_licence ? 'Yes' : 'No'}</p>
                                    <p class="muted"><strong>Own car:</strong> ${applicationState[1]?.own_car ? 'Yes' : 'No'}</p>
                                    <p class="muted"><strong>Valid passport:</strong> ${applicationState[1]?.valid_passport ? 'Yes' : 'No'}</p>
                                `;
                                personalCard.innerHTML = personalHtml;
                            }
                            
                            // Populate Roles
                            if (rolesCard) {
                                const roleIds = applicationState[2]?.roles || [];
                                const roleNames = roleIds.map(id => allRoles[id]).filter(Boolean);
                                const rolesHtml = `
                                    <h3>Roles Interested In</h3>
                                    ${roleNames.length > 0 
                                        ? '<ul style="list-style: none; padding: 0; margin: 0;">' + 
                                          roleNames.map(name => `<li style="padding: 4px 0;">• ${name}</li>`).join('') +
                                          '</ul>'
                                        : '<p class="muted">No roles selected</p>'}
                                `;
                                rolesCard.innerHTML = rolesHtml;
                            }
                            
                            // Populate Preferences
                            if (prefsCard) {
                                const prefsHtml = `
                                    <h3>Preferences</h3>
                                    <p><strong>Pets:</strong> ${(applicationState[3]?.pets_preference || 'No preference').replace(/_/g, ' ').charAt(0).toUpperCase() + (applicationState[3]?.pets_preference || 'No preference').replace(/_/g, ' ').slice(1)}</p>
                                    <p><strong>Smokers:</strong> ${(applicationState[3]?.smokers_preference || 'No preference').replace(/_/g, ' ').charAt(0).toUpperCase() + (applicationState[3]?.smokers_preference || 'No preference').replace(/_/g, ' ').slice(1)}</p>
                                    <p><strong>Living:</strong> ${(applicationState[3]?.living_arrangement || 'either').replace(/_/g, ' ').charAt(0).toUpperCase() + (applicationState[3]?.living_arrangement || 'either').replace(/_/g, ' ').slice(1)}</p>
                                    <p><strong>Avoid:</strong> ${applicationState[3]?.unwanted || 'Not provided'}</p>
                                `;
                                prefsCard.innerHTML = prefsHtml;
                            }
                            
                            // Populate Locations
                            if (locsCard) {
                                const locIds = applicationState[4]?.locations || [];
                                const locNames = locIds.map(id => allLocations[id]).filter(Boolean);
                                const locsHtml = `
                                    <h3>Locations</h3>
                                    ${locNames.length > 0 
                                        ? '<p class="muted">' + locNames.join(', ') + '</p>'
                                        : '<p class="muted">No locations selected</p>'}
                                `;
                                locsCard.innerHTML = locsHtml;
                            }
                            
                            // Populate Remuneration
                            if (remuCard) {
                                const hours = applicationState[5]?.working_hours || [];
                                const hoursStr = hours.length > 0 
                                    ? hours.map(h => h.replace(/_/g, ' ').charAt(0).toUpperCase() + h.replace(/_/g, ' ').slice(1)).join(', ')
                                    : 'None selected';
                                const remuHtml = `
                                    <h3>Remuneration</h3>
                                    <p><strong>Type:</strong> ${(applicationState[5]?.employment_type || 'Not specified').replace(/_/g, ' ').charAt(0).toUpperCase() + (applicationState[5]?.employment_type || 'Not specified').replace(/_/g, ' ').slice(1)}</p>
                                    <p><strong>Annual:</strong> ${applicationState[5]?.salary_expectation_annual ? '£' + applicationState[5]?.salary_expectation_annual : 'Not provided'}</p>
                                    <p><strong>Hourly:</strong> ${applicationState[5]?.salary_expectation_hourly ? '£' + applicationState[5]?.salary_expectation_hourly : 'Not provided'}</p>
                                    <p class="muted"><strong>Working hours:</strong> ${hoursStr}</p>
                                `;
                                remuCard.innerHTML = remuHtml;
                            }
                            
                            // Populate Experience & Profile
                            if (expCard) {
                                const expHtml = `
                                    <h3>Experience & Profile</h3>
                                    <p><strong>Overview:</strong> ${(applicationState[6]?.overview || 'Not provided').substring(0, 120)}</p>
                                    <p class="muted"><strong>Skills:</strong> ${(applicationState[6]?.skills || 'Not provided').substring(0, 100)}</p>
                                    <p class="muted"><strong>Qualifications:</strong> ${(applicationState[6]?.qualifications || 'Not provided').substring(0, 100)}</p>
                                    <p class="muted"><strong>Training completed:</strong> ${(applicationState[6]?.training || 'Not provided').substring(0, 100)}</p>
                                    <p class="muted"><strong>Duties performed:</strong> ${(applicationState[6]?.duties_performed || 'Not provided').substring(0, 100)}</p>
                                    <p class="muted"><strong>Personal qualities:</strong> ${(applicationState[6]?.personal_qualities || 'Not provided').substring(0, 100)}</p>
                                `;
                                expCard.innerHTML = expHtml;
                            }
                            
                            // Populate Documents
                            const docMeta = {
                                'photo': { label: 'Photo', preview: true },
                                'intro_video': { label: 'Intro Video', preview: false },
                                'cv': { label: 'CV / Resume', preview: false },
                                'certificates': { label: 'Certificates', preview: false },
                                'training_documents': { label: 'Training Documents', preview: false },
                                'security_checks': { label: 'Security Checks', preview: false },
                            };
                            
                            let documentsHtml = '';
                            
                            Object.entries(docMeta).forEach(([key, meta]) => {
                                const fileData = step7Data[key];
                                
                                if (fileData) {
                                    const filePath = typeof fileData === 'object' ? fileData.path : fileData;
                                    const fileName = typeof fileData === 'object' ? fileData.original_name : meta.label;
                                    const fileUrl = '{{ $portalApiUrl }}/storage/' + filePath;
                                    
                                    if (meta.preview && key === 'photo') {
                                        documentsHtml += `
                                            <div style="margin-bottom: 12px;">
                                                <img src="${fileUrl}" alt="Photo preview" 
                                                     style="max-width: 100%; height: auto; border-radius: 6px; margin-bottom: 8px;">
                                            </div>
                                        `;
                                    } else {
                                        documentsHtml += `
                                            <div style="margin-bottom: 12px;">
                                                <a href="${fileUrl}" target="_blank" style="
                                                    display: inline-flex;
                                                    align-items: center;
                                                    gap: 8px;
                                                    padding: 8px 12px;
                                                    background: #f0f0f0;
                                                    border-radius: 6px;
                                                    text-decoration: none;
                                                    color: #0066cc;
                                                    font-size: 13px;
                                                    border: 1px solid #ddd;
                                                ">
                                                    <span style="font-size: 14px;">📄</span>
                                                    <span>${fileName}</span>
                                                    <span style="font-size: 11px; color: #666;">(View)</span>
                                                </a>
                                            </div>
                                        `;
                                    }
                                } else {
                                    documentsHtml += `<p class="muted" style="margin-bottom: 6px;">• ${meta.label} (Not uploaded)</p>`;
                                }
                            });
                            
                            const container = document.getElementById('step8-documents-container');
                            if (container) {
                                container.innerHTML = documentsHtml || '<p class="muted">No documents uploaded</p>';
                            }
                        }
                    })
                    .catch(err => console.error('Failed to load step 7 state:', err));
                    }, 600); // Wait for dynamic content to render
                }
            })
            .catch(err => console.error('Failed to load options:', err));
            
            // Check if FilePond is loaded
            if (typeof FilePond !== 'undefined') {
                console.log('FilePond loaded, initializing...');
                // Register FilePond plugins
                FilePond.registerPlugin(FilePondPluginImagePreview);
                FilePond.registerPlugin(FilePondPluginFileValidateSize);
                FilePond.registerPlugin(FilePondPluginFileValidateType);

                // Initialize FilePond on all elements with filepond class
                setTimeout(() => {
                    const inputElements = document.querySelectorAll('.filepond');
                    console.log('Found', inputElements.length, 'filepond inputs');
                    inputElements.forEach(inputElement => {
                        const isVideoInput = inputElement.accept.includes('video');
                        const isImageInput = inputElement.accept.includes('image');
                        const pond = FilePond.create(inputElement, {
                            maxFileSize: isVideoInput ? null : '10MB',
                            storeAsFile: true,
                            allowFileTypeValidation: true,
                            acceptedFileTypes: [
                                'image/jpeg',
                                'image/png',
                                'image/gif',
                                'application/pdf',
                                'application/msword',
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                'video/mp4',
                                'video/quicktime'
                            ],
                            server: {
                                url: API_BASE,
                                process: (fieldName, file, metadata, load, error, progress) => {
                                    // Disable submit button during upload
                                    const submitBtn = document.getElementById('submitBtn');
                                    if (submitBtn) {
                                        submitBtn.disabled = true;
                                        submitBtn.style.opacity = '0.5';
                                    }

                                    const formData = new FormData();
                                    formData.append('file', file, file.name);

                                    const request = new XMLHttpRequest();
                                    const csrfToken = document.querySelector(
                                            'meta[name="csrf-token"]')?.getAttribute('content') ||
                                        '';

                                    request.upload.onprogress = (e) => {
                                        progress(e.lengthComputable, e.loaded, e.total);
                                    };

                                    request.onload = () => {
                                        try {
                                            const response = JSON.parse(request.responseText);
                                            console.log('Upload response:', response);
                                            
                                            if (request.status >= 200 && request.status < 300) {
                                                load(response.path);
                                                // Store file path in data attribute
                                                inputElement.setAttribute('data-file-path', response.path);
                                                console.log('File stored at data-file-path:', response.path);

                                                // Update image preview if it's an image input
                                                if (isImageInput) {
                                                    const previewId = inputElement.id + '_preview';
                                                    const previewDiv = document.getElementById(previewId);
                                                    
                                                    if (previewDiv) {
                                                        // Use the url from response, this is the correct path
                                                        const imageUrl = response.url;
                                                        console.log('Loading preview image from:', imageUrl);
                                                        
                                                        if (imageUrl) {
                                                            // Use the handlePreviewImage function
                                                            handlePreviewImage(inputElement, previewDiv, imageUrl);
                                                        } else {
                                                            console.error('No image URL in response:', response);
                                                            previewDiv.innerHTML = '<p style="color: #e74c3c; font-size: 12px;">No preview URL</p>';
                                                            previewDiv.style.display = 'block';
                                                        }
                                                    } else {
                                                        console.error('Preview div not found:', previewId);
                                                    }
                                                }

                                                // Enable submit button when upload completes
                                                if (submitBtn) {
                                                    submitBtn.disabled = false;
                                                    submitBtn.style.opacity = '1';
                                                }
                                            } else {
                                                console.error('Upload failed with status:', request.status, response);
                                                error(response.error || 'Upload failed');
                                                // Enable submit button on error
                                                if (submitBtn) {
                                                    submitBtn.disabled = false;
                                                    submitBtn.style.opacity = '1';
                                                }
                                            }
                                        } catch (e) {
                                            console.error('Error parsing upload response:', e);
                                            error('Invalid response from server');
                                            // Enable submit button on error
                                            if (submitBtn) {
                                                submitBtn.disabled = false;
                                                submitBtn.style.opacity = '1';
                                            }
                                        }
                                    };

                                    request.onerror = () => {
                                        error('Network error');
                                        // Enable submit button on error
                                        if (submitBtn) {
                                            submitBtn.disabled = false;
                                            submitBtn.style.opacity = '1';
                                        }
                                    };

                                    request.open('POST', API_BASE + '/upload');
                                    request.setRequestHeader('X-CSRF-TOKEN', csrfToken);
                                    request.withCredentials = WITH_CREDENTIALS;
                                    request.send(formData);
                                }
                            }
                        });
                    });
                }, 500);
            } else {
                console.warn('FilePond not loaded');
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('applicationForm');

            // Location search is now setup after API loads locations (see above)
            // Select all & toggle for UK/International
            const selectAllUk = document.getElementById('selectAllUk');
            const selectAllIntl = document.getElementById('selectAllIntl');
            const toggleUk = document.getElementById('toggleUk');
            const toggleIntl = document.getElementById('toggleIntl');
            const ukGrid = document.getElementById('ukGrid');
            const intlGrid = document.getElementById('intlGrid');

            function checkAll(gridEl) {
                gridEl?.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = true);
            }

            function toggleGrid(button, gridEl) {
                if (!gridEl) return;
                const isHidden = gridEl.style.display === 'none';
                gridEl.style.display = isHidden ? '' : 'none';

                // Update arrow rotation
                const arrowSpan = button.querySelector('.toggle-arrow');
                if (isHidden) {
                    arrowSpan.style.transform = '';
                } else {
                    arrowSpan.style.transform = 'rotate(180deg)';
                }
            }

            selectAllUk?.addEventListener('click', () => checkAll(ukGrid));
            selectAllIntl?.addEventListener('click', () => checkAll(intlGrid));
            toggleUk?.addEventListener('click', () => toggleGrid(toggleUk, ukGrid));
            toggleIntl?.addEventListener('click', () => toggleGrid(toggleIntl, intlGrid));
        });
    </script>

    <script>
        // Preview image handler
        function handlePreviewImage(inputElement, previewDiv, imageUrl) {
            if (!previewDiv || !imageUrl) {
                console.warn('Invalid preview data:', { previewDiv, imageUrl });
                return false;
            }

            // Create img element
            const img = document.createElement('img');
            img.style.maxWidth = '100%';
            img.style.height = 'auto';
            img.alt = 'Preview';
            
            // Load with proper error handling
            img.onload = function() {
                console.log('✅ Preview image loaded successfully:', imageUrl);
                previewDiv.innerHTML = '';
                previewDiv.appendChild(img);
                previewDiv.style.display = 'block';
            };
            
            img.onerror = function(err) {
                console.error('❌ Failed to load preview image:', imageUrl, err);
                previewDiv.innerHTML = '<p style="color: #e74c3c; font-size: 12px;">Failed to load preview</p>';
                previewDiv.style.display = 'block';
                return false;
            };
            
            // Set source last to trigger loading
            img.src = imageUrl;
            return true;
        }

        // Ensure preview divs exist on page load
        document.addEventListener('DOMContentLoaded', function() {
            const photoInput = document.getElementById('photo');
            if (photoInput && !document.getElementById('photo_preview')) {
                const previewDiv = document.createElement('div');
                previewDiv.id = 'photo_preview';
                previewDiv.className = 'image-preview';
                photoInput.parentElement.appendChild(previewDiv);
                console.log('Created photo_preview div');
            }
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css">

    <script>
        $(document).ready(function() {
            // Configure toastr notifications
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
            // Show server-side Laravel validation errors (if present)
            @if ($errors->any())
                const serverErrors = @json($errors->all());
                serverErrors.forEach(function(msg) {
                    toastr.error(msg, 'Validation Error');
                });
            @endif
            
            // Initialize Select2 for country dropdowns
            $('.country-select').select2({
                placeholder: '-- Select Country --',
                allowClear: true,
                templateResult: formatCountry,
                templateSelection: formatCountrySelection,
                width: '100%',
                language: {
                    noResults: function() {
                        return 'No countries found';
                    }
                }
            });

            function formatCountry(state) {
                if (!state.id) return state.text;
                return $('<span>' + state.text + '</span>');
            }

            function formatCountrySelection(state) {
                if (!state.id) return state.text;
                return $('<span>' + state.text + '</span>');
            }

            // Call Portal API directly
            const API_BASE = '{{ $portalApiUrl }}/api/apply';
            const WITH_CREDENTIALS = false;

            // Handle form submission via AJAX
            $('#applicationForm').on('submit', function(e) {
                e.preventDefault();
                
                const form = $(this);
                const formData = new FormData(this);
                const submitBtn = $('#submitBtn');
                
                // Clear previous danger styling
                document.querySelectorAll('.input-danger').forEach(el => {
                    el.classList.remove('input-danger');
                });
                
                // Collect uploaded file paths from FilePond data attributes
                const fileInputs = document.querySelectorAll('.filepond');
                fileInputs.forEach(input => {
                    const filePath = input.getAttribute('data-file-path');
                    const fieldName = input.getAttribute('id');
                    if (filePath && fieldName) {
                        // Remove file object and add path string instead
                        formData.delete(fieldName);
                        formData.append(fieldName, filePath);
                    }
                });
                
                // Show loading message and disable submit button immediately
                submitBtn.prop('disabled', true).css('opacity', '0.5');
                submitBtn.html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Submitting...');
                toastr.info('Submitting your application...', 'Please wait');

                $.ajax({
                    url: API_BASE + '/submit',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: { 'Accept': 'application/json' },
                    dataType: 'json',
                    xhrFields: {
                        withCredentials: WITH_CREDENTIALS
                    },
                    success: function(response) {
                        console.log('AJAX Success Response:', response);
                        console.log('response.success:', response.success);
                        console.log('response.redirect:', response.redirect);
                        
                        if (response.success) {
                            // Build redirect URL from step and candidate if redirect not provided
                            let redirectUrl = response.redirect;
                            
                            // If on final step (8), redirect to success page immediately
                            if (window.currentStep == 8) {
                                toastr.success('Application submitted successfully!', 'Success');
                                redirectUrl = window.location.origin + '/apply/success';
                                // Immediate redirect for final step
                                window.location.replace(redirectUrl);
                                return;
                            }
                            
                            // Show success message for other steps
                            toastr.success('Application submitted successfully!', 'Success');
                            
                            if (!redirectUrl && response.step && response.candidate) {
                                redirectUrl = window.location.origin + '/?step=' + response.step + '&candidate=' + response.candidate;
                            } else if (!redirectUrl && response.step) {
                                redirectUrl = window.location.origin + '/?step=' + response.step;
                            }
                            
                            if (redirectUrl) {
                                console.log('Attempting redirect to:', redirectUrl);
                                // Redirect after short delay to let user see success message
                                setTimeout(() => {
                                    window.location.replace(redirectUrl);
                                }, 300);
                            } else {
                                console.log('No redirect - re-enabling button');
                                submitBtn.prop('disabled', false).css('opacity', '1');
                                submitBtn.html('Submit');
                            }
                        } else {
                            console.log('No redirect - re-enabling button');
                            // Re-enable button if no redirect
                            submitBtn.prop('disabled', false).css('opacity', '1');
                        }
                    },
                    error: function(xhr) {
                        // Re-enable button on error
                        submitBtn.prop('disabled', false).css('opacity', '1');
                        submitBtn.html('Submit');
                        
                        console.log('Error Status:', xhr.status);
                        console.log('Error Response:', xhr.responseJSON);
                        console.log('Error Response Text:', xhr.responseText);
                        
                        // Build a list of messages to show
                        const hasErrorsObject = !!(xhr.responseJSON && xhr.responseJSON.errors);
                        const errorsObj = (xhr.responseJSON && xhr.responseJSON.errors) || {};
                        let messages = [];

                        // Prefer field errors if provided (often 422)
                        if (hasErrorsObject) {
                            // Highlight fields with errors
                            Object.keys(errorsObj).forEach(function(fieldName) {
                                const input = document.querySelector(`[name="${fieldName}"]`);
                                if (input) {
                                    input.classList.add('input-danger');
                                }
                            });
                            messages = Object.values(errorsObj).flat();
                        }

                        // Prefer specific error string over generic message when both exist
                        if (xhr.responseJSON && typeof xhr.responseJSON.error === 'string') {
                            messages.push(xhr.responseJSON.error);
                        }

                        // Fallbacks
                        if (messages.length === 0 && xhr.responseJSON && typeof xhr.responseJSON.message === 'string') {
                            messages.push(xhr.responseJSON.message);
                        }
                        if (messages.length === 0 && xhr.statusText) {
                            messages.push(xhr.statusText);
                        }
                        if (messages.length === 0) {
                            messages.push('An error occurred. Please try again.');
                        }

                        // Show all gathered messages via toastr
                        messages.forEach(function(msg) {
                            toastr.error(msg, hasErrorsObject ? 'Validation Error' : 'Error');
                        });

                        // Scroll to the first highlighted field if any
                        const firstError = document.querySelector('.input-danger');
                        if (firstError) {
                            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            firstError.focus();
                        }
                    }
                });
            });
        });
    </script>
@endsection
