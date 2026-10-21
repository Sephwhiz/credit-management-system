<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Credit Management System</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            .auth-shell {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 2rem 1rem;
                background: #f9fafb;
            }

            .auth-card {
                width: 100%;
                max-width: 28rem;
                padding: 2rem;
                border: 1px solid #e5e7eb;
                border-radius: 0.75rem;
                background: #fff;
                box-shadow: 0 10px 25px rgb(15 23 42 / 0.08);
            }

            .auth-card input:not([type="checkbox"]):not([type="hidden"]) {
                display: block;
                width: 100%;
                min-height: 2.75rem;
                border: 1px solid #d1d5db;
                border-radius: 0.375rem;
                background: #fff;
                padding: 0.625rem 0.75rem;
            }

            .auth-card input:not([type="checkbox"]):not([type="hidden"]):focus {
                border-color: #6366f1;
                outline: 2px solid transparent;
                outline-offset: 2px;
                box-shadow: 0 0 0 2px #a5b4fc;
            }

            .auth-card a {
                color: #4f46e5;
            }

            .auth-card a:hover {
                color: #3730a3;
            }

            .auth-card button[type="submit"] {
                position: relative;
                z-index: 1;
                display: inline-flex;
                min-height: 2.75rem;
                align-items: center;
                justify-content: center;
                border: 1px solid transparent;
                border-radius: 0.375rem;
                background: #4f46e5;
                padding: 0.625rem 1rem;
                color: #fff;
                font-weight: 600;
                cursor: pointer;
            }

            .auth-card button[type="submit"]:hover {
                background: #4338ca;
            }

            .auth-card button[type="submit"]:focus-visible,
            .auth-card a:focus-visible {
                outline: 2px solid #6366f1;
                outline-offset: 2px;
            }

            .auth-actions {
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                justify-content: space-between;
                gap: 0.75rem;
            }

            @media (max-width: 480px) {
                .auth-card {
                    padding: 1.5rem;
                }
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <main class="auth-shell">
            <section class="auth-card">
                <a href="/" class="mb-6 block text-center text-lg font-bold text-indigo-700 hover:text-indigo-800">
                    Credit Management System
                </a>
                {{ $slot }}
            </section>
        </main>
    </body>
</html>