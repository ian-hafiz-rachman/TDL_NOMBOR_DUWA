@extends('layouts.app')

@section('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<style>
    /* Dashboard Container Spacing */
    .dashboard-container {
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
    }

    /* Task List Table Styling */
    #taskListSection .card {
        background: #fff;
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05) !important;
    }

    .table {
        margin-bottom: 0;
    }

    .table th {
        font-weight: 600;
        color: #4e73df;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        background-color: #f8f9fc;
        border-bottom: 2px solid #4e73df !important;
        padding: 1rem 1.5rem;
    }

    .table td {
        vertical-align: middle;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e3e6f0;
    }

    .table tr:last-child td {
        border-bottom: none;
    }

    .table tr {
        background-color: #fff;
    }

    .badge {
        font-weight: 500;
        padding: 0.5rem 1rem;
        border-radius: 50px;
    }

    .form-check-input {
        width: 1.2rem;
        height: 1.2rem;
        margin-top: 0.25rem;
        cursor: pointer;
        border: 2px solid #d1d3e2;
    }

    .form-check-input:checked {
        background-color: #4e73df;
        border-color: #4e73df;
    }

    .form-check-label {
        cursor: pointer;
        padding-left: 0.5rem;
    }

    /* Action Buttons */
    .btn-sm {
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
        border-radius: 50px;
        transition: all 0.2s;
    }

    .btn-outline-primary {
        border-width: 2px;
    }

    .btn-outline-primary:hover {
        background-color: #4e73df;
        color: white;
        transform: translateY(-1px);
    }

    .btn-outline-danger {
        border-width: 2px;
    }

    .btn-outline-danger:hover {
        background-color: #e74a3b;
        color: white;
        transform: translateY(-1px);
    }

    /* Task Detail Modal Styling */
    .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .modal-header {
        border-bottom: none;
        padding: 1.2rem 1.5rem 0.8rem;
        background-color: #f8f9fa;
    }
    
    .modal-title {
        font-weight: 600;
        color: #2c3e50;
        display: flex;
        align-items: center;
        font-size: 1.25rem;
    }
    
    .priority-icon {
        color: #f6c23e;
        margin-right: 12px;
        font-size: 1.5rem;
    }
    
    .modal-body {
        padding: 1.2rem 1.8rem;
    }
    
    .section-divider {
        height: 1px;
        background-color: #f0f0f0;
        margin: 1.5rem 0;
        width: 100%;
    }
    
    .detail-section {
        margin-bottom: 1.5rem;
    }
    
    .detail-label {
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .detail-value {
        color: #2c3e50;
        font-size: 1rem;
        line-height: 1.5;
    }
    
    .task-title-section {
        margin-bottom: 1rem;
        border-left: 4px solid #4e73df;
        padding-left: 12px;
    }
    
    .task-title {
        font-size: 1.4rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0;
        line-height: 1.3;
    }
    
    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 1.2rem;
        margin-bottom: 1.5rem;
    }
    
    .detail-grid .detail-section {
        margin-bottom: 0;
    }
    
    .priority-badge {
        display: inline-block;
        padding: 0.4rem 1rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 500;
    }
    
    .priority-high {
        background-color: rgba(231, 74, 59, 0.15);
        color: #e74a3b;
    }
    
    .priority-medium {
        background-color: rgba(246, 194, 62, 0.15);
        color: #f6c23e;
    }
    
    .priority-low {
        background-color: rgba(28, 200, 138, 0.15);
        color: #1cc88a;
    }
    
    .status-badge {
        display: inline-block;
        padding: 0.4rem 1rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 500;
    }
    
    .status-completed {
        background-color: rgba(28, 200, 138, 0.15);
        color: #1cc88a;
    }
    
    .status-pending {
        background-color: rgba(78, 115, 223, 0.15);
        color: #4e73df;
    }
    
    .priority-section {
        display: flex;
        align-items: center;
    }
    
    .priority-section .detail-label {
        margin-right: 1rem;
        margin-bottom: 0;
    }
    
    .modal-footer {
        border-top: none;
        padding: 1.2rem 1.8rem 1.5rem;
        justify-content: flex-end;
        background-color: #f8f9fa;
    }
    
    .btn-complete-task {
        background-color: #4e73df;
        border-color: #4e73df;
        color: white;
        padding: 0.6rem 1.5rem;
        border-radius: 5px;
        font-weight: 500;
        transition: all 0.2s;
    }
    
    .btn-complete-task:hover {
        background-color: #2e59d9;
        border-color: #2e59d9;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(78, 115, 223, 0.3);
    }
    
    .btn-edit-task {
        background-color: transparent;
        border: 1px solid #4e73df;
        color: #4e73df;
        padding: 0.6rem 1.5rem;
        border-radius: 5px;
        font-weight: 500;
        margin-right: 0.75rem;
        transition: all 0.2s;
    }
    
    .btn-edit-task:hover {
        background-color: rgba(78, 115, 223, 0.1);
    }
    
    .task-image-container {
        margin: 1.5rem 0;
        text-align: center;
        background-color: #f8f9fa;
        padding: 1rem;
        border-radius: 10px;
    }
    
    .task-image {
        max-width: 100%;
        max-height: 250px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        object-fit: cover;
        cursor: pointer;
        transition: transform 0.2s ease;
    }
    
    .task-image:hover {
        transform: scale(1.02);
    }
    
    /* Google Drive style image preview */
    #imagePreviewModal .modal-dialog {
        max-width: 100%;
        margin: 0;
        height: 100vh;
    }
    
    #imagePreviewModal .modal-content {
        height: 100vh;
        border-radius: 0;
        background-color: #202124;
        border: none;
        overflow: hidden;
    }
    
    #imagePreviewModal .modal-header {
        background-color: rgba(32, 33, 36, 0.8);
        border-bottom: none;
        padding: 12px 16px;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        z-index: 10;
        color: white;
    }
    
    #imagePreviewModal .modal-title {
        color: #e8eaed;
        font-size: 16px;
        font-weight: 500;
    }
    
    #imagePreviewModal .modal-body {
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
        overflow: hidden;
    }
    
    #imagePreviewModal .image-container {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: default;
        overflow: hidden;
        position: relative;
    }
    
    #imagePreviewModal #previewImage {
        max-height: 90vh;
        max-width: 90%;
        cursor: grab;
        object-fit: contain;
        box-shadow: 0 0 30px rgba(0, 0, 0, 0.3);
        transition: transform 0.1s ease;
        transform-origin: center center;
    }
    
    #imagePreviewModal #previewImage:active {
        cursor: grabbing;
    }
    
    #imagePreviewModal .btn-close {
        background-color: transparent;
        color: white;
        opacity: 0.7;
        filter: invert(1);
    }
    
    #imagePreviewModal .btn-close:hover {
        opacity: 1;
    }
    
    .drive-preview-controls {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        background-color: rgba(32, 33, 36, 0.8);
        border-radius: 24px;
        padding: 8px 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        display: flex;
        align-items: center;
        z-index: 10;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    #imagePreviewModal:hover .drive-preview-controls {
        opacity: 1;
    }
    
    .drive-preview-controls button {
        background-color: transparent;
        border: none;
        color: white;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        margin: 0 4px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .drive-preview-controls button:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }
    
    .drive-preview-controls button i {
        font-size: 18px;
    }
    
    .drive-preview-controls .zoom-text {
        color: white;
        font-size: 14px;
        padding: 0 12px;
        min-width: 60px;
        text-align: center;
    }
    
    .image-container {
        cursor: move;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 100%;
    }
    
    #previewImage {
        position: relative;
        cursor: grab;
    }
    
    #previewImage:active {
        cursor: grabbing;
    }
    
    .zoom-controls {
        display: flex;
        align-items: center;
    }
    
    @media (max-width: 576px) {
        .zoom-controls {
            margin-left: 0;
            margin-top: 0.5rem;
        }
    }

    /* Task List Styling */
    #taskListSection {
        scroll-margin-top: 20px;
    }
    
    #taskListSection .card {
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08) !important;
    }

    #taskListSection .card-header {
        border-bottom: 1px solid #f0f0f0;
    }

    #taskListSection .table th {
        font-weight: 600;
        color: #4e73df;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    #taskListSection .table td {
        border-bottom: 1px solid #f0f0f0;
    }

    #taskListSection .table tr:last-child td {
        border-bottom: none;
    }

    #taskListSection .table tr:hover {
        background-color: #f8f9ff;
    }

    #taskListSection .badge {
        padding: 0.5rem 1rem;
        font-weight: 500;
    }

    #taskListSection .btn-sm {
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
    }

    #taskListSection .form-check-input {
        width: 1.2rem;
        height: 1.2rem;
        border-color: #e0e0e0;
        cursor: pointer;
    }

    #taskListSection .form-check-input:checked {
        background-color: #4e73df;
        border-color: #4e73df;
    }

    #taskListSection .form-check-label {
        cursor: pointer;
        user-select: none;
    }

    /* Fixed Pagination Styling */
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 4px;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .pagination > li {
        margin: 0;
    }

    .pagination > li > a,
    .pagination > li > span {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        height: 36px;
        padding: 0 12px;
        margin: 0;
        font-size: 14px;
        font-weight: 500;
        line-height: 36px;
        text-decoration: none;
        background-color: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        color: #4a5568;
        transition: all 0.2s ease;
    }

    .pagination > li > a:hover {
        background-color: #f7fafc;
        border-color: #cbd5e0;
        color: #2d3748;
        z-index: 2;
    }

    .pagination > .active > a,
    .pagination > .active > span {
        background-color: #4e73df !important;
        border-color: #4e73df !important;
        color: #fff !important;
        z-index: 3;
    }

    .pagination > .disabled > span,
    .pagination > .disabled > a {
        color: #a0aec0 !important;
        background-color: #f7fafc !important;
        border-color: #e2e8f0 !important;
        cursor: not-allowed;
    }

    .pagination > li:first-child > a,
    .pagination > li:last-child > a {
        padding: 0 12px;
        font-weight: 600;
    }

    .pagination-info {
        margin-top: 12px;
        font-size: 14px;
        color: #718096;
    }

    /* Filter Button Active States */
    .btn-outline-secondary.active-sort {
        background-color: #f8f9fa;
        border-color: #6c757d;
        color: #6c757d;
        font-weight: 500;
    }

    .btn-outline-secondary.active-sort i.sort-arrow {
        margin-left: 4px;
    }

    .priority-dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-right: 6px;
    }

    .priority-dot.high {
        background-color: #e74a3b;
    }

    .priority-dot.medium {
        background-color: #f6c23e;
    }

    .priority-dot.low {
        background-color: #1cc88a;
    }

    .priority-filter-btn.active {
        background-color: #f8f9fa;
    }

    .priority-filter-btn.active[data-priority="high"] {
        color: #e74a3b;
    }

    .priority-filter-btn.active[data-priority="medium"] {
        color: #f6c23e;
    }

    .priority-filter-btn.active[data-priority="low"] {
        color: #1cc88a;
    }

    .priority-filter-btn.active[data-priority="all"] {
        color: #4e73df;
    }
</style>
@endsection

@section('content')
<div id="alert-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>
<div class="dashboard-container" style="margin-top: 0.5rem;">
    <div class="container-fluid">
        <!-- Calendar and Tasks Section -->
        <div class="row" style="height: 100%;">
            <x-dashboard.calender />
            <div class="col-md-4 d-flex flex-column" style="height: 100%;">
                <x-dashboard.button />
                <!-- Task Statistics Card -->
                <x-dashboard.task_statistic_card
                    :totalTasks="$totalTasks"
                    :completedTasks="$completedTasks" 
                    :pendingTasks="$pendingTasks"
                    />
                <!-- Upcoming Deadlines Card -->
                <x-dashboard.upcoming_deadline_card
                :upcomingDeadlines="$upcomingDeadlines"
                />

                <!-- Overdue Tasks Card  -->
                <x-dashboard.overdue_task_card
                :overdueTasks="$overdueTasks"
                />
            </div>
        </div>

        <!-- Task List Section -->
        <div class="row mt-4" id="taskListSection">
            <div class="col-12">
                <div class="card border-0 shadow" style="border-radius: 15px; overflow: hidden;">
                    <div class="card-header bg-white py-3" style="border-bottom: 1px solid rgba(0,0,0,.05);">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-primary fw-bold">
                                <i class="fas fa-tasks me-2"></i>
                                Daftar Semua Tugas
                            </h5>
                            <div class="d-flex align-items-center gap-2">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-clock me-1"></i> Urutkan Waktu <span class="sort-indicator"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><button class="dropdown-item sort-btn" data-sort="time" data-order="asc">Terlama ke Terbaru <i class="fas fa-arrow-up ms-1"></i></button></li>
                                        <li><button class="dropdown-item sort-btn" data-sort="time" data-order="desc">Terbaru ke Terlama <i class="fas fa-arrow-down ms-1"></i></button></li>
                                    </ul>
                                </div>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-flag me-1"></i> Filter Prioritas <span class="priority-indicator"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><button class="dropdown-item priority-filter-btn" data-priority="all">Semua</button></li>
                                        <li><button class="dropdown-item priority-filter-btn" data-priority="high"><span class="priority-dot high"></span>Prioritas Tinggi</button></li>
                                        <li><button class="dropdown-item priority-filter-btn" data-priority="medium"><span class="priority-dot medium"></span>Prioritas Sedang</button></li>
                                        <li><button class="dropdown-item priority-filter-btn" data-priority="low"><span class="priority-dot low"></span>Prioritas Rendah</button></li>
                                    </ul>
                                </div>
                                <button type="button" class="btn btn-primary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                                    <i class="fas fa-plus me-1"></i> Tambah Tugas Baru
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" style="border-collapse: separate; border-spacing: 0;">
                                <thead>
                                    <tr class="bg-light">
                                        <th scope="col" class="py-3" style="border-bottom: 2px solid #4e73df;">Status</th>
                                        <th scope="col" class="px-4 py-3" style="border-bottom: 2px solid #4e73df;">Judul</th>
                                        <th scope="col" class="py-3" style="border-bottom: 2px solid #4e73df;">Tenggat</th>
                                        <th scope="col" class="py-3" style="border-bottom: 2px solid #4e73df;">Prioritas</th>
                                        <th scope="col" class="text-end px-4 py-3" style="border-bottom: 2px solid #4e73df;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tasks as $task)
                                    <tr class="align-middle">
                                        <td class="py-3">
                                            <div class="form-check">
                                                <form class="task-status-form" action="{{ route('tasks.toggle-status', $task->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-sm {{ $task->status === 'completed' ? 'btn-success' : 'btn-outline-success' }}"
                                                            style="width: 100px;">
                                                        @if($task->status === 'completed')
                                                            <i class="fas fa-check-circle"></i> Selesai
                                                        @else
                                                            <i class="far fa-circle"></i> Belum
                                                        @endif
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="fw-medium text-dark">{{ $task->title }}</div>
                                            @if($task->description)
                                                <small class="text-muted d-block mt-1">{{ Str::limit($task->description, 50) }}</small>
                                            @endif
                                        </td>
                                        <td class="py-3">
                                            <div class="{{ $task->end_date < now() && $task->status !== 'completed' ? 'text-danger' : 'text-dark' }}">
                                                {{ \Carbon\Carbon::parse($task->end_date)->format('d M Y') }}
                                            </div>
                                            <small class="text-muted d-block">{{ \Carbon\Carbon::parse($task->end_date)->diffForHumans() }}</small>
                                        </td>
                                        <td class="py-3">
                                            <span class="badge bg-{{ $task->priority === 'high' ? 'danger' : ($task->priority === 'medium' ? 'warning' : 'success') }} rounded-pill">
                                                {{ $task->priority === 'high' ? 'Tinggi' : ($task->priority === 'medium' ? 'Sedang' : 'Rendah') }}
                                            </span>
                                        </td>
                                        <td class="text-end px-4 py-3">
                                            <button type="button" 
                                                onclick="openEditTaskModal({{ $task->id }})" 
                                                class="btn btn-sm btn-outline-primary me-1">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus tugas ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fas fa-tasks fa-3x mb-3"></i>
                                                <p class="mb-0 h5">Tidak ada tugas</p>
                                                <p class="text-muted">Silakan tambah tugas baru</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                @if(isset($tasks) && $tasks->hasPages())
                <div class="d-flex flex-column align-items-center mt-4">
                    {{ $tasks->links() }}
                    <div class="pagination-info">
                        Showing {{ $tasks->firstItem() }} to {{ $tasks->lastItem() }} of {{ $tasks->total() }} tasks
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

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

<!-- Add Task Modal -->
<x-dashboard.add_task_modal />

<!-- Task Detail Modal -->
<div class="modal fade" id="taskDetailModal" tabindex="-1" aria-labelledby="taskDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="taskDetailModalLabel">
                    <i class="fas fa-triangle-exclamation priority-icon" id="priorityIcon"></i>
                    <span>Detail Tugas</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Title Section -->
                <div class="task-title-section">
                    <div id="taskTitle" class="task-title"></div>
                    </div>
                
                <!-- Image Section (if available) -->
                <div class="task-image-container" id="taskImageContainer">
                    <img id="taskImage" class="task-image" src="https://placehold.co/600x400/4e73df/ffffff?text=Task+Image+Example" alt="Task Image">
                    </div>
                
                <!-- Description Section -->
                <div class="detail-section">
                    <div class="detail-label">Deskripsi</div>
                    <div id="taskDescription" class="detail-value"></div>
                    </div>
                
                <div class="section-divider"></div>
                
                <!-- Deadline & Status Section -->
                <div class="detail-grid">
                    <div class="detail-section">
                        <div class="detail-label">Tanggal Deadline</div>
                        <div id="taskDeadline" class="detail-value"></div>
                    </div>
                    
                    <div class="detail-section">
                        <div class="detail-label">Prioritas</div>
                        <div><span id="taskPriority" class="priority-badge"></span></div>
                    </div>
                </div>
                
                <!-- Status Section -->
                <div class="priority-section">
                    <div class="detail-label">Status</div>
                    <div><span id="taskStatus" class="status-badge"></span></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-edit-task" id="editTaskBtn">
                    <i class="fas fa-edit me-1"></i> Edit Tugas
                </button>
                <button type="button" class="btn btn-complete-task" id="completeTaskBtn">
                    <i class="fas fa-check me-1"></i> Tandai Selesai
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imagePreviewModalLabel">Preview Gambar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="image-container">
                    <img id="previewImage" src="" alt="Preview">
                </div>
                <div class="drive-preview-controls">
                    <button type="button" id="zoomOutBtn" title="Perkecil">
                        <i class="fas fa-search-minus"></i>
                    </button>
                    <div class="zoom-text" id="zoomLevel">100%</div>
                    <button type="button" id="zoomInBtn" title="Perbesar">
                        <i class="fas fa-search-plus"></i>
                    </button>
                    <button type="button" id="resetZoomBtn" title="Reset Zoom">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- For Demo: Preview of Modal -->
<div style="display: none;">
    <!-- This is just for reference on how the modal would look like with a real image -->
    <h3>Demo Task Modal Preview:</h3>
    <p>When you click a task, the modal will show the task details with an image like this:</p>
    <div style="border: 1px solid #ddd; padding: 15px; border-radius: 8px;">
        <h4>dgree</h4>
        <div style="text-align: center; margin: 15px 0;">
            <img src="https://placehold.co/600x400/4e73df/ffffff?text=Task+Image+Example" style="max-width: 100%; border-radius: 8px;" alt="Sample Task Image">
        </div>
        <p><strong>Deskripsi:</strong> sgsfg</p>
        <div style="display: flex; justify-content: space-between;">
            <div>
                <p><strong>Tanggal Deadline:</strong> 17 Apr 2025</p>
            </div>
            <div>
                <p><strong>Status:</strong> <span style="background-color: rgba(78, 115, 223, 0.15); color: #4e73df; padding: 5px 10px; border-radius: 50px; font-size: 0.85rem;">Belum Selesai</span></p>
            </div>
        </div>
        <p><strong>Prioritas:</strong> <span style="background-color: rgba(246, 194, 62, 0.15); color: #f6c23e; padding: 5px 10px; border-radius: 50px; font-size: 0.85rem;">Medium</span></p>
    </div>
</div>

<!-- Include Edit Task Modal -->
@include('components.dashboard.edit_task_modal')

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Check if jQuery is loaded
if (typeof jQuery === 'undefined') {
    console.error('jQuery is not loaded!');
} else {
    console.log('jQuery is loaded, version:', jQuery.fn.jquery);
}

document.addEventListener('DOMContentLoaded', function() {
    const calendarView = document.getElementById('calendarView');
    const statisticsView = document.getElementById('statisticsView');
    const calendarViewBtn = document.getElementById('calendarViewBtn');
    const statsViewBtn = document.getElementById('statsViewBtn');
    let calendar = null;

    // Initialize Calendar
    function initializeCalendar() {
        if (!calendar) {
            const calendarEl = document.getElementById('calendar');
            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 'auto',
                headerToolbar: {
                    left: 'prev',
                    center: 'title',
                    right: 'next today'
                },
                buttonText: {
                    today: 'Today'
                },
                events: "{{ route('tasks.get') }}",
                dayMaxEvents: false, // Matikan pembatasan event, tampilkan semua
                eventDisplay: 'block',
                displayEventTime: false,
                firstDay: 1,
                height: 'auto',
                fixedWeekCount: true,
                
                // Konfigurasi tambahan untuk tooltip
                eventDidMount: function(info) {
                    // Menambahkan tooltip sebagai cadangan
                    const eventEl = info.el;
                    const eventTitle = info.event.title;
                    
                    // Menambahkan atribut title untuk tooltip native browser
                    eventEl.setAttribute('title', eventTitle);
                }
            });
        }
        calendar.render();
    }

    // Initialize Charts
    function initializeCharts() {
        // Priority Chart
        new Chart(document.getElementById('priorityChart'), {
            type: 'doughnut',
            data: {
                labels: ['High', 'Medium', 'Low'],
                datasets: [{
                    data: [
                        {{ $highPriorityCount }}, 
                        {{ $mediumPriorityCount }}, 
                        {{ $lowPriorityCount }}
                    ],
                    backgroundColor: ['#e74a3b', '#f6c23e', '#1cc88a']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Completion Chart
        new Chart(document.getElementById('completionChart'), {
            type: 'pie',
            data: {
                labels: ['Completed', 'Pending'],
                datasets: [{
                    data: [{{ $completedTasks }}, {{ $pendingTasks }}],
                    backgroundColor: ['#1cc88a', '#858796']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Weekly Chart
        new Chart(document.getElementById('weeklyChart'), {
            type: 'line',
            data: {
                labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                datasets: [
                    {
                        label: 'Total Tugas',
                        data: {{ json_encode(array_column($weeklyStats, 'total')) }},
                        borderColor: '#0099ff',
                        backgroundColor: 'rgba(0, 153, 255, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Tugas Selesai',
                        data: {{ json_encode(array_column($weeklyStats, 'completed')) }},
                        borderColor: '#1cc88a',
                        backgroundColor: 'rgba(28, 200, 138, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    title: {
                        display: true,
                        text: 'Statistik Tugas Mingguan'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 5
                        }
                    }
                }
            }
        });
    }

    // Restore view state after refresh
    const currentView = localStorage.getItem('currentView') || 'calendar';
    if (currentView === 'statistics') {
        calendarView.style.display = 'none';
        statisticsView.style.display = 'block';
        statsViewBtn.classList.add('active');
        calendarViewBtn.classList.remove('active');
        initializeCharts();
    } else {
        initializeCalendar();
    }

    // Toggle views and save state
    calendarViewBtn.addEventListener('click', function() {
        calendarView.style.display = 'block';
        statisticsView.style.display = 'none';
        calendarViewBtn.classList.add('active');
        statsViewBtn.classList.remove('active');
        localStorage.setItem('currentView', 'calendar');
        
        // Re-render calendar saat beralih ke view calendar
        if (calendar) {
            calendar.render();
        } else {
            initializeCalendar();
        }
    });

    statsViewBtn.addEventListener('click', function() {
        calendarView.style.display = 'none';
        statisticsView.style.display = 'block';
        statsViewBtn.classList.add('active');
        calendarViewBtn.classList.remove('active');
        localStorage.setItem('currentView', 'statistics');
        initializeCharts();
    });

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Task status form handling
    document.querySelectorAll('.task-status-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const button = this.querySelector('button');
            button.disabled = true;
            
            fetch(this.action, {
                method: 'POST',
                body: new FormData(this),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || 'Gagal mengubah status tugas');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengubah status tugas');
            })
            .finally(() => {
                button.disabled = false;
            });
        });
    });

    // Show task details in modal
    document.querySelectorAll('.task-item').forEach(taskItem => {
        taskItem.addEventListener('click', function(e) {
            // Don't open modal if checkbox was clicked
            if (e.target.classList.contains('form-check-input') || 
                e.target.closest('.form-check')) {
                return;
            }

            const taskId = this.dataset.taskId;
            const taskTitle = this.dataset.taskTitle;
            const taskDescription = this.dataset.taskDescription || 'Tidak ada deskripsi';
            const taskDeadline = this.dataset.taskDeadline;
            const taskPriority = this.dataset.taskPriority;
            const taskStatus = this.dataset.taskStatus;
            // For demo purposes, we'll always show a sample image
            const taskImage = this.dataset.taskImage || 'https://placehold.co/600x400/4e73df/ffffff?text=Task+Image+Example';
            
            // Set modal content
            document.getElementById('taskTitle').textContent = taskTitle;
            document.getElementById('taskDescription').textContent = taskDescription;
            document.getElementById('taskDeadline').textContent = taskDeadline;
            
            // Handle image
            const imageContainer = document.getElementById('taskImageContainer');
            const imageElement = document.getElementById('taskImage');
            // For demo, always show the image
            imageElement.src = taskImage;
            imageContainer.style.display = 'block';
            
            // Show the modal
            const detailModal = new bootstrap.Modal(document.getElementById('taskDetailModal'));
            detailModal.show();
            
            // Add click event for image preview
            imageElement.addEventListener('click', function() {
                // Get the task detail modal instance
                const taskDetailModal = bootstrap.Modal.getInstance(document.getElementById('taskDetailModal'));
                
                // Set up image preview modal
                document.getElementById('previewImage').src = this.src;
                const previewModal = new bootstrap.Modal(document.getElementById('imagePreviewModal'));
                
                // Hide task detail modal when preview opens
                taskDetailModal.hide();
                
                // Show image preview modal
                previewModal.show();
                
                // Reset zoom and position
                scale = 1;
                translateX = 0;
                translateY = 0;
                
                // When preview modal is hidden, show task detail modal again
                document.getElementById('imagePreviewModal').addEventListener('hidden.bs.modal', function handler() {
                    // Remove this event listener to prevent multiple registrations
                    this.removeEventListener('hidden.bs.modal', handler);
                    
                    // Wait a short time to ensure modal cleanup is complete
                    setTimeout(function() {
                        taskDetailModal.show();
                    }, 350);
                });
            });
            
            // Set priority icon color
            const priorityIcon = document.getElementById('priorityIcon');
            if (taskPriority === 'high') {
                priorityIcon.style.color = '#e74a3b';
            } else if (taskPriority === 'medium') {
                priorityIcon.style.color = '#f6c23e';
            } else {
                priorityIcon.style.color = '#1cc88a';
            }
            
            // Set priority badge
            const priorityBadge = document.getElementById('taskPriority');
            priorityBadge.textContent = 
                taskPriority === 'high' ? 'Tinggi' : 
                (taskPriority === 'medium' ? 'Sedang' : 'Rendah');
            priorityBadge.className = 'priority-badge ' + 
                (taskPriority === 'high' ? 'priority-high' : 
                (taskPriority === 'medium' ? 'priority-medium' : 'priority-low'));
            
            // Set status badge
            const statusBadge = document.getElementById('taskStatus');
            statusBadge.textContent = taskStatus === 'completed' ? 'Selesai' : 'Belum Selesai';
            statusBadge.className = 'status-badge ' + 
                (taskStatus === 'completed' ? 'status-completed' : 'status-pending');
            
            // Setup edit button link
            document.getElementById('editTaskBtn').onclick = function() {
                openEditTaskModal(taskId);
            };
            
            // Setup complete button (hide if already completed)
            const completeBtn = document.getElementById('completeTaskBtn');
            if (taskStatus === 'completed') {
                completeBtn.style.display = 'none';
            } else {
                completeBtn.style.display = 'block';
                completeBtn.onclick = function() {
                    const checkbox = document.querySelector(`#task-${taskId}`);
                    if (checkbox) {
                        checkbox.checked = true;
                        const changeEvent = new Event('change', { bubbles: true });
                        checkbox.dispatchEvent(changeEvent);
                    }
                    const modal = bootstrap.Modal.getInstance(document.getElementById('taskDetailModal'));
                    modal.hide();
                };
            }
        });
    });

    // Image zoom functionality
    let scale = 1;
    const MAX_SCALE = 5;
    const MIN_SCALE = 0.5;
    const SCALE_STEP = 0.2;
    
    // Variables for dragging
    let isDragging = false;
    let startX, startY;
    let translateX = 0;
    let translateY = 0;
    
    function updateZoom() {
        const previewImage = document.getElementById('previewImage');
        if (previewImage) {
            previewImage.style.transform = `translate(${translateX}px, ${translateY}px) scale(${scale})`;
            
            // Update zoom percentage text
            const zoomLevel = document.getElementById('zoomLevel');
            if (zoomLevel) {
                zoomLevel.textContent = Math.round(scale * 100) + '%';
            }
        }
    }
    
    function resetZoom() {
        scale = 1;
        translateX = 0;
        translateY = 0;
        updateZoom();
    }
    
    // Add event listener for imagePreviewModal being shown
    document.getElementById('imagePreviewModal').addEventListener('shown.bs.modal', function() {
        // Initialize/reset the image preview controls
        const zoomInBtn = document.getElementById('zoomInBtn');
        const zoomOutBtn = document.getElementById('zoomOutBtn');
        const resetZoomBtn = document.getElementById('resetZoomBtn');
        const previewImage = document.getElementById('previewImage');
        
        // Reset values
        resetZoom();
        
        // Set up event listeners
        if (zoomInBtn) {
            zoomInBtn.addEventListener('click', function() {
                if (scale < MAX_SCALE) {
                    scale += SCALE_STEP;
                    updateZoom();
                }
            });
        }
        
        if (zoomOutBtn) {
            zoomOutBtn.addEventListener('click', function() {
                if (scale > MIN_SCALE) {
                    scale -= SCALE_STEP;
                    
                    // Reset position if zoomed out to original size
                    if (scale <= 1) {
                        translateX = 0;
                        translateY = 0;
                    }
                    
                    updateZoom();
                }
            });
        }
        
        if (resetZoomBtn) {
            resetZoomBtn.addEventListener('click', resetZoom);
        }
        
        // Mouse wheel zoom
        if (previewImage) {
            previewImage.addEventListener('wheel', function(e) {
                e.preventDefault();
                
                if (e.deltaY < 0 && scale < MAX_SCALE) {
                    // Zoom in
                    scale += SCALE_STEP;
                } else if (e.deltaY > 0 && scale > MIN_SCALE) {
                    // Zoom out
                    scale -= SCALE_STEP;
                    
                    // Reset position if zoomed out to original size
                    if (scale <= 1) {
                        translateX = 0;
                        translateY = 0;
                    }
                }
                
                updateZoom();
            });
            
            // Drag functionality
            previewImage.addEventListener('mousedown', function(e) {
                if (scale > 1) {
                    isDragging = true;
                    startX = e.clientX - translateX;
                    startY = e.clientY - translateY;
                    this.style.cursor = 'grabbing';
                    e.preventDefault(); // Prevent image drag default behavior
                }
            });
            
            previewImage.addEventListener('touchstart', function(e) {
                if (e.touches.length === 1 && scale > 1) {
                    isDragging = true;
                    startX = e.touches[0].clientX - translateX;
                    startY = e.touches[0].clientY - translateY;
                    e.preventDefault();
                }
            });
        }
        
        // These need to be on document to catch moves outside the image
        document.addEventListener('mousemove', function(e) {
            if (!isDragging) return;
            
            translateX = e.clientX - startX;
            translateY = e.clientY - startY;
            updateZoom();
            e.preventDefault();
        });
        
        document.addEventListener('mouseup', function() {
            if (isDragging) {
                isDragging = false;
                if (previewImage) {
                    previewImage.style.cursor = 'grab';
                }
            }
        });
        
        document.addEventListener('touchmove', function(e) {
            if (isDragging && e.touches.length === 1) {
                translateX = e.touches[0].clientX - startX;
                translateY = e.touches[0].clientY - startY;
                updateZoom();
                e.preventDefault();
            }
        });
        
        document.addEventListener('touchend', function() {
            isDragging = false;
        });
    });
    
    // Fix for modal backdrop not being removed
    document.getElementById('imagePreviewModal').addEventListener('hidden.bs.modal', function() {
        isDragging = false;
        resetZoom();
        
        // Remove event listeners to prevent duplicates
        const previewImage = document.getElementById('previewImage');
        if (previewImage) {
            previewImage.style.transform = '';
        }
        
        // Fix for modal backdrop not being removed properly
        setTimeout(function() {
            // Remove any lingering backdrop elements
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(backdrop => {
                backdrop.remove();
            });
            
            // Ensure body scrolling is restored
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
            
            // Re-enable pointer events
            document.body.style.pointerEvents = '';
        }, 300);
    });

    // Scroll to task list function
    function scrollToTaskList() {
        const taskList = document.getElementById('taskListSection');
        if (taskList) {
            taskList.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    // Add click event to "View All Tasks" button
    document.querySelectorAll('[data-action="view-all-tasks"]').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            scrollToTaskList();
        });
    });

    // Handle pagination links to maintain scroll position
    function attachPaginationHandlers() {
        document.querySelectorAll('.pagination a').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const href = this.getAttribute('href');
                
                // Store the current position of the task list section
                const taskListTop = taskListSection.offsetTop;
                
                // Make AJAX request to get new page content
                fetch(href)
                    .then(response => response.text())
                    .then(html => {
                        // Create a temporary element to parse the HTML
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        
                        // Update only the task list section content
                        const newTaskList = doc.getElementById('taskListSection');
                        if (newTaskList) {
                            taskListSection.innerHTML = newTaskList.innerHTML;
                            
                            // Update URL without refreshing page
                            window.history.pushState({}, '', href);
                            
                            // Scroll back to the task list position
                            window.scrollTo({
                                top: taskListTop,
                                behavior: 'instant'
                            });
                            
                            // Reinitialize all event listeners for the new content
                            initializeTaskListeners();
                            attachPaginationHandlers(); // Reattach pagination handlers
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // Fallback to normal page load if AJAX fails
                        window.location.href = href;
                    });
            });
        });
    }

    // Function to initialize event listeners for task list items
    function initializeTaskListeners() {
        // Re-attach checkbox handlers for ALL task checkboxes in the page
        document.querySelectorAll('.task-checkbox').forEach(checkbox => {
            checkbox.removeEventListener('change', handleCheckboxChange);
            checkbox.addEventListener('change', handleCheckboxChange);
        });

        // Re-attach edit and delete button handlers
        document.querySelectorAll('[onclick^="openEditTaskModal"]').forEach(button => {
            const taskId = button.getAttribute('onclick').match(/\d+/)[0];
            button.onclick = null; // Remove existing onclick handler
            button.addEventListener('click', () => openEditTaskModal(taskId));
        });
    }

    // Separate function to handle checkbox changes
    function handleCheckboxChange(e) {
        e.preventDefault();
        
        const checkbox = e.target;
        const form = checkbox.closest('form.task-status-form');
        
        // Disable checkbox while processing
        checkbox.disabled = true;
        
        // Submit form via AJAX
        fetch(form.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                // Reload the page after a short delay
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                // Revert checkbox state
                checkbox.checked = !checkbox.checked;
                showNotification(data.message || 'Gagal mengubah status tugas', 'danger');
            }
        })
        .catch(error => {
            // Revert checkbox state
            checkbox.checked = !checkbox.checked;
            showNotification('Terjadi kesalahan saat mengubah status tugas', 'danger');
            console.error('Error:', error);
        })
        .finally(() => {
            // Re-enable checkbox
            checkbox.disabled = false;
        });
    }
    
    // Initialize listeners for initial page load
    initializeTaskListeners();
    attachPaginationHandlers();

    // Sort buttons click handler
    document.querySelectorAll('.sort-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const sort = this.dataset.sort;
            const order = this.dataset.order;
            const activePriority = document.querySelector('.priority-filter-btn.active')?.dataset.priority || 'all';
            
            // Add loading state
            const tableBody = document.querySelector('tbody');
            tableBody.style.opacity = '0.5';
            
            // Update active state and dropdown button text
            document.querySelectorAll('.sort-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Update dropdown button text and icon
            const dropdownButton = this.closest('.btn-group').querySelector('.dropdown-toggle');
            dropdownButton.classList.add('active-sort');
            const sortIndicator = dropdownButton.querySelector('.sort-indicator');
            sortIndicator.innerHTML = ` • ${order === 'asc' ? '<i class="fas fa-arrow-up sort-arrow"></i>' : '<i class="fas fa-arrow-down sort-arrow"></i>'}`;
            
            // Fetch filtered & sorted data
            fetch(`/tasks/filter?priority=${activePriority}&sort=${sort}&order=${order}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateTaskTable(data.tasks);
                }
            })
            .finally(() => {
                tableBody.style.opacity = '1';
            });
        });
    });

    // Priority filter buttons click handler
    document.querySelectorAll('.priority-filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const priority = this.dataset.priority;
            const activeSort = document.querySelector('.sort-btn.active');
            const sort = activeSort?.dataset.sort || 'time';
            const order = activeSort?.dataset.order || 'asc';
            
            // Add loading state
            const tableBody = document.querySelector('tbody');
            tableBody.style.opacity = '0.5';
            
            // Update active state and dropdown button text
            document.querySelectorAll('.priority-filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Update dropdown button text
            const dropdownButton = this.closest('.btn-group').querySelector('.dropdown-toggle');
            dropdownButton.classList.add('active-sort');
            const priorityIndicator = dropdownButton.querySelector('.priority-indicator');
            
            if (priority === 'all') {
                priorityIndicator.innerHTML = '';
                dropdownButton.classList.remove('active-sort');
            } else {
                const priorityText = priority === 'high' ? 'Tinggi' : (priority === 'medium' ? 'Sedang' : 'Rendah');
                const dotColor = priority === 'high' ? 'high' : (priority === 'medium' ? 'medium' : 'low');
                priorityIndicator.innerHTML = ` • <span class="priority-dot ${dotColor}"></span>${priorityText}`;
            }
            
            // Fetch filtered & sorted data
            fetch(`/tasks/filter?priority=${priority}&sort=${sort}&order=${order}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateTaskTable(data.tasks);
                }
            })
            .finally(() => {
                tableBody.style.opacity = '1';
            });
        });
    });

    // Function to update task table with new data
    function updateTaskTable(tasks) {
        const tableBody = document.querySelector('tbody');
        
        if (tasks.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <div class="text-muted">
                            <i class="fas fa-tasks fa-3x mb-3"></i>
                            <p class="mb-0 h5">Tidak ada tugas</p>
                            <p class="text-muted">Silakan tambah tugas baru</p>
                        </div>
                    </td>
                </tr>
            `;
            return;
        }

        tableBody.innerHTML = tasks.map(task => `
            <tr class="align-middle">
                <td class="py-3">
                    <div class="form-check">
                        <form class="task-status-form" action="/tasks/${task.id}/toggle-status" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="btn task-status-btn ${task.status === 'completed' ? 'completed' : ''}"
                                    data-bs-toggle="tooltip" 
                                    data-bs-placement="top" 
                                    title="${task.status === 'completed' ? 'Tandai belum selesai' : 'Tandai selesai'}">
                                <i class="fas fa-check"></i>
                                Selesai
                            </button>
                        </form>
                    </div>
                </td>
                <td class="px-4 py-3">
                    <div class="fw-medium text-dark">${task.title}</div>
                    ${task.description ? `<small class="text-muted d-block mt-1">${task.description.substring(0, 50)}...</small>` : ''}
                </td>
                <td class="py-3">
                    <div class="${new Date(task.end_date) < new Date() && task.status !== 'completed' ? 'text-danger' : 'text-dark'}">
                        ${new Date(task.end_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })}
                    </div>
                    <small class="text-muted d-block">${task.end_date_human}</small>
                </td>
                <td class="py-3">
                    <span class="badge bg-${task.priority === 'high' ? 'danger' : (task.priority === 'medium' ? 'warning' : 'success')} rounded-pill">
                        ${task.priority === 'high' ? 'Tinggi' : (task.priority === 'medium' ? 'Sedang' : 'Rendah')}
                    </span>
                </td>
                <td class="text-end px-4 py-3">
                    <button type="button" 
                        onclick="openEditTaskModal(${task.id})" 
                        class="btn btn-sm btn-outline-primary me-1">
                        <i class="fas fa-edit"></i>
                    </button>
                    <form action="/tasks/${task.id}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus tugas ini?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
        `).join('');

        // Reinitialize tooltips and event listeners
        initializeTaskListeners();
    }
});
</script>
@endsection