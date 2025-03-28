@extends('layouts.app')

@section('content')
<div class="auth-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card border-0">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <img src="{{ asset('images/zap 1.png') }}" alt="Logo" class="logo-login mb-3">
                            <h4 class="fw-bold">Selamat Datang Kembali!</h4>
                            <p class="text-muted">Masuk untuk melanjutkan aktivitas Anda</p>
                        </div>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text border-end-0">
                                        <i class="fas fa-envelope text-muted"></i>
                                    </span>
                                    <input id="email" type="email" 
                                        class="form-control border-start-0 @error('email') is-invalid @enderror" 
                                        name="email" value="{{ old('email') }}" 
                                        required autocomplete="email" autofocus 
                                        placeholder="Masukkan email Anda">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text border-end-0">
                                        <i class="fas fa-lock text-muted"></i>
                                    </span>
                                    <input id="password" type="password" 
                                        class="form-control border-start-0 @error('password') is-invalid @enderror" 
                                        name="password" required autocomplete="current-password"
                                        placeholder="Masukkan password Anda">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" 
                                        id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label text-muted" for="remember">
                                        Ingat Saya
                                    </label>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    Masuk
                                </button>
                                
                                <div class="text-center">
                                    <span class="text-muted">atau</span>
                                </div>

                                <a href="{{ route('google.login') }}" class="btn btn-outline-secondary">
                                    <img src="https://www.google.com/favicon.ico" alt="Google" class="me-2" style="width: 20px;">
                                    Masuk dengan Google
                                </a>
                                
                                @if (Route::has('password.request'))
                                    <a class="btn btn-link text-muted text-decoration-none" href="{{ route('password.request') }}">
                                        Lupa Password?
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .auth-container {
        min-height: calc(100vh - 60px);
        display: flex;
        align-items: center;
        padding: 2rem 0;
        background-color: #f8f9fa;
        position: relative;
        overflow: hidden;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300' viewBox='0 0 300 300'%3E%3Cstyle%3E.todo-list %7B fill: none; stroke: %230099ff; stroke-width: 2; stroke-linecap: round; stroke-opacity: 0.2;%7D%3C/style%3E%3Cg class='todo-list'%3E%3Cpath d='M50 50h80v100H50z M50 50l5-5h80l5 5 M55 70h70 M55 90h70 M55 110h70 M55 130h70'/%3E%3Cpath d='M150 100h80v100h-80z M150 100c0-5 0-5 5-10h70c5 5 5 5 5 10 M155 120h70 M155 140h70 M155 160h70 M155 180h70'/%3E%3Cpath d='M50 170h80v100H50z M50 170c0-5 2-8 5-10h70c3 2 5 5 5 10 M55 190h70 M55 210h70 M55 230h70 M55 250h70'/%3E%3Cpath d='M150 220h80v100h-80z M150 220l5-8h70l5 8 M155 240h70 M155 260h70 M155 280h70 M155 300h70'/%3E%3C/g%3E%3C/svg%3E");
    }

    .auth-container::before {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='200' height='200' viewBox='0 0 200 200'%3E%3Cstyle%3E.checklist %7B fill: none; stroke: %23666666; stroke-width: 1.5; stroke-linecap: round; stroke-opacity: 0.1;%7D%3C/style%3E%3Cg class='checklist'%3E%3Cpath d='M40 40h60v80H40z M40 40c0-3 1-5 3-6h54c2 1 3 3 3 6 M45 55h50 M45 70h50 M45 85h50 M45 100h50'/%3E%3Crect x='120' y='60' width='50' height='70' rx='5'/%3E%3Cpath d='M125 75h40 M125 90h40 M125 105h40 M125 120h40'/%3E%3C/g%3E%3C/svg%3E");
        opacity: 0.7;
        transform: rotate(-5deg);
    }

    .card {
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid rgba(0, 0, 0, 0.08);
        backdrop-filter: blur(10px);
        position: relative;
        z-index: 1;
    }

    .logo-login {
        height: 50px;
        width: auto;
        margin-bottom: 1rem;
    }

    .form-label {
        font-weight: 500;
        font-size: 0.9rem;
        color: #444;
    }

    .input-group {
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        border-radius: 8px;
    }

    .input-group-text {
        background-color: #fff;
        border-right: 0;
        border-radius: 8px 0 0 8px;
    }

    .form-control {
        border-left: 0;
        border-radius: 0 8px 8px 0;
    }

    .input-group-text i {
        width: 1rem;
        text-align: center;
    }

    .form-control:focus {
        border-color: #dee2e6;
        box-shadow: none;
    }

    .form-control:focus + .input-group-text {
        border-color: #dee2e6;
    }

    .btn-primary {
        padding: 0.75rem;
        font-weight: 500;
        background-color: var(--primary-color);
        border: none;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 153, 255, 0.2);
    }

    .btn-primary:hover {
        background-color: var(--hover-color);
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(0, 153, 255, 0.3);
    }

    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .card-body {
        padding: 2.5rem 4rem !important;
    }

    @media (max-width: 768px) {
        .auth-container {
            padding: 1rem;
        }
        
        .card {
            margin: 0 0.5rem;
        }

        .card-body {
            padding: 2rem !important;
        }
    }

    .btn-outline-secondary {
        border: 1px solid #ddd;
        padding: 0.75rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .btn-outline-secondary:hover {
        background-color: #f8f9fa;
        border-color: #ddd;
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection
