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
                            <h4 class="fw-bold">Buat Akun Baru</h4>
                            <p class="text-muted">Mulai kelola tugas Anda dengan lebih baik</p>
                        </div>

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">Nama</label>
                                <div class="input-group">
                                    <span class="input-group-text border-end-0">
                                        <i class="fas fa-user text-muted"></i>
                                    </span>
                                    <input id="name" type="text" 
                                        class="form-control border-start-0 @error('name') is-invalid @enderror" 
                                        name="name" value="{{ old('name') }}" 
                                        required autocomplete="name" autofocus
                                        placeholder="Masukkan nama Anda">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text border-end-0">
                                        <i class="fas fa-envelope text-muted"></i>
                                    </span>
                                    <input id="email" type="email" 
                                        class="form-control border-start-0 @error('email') is-invalid @enderror" 
                                        name="email" value="{{ old('email') }}" 
                                        required autocomplete="email"
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
                                        name="password" required autocomplete="new-password"
                                        placeholder="Buat password Anda">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password-confirm" class="form-label">Konfirmasi Password</label>
                                <div class="input-group">
                                    <span class="input-group-text border-end-0">
                                        <i class="fas fa-lock text-muted"></i>
                                    </span>
                                    <input id="password-confirm" type="password" 
                                        class="form-control border-start-0" 
                                        name="password_confirmation" required autocomplete="new-password"
                                        placeholder="Masukkan ulang password Anda">
                                </div>
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary">
                                    Daftar
                                </button>
                            </div>

                            <div class="text-center">
                                <span class="text-muted">Sudah punya akun? </span>
                                <a href="{{ route('login') }}" class="text-decoration-none">Masuk di sini</a>
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
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='400' height='400' viewBox='0 0 400 400'%3E%3Cstyle%3E.todo-list %7B fill: none; stroke: %230099ff; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; stroke-opacity: 0.15;%7D%3C/style%3E%3Cg class='todo-list'%3E%3Cpath d='M50 50h100v120H50z' transform='rotate(-2 100 100)' /%3E%3Cpath d='M55 70h90 M55 90h90 M55 110h90 M55 130h90 M55 150h90' transform='rotate(-2 100 100)' /%3E%3Cpath d='M60 75l-5-5 M60 95l-5-5 M60 115l-5-5 M60 135l-5-5' transform='rotate(-2 100 100)' /%3E%3Cpath d='M200 80h80v100h-80z' transform='rotate(3 240 130)' /%3E%3Cpath d='M205 100h70 M205 120h70 M205 140h70 M205 160h70' transform='rotate(3 240 130)' /%3E%3Cpath d='M50 200h90v110H50z' transform='rotate(-1 95 255)' /%3E%3Cpath d='M55 220h80 M55 240h80 M55 260h80 M55 280h80' transform='rotate(-1 95 255)' /%3E%3Cpath d='M180 220h100v90h-100z' transform='rotate(1 230 265)' /%3E%3Cpath d='M190 240h80 M190 260h80 M190 280h80' transform='rotate(1 230 265)' /%3E%3C/g%3E%3C/svg%3E");
    }

    .auth-container::before {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300' viewBox='0 0 300 300'%3E%3Cstyle%3E.notes %7B fill: none; stroke: %23666666; stroke-width: 1.5; stroke-linecap: round; stroke-opacity: 0.1;%7D%3C/style%3E%3Cg class='notes'%3E%3Cpath d='M30 30h80v90H30z M35 45h70 M35 60h70 M35 75h70 M35 90h70 M35 105h70' transform='rotate(-3 70 75)' /%3E%3Cpath d='M150 50h60v80h-60z M155 65h50 M155 80h50 M155 95h50 M155 110h50' transform='rotate(2 180 90)' /%3E%3Cpath d='M40 150h70v85H40z M45 165h60 M45 180h60 M45 195h60 M45 210h60' transform='rotate(-2 75 192)' /%3E%3Cpath d='M160 140h65v75h-65z M165 155h55 M165 170h55 M165 185h55' transform='rotate(3 192 177)' /%3E%3C/g%3E%3C/svg%3E");
        opacity: 0.7;
        transform: rotate(-2deg);
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

    .card-body {
        padding: 2.5rem 4rem !important;
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

    @media (max-width: 768px) {
        .card-body {
            padding: 2rem !important;
        }
        
        .card {
            margin: 0 0.5rem;
        }
    }
</style>
@endsection
