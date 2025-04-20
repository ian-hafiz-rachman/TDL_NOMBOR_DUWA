@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Add New Task</h5>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('tasks.store') }}" enctype="multipart/form-data" id="createTaskForm">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Task Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="end_date" class="form-label">Due Date <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror" 
                                   id="end_date" name="end_date" value="{{ old('end_date', date('Y-m-d\TH:i')) }}" required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                            <select class="form-select @error('priority') is-invalid @enderror" 
                                    id="priority" name="priority" required>
                                <option value="">Select Priority</option>
                                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="image" class="form-label">Task Image</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Optional. Upload an image related to this task.</div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <span class="spinner-border spinner-border-sm d-none me-2" role="status" aria-hidden="true"></span>
                                <i class="fas fa-plus me-2"></i>Save Task
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .form-label {
        font-weight: 500;
        color: #4e73df;
    }
    .form-control:focus, .form-select:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
    }
    .btn:disabled {
        cursor: not-allowed;
        opacity: 0.65;
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('createTaskForm');
    const submitBtn = document.getElementById('submitBtn');
    const spinner = submitBtn.querySelector('.spinner-border');
    const icon = submitBtn.querySelector('.fas');

    // Set minimum date to today
    const endDateInput = document.getElementById('end_date');
    const today = new Date();
    today.setMinutes(today.getMinutes() - today.getTimezoneOffset());
    endDateInput.min = today.toISOString().slice(0,16);

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Basic validation
        const title = document.getElementById('title').value.trim();
        const endDate = document.getElementById('end_date').value;
        const priority = document.getElementById('priority').value;
        
        if (!title) {
            Swal.fire({
                title: 'Error!',
                text: 'Please enter a task title',
                icon: 'error'
            });
            return;
        }
        
        if (!endDate) {
            Swal.fire({
                title: 'Error!',
                text: 'Please select a due date',
                icon: 'error'
            });
            return;
        }
        
        if (!priority) {
            Swal.fire({
                title: 'Error!',
                text: 'Please select a priority level',
                icon: 'error'
            });
            return;
        }

        // Debug: Log form data
        const formData = new FormData(this);
        console.log('Form data:');
        for (let [key, value] of formData.entries()) {
            console.log(key + ':', value);
        }
        
        // Disable the button and show spinner
        submitBtn.disabled = true;
        spinner.classList.remove('d-none');
        icon.classList.add('d-none');
        
        // Submit the form
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            // Debug: Log response
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            
            return response.text().then(text => {
                try {
                    // Try to parse as JSON
                    return { 
                        status: response.status,
                        body: text ? JSON.parse(text) : {}
                    };
                } catch (e) {
                    // If not JSON, return the raw text
                    console.error('Failed to parse response as JSON:', text);
                    throw new Error('Server returned invalid JSON');
                }
            });
        })
        .then(({ status, body }) => {
            // Debug: Log parsed response
            console.log('Parsed response:', { status, body });
            
            if (status === 422) {
                // Validation errors
                const errors = Object.values(body.errors).flat();
                throw new Error(errors.join('\n'));
            }
            
            if (!body.success) {
                throw new Error(body.message || 'Failed to create task');
            }
            
            // Show success message
            Swal.fire({
                title: 'Success!',
                text: body.message || 'Task created successfully',
                icon: 'success',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                // Redirect to dashboard
                window.location.href = '{{ route("dashboard") }}';
            });
        })
        .catch(error => {
            console.error('Error:', error);
            
            // Show error message
            Swal.fire({
                title: 'Error!',
                text: error.message || 'An error occurred while creating the task',
                icon: 'error'
            });
        })
        .finally(() => {
            // Re-enable the button and hide spinner
            submitBtn.disabled = false;
            spinner.classList.add('d-none');
            icon.classList.remove('d-none');
        });
    });
});
</script>
@endsection 