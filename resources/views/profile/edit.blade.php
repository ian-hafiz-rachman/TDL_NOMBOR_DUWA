@extends('layouts.app')

@section('content')
<div class="container pt-3">
    <!-- Back Button -->
    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('profile.index') }}" class="btn btn-outline-primary">
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
                        <div class="avatar-circle position-relative" id="avatar-container">
                            @if($user->avatar)
                                <img src="{{ Storage::url($user->avatar) }}" alt="Profile" class="avatar-image">
                            @else
                                <span class="initials">{{ substr($user->name, 0, 1) }}</span>
                            @endif
                            <div class="avatar-overlay">
                                <i class="fas fa-camera"></i>
                            </div>
                            <input type="file" id="avatar-upload" class="d-none" accept="image/*">
                        </div>
                    </div>

                    <!-- Edit Form -->
                    <form method="POST" action="{{ route('profile.update') }}" class="text-start">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label text-muted">Nama</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $user->name) }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label text-muted">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $user->email) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="current_password" class="form-label text-muted">Password Saat Ini</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" name="current_password">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>

                    <!-- Password Change Form -->
                    <form method="POST" action="{{ route('profile.password') }}" class="text-start mt-4">
                        @csrf
                        @method('PUT')
                        
                        <h5 class="mb-3">Ubah Password</h5>

                        <div class="mb-3">
                            <label for="old_password" class="form-label text-muted">Password Lama</label>
                            <input type="password" class="form-control @error('old_password') is-invalid @enderror" 
                                   id="old_password" name="old_password">
                            @error('old_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label text-muted">Password Baru</label>
                            <input type="password" class="form-control @error('new_password') is-invalid @enderror" 
                                   id="new_password" name="new_password">
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Minimal 8 karakter</div>
                        </div>

                        <div class="mb-4">
                            <label for="new_password_confirmation" class="form-label text-muted">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" 
                                   id="new_password_confirmation" name="new_password_confirmation">
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-key me-2"></i>Ubah Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@include('profile.crop-modal')

<!-- Alert Messages -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@endsection

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
<style>
    .profile-card {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 0 20px rgba(0,0,0,0.08);
    }

    .profile-avatar-large {
        width: 180px;
        height: 180px;
        margin: 0 auto;
        position: relative;
    }

    .avatar-circle {
        width: 100%;
        height: 100%;
        background-color: #4e73df;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        overflow: hidden;
        border: 4px solid #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .avatar-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s;
        border-radius: 50%;
    }

    .avatar-overlay i {
        color: white;
        font-size: 32px;
    }

    .avatar-circle:hover .avatar-overlay {
        opacity: 1;
    }

    .initials {
        font-size: 72px;
        color: white;
        font-weight: 500;
        text-transform: uppercase;
    }

    .form-control {
        padding: 0.75rem 1rem;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
    }

    .form-control:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
    }

    .img-container {
        max-width: 100%;
        max-height: 70vh;
        margin: 0 auto;
        background-color: #f8f9fa;
    }

    .cropper-view-box,
    .cropper-face {
        border-radius: 50%;
    }

    .cropper-container {
        max-height: 70vh !important;
    }

    /* Memastikan crop box selalu terlihat */
    .cropper-crop-box {
        min-width: 200px !important;
        min-height: 200px !important;
    }

    /* Memperbaiki tampilan modal */
    .modal-content {
        border-radius: 15px;
        overflow: hidden;
    }

    .modal-body {
        padding: 1rem;
    }
</style>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let cropper;

    // Click handler for avatar container
    const avatarContainer = document.getElementById('avatar-container');
    const fileInput = document.getElementById('avatar-upload');

    // Add click event to avatar container
    avatarContainer.addEventListener('click', function() {
        console.log('Avatar container clicked');
        fileInput.click();
    });

    // File input change handler
    fileInput.addEventListener('change', function(e) {
        console.log('File input changed');
        if (e.target.files && e.target.files[0]) {
            const file = e.target.files[0];
            console.log('File selected:', file.name);

            const reader = new FileReader();
            reader.onload = function(e) {
                console.log('File loaded');
                const cropImage = document.getElementById('crop-image');
                cropImage.src = e.target.result;

                // Show modal
                const cropModal = new bootstrap.Modal(document.getElementById('cropModal'));
                cropModal.show();

                // Initialize cropper after modal is shown
                document.getElementById('cropModal').addEventListener('shown.bs.modal', function() {
                    console.log('Modal shown, initializing cropper');
                    if (cropper) {
                        cropper.destroy();
                    }
                    cropper = new Cropper(cropImage, {
                        aspectRatio: 1,
                        viewMode: 2,
                        dragMode: 'move',
                        autoCropArea: 1,
                        restore: false,
                        guides: false,
                        center: true,
                        highlight: false,
                        cropBoxMovable: false,
                        cropBoxResizable: false,
                        toggleDragModeOnDblclick: false,
                    });
                }, { once: true });
            };
            reader.readAsDataURL(file);
        }
    });

    // Crop button handler
    document.getElementById('crop-button').addEventListener('click', function() {
        console.log('Crop button clicked');
        if (!cropper) {
            console.error('Cropper not initialized');
            return;
        }

        const button = this;
        button.disabled = true;
        button.querySelector('.normal-text').classList.add('d-none');
        button.querySelector('.loading-text').classList.remove('d-none');

        cropper.getCroppedCanvas({
            width: 400,
            height: 400
        }).toBlob(function(blob) {
            console.log('Image cropped, preparing to upload');
            const formData = new FormData();
            formData.append('avatar', blob, 'avatar.jpg');
            formData.append('_token', '{{ csrf_token() }}');

            fetch('{{ route("profile.avatar") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log('Upload response:', data);
                if (data.success) {
                    window.location.reload();
                } else {
                    throw new Error(data.message || 'Upload failed');
                }
            })
            .catch(error => {
                console.error('Upload error:', error);
                alert('Failed to upload avatar: ' + error.message);
            })
            .finally(() => {
                button.disabled = false;
                button.querySelector('.normal-text').classList.remove('d-none');
                button.querySelector('.loading-text').classList.add('d-none');
            });
        }, 'image/jpeg', 0.8);
    });

    // Control buttons
    document.getElementById('zoom-in')?.addEventListener('click', () => cropper?.zoom(0.1));
    document.getElementById('zoom-out')?.addEventListener('click', () => cropper?.zoom(-0.1));
    document.getElementById('rotate-left')?.addEventListener('click', () => cropper?.rotate(-90));
    document.getElementById('rotate-right')?.addEventListener('click', () => cropper?.rotate(90));
    document.getElementById('change-image')?.addEventListener('click', () => fileInput.click());

    // Clean up when modal is hidden
    document.getElementById('cropModal').addEventListener('hidden.bs.modal', function() {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
    });
});
</script>
@endsection 