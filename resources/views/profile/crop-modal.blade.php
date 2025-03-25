<!-- Crop Modal -->
<div class="modal fade" id="cropModal" tabindex="-1" aria-labelledby="cropModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cropModalLabel">Crop Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <img id="crop-image" src="" alt="Preview" class="img-fluid">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="crop-button">
                    <span class="normal-text">Save</span>
                    <span class="loading-text d-none">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Loading...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .img-container {
        max-height: 400px;
        overflow: hidden;
    }
    
    #crop-image {
        max-width: 100%;
        display: block;
    }

    .modal-content {
        border-radius: 15px;
        border: none;
    }

    .modal-header {
        border-bottom: 1px solid rgba(0,0,0,0.05);
        padding: 1rem 1.5rem;
    }

    .modal-footer {
        border-top: 1px solid rgba(0,0,0,0.05);
        padding: 1rem 1.5rem;
    }

    .btn-secondary {
        background-color: #edf2f7;
        border: none;
        color: #4a5568;
    }

    .btn-secondary:hover {
        background-color: #e2e8f0;
        color: #2d3748;
    }
</style>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
<script>
    let cropper;
    
    document.getElementById('cropModal').addEventListener('shown.bs.modal', function () {
        cropper = new Cropper(document.getElementById('crop-image'), {
            aspectRatio: 1,
            viewMode: 1,
            dragMode: 'move',
            autoCropArea: 1,
            restore: false,
            guides: true,
            center: true,
            highlight: false,
            cropBoxMovable: false,
            cropBoxResizable: false,
            toggleDragModeOnDblclick: false,
        });
    });

    document.getElementById('cropModal').addEventListener('hidden.bs.modal', function () {
        cropper.destroy();
        cropper = null;
    });

    document.getElementById('crop-button').addEventListener('click', function() {
        const button = this;
        button.disabled = true;
        button.querySelector('.normal-text').classList.add('d-none');
        button.querySelector('.loading-text').classList.remove('d-none');

        const canvas = cropper.getCroppedCanvas({
            width: 400,
            height: 400
        });

        canvas.toBlob(function(blob) {
            const formData = new FormData();
            formData.append('avatar', blob, 'avatar.jpg');
            formData.append('_token', '{{ csrf_token() }}');

            fetch('{{ route("profile.avatar") }}', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    throw new Error('Failed to update avatar');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to update avatar');
                button.disabled = false;
                button.querySelector('.normal-text').classList.remove('d-none');
                button.querySelector('.loading-text').classList.add('d-none');
            });
        }, 'image/jpeg', 0.8);
    });
</script>
@endpush 