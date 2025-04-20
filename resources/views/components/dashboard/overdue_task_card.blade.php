<style>
    .overdue-card {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 0 10px 0 rgba(0,0,0,0.1);
    }
    .overdue-card .card-header {
        border-bottom: 1px solid #e9ecef;
        position: relative;
        z-index: 2;
    }
    .overdue-card .card-body {
        position: relative;
        z-index: 1;
    }
    .overdue-card .fa-exclamation-circle {
        font-size: 1.5rem;
    }
    .overdue-card .list-group-item {
        padding: 1rem;
        border-bottom: 1px solid #eee !important;
    }
    .overdue-card .task-title {
        margin-bottom: 0.25rem;
        font-size: 0.95rem;
        color: #333;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 200px;
    }
    .overdue-card .text-danger {
        font-size: 0.8rem;
    }
    .overdue-card .badge {
        font-weight: normal;
        padding: 0.35rem 0.65rem;
        border-radius: 4px;
        font-size: 0.75rem;
    }
    .overdue-card .form-check-input {
        border-color: #ddd;
        width: 1.1rem;
        height: 1.1rem;
    }
    .overdue-card .overdue-tasks-container {
        max-height: 400px;
        overflow-y: auto;
        overflow-x: hidden;
    }
    .overdue-card .task-content {
        flex: 1;
        min-width: 0;
    }
    .overdue-card .badge-container {
        min-width: 70px;
        text-align: right;
    }
    .overdue-card .task-item {
        cursor: pointer;
        transition: background-color 0.2s;
    }
    .overdue-card .task-item:hover {
        background-color: rgba(231, 74, 59, 0.05);
    }
    .overdue-card .task-status-btn {
        transition: all 0.2s;
        cursor: pointer;
        border: 1.5px solid #6c757d !important;
        border-radius: 50px !important;
        padding: 3px 10px !important;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: transparent !important;
        font-size: 12px;
        font-weight: 600;
        color: #6c757d;
        min-width: auto;
    }
    .overdue-card .task-status-btn i {
        font-size: 11px;
    }
    .overdue-card .task-status-btn:hover {
        transform: scale(1.02);
        border-color: #28a745 !important;
        color: #28a745;
    }
    .overdue-card .task-status-btn.completed {
        background: #6c757d !important;
        color: white;
        border-color: #6c757d !important;
    }
    .overdue-card .task-status-btn.completed:hover {
        border-color: #dc3545 !important;
        background: transparent !important;
        color: #dc3545;
    }
</style>

<div class="overdue-card mb-3 px-3">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0">
            <i class="fas fa-exclamation-circle text-danger me-2" style="font-size: 1.5rem;"></i> Tugas Terlewat
        </h5>
    </div>
    <div class="card-body p-0 bg-white">
        <div class="list-group list-group-flush overdue-tasks-container">
            @if($overdueTasks->isEmpty())
            <div class="d-flex flex-column align-items-center justify-content-center py-4">
                <i class="fas fa-trophy text-warning mb-3" style="font-size: 3rem;"></i>
                <h6 class="text-muted">Tidak ada tugas terlewat</h6>
                <p class="text-muted small">Semua tugas Anda sudah dikerjakan tepat waktu!</p>
            </div>
            @else
                @foreach($overdueTasks as $task)
                <div class="list-group-item border-0 task-item"
                    data-task-id="{{ $task->id }}"
                    data-task-title="{{ $task->title }}"
                    data-task-description="{{ $task->description }}"
                    data-task-deadline="{{ \Carbon\Carbon::parse($task->end_date)->format('d M Y') }}"
                    data-task-priority="{{ $task->priority }}"
                    data-task-status="{{ $task->status }}">
                    <div class="d-flex align-items-center">
                        <div class="form-check me-2">
                            <form class="task-status-form" action="{{ route('tasks.toggle-status', $task->id) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="btn task-status-btn {{ $task->status === 'completed' ? 'completed' : '' }}"
                                        data-bs-toggle="tooltip" 
                                        data-bs-placement="top" 
                                        title="{{ $task->status === 'completed' ? 'Tandai belum selesai' : 'Tandai selesai' }}">
                                    <i class="fas fa-check"></i>
                                    Selesai
                                </button>
                            </form>
                        </div>
                        <div class="task-content">
                            <h6 class="mb-1 task-title {{ $task->status === 'completed' ? 'text-decoration-line-through text-muted' : '' }}">
                                {{ $task->title }}
                            </h6>
                            <small class="text-danger">
                                Tenggat: {{ \Carbon\Carbon::parse($task->end_date)->format('d M Y') }}
                            </small>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>
</div>