<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('ZapTask', 'ZapTask') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/zap icon.svg') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --primary-color: #0099ff;
            --hover-color: #0088ee;
            --navbar-height: 60px;
        }

        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding-top: var(--navbar-height);
        }

        /* Navbar Styling */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 0.5rem 0;
            transition: all 0.3s ease;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            height: var(--navbar-height);
        }

        .navbar.scrolled {
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            background: rgba(255, 255, 255, 0.98);
        }

        .navbar-brand img {
            height: 28px;
            transition: transform 0.3s ease;
        }

        .navbar-brand:hover img {
            transform: scale(1.05);
        }

        /* Auth Buttons */
        .auth-buttons {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .auth-btn {
            padding: 0.4rem 1.2rem;
            border-radius: 50px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .btn-register {
            color: var(--primary-color);
            background: transparent;
            border: 2px solid var(--primary-color);
        }

        .btn-register:hover {
            background: rgba(0, 153, 255, 0.1);
            transform: translateY(-2px);
            color: var(--primary-color);
        }

        .btn-login {
            background: var(--primary-color);
            color: white;
            border: 2px solid var(--primary-color);
        }

        .btn-login:hover {
            background: var(--hover-color);
            border-color: var(--hover-color);
            transform: translateY(-2px);
            color: white;
        }

        /* User Profile Section */
        .header-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--primary-color);
            transition: transform 0.3s ease;
        }

        .header-avatar:hover {
            transform: scale(1.05);
        }

        .avatar-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-initials {
            color: white;
            font-size: 14px;
            font-weight: 500;
            text-transform: uppercase;
        }

        .user-name {
            color: #2d3748;
            margin-left: 8px;
            font-weight: 500;
            transition: color 0.3s ease;
            font-size: 0.9rem;
        }

        .user-name:hover {
            color: var(--primary-color);
        }

        @media (max-width: 768px) {
            :root {
                --navbar-height: 50px;
            }

            .auth-buttons {
                gap: 8px;
            }

            .auth-btn {
                padding: 0.35rem 1rem;
                font-size: 0.85rem;
            }

            .user-name {
                display: none;
            }
        }

        /* Khusus untuk halaman welcome yang memiliki scroll-container */
        .welcome-page {
            padding-top: 0 !important;
        }

        .welcome-page .scroll-container {
            height: 100vh;
        }
    </style>
    @yield('styles')
</head>
<body class="{{ Request::is('/') ? 'welcome-page' : '' }}">
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/zap 3.png') }}" alt="{{ config('app.name', 'Laravel') }}">
            </a>
            
            <div class="ms-auto">
                @auth
                    <a href="{{ route('profile.index') }}" class="d-flex align-items-center text-decoration-none">
                        <div class="header-avatar">
                            @if(Auth::user()->avatar)
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Profile" class="avatar-image">
                            @else
                                <div class="avatar-initials">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <span class="user-name">{{ Auth::user()->name }}</span>
                    </a>
                @else
                    <div class="auth-buttons">
                        <a href="{{ route('login') }}" class="auth-btn btn-login">Login</a>
                        <a href="{{ route('register') }}" class="auth-btn btn-register">Register</a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Add scroll effect to navbar
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 10) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
