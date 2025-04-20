<div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header py-3">
                <h5 class="modal-title" id="addTaskModalLabel">Tambah Tugas Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body custom-scroll">
                <form id="addTaskForm" method="POST" action="{{ route('tasks.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="title" class="form-label">Judul Tugas <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg @error('title') is-invalid @enderror" 
                               id="title" name="title" required placeholder="Masukkan judul tugas">
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4" 
                                  placeholder="Masukkan deskripsi tugas"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>

                    <div class="mb-4">
                        <label for="image" class="form-label">Gambar Tugas (Opsional)</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/*">
                        <div class="invalid-feedback"></div>
                        <div class="mt-2">
                            <small class="text-muted">Format yang didukung: JPG, JPEG, PNG, GIF. Maksimal 5MB.</small>
                        </div>
                        <div id="imagePreview" class="mt-3" style="display: none;">
                            <img src="" alt="Preview" class="img-thumbnail" style="max-height: 200px;">
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="end_date" class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                   id="end_date" name="end_date" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="priority" class="form-label">Prioritas <span class="text-danger">*</span></label>
                            <select class="form-select @error('priority') is-invalid @enderror" 
                                    id="priority" name="priority" required>
                                <option value="">Pilih Prioritas</option>
                                <option value="low">Rendah</option>
                                <option value="medium">Sedang</option>
                                <option value="high">Tinggi</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" form="addTaskForm" class="btn btn-primary px-4" id="submitBtn">
                    <span class="spinner-border spinner-border-sm d-none me-2" role="status"></span>
                    Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('addTaskModal');
    const form = document.getElementById('addTaskForm');
    const submitBtn = document.getElementById('submitBtn');
    const spinner = submitBtn.querySelector('.spinner-border');
    
    // Set minimum date to today
    const endDateInput = document.getElementById('end_date');
    const today = new Date();
    today.setMinutes(today.getMinutes() - today.getTimezoneOffset());
    endDateInput.min = today.toISOString().split('T')[0];
    endDateInput.value = today.toISOString().split('T')[0];

    // Image preview
    document.getElementById('image').addEventListener('change', function(e) {
        const preview = document.getElementById('imagePreview');
        const previewImg = preview.querySelector('img');
        const file = e.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    });

    // Reset form when modal is hidden
    modal.addEventListener('hidden.bs.modal', function () {
        form.reset();
        document.getElementById('imagePreview').style.display = 'none';
        clearErrors();
        // Reset date to today
        endDateInput.value = today.toISOString().split('T')[0];
    });

    // Handle form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        clearErrors();
        
        // Basic validation
        const title = document.getElementById('title').value.trim();
        const endDate = document.getElementById('end_date').value;
        const priority = document.getElementById('priority').value;
        
        if (!title) {
            showError('title', 'Judul tugas harus diisi');
            return;
        }
        
        if (!endDate) {
            showError('end_date', 'Tanggal selesai harus diisi');
            return;
        }
        
        if (!priority) {
            showError('priority', 'Prioritas harus dipilih');
            return;
        }
        
        // Add default time to end_date (23:59:59)
        const formData = new FormData(this);
        formData.set('end_date', endDate + ' 23:59:59');
        
        // Disable submit button and show spinner
        submitBtn.disabled = true;
        spinner.classList.remove('d-none');
        
        // Submit form
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(({ status, body }) => {
            if (status === 422) {
                // Validation errors
                Object.keys(body.errors).forEach(field => {
                    showError(field, body.errors[field][0]);
                });
                throw new Error('Validation failed');
            }
            
            if (!body.success) {
                throw new Error(body.message || 'Gagal membuat tugas');
            }
            
            // Hide modal
            bootstrap.Modal.getInstance(modal).hide();
            
            // Show success message and reload
            Swal.fire({
                title: 'Berhasil!',
                text: body.message || 'Tugas berhasil dibuat',
                icon: 'success',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location.reload();
            });
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error!',
                text: error.message || 'Terjadi kesalahan saat membuat tugas',
                icon: 'error'
            });
        })
        .finally(() => {
            // Re-enable submit button and hide spinner
            submitBtn.disabled = false;
            spinner.classList.add('d-none');
        });
    });

    // Helper function to show field error
    function showError(field, message) {
        const input = document.getElementById(field);
        input.classList.add('is-invalid');
        const feedback = input.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.textContent = message;
        }
    }

    // Helper function to clear all errors
    function clearErrors() {
        form.querySelectorAll('.is-invalid').forEach(input => {
            input.classList.remove('is-invalid');
        });
        form.querySelectorAll('.invalid-feedback').forEach(feedback => {
            feedback.textContent = '';
        });
    }
});
</script>