<div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header py-3">
                <h5 class="modal-title" id="editTaskModalLabel">Edit Tugas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body custom-scroll">
                <form id="editTaskForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="edit_title" class="form-label">Judul Tugas</label>
                        <input type="text" class="form-control form-control-lg" id="edit_title" name="title" required 
                               placeholder="Masukkan judul tugas">
                    </div>
                    <div class="mb-4">
                        <label for="edit_description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="edit_description" name="description" 
                                  rows="4" placeholder="Masukkan deskripsi tugas"></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="edit_image" class="form-label">Gambar Tugas (Opsional)</label>
                        <input type="file" class="form-control" id="edit_image" name="image" accept="image/*">
                        <div class="mt-2">
                            <small class="text-muted">Format yang didukung: JPG, JPEG, PNG, GIF. Maksimal 2MB.</small>
                        </div>
                        <div id="editImagePreview" class="mt-3">
                            <img src="" alt="Preview" class="img-thumbnail" style="max-height: 200px;">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="edit_end_date" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="edit_end_date" 
                                   name="end_date" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_priority" class="form-label">Prioritas</label>
                            <select class="form-select" id="edit_priority" name="priority" required>
                                <option value="low">Rendah</option>
                                <option value="medium">Sedang</option>
                                <option value="high">Tinggi</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="edit_status" class="form-label">Status</label>
                        <select class="form-select" id="edit_status" name="status" required>
                            <option value="pending">Belum Selesai</option>
                            <option value="completed">Selesai</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" form="editTaskForm" class="btn btn-primary px-4">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script>
function openEditTaskModal(taskId) {
    // Fetch task data
    fetch(`/tasks/${taskId}/edit`)
        .then(response => response.json())
        .then(task => {
            // Set form action
            document.getElementById('editTaskForm').action = `/tasks/${taskId}`;
            
            // Format the date properly (remove time part and format as YYYY-MM-DD)
            const date = new Date(task.end_date);
            const formattedDate = date.toISOString().split('T')[0];
            
            // Fill form fields
            document.getElementById('edit_title').value = task.title;
            document.getElementById('edit_description').value = task.description;
            document.getElementById('edit_end_date').value = formattedDate;
            document.getElementById('edit_priority').value = task.priority;
            document.getElementById('edit_status').value = task.status;
            
            // Handle image preview
            const imagePreview = document.getElementById('editImagePreview');
            const previewImg = imagePreview.querySelector('img');
            
            if (task.image_path) {
                previewImg.src = `/storage/${task.image_path}`;
                imagePreview.style.display = 'block';
            } else {
                imagePreview.style.display = 'none';
            }
            
            // Show modal
            new bootstrap.Modal(document.getElementById('editTaskModal')).show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading task data');
        });
}

// Preview image when selected
document.getElementById('edit_image').addEventListener('change', function(e) {
    const preview = document.getElementById('editImagePreview');
    const previewImg = preview.querySelector('img');
    const file = e.target.files[0];

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
});

// Handle form submission
document.getElementById('editTaskForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Hide modal
            bootstrap.Modal.getInstance(document.getElementById('editTaskModal')).hide();
            
            // Show success message
            alert(data.message);
            
            // Reload page to show updated data
            window.location.reload();
        } else {
            alert(data.message || 'Error updating task');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating task');
    });
});
</script> 