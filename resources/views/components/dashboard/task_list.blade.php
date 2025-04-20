@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">All Tasks</h5>
                <div>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm me-2">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                    <a href="{{ route('tasks.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add New Task
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th scope="col" class="px-4">Title</th>
                            <th scope="col">Due Date</th>
                            <th scope="col">Priority</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-end px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tasks as $task)
                        <tr>
                            <td class="px-4">
                                <div class="fw-medium">{{ $task->title }}</div>
                                @if($task->description)
                                    <small class="text-muted">{{ Str::limit($task->description, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                <div class="{{ $task->end_date < now() && $task->status !== 'completed' ? 'text-danger' : '' }}">
                                    {{ $task->end_date->format('d M Y, H:i') }}
                                </div>
                                <small class="text-muted">{{ $task->end_date->diffForHumans() }}</small>
                            </td>
                            <td>
                                <span class="badge bg-{{ $task->priority === 'high' ? 'danger' : ($task->priority === 'medium' ? 'warning' : 'success') }}">
                                    {{ ucfirst($task->priority) }}
                                </span>
                            </td>
                            <td>
                                <div class="form-check">
                                    <form class="task-status-form" data-task-id="{{ $task->id }}" action="{{ route('tasks.toggle-status', $task->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="{{ $task->status === 'completed' ? 'pending' : 'completed' }}">
                                        <input class="form-check-input task-checkbox" 
                                               type="checkbox" 
                                               name="checkbox"
                                               id="task-{{ $task->id }}"
                                               {{ $task->status === 'completed' ? 'checked' : '' }}>
                                        <label class="form-check-label {{ $task->status === 'completed' ? 'text-decoration-line-through text-muted' : '' }}" 
                                               for="task-{{ $task->id }}">
                                            {{ $task->status === 'completed' ? 'Completed' : 'Pending' }}
                                        </label>
                                    </form>
                                </div>
                            </td>
                            <td class="text-end px-4">
                                <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-outline-primary me-2">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-tasks fa-2x mb-3"></i>
                                    <p class="mb-0">No tasks found</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        {{ $tasks->links() }}
    </div>
</div>

<div id="alert-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show notification" role="alert">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show notification" role="alert">
        {{ session('error') }}
    </div>
@endif
@endsection

@section('styles')
<style>
    .table th {
        font-weight: 600;
        color: #4e73df;
    }
    .table td {
        vertical-align: middle;
    }
    .badge {
        font-weight: 500;
    }
    .form-check-input:checked {
        background-color: #4e73df;
        border-color: #4e73df;
    }

    /* Pagination Styling */
    .pagination {
        margin: 0;
        gap: 8px;
        justify-content: center;
    }

    .page-item {
        margin: 0;
    }

    .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 45px;
        height: 45px;
        padding: 0.5rem;
        font-size: 1rem;
        font-weight: 500;
        color: #4e73df;
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
        transition: all 0.2s ease-in-out;
        text-decoration: none;
    }

    .page-link:hover {
        z-index: 2;
        color: #fff;
        background-color: #4e73df;
        border-color: #4e73df;
        transform: translateY(-2px);
        box-shadow: 0 3px 5px rgba(78, 115, 223, 0.1);
    }

    .page-item.active .page-link {
        z-index: 3;
        color: #fff;
        background-color: #4e73df;
        border-color: #4e73df;
        transform: translateY(-2px);
        box-shadow: 0 3px 5px rgba(78, 115, 223, 0.2);
    }

    .page-item.disabled .page-link {
        color: #6c757d;
        pointer-events: none;
        background-color: #f8f9fa;
        border-color: #dee2e6;
        opacity: 0.7;
    }

    .text-muted.small {
        color: #6c757d;
        font-size: 0.875rem;
        text-align: center;
        margin-top: 1rem;
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get CSRF token from meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Task checkbox handling
    document.querySelectorAll('.task-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function(e) {
            // Prevent default behavior
            e.preventDefault();
            
            const form = this.closest('form.task-status-form');
            const formData = new FormData(form);
            
            // Store the original checked state
            const wasChecked = this.checked;
            
            // Disable checkbox while processing
            this.disabled = true;
            
            // Submit form via fetch
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData,
                credentials: 'same-origin'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Show success notification
                    showNotification(data.message, 'success');
                    
                    // Update UI without page reload
                    const label = this.nextElementSibling;
                    if (data.status === 'completed') {
                        label.classList.add('text-decoration-line-through', 'text-muted');
                        label.textContent = 'Completed';
                        this.checked = true;
                        
                        // Update hidden status input
                        form.querySelector('input[name="status"]').value = 'pending';
                    } else {
                        label.classList.remove('text-decoration-line-through', 'text-muted');
                        label.textContent = 'Pending';
                        this.checked = false;
                        
                        // Update hidden status input
                        form.querySelector('input[name="status"]').value = 'completed';
                    }
                    
                    // Update task statistics if they exist
                    updateTaskStatistics();
                } else {
                    // Revert checkbox state on error
                    this.checked = wasChecked;
                    showNotification(data.message || 'Failed to update task status', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Revert checkbox state on error
                this.checked = wasChecked;
                showNotification('Error updating task status', 'danger');
            })
            .finally(() => {
                // Re-enable checkbox
                this.disabled = false;
            });
        });
    });
    
    // Function to update task statistics
    function updateTaskStatistics() {
        fetch('/tasks/statistics', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            // Update statistics if elements exist
            const totalElement = document.querySelector('.total-tasks');
            const completedElement = document.querySelector('.completed-tasks');
            const pendingElement = document.querySelector('.pending-tasks');
            
            if (totalElement) totalElement.textContent = data.totalTasks;
            if (completedElement) completedElement.textContent = data.completedTasks;
            if (pendingElement) pendingElement.textContent = data.pendingTasks;
            
            // Update progress percentage if it exists
            const progressElement = document.querySelector('.progress-percentage');
            if (progressElement && data.totalTasks > 0) {
                const percentage = Math.round((data.completedTasks / data.totalTasks) * 100);
                progressElement.textContent = percentage + '%';
            }
        })
        .catch(error => {
            console.error('Error updating statistics:', error);
        });
    }
    
    // Notification function
    function showNotification(message, type = 'success') {
        const alertContainer = document.getElementById('alert-container');
        
        // Remove existing notifications
        while (alertContainer.firstChild) {
            alertContainer.removeChild(alertContainer.firstChild);
        }
        
        // Create new notification
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show`;
        notification.role = 'alert';
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        // Add to container
        alertContainer.appendChild(notification);
        
        // Auto hide after 3 seconds
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
});
</script>
@endsection 