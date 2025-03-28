@extends('layouts.app')

@section('content')
<div class="welcome-container">
    <div class="text-center">
        <div class="welcome-message">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <div class="spinner-border text-primary mb-3" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <h4 class="text-primary mb-2">Welcome, {{ Auth::user()->name }}!</h4>
            <p class="text-muted">Redirecting to your dashboard...</p>
        </div>
    </div>
</div>

<style>
    .welcome-container {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .welcome-message {
        opacity: 0;
        animation: fadeIn 0.5s ease-out forwards;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            window.location.href = "{{ route('dashboard') }}";
        }, 1500);
    });
</script>
@endsection
