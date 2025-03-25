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
                                    <input class="form-check-input task-status" 
                                           type="checkbox" 
                                           value="{{ $task->id }}"
                                           {{ $task->status === 'completed' ? 'checked' : '' }}
                                           data-task-id="{{ $task->id }}">
                                    <label class="form-check-label {{ $task->status === 'completed' ? 'text-decoration-line-through text-muted' : '' }}">
                                        {{ $task->status === 'completed' ? 'Completed' : 'Pending' }}
                                    </label>
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
    
    @if($tasks->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $tasks->links() }}
    </div>
    @endif
</div>
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
</style>
@endsection

@section('scripts')
<script>
document.querySelectorAll('.task-status').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const taskId = this.dataset.taskId;
        
        fetch(`/tasks/${taskId}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            this.checked = !this.checked;
            alert('Error updating task status!');
        });
    });
});
</script>
@endsection 