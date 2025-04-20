@extends('layouts.app')

@section('content')
<div class="container pt-3">
    <!-- Back Button -->
    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="profile-card">
                <!-- Profile Content -->
                <div class="text-center">
                    <!-- Profile Avatar -->
                    <div class="profile-avatar-large">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Profile" class="rounded-circle shadow">
                        @else
                            <div class="avatar-circle-large">
                                <span class="initials-large">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Profile Info -->
                    <div class="profile-info">
                        <h1 class="profile-name">{{ $user->name }}</h1>
                        <div class="info-details">
                            <p class="mb-1">{{ $user->email }}</p>
                            <p class="mb-0">Bergabung {{ $user->created_at->format('d F Y') }}</p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <div class="d-flex gap-3 justify-content-center">
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit me-2"></i>Edit Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .profile-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 0 20px rgba(0,0,0,0.08);
    }

    .profile-avatar-large {
        width: 180px;
        height: 180px;
        margin: 0 auto;
        position: relative;
        margin-bottom: 0.75rem;
    }

    .profile-avatar-large img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border: 4px solid #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
    }

    .avatar-circle-large {
        width: 180px;
        height: 180px;
        background-color: #4e73df;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        border: 4px solid #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .initials-large {
        font-size: 72px;
        color: white;
        font-weight: 500;
        text-transform: uppercase;
    }

    .profile-info {
        margin-bottom: 1.5rem;
    }

    .profile-name {
        font-size: 2.5rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.25rem;
    }

    .info-details {
        color: #6b7280;
        font-size: 1rem;
    }

    .info-details p {
        margin-bottom: 0.25rem;
    }

    .action-buttons {
        max-width: 500px;
        margin: 0 auto;
    }

    /* Button Styles */
    .btn {
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        border-radius: 8px;
    }

    .btn:hover {
        transform: translateY(-1px);
    }

    .btn:active {
        transform: translateY(0);
    }

    .btn-primary {
        background-color: #4e73df;
        border: none;
        box-shadow: 0 2px 4px rgba(78, 115, 223, 0.1);
    }

    .btn-primary:hover {
        background-color: #2e59d9;
    }

    .btn-outline-primary {
        color: #4e73df;
        border-color: #4e73df;
    }

    .btn-outline-primary:hover {
        background-color: #4e73df;
        color: white;
    }

    .btn-outline-danger {
        color: #e74a3b;
        border-color: #e74a3b;
    }

    .btn-outline-danger:hover {
        background-color: #e74a3b;
        color: white;
    }
</style>
@endsection 