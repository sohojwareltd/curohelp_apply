<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Curohelp' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cabin:ital,wght@0,400..700;1,400..700&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <!-- FilePond CSS -->
    <link href="https://unpkg.com/filepond/dist/filepond.min.css" rel="stylesheet">
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="{{asset('images/icon.png')}}" type="image/x-icon">
    <style>
        :root {
            --bg: #ffffff;
            --fg: #0c0c0c;
            --muted: #5f5f5a;
            --border: #e5e5e0;
            --primary: #dbb88e;
            --card: #f8f8f5;
            --radius: 18px;
            --shadow: 0 20px 60px rgba(0, 0, 0, 0.06);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--fg);
            font-family: "Cabin", sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
        }

        footer {
            margin-top: auto;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .shell {
            width: min(1200px, 100%);
            margin: 0 auto;
            padding: 32px 24px 64px;
        }

        header.site-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 45px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: linear-gradient(135deg, #ffffff 0%, #fafafa 100%);
            border-bottom: 1px solid #e8e8e8;
            z-index: 100;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
            gap: 24px;
            transition: padding 400ms cubic-bezier(0.68, -0.55, 0.265, 1.55), box-shadow 300ms ease, background 300ms ease, opacity 400ms ease;
            opacity: 1;
        }

        header.site-header.scrolled {
            /* padding: 10px 45px; */
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(219, 184, 142, 0.2);
        }

        header.site-header:hover {
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        main {
            margin-top: 0;
        }

        .brand {
            font-weight: 800;
            letter-spacing: -0.02em;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 20px;
            flex-shrink: 0;
            color: #0c0c0c;
            transition: all 400ms cubic-bezier(0.68, -0.55, 0.265, 1.55);
            cursor: pointer;
        }

        .brand:hover {
            transform: scale(1.05);
        }

        header.site-header.scrolled .brand {
            font-size: 18px;
        }

        .brand-dot {
            width: 12px;
            height: 12px;
            border-radius: 999px;
            background: linear-gradient(135deg, #dbb88e 0%, #c4a074 100%);
            display: inline-block;
            box-shadow: 0 2px 8px rgba(219, 184, 142, 0.4);
            transition: all 400ms cubic-bezier(0.68, -0.55, 0.265, 1.55);
            animation: pulse-glow 3s ease-in-out infinite;
        }

        .brand:hover .brand-dot {
            transform: rotate(360deg) scale(1.2);
            box-shadow: 0 4px 20px rgba(219, 184, 142, 0.8);
        }

        @keyframes pulse-glow {

            0%,
            100% {
                transform: scale(1);
                box-shadow: 0 2px 8px rgba(219, 184, 142, 0.4);
            }

            50% {
                transform: scale(1.15);
                box-shadow: 0 6px 24px rgba(219, 184, 142, 0.7);
            }
        }

        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            border: 1px solid transparent;
            background: linear-gradient(135deg, #dbb88e 0%, #c4a074 100%);
            color: #ffffff;
            font-weight: 700;
            font-size: 13px;
            transition: all 350ms cubic-bezier(0.68, -0.55, 0.265, 1.55);
            box-shadow: 0 6px 20px rgba(219, 184, 142, 0.4);
            cursor: pointer;
            position: relative;
            overflow: hidden;
            z-index: 0;
        }

        .btn::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #c4a074 0%, #dbb88e 100%);
            opacity: 0;
            transition: opacity 350ms ease;
            z-index: -1;
            pointer-events: none;
        }

        .btn:hover {
            transform: translateY(-4px) scale(1.03);
            box-shadow: 0 12px 32px rgba(219, 184, 142, 0.6);
            color: #ffffff !important;
        }

        .btn:hover::before {
            opacity: 1;
        }

        .btn>* {
            position: relative;
            z-index: 2;
            color: inherit;
        }

        .btn a,
        .btn span {
            color: inherit;
        }

        .btn.outline {
            background: #fff;
            border: 1px solid #0c0c0c;
            color: #0c0c0c;
            box-shadow: inset 0 0 0 1px rgba(12, 12, 12, 0.1);
            overflow: hidden;
            position: relative;
        }

        .btn.outline::before {
            background: linear-gradient(135deg, rgba(219, 184, 142, 0.18), rgba(196, 160, 116, 0.18));
            opacity: 0;
            transition: opacity 250ms ease;
            pointer-events: none;
            z-index: -1;
        }

        .btn.outline:hover {
            background: #fff7ed;
            border-color: #c79b63;
            color: #0c0c0c !important;
            box-shadow: 0 8px 20px rgba(219, 184, 142, 0.24);
        }

        .btn.outline:hover::before {
            opacity: 1;
        }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        .muted {
            color: var(--muted);
        }

        .pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 20px;
            border-radius: 999px;
            background: #0c0c0c;
            color: #fff;
            font-weight: 600;
            letter-spacing: 0.02em;
        }

        .stack {
            display: grid;
            gap: 18px;
        }

        .grid-2 {
            display: grid;
            gap: 18px;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        }

        .progress {
            width: 100%;
            height: 10px;
            background: #ecebe8;
            border-radius: 999px;
            overflow: hidden;
            position: relative;
        }

        .progress>span {
            display: block;
            height: 100%;
            background: var(--primary);
            width: var(--progress, 0%);
            transition: width 260ms ease;
        }

        .surface {
            padding: 28px;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            background: #fff;
            box-shadow: var(--shadow);
        }

        form .field {
            display: grid;
            gap: 8px;
        }

        form label {
            font-weight: 600;
            letter-spacing: 0.01em;
        }

        form input[type="text"],
        form input[type="email"],
        form input[type="number"],
        form textarea,
        form select {
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid var(--border);
            background: #fff;
            font-family: "Cabin", sans-serif;
        }

        form textarea {
            min-height: 110px;
            resize: vertical;
        }

        .step-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-top: 20px;
        }

        .badge {
            display: inline-flex;
            padding: 8px 10px;
            background: #efede8;
            border-radius: 10px;
            font-weight: 600;
        }

        .list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            gap: 10px;
        }

        /* .checkbox-grid {
            display: grid;
            gap: 12px;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        } */

        .checkbox-item {
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 12px;
            display: flex;
            gap: 10px;
            align-items: center;
            cursor: pointer;
            background: #fff;
            transition: border 150ms ease, transform 150ms ease;
        }

        .checkbox-item:hover {
            border-color: var(--primary);
            transform: translateY(-1px);
        }

        .files {
            display: grid;
            gap: 12px;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        }

        .file-tile {
            border: 1px dashed var(--border);
            border-radius: 14px;
            padding: 14px;
            background: #fff;
        }

        .callout {
            padding: 14px 16px;
            background: #0c0c0c;
            color: #fff;
            border-radius: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .section {
            display: grid;
            gap: 24px;
        }

        .hero {
            display: grid;
            gap: 28px;
            align-items: center;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        }

        .hero-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 26px;
            box-shadow: var(--shadow);
        }

        .tiles {
            display: grid;
            gap: 14px;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        }

        .stepper {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 8px;
        }

        .stepper__item {
            padding: 10px 12px;
            border-radius: 12px;
            border: 1px solid var(--border);
            background: #fff;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
        }

        .stepper__dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--border);
        }

        .stepper__item.is-active {
            border-color: var(--primary);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.04);
        }

        .stepper__item.is-active .stepper__dot {
            background: var(--primary);
        }

        .upload-grid {
            display: grid;
            gap: 14px;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        }

        .pill-ghost {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 10px;
            border-radius: 999px;
            background: #f1f0eb;
            color: #0c0c0c;
        }

        .roles-grid {
            display: grid;
            gap: 18px;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            margin: 24px 0;
        }

        .role-card {
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 18px;
            background: #fff;
            text-align: center;
            transition: transform 180ms ease, box-shadow 180ms ease;
        }

        .role-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 40px rgba(0, 0, 0, 0.08);
            border-color: var(--primary);
        }

        .role-card__icon {
            font-size: 32px;
            margin-bottom: 10px;
            display: block;
        }

        .role-card__name {
            font-weight: 600;
            font-size: 15px;
            margin: 0;
        }

        .benefits-grid {
            display: grid;
            gap: 20px;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            margin: 24px 0;
        }

        .benefit-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 20px;
            display: grid;
            gap: 10px;
        }

        .benefit-card__number {
            color: var(--primary);
            font-weight: 700;
            font-size: 28px;
        }

        .benefit-card__title {
            font-weight: 600;
        }

        .testimonial {
            background: #0c0c0c;
            color: #fff;
            border-radius: 18px;
            padding: 32px;
            text-align: center;
            max-width: 680px;
            margin: 0 auto;
        }

        .testimonial__quote {
            font-size: 18px;
            line-height: 1.6;
            margin-bottom: 16px;
            font-style: italic;
        }

        .testimonial__author {
            font-weight: 600;
            color: var(--primary);
        }

        .featured-logos {
            display: grid;
            gap: 20px;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            align-items: center;
            justify-items: center;
            margin: 24px 0;
            padding: 24px 0;
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }

        .featured-logo {
            opacity: 0.5;
            transition: opacity 180ms ease;
            font-weight: 700;
            letter-spacing: 0.04em;
            font-size: 13px;
        }

        .featured-logo:hover {
            opacity: 0.8;
        }

        .pricing-strip {
            background: var(--card);
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            padding: 28px 0;
            margin: 32px 0;
        }

        .pricing-grid {
            display: grid;
            gap: 18px;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        }

        .pricing-item {
            text-align: center;
        }

        .pricing-item__number {
            color: var(--primary);
            font-weight: 700;
            font-size: 32px;
            line-height: 1;
        }

        .pricing-item__label {
            color: var(--muted);
            font-size: 14px;
            margin-top: 4px;
        }

        .site-footer {
            background: #0c0c0c;
            color: #fff;
            padding: 40px 0 32px;
            margin-top: 48px;
        }

        .footer-grid {
            display: grid;
            gap: 24px;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            margin-bottom: 24px;
        }

        .footer-col h4 {
            margin: 0 0 12px;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--primary);
        }

        .footer-col ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            gap: 8px;
        }

        .footer-col a {
            color: #aaa;
            text-decoration: none;
            font-size: 14px;
            transition: color 180ms ease;
        }

        .footer-col a:hover {
            color: #fff;
        }

        .footer-bottom {
            border-top: 1px solid #333;
            padding-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            font-size: 13px;
            color: #666;
        }

        header.site-header nav {
            display: flex;
            align-items: center;
            flex: 1;
            justify-content: center;
        }

        header.site-header .nav-links {
            display: flex;
            gap: 42px;
            align-items: center;
        }

        header.site-header .nav-links a {
            font-size: 15px;
            font-weight: 600;
            color: #333;
            transition: all 350ms cubic-bezier(0.68, -0.55, 0.265, 1.55);
            padding: 10px 16px;
            border-radius: 10px;
            position: relative;
            background: transparent;
            letter-spacing: 0.01em;
        }

        header.site-header .nav-links a::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 10px;
            background: linear-gradient(135deg, rgba(219, 184, 142, 0.12) 0%, rgba(196, 160, 116, 0.12) 100%);
            opacity: 0;
            transition: opacity 350ms cubic-bezier(0.68, -0.55, 0.265, 1.55);
            z-index: -1;
        }

        header.site-header .nav-links a::after {
            content: '';
            position: absolute;
            bottom: 6px;
            left: 50%;
            transform: translateX(-50%) scaleX(0);
            width: calc(100% - 32px);
            height: 3px;
            background: linear-gradient(90deg, #dbb88e 0%, #c4a074 100%);
            transition: transform 400ms cubic-bezier(0.68, -0.55, 0.265, 1.55);
            border-radius: 999px;
            box-shadow: 0 2px 8px rgba(219, 184, 142, 0.5);
        }

        header.site-header .nav-links a:hover {
            color: #dbb88e;
            transform: translateY(-3px) scale(1.05);
        }

        header.site-header .nav-links a:hover::before {
            opacity: 1;
        }

        header.site-header .nav-links a:hover::after {
            transform: translateX(-50%) scaleX(1);
        }

        header.site-header .nav-links a:nth-child(1) {
            animation: slideInDown 0.5s ease-out 0.1s both;
        }

        header.site-header .nav-links a:nth-child(2) {
            animation: slideInDown 0.5s ease-out 0.2s both;
        }

        header.site-header .nav-links a:nth-child(3) {
            animation: slideInDown 0.5s ease-out 0.3s both;
        }

        header.site-header .nav-links a:nth-child(4) {
            animation: slideInDown 0.5s ease-out 0.4s both;
        }

        header.site-header .nav-links a:nth-child(5) {
            animation: slideInDown 0.5s ease-out 0.5s both;
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        header.site-header .nav-actions {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-shrink: 0;
        }

        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: var(--fg);
            padding: 8px;
            transition: all 200ms ease;
        }

        .mobile-menu-toggle:hover {
            color: #dbb88e;
            transform: scale(1.1);
        }

        .mobile-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            flex-direction: column;
            background: linear-gradient(135deg, #ffffff 0%, #fafafa 100%);
            border-bottom: 1px solid #e8e8e8;
            padding: 16px;
            gap: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }

        .mobile-menu a {
            padding: 12px 14px;
            border-radius: 8px;
            font-size: 14px;
            transition: all 200ms ease;
            color: #555;
            font-weight: 600;
        }

        .mobile-menu a:hover {
            background: rgba(219, 184, 142, 0.1);
            color: #dbb88e;
        }

        header.site-header.mobile-open .mobile-menu {
            display: flex;
        }

        @media (max-width: 720px) {
            header.site-header {
                padding: 12px 20px;
                gap: 12px;
            }

            header.site-header .nav-links {
                display: none;
            }

            header.site-header .nav-actions {
                gap: 8px;
            }

            .mobile-menu-toggle {
                display: block;
            }

            .shell {
                padding: 20px 18px 48px;
            }

            .roles-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .testimonial {
                padding: 20px;
            }

            .footer-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .footer-bottom {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>
    <!-- Header removed per request -->
    <main class="container-fluid">
        @yield('content')
    </main>
    
    <!-- Header JS removed per request -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <!-- FilePond JS -->
    <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.min.js"></script>
</body>
