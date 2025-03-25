@extends('layouts.app')

@section('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<style>
    :root {
        --spacing-base: 25px;
        --primary-color: #0099ff;
        --hover-color: #0077cc;
        --success-color: #1cc88a;
        --warning-color: #f6c23e;
        --danger-color: #e74a3b;
    }

    /* Dashboard Container */
    .dashboard-container {
        padding: 30px;
        background-color: #f8f9fa;
        min-height: 100vh;
        position: relative;
        overflow: hidden;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='400' height='400' viewBox='0 0 400 400'%3E%3Cstyle%3E.todo-list %7B fill: none; stroke: %230099ff; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; stroke-opacity: 0.1;%7D%3C/style%3E%3Cg class='todo-list'%3E%3Cpath d='M50 50h100v120H50z' transform='rotate(-2 100 100)' /%3E%3Cpath d='M55 70h90 M55 90h90 M55 110h90 M55 130h90 M55 150h90' transform='rotate(-2 100 100)' /%3E%3Cpath d='M60 75l-5-5 M60 95l-5-5 M60 115l-5-5 M60 135l-5-5' transform='rotate(-2 100 100)' /%3E%3Cpath d='M200 80h80v100h-80z' transform='rotate(3 240 130)' /%3E%3Cpath d='M205 100h70 M205 120h70 M205 140h70 M205 160h70' transform='rotate(3 240 130)' /%3E%3C/g%3E%3C/svg%3E");
    }

    /* Stats Cards Styling */
    .stats-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 15px;
        overflow: hidden;
        margin-bottom: 30px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(0, 0, 0, 0.08);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }
    
    .stats-card .card-body {
        padding: 1.8rem;
    }
    
    .stats-card h6 {
        font-size: 0.9rem;
        font-weight: 600;
    }
    
    .stats-card h4 {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--primary-color);
    }

    /* Action buttons spacing */
    .action-buttons {
        display: flex;
        gap: 10px;
        margin-bottom: var(--spacing-base); /* Konsisten 25px */
    }
    
    .action-buttons .btn {
        flex: 1;
        padding: 8px 16px;
        font-weight: 500;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .action-buttons .btn-primary {
        background-color: var(--primary-color);
        border: none;
        box-shadow: 0 2px 5px rgba(0, 153, 255, 0.2);
    }

    .action-buttons .btn-outline-primary {
        color: var(--primary-color);
        border-color: var(--primary-color);
        background-color: white;
    }

    .action-buttons .btn-outline-primary:hover {
        background-color: var(--primary-color);
        color: white;
    }

    /* Calendar Container */
    .task-calendar {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(0, 0, 0, 0.08);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    /* Upcoming Deadlines Card */
    .upcoming-deadlines {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(0, 0, 0, 0.08);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        height: calc(100% - 260px);
        margin-top: var(--spacing-base); /* Konsisten 25px */
    }
    
    .upcoming-deadlines .card-header {
        padding: var(--spacing-base); /* Konsisten 25px */
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    
    .upcoming-deadlines .list-group-item {
        padding: 15px;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        transition: background-color 0.3s ease;
    }
    
    .upcoming-deadlines .list-group-item:hover {
        background-color: rgba(248, 249, 252, 0.8);
    }
    
    .upcoming-deadlines .task-actions {
        display: flex;
        gap: 10px;
        align-items: center;
    }
    
    .upcoming-deadlines .badge {
        padding: 6px 12px;
        font-weight: 600;
        border-radius: 6px;
    }

    /* Alert Styling */
    .alert {
        position: fixed;
        top: 80px;
        right: 20px;
        z-index: 1000;
        min-width: 300px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        animation: slideIn 0.5s ease-out;
        padding: 12px 20px; /* Adjust padding since we removed the close button */
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .alert-success {
        background-color: var(--success-color);
        border-color: var(--success-color);
        color: white;
    }

    .alert-danger {
        background-color: var(--danger-color);
        border-color: var(--danger-color);
        color: white;
    }

    .alert .btn-close {
        color: white;
        opacity: 0.8;
    }

    /* Reset container styles */
    .dashboard-container {
        padding: 20px;
        background: #f8f9fc;
    }
    
    /* Task calendar styles */
    .task-calendar {
        background: white;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(0,0,0,0.05);
        margin-bottom: 20px;
    }

    #calendar {
        width: 100%;
        min-height: 600px;
        font-family: 'Nunito', sans-serif;
    }

    /* Stats card styles */
    .stats-card {
        transition: all 0.3s ease;
        border-radius: 15px;
        overflow: hidden;
        margin-bottom: 20px;
    }

    /* Upcoming deadlines card */
    .card-body.p-0 {
        max-height: 600px;
        overflow-y: auto;
    }

    /* Keep your existing styles below */
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .stats-card .card-body {
        padding: 1.5rem;
    }
    /* Calendar Styling */
    .fc-header-toolbar {
        padding: 15px 0 !important;
        margin-bottom: 1.5rem !important;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .fc-toolbar-title {
        font-size: 1.5em !important;
        font-weight: 600 !important;
        color: #2c3e50;
    }
    .fc-button {
        padding: 8px 15px !important;
        font-weight: 500 !important;
        border-radius: 8px !important;
        text-transform: capitalize !important;
    }
    .fc-button-primary {
        background-color: var(--primary-color) !important;
        border-color: var(--primary-color) !important;
    }
    .fc-button-primary:hover {
        background-color: var(--hover-color) !important;
        border-color: var(--hover-color) !important;
    }
    .fc-daygrid-day {
        transition: all 0.2s ease;
    }
    .fc-daygrid-day:hover {
        background-color: #f8f9fc !important;
    }
    .fc-daygrid-day-number {
        font-size: 0.9em !important;
        padding: 4px 8px !important;
        color: #5a5c69;
    }
    .fc-day-today .fc-daygrid-day-number {
        background: var(--primary-color);
        color: white;
        border-radius: 50%;
    }
    .fc-event {
        border-radius: 6px !important;
        padding: 3px 8px !important;
        font-size: 0.85em !important;
        border: none !important;
        margin: 2px 0 !important;
    }
    /* Priority Colors */
    .priority-high {
        background-color: var(--danger-color) !important;
        border-color: var(--danger-color) !important;
    }
    .priority-medium {
        background-color: var(--warning-color) !important;
        border-color: var(--warning-color) !important;
        color: #000 !important;
    }
    .priority-low {
        background-color: var(--success-color) !important;
        border-color: var(--success-color) !important;
    }
    /* Today's Tasks Section */
    .today-tasks {
        background: white;
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(0,0,0,0.05);
        height: 600px;
        overflow-y: auto;
    }
    .today-tasks .card-header {
        background: transparent;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        padding: 1.5rem;
    }
    .today-tasks .list-group-item {
        border: none;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        padding: 1rem 1.5rem;
    }
    .today-tasks .list-group-item:last-child {
        border-bottom: none;
    }
    /* Navigation Button Group Styling */
    .fc-button-group {
        gap: 5px !important;
        background: #f8f9fc;
        padding: 5px;
        border-radius: 12px;
    }
    .fc-button-group .fc-button {
        border-radius: 8px !important;
        padding: 8px 16px !important;
        font-weight: 500 !important;
        border: none !important;
        background: transparent !important;
        color: #6c757d !important;
    }
    .fc-button-group .fc-button.fc-button-active {
        background-color: var(--primary-color) !important;
        color: white !important;
    }
    .fc-button-group .fc-button:hover:not(.fc-button-active) {
        background-color: #eaecf4 !important;
    }
    /* Header Toolbar Spacing */
    .fc-header-toolbar {
        padding: 15px 0 !important;
        margin-bottom: 1.5rem !important;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    /* Calendar Grid */
    .fc-daygrid-day {
        height: 120px !important;
    }
    .fc-daygrid-day-frame {
        min-height: 100% !important;
        padding: 4px !important;
    }
    .fc-daygrid-day-top {
        flex-direction: row !important;
        margin-bottom: 4px;
    }
    .fc-daygrid-day-number {
        float: left !important;
        margin: 5px !important;
        font-size: 14px !important;
        font-weight: 500;
        color: #5a5c69;
    }
    .fc-day-today .fc-daygrid-day-number {
        background: var(--primary-color);
        color: white;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Calendar Header */
    .fc-col-header-cell {
        padding: 10px 0 !important;
        background: #f8f9fc !important;
        border-color: #e3e6f0 !important;
    }
    .fc-col-header-cell-cushion {
        padding: 8px !important;
        color: #4e73df !important;
        font-weight: 600 !important;
        text-decoration: none !important;
    }
    
    /* Event Styling */
    .fc-daygrid-event {
        margin: 2px 4px !important;
        padding: 2px 4px !important;
        border-radius: 4px !important;
    }
    
    /* Navigation Buttons */
    .fc-button-group {
        gap: 5px !important;
    }
    .fc-button {
        padding: 8px 16px !important;
        font-weight: 500 !important;
        border: none !important;
    }
    .fc-button-primary {
        background-color: var(--primary-color) !important;
        color: white !important;
    }
    .fc-button-primary:not(.fc-button-active):hover {
        background-color: var(--hover-color) !important;
    }
    .fc-button-active {
        background-color: var(--hover-color) !important;
    }
    
    /* Event styling */
    .fc-event {
        border: none !important;
        padding: 3px 6px !important;
        font-size: 0.85em !important;
    }
    
    .priority-high {
        background-color: rgba(231, 74, 59, 0.9) !important;
        color: white !important;
    }
    
    .priority-medium {
        background-color: rgba(246, 194, 62, 0.9) !important;
        color: black !important;
    }
    
    .priority-low {
        background-color: rgba(28, 200, 138, 0.9) !important;
        color: white !important;
    }
    
    .fc-event i {
        opacity: 0.8;
    }

    /* New styles for task statistics */
    .task-stats {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(0, 0, 0, 0.08);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        margin-bottom: var(--spacing-base); /* Konsisten 25px */
    }

    .task-stats .progress {
        border-radius: 10px;
        background-color: #f0f2f5;
        margin-bottom: var(--spacing-base); /* Konsisten 25px */
    }

    .task-counts {
        text-align: center;
    }

    .stat-item h4 {
        font-size: 1.5rem;
        font-weight: 600;
    }

    .stat-item span {
        font-size: 0.85rem;
    }

    /* Update upcoming deadlines card styling */
    .upcoming-deadlines {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        height: calc(100% - 260px);
        margin-top: var(--spacing-base); /* Konsisten 25px */
    }
    
    .upcoming-deadlines .card-body {
        height: calc(100% - 60px);
        overflow-y: auto;
    }

    .upcoming-deadlines .list-group {
        height: 100%;
    }

    /* Update list item styling */
    .upcoming-deadlines .list-group-item {
        padding: 15px;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        transition: background-color 0.3s ease;
    }
    
    .upcoming-deadlines .list-group-item:hover {
        background-color: #f8f9fc;
    }
    
    /* Checkbox styling */
    .form-check-input {
        width: 1.2rem;
        height: 1.2rem;
        margin-top: 0.2rem;
        cursor: pointer;
        border-color: #e0e3e7;
    }
    
    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    /* Task title styling */
    .upcoming-deadlines h6 {
        font-size: 0.95rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.3rem;
    }
    
    /* Task completed styling */
    .task-completed {
        text-decoration: line-through;
        color: #858796;
    }
    
    /* Badge styling */
    .badge {
        padding: 0.4rem 0.8rem;
        font-weight: 500;
        font-size: 0.75rem;
    }
    
    /* Edit button styling */
    .btn-outline-primary {
        padding: 0.25rem 0.5rem;
        border-color: #e0e3e7;
        color: #4e73df;
    }
    
    .btn-outline-primary:hover {
        background-color: #4e73df;
        border-color: #4e73df;
        color: white;
    }
    
    /* Date text styling */
    .text-muted {
        font-size: 0.8rem;
        color: #6c757d;
    }

    /* Update progress bar styling */
    .progress {
        background-color: #eaecf4;
        border-radius: 10px;
        height: 10px !important;
    }

    .progress-bar {
        border-radius: 10px;
        transition: width 0.5s ease;
    }

    /* Update stats card styling */
    .task-stats {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    .task-stats .card-body {
        padding: 1.5rem;
    }

    .stat-item {
        flex: 1;
        padding: 0 10px;
    }

    .stat-item h4 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.3rem;
    }

    .stat-item span {
        font-size: 0.8rem;
        color: #858796;
    }

    /* Add hover effect to stats */
    .stat-item:hover h4 {
        transform: scale(1.1);
        transition: transform 0.2s ease;
    }

    /* Modal styling */
    .modal-content {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .modal-header {
        border-bottom: 1px solid rgba(0,0,0,0.05);
        padding: 1.5rem;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        border-top: 1px solid rgba(0,0,0,0.05);
        padding: 1.5rem;
    }

    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #e0e3e7;
        padding: 0.6rem 1rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78,115,223,0.25);
    }

    .form-label {
        font-weight: 500;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }

    /* Update Modal styling */
    .modal-lg {
        max-width: 800px;
    }

    .modal-content {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        max-height: 90vh; /* Maksimum tinggi modal 90% dari tinggi viewport */
    }

    .modal-header {
        background-color: #f8f9fc;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        padding: 1rem 1.5rem; /* Mengurangi padding */
    }

    .modal-header .modal-title {
        font-size: 1.1rem;
        font-weight: 600;
    }

    .modal-body {
        padding: 1.5rem;
        overflow-y: auto; /* Tambah scroll untuk modal body */
        max-height: calc(90vh - 110px); /* Tinggi maksimum body modal */
    }

    .modal-footer {
        background-color: #f8f9fc;
        border-top: 1px solid rgba(0,0,0,0.05);
        padding: 0.75rem 1.5rem; /* Mengurangi padding */
    }

    /* Form styling */
    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #e0e3e7;
        padding: 0.7rem 1rem; /* Sedikit mengurangi padding */
        font-size: 1rem;
    }

    .form-control-lg {
        font-size: 1.1rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78,115,223,0.25);
    }

    .form-label {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    /* Button styling */
    .btn-primary {
        padding: 0.5rem 1.25rem;
        font-weight: 500;
    }

    .btn-secondary {
        background-color: #eaecf4;
        border: none;
        color: #6e707e;
        padding: 0.5rem 1.25rem;
    }

    .btn-secondary:hover {
        background-color: #dde0e9;
    }

    /* Custom scrollbar styling */
    .custom-scroll {
        scrollbar-width: thin;
        scrollbar-color: #cbd5e0 #f8f9fc;
    }

    .custom-scroll::-webkit-scrollbar {
        width: 6px;
    }

    .custom-scroll::-webkit-scrollbar-track {
        background: #f8f9fc;
    }

    .custom-scroll::-webkit-scrollbar-thumb {
        background-color: #cbd5e0;
        border-radius: 3px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .dashboard-container {
            padding: 15px;
        }
    }

    /* Tambahkan style untuk modal */
    .modal-content {
        border-radius: 15px;
        border: none;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
        padding: 1.5rem;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        border-top: 1px solid rgba(0, 0, 0, 0.08);
        padding: 1.5rem;
    }

    .task-detail-content label {
        color: #666;
        font-size: 0.9rem;
    }

    .task-detail-content p {
        color: #2c3e50;
        font-size: 1rem;
    }

    .badge {
        padding: 0.5rem 1rem;
        font-weight: 500;
        border-radius: 6px;
    }
</style>
@endsection

@section('content')
<div class="dashboard-container">
    <div class="container-fluid">
        <!-- Calendar and Tasks Section -->
        <div class="row">
            <div class="col-md-8">
                <div class="task-calendar">
                    <div id="calendar"></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="action-buttons mb-3">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                        <i class="fas fa-plus"></i> Add New Task
                    </button>
                    <a href="{{ route('tasks.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-list"></i> View All Tasks
                    </a>
                </div>
                
                <!-- Task Statistics Card -->
                <div class="card task-stats border-0 mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="text-muted mb-0">Persentase Tugas</h6>
                            <h6 class="text-primary mb-0">
                                {{ number_format(($totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0), 1) }}%
                            </h6>
                        </div>
                        <div class="progress mb-3" style="height: 10px;">
                            <div class="progress-bar bg-primary" role="progressbar" 
                                 style="width: {{ $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0 }}%"
                                 aria-valuenow="{{ $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0 }}"
                                 aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between task-counts">
                            <div class="stat-item text-center">
                                <h4 class="mb-1">{{ $totalTasks }}</h4>
                                <span class="text-muted">Total Tasks</span>
                            </div>
                            <div class="stat-item text-center">
                                <h4 class="mb-1 text-success">{{ $completedTasks }}</h4>
                                <span class="text-muted">Completed</span>
                            </div>
                            <div class="stat-item text-center">
                                <h4 class="mb-1 text-warning">{{ $pendingTasks }}</h4>
                                <span class="text-muted">Pending</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card upcoming-deadlines border-0">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 d-flex align-items-center">
                            <i class="fas fa-hourglass-half text-warning me-2"></i>
                            Upcoming Deadlines
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        @if($upcomingDeadlines->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($upcomingDeadlines as $task)
                                    <div class="list-group-item">
                                        <div class="d-flex align-items-start gap-3">
                                            <!-- Checkbox di sebelah kiri -->
                                            <div class="form-check mt-1">
                                                <input class="form-check-input task-status" 
                                                       type="checkbox" 
                                                       value="{{ $task->id }}"
                                                       {{ $task->status === 'completed' ? 'checked' : '' }}
                                                       data-task-id="{{ $task->id }}">
                                            </div>
                                            
                                            <!-- Task details -->
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h6 class="mb-1 {{ $task->status === 'completed' ? 'task-completed' : '' }}">{{ $task->title }}</h6>
                                                        <small class="text-muted">
                                                            <i class="far fa-clock me-1"></i>
                                                            {{ $task->end_date->format('d M Y, H:i') }}
                                                            ({{ $task->end_date->diffForHumans() }})
                                                        </small>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge bg-{{ $task->priority === 'high' ? 'danger' : ($task->priority === 'medium' ? 'warning' : 'success') }}">
                                                            {{ ucfirst($task->priority) }}
                                                        </span>
                                                        <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                                <p class="text-muted mb-0">No upcoming deadlines!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    @if(str_contains(strtolower(session('success')), 'hapus'))
        <div class="alert alert-danger fade show" role="alert">
            {{ session('success') }}
        </div>
    @else
        <div class="alert alert-success fade show" role="alert">
            {{ session('success') }}
        </div>
    @endif
@endif

@if(session('error'))
    <div class="alert alert-danger fade show" role="alert">
        {{ session('error') }}
    </div>
@endif

<!-- Add Task Modal -->
<div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header py-3"> <!-- Mengurangi padding atas bawah -->
                <h5 class="modal-title" id="addTaskModalLabel">Add New Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body custom-scroll"> <!-- Tambah class untuk custom scroll -->
                <form id="addTaskForm" action="{{ route('tasks.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="title" class="form-label">Task Title</label>
                        <input type="text" class="form-control form-control-lg" id="title" name="title" required 
                               placeholder="Enter task title">
                    </div>
                    <div class="mb-4">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" 
                                  rows="4" placeholder="Enter task description"></textarea>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="start_date" class="form-label">Due Date</label>
                            <input type="datetime-local" class="form-control" id="start_date" 
                                   name="start_date" required>
                        </div>
                        <div class="col-md-6">
                            <label for="priority" class="form-label">Priority</label>
                            <select class="form-select" id="priority" name="priority" required>
                                <option value="low">Low Priority</option>
                                <option value="medium">Medium Priority</option>
                                <option value="high">High Priority</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer py-2"> <!-- Mengurangi padding atas bawah -->
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="addTaskForm" class="btn btn-primary px-4">Add Task</button>
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan modal di bagian bawah dashboard container -->
<div class="modal fade" id="taskDetailModal" tabindex="-1" aria-labelledby="taskDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="taskDetailModalLabel">Detail Tugas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="task-detail-content">
                    <div class="mb-3">
                        <label class="fw-bold">Judul Tugas:</label>
                        <p id="taskTitle" class="mb-2"></p>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Deskripsi:</label>
                        <p id="taskDescription" class="mb-2"></p>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Batas Waktu:</label>
                        <p id="taskDeadline" class="mb-2"></p>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Prioritas:</label>
                        <span id="taskPriority" class="badge"></span>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Status:</label>
                        <span id="taskStatus" class="badge bg-secondary"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="editTaskBtn">Edit Tugas</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 'auto',
        aspectRatio: 1.35,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth'
        },
        dayMaxEvents: true,
        views: {
            dayGridMonth: {
                dayMaxEvents: 2,
                titleFormat: { month: 'long', year: 'numeric' }
            }
        },
        buttonText: {
            today: 'Today',
            month: 'Month'
        },
        firstDay: 1,
        editable: true,
        selectable: true,
        selectMirror: true,
        events: {
            url: '{{ route('tasks.get') }}',
            method: 'GET',
            failure: function() {
                console.error('Failed to load tasks');
            }
        },
        eventContent: function(arg) {
            let titleEl = document.createElement('div');
            titleEl.className = 'd-flex align-items-center';
            titleEl.innerHTML = `
                <i class="fas fa-clock" style="margin-right: 4px;"></i>
                <span>${arg.event.title}</span>
            `;
            return { domNodes: [titleEl] }
        },

        eventDrop: function(info) {
            fetch(`/tasks/${info.event.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    end_date: info.event.start.toISOString().split('T')[0]
                })
            })
            .catch(error => {
                console.error('Error:', error);
                info.revert();
                alert('Error updating task!');
            });
        },

        eventClick: function(info) {
            // Mengambil data event yang diklik
            const event = info.event;
            
            // Mengisi data ke dalam modal
            document.getElementById('taskTitle').textContent = event.title;
            
            // Perbaikan untuk menampilkan deskripsi
@extends('layouts.app')

@section('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<style>
    :root {
        --spacing-base: 25px;
        --primary-color: #0099ff;
        --hover-color: #0077cc;
        --success-color: #1cc88a;
        --warning-color: #f6c23e;
        --danger-color: #e74a3b;
    }

    /* Dashboard Container */
    .dashboard-container {
        padding: 30px;
        background-color: #f8f9fa;
        min-height: 100vh;
        position: relative;
        overflow: hidden;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='400' height='400' viewBox='0 0 400 400'%3E%3Cstyle%3E.todo-list %7B fill: none; stroke: %230099ff; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; stroke-opacity: 0.1;%7D%3C/style%3E%3Cg class='todo-list'%3E%3Cpath d='M50 50h100v120H50z' transform='rotate(-2 100 100)' /%3E%3Cpath d='M55 70h90 M55 90h90 M55 110h90 M55 130h90 M55 150h90' transform='rotate(-2 100 100)' /%3E%3Cpath d='M60 75l-5-5 M60 95l-5-5 M60 115l-5-5 M60 135l-5-5' transform='rotate(-2 100 100)' /%3E%3Cpath d='M200 80h80v100h-80z' transform='rotate(3 240 130)' /%3E%3Cpath d='M205 100h70 M205 120h70 M205 140h70 M205 160h70' transform='rotate(3 240 130)' /%3E%3C/g%3E%3C/svg%3E");
    }

    /* Stats Cards Styling */
    .stats-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 15px;
        overflow: hidden;
        margin-bottom: 30px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(0, 0, 0, 0.08);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }
    
    .stats-card .card-body {
        padding: 1.8rem;
    }
    
    .stats-card h6 {
        font-size: 0.9rem;
        font-weight: 600;
    }
    
    .stats-card h4 {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--primary-color);
    }

    /* Action buttons spacing */
    .action-buttons {
        display: flex;
        gap: 10px;
        margin-bottom: var(--spacing-base); /* Konsisten 25px */
    }
    
    .action-buttons .btn {
        flex: 1;
        padding: 8px 16px;
        font-weight: 500;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .action-buttons .btn-primary {
        background-color: var(--primary-color);
        border: none;
        box-shadow: 0 2px 5px rgba(0, 153, 255, 0.2);
    }

    .action-buttons .btn-outline-primary {
        color: var(--primary-color);
        border-color: var(--primary-color);
        background-color: white;
    }

    .action-buttons .btn-outline-primary:hover {
        background-color: var(--primary-color);
        color: white;
    }

    /* Calendar Container */
    .task-calendar {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(0, 0, 0, 0.08);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    /* Upcoming Deadlines Card */
    .upcoming-deadlines {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(0, 0, 0, 0.08);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        height: calc(100% - 260px);
        margin-top: var(--spacing-base); /* Konsisten 25px */
    }
    
    .upcoming-deadlines .card-header {
        padding: var(--spacing-base); /* Konsisten 25px */
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
    
    .upcoming-deadlines .list-group-item {
        padding: 15px;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        transition: background-color 0.3s ease;
    }
    
    .upcoming-deadlines .list-group-item:hover {
        background-color: rgba(248, 249, 252, 0.8);
    }
    
    .upcoming-deadlines .task-actions {
        display: flex;
        gap: 10px;
        align-items: center;
    }
    
    .upcoming-deadlines .badge {
        padding: 6px 12px;
        font-weight: 600;
        border-radius: 6px;
    }

    /* Alert Styling */
    .alert {
        position: fixed;
        top: 80px;
        right: 20px;
        z-index: 1000;
        min-width: 300px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        animation: slideIn 0.5s ease-out;
        padding: 12px 20px; /* Adjust padding since we removed the close button */
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .alert-success {
        background-color: var(--success-color);
        border-color: var(--success-color);
        color: white;
    }

    .alert-danger {
        background-color: var(--danger-color);
        border-color: var(--danger-color);
        color: white;
    }

    .alert .btn-close {
        color: white;
        opacity: 0.8;
    }

    /* Reset container styles */
    .dashboard-container {
        padding: 20px;
        background: #f8f9fc;
    }
    
    /* Task calendar styles */
    .task-calendar {
        background: white;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(0,0,0,0.05);
        margin-bottom: 20px;
    }

    #calendar {
        width: 100%;
        min-height: 600px;
        font-family: 'Nunito', sans-serif;
    }

    /* Stats card styles */
    .stats-card {
        transition: all 0.3s ease;
        border-radius: 15px;
        overflow: hidden;
        margin-bottom: 20px;
    }

    /* Upcoming deadlines card */
    .card-body.p-0 {
        max-height: 600px;
        overflow-y: auto;
    }

    /* Keep your existing styles below */
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .stats-card .card-body {
        padding: 1.5rem;
    }
    /* Calendar Styling */
    .fc-header-toolbar {
        padding: 15px 0 !important;
        margin-bottom: 1.5rem !important;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .fc-toolbar-title {
        font-size: 1.5em !important;
        font-weight: 600 !important;
        color: #2c3e50;
    }
    .fc-button {
        padding: 8px 15px !important;
        font-weight: 500 !important;
        border-radius: 8px !important;
        text-transform: capitalize !important;
    }
    .fc-button-primary {
        background-color: var(--primary-color) !important;
        border-color: var(--primary-color) !important;
    }
    .fc-button-primary:hover {
        background-color: var(--hover-color) !important;
        border-color: var(--hover-color) !important;
    }
    .fc-daygrid-day {
        transition: all 0.2s ease;
    }
    .fc-daygrid-day:hover {
        background-color: #f8f9fc !important;
    }
    .fc-daygrid-day-number {
        font-size: 0.9em !important;
        padding: 4px 8px !important;
        color: #5a5c69;
    }
    .fc-day-today .fc-daygrid-day-number {
        background: var(--primary-color);
        color: white;
        border-radius: 50%;
    }
    .fc-event {
        border-radius: 6px !important;
        padding: 3px 8px !important;
        font-size: 0.85em !important;
        border: none !important;
        margin: 2px 0 !important;
    }
    /* Priority Colors */
    .priority-high {
        background-color: var(--danger-color) !important;
        border-color: var(--danger-color) !important;
    }
    .priority-medium {
        background-color: var(--warning-color) !important;
        border-color: var(--warning-color) !important;
        color: #000 !important;
    }
    .priority-low {
        background-color: var(--success-color) !important;
        border-color: var(--success-color) !important;
    }
    /* Today's Tasks Section */
    .today-tasks {
        background: white;
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(0,0,0,0.05);
        height: 600px;
        overflow-y: auto;
    }
    .today-tasks .card-header {
        background: transparent;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        padding: 1.5rem;
    }
    .today-tasks .list-group-item {
        border: none;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        padding: 1rem 1.5rem;
    }
    .today-tasks .list-group-item:last-child {
        border-bottom: none;
    }
    /* Navigation Button Group Styling */
    .fc-button-group {
        gap: 5px !important;
        background: #f8f9fc;
        padding: 5px;
        border-radius: 12px;
    }
    .fc-button-group .fc-button {
        border-radius: 8px !important;
        padding: 8px 16px !important;
        font-weight: 500 !important;
        border: none !important;
        background: transparent !important;
        color: #6c757d !important;
    }
    .fc-button-group .fc-button.fc-button-active {
        background-color: var(--primary-color) !important;
        color: white !important;
    }
    .fc-button-group .fc-button:hover:not(.fc-button-active) {
        background-color: #eaecf4 !important;
    }
    /* Header Toolbar Spacing */
    .fc-header-toolbar {
        padding: 15px 0 !important;
        margin-bottom: 1.5rem !important;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    /* Calendar Grid */
    .fc-daygrid-day {
        height: 120px !important;
    }
    .fc-daygrid-day-frame {
        min-height: 100% !important;
        padding: 4px !important;
    }
    .fc-daygrid-day-top {
        flex-direction: row !important;
        margin-bottom: 4px;
    }
    .fc-daygrid-day-number {
        float: left !important;
        margin: 5px !important;
        font-size: 14px !important;
        font-weight: 500;
        color: #5a5c69;
    }
    .fc-day-today .fc-daygrid-day-number {
        background: var(--primary-color);
        color: white;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Calendar Header */
    .fc-col-header-cell {
        padding: 10px 0 !important;
        background: #f8f9fc !important;
        border-color: #e3e6f0 !important;
    }
    .fc-col-header-cell-cushion {
        padding: 8px !important;
        color: #4e73df !important;
        font-weight: 600 !important;
        text-decoration: none !important;
    }
    
    /* Event Styling */
    .fc-daygrid-event {
        margin: 2px 4px !important;
        padding: 2px 4px !important;
        border-radius: 4px !important;
    }
    
    /* Navigation Buttons */
    .fc-button-group {
        gap: 5px !important;
    }
    .fc-button {
        padding: 8px 16px !important;
        font-weight: 500 !important;
        border: none !important;
    }
    .fc-button-primary {
        background-color: var(--primary-color) !important;
        color: white !important;
    }
    .fc-button-primary:not(.fc-button-active):hover {
        background-color: var(--hover-color) !important;
    }
    .fc-button-active {
        background-color: var(--hover-color) !important;
    }
    
    /* Event styling */
    .fc-event {
        border: none !important;
        padding: 3px 6px !important;
        font-size: 0.85em !important;
    }
    
    .priority-high {
        background-color: rgba(231, 74, 59, 0.9) !important;
        color: white !important;
    }
    
    .priority-medium {
        background-color: rgba(246, 194, 62, 0.9) !important;
        color: black !important;
    }
    
    .priority-low {
        background-color: rgba(28, 200, 138, 0.9) !important;
        color: white !important;
    }
    
    .fc-event i {
        opacity: 0.8;
    }

    /* New styles for task statistics */
    .task-stats {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(0, 0, 0, 0.08);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        margin-bottom: var(--spacing-base); /* Konsisten 25px */
    }

    .task-stats .progress {
        border-radius: 10px;
        background-color: #f0f2f5;
        margin-bottom: var(--spacing-base); /* Konsisten 25px */
    }

    .task-counts {
        text-align: center;
    }

    .stat-item h4 {
        font-size: 1.5rem;
        font-weight: 600;
    }

    .stat-item span {
        font-size: 0.85rem;
    }

    /* Update upcoming deadlines card styling */
    .upcoming-deadlines {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        height: calc(100% - 260px);
        margin-top: var(--spacing-base); /* Konsisten 25px */
    }
    
    .upcoming-deadlines .card-body {
        height: calc(100% - 60px);
        overflow-y: auto;
    }

    .upcoming-deadlines .list-group {
        height: 100%;
    }

    /* Update list item styling */
    .upcoming-deadlines .list-group-item {
        padding: 15px;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        transition: background-color 0.3s ease;
    }
    
    .upcoming-deadlines .list-group-item:hover {
        background-color: #f8f9fc;
    }
    
    /* Checkbox styling */
    .form-check-input {
        width: 1.2rem;
        height: 1.2rem;
        margin-top: 0.2rem;
        cursor: pointer;
        border-color: #e0e3e7;
    }
    
    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    /* Task title styling */
    .upcoming-deadlines h6 {
        font-size: 0.95rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.3rem;
    }
    
    /* Task completed styling */
    .task-completed {
        text-decoration: line-through;
        color: #858796;
    }
    
    /* Badge styling */
    .badge {
        padding: 0.4rem 0.8rem;
        font-weight: 500;
        font-size: 0.75rem;
    }
    
    /* Edit button styling */
    .btn-outline-primary {
        padding: 0.25rem 0.5rem;
        border-color: #e0e3e7;
        color: #4e73df;
    }
    
    .btn-outline-primary:hover {
        background-color: #4e73df;
        border-color: #4e73df;
        color: white;
    }
    
    /* Date text styling */
    .text-muted {
        font-size: 0.8rem;
        color: #6c757d;
    }

    /* Update progress bar styling */
    .progress {
        background-color: #eaecf4;
        border-radius: 10px;
        height: 10px !important;
    }

    .progress-bar {
        border-radius: 10px;
        transition: width 0.5s ease;
    }

    /* Update stats card styling */
    .task-stats {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    .task-stats .card-body {
        padding: 1.5rem;
    }

    .stat-item {
        flex: 1;
        padding: 0 10px;
    }

    .stat-item h4 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.3rem;
    }

    .stat-item span {
        font-size: 0.8rem;
        color: #858796;
    }

    /* Add hover effect to stats */
    .stat-item:hover h4 {
        transform: scale(1.1);
        transition: transform 0.2s ease;
    }

    /* Modal styling */
    .modal-content {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .modal-header {
        border-bottom: 1px solid rgba(0,0,0,0.05);
        padding: 1.5rem;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        border-top: 1px solid rgba(0,0,0,0.05);
        padding: 1.5rem;
    }

    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #e0e3e7;
        padding: 0.6rem 1rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78,115,223,0.25);
    }

    .form-label {
        font-weight: 500;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }

    /* Update Modal styling */
    .modal-lg {
        max-width: 800px;
    }

    .modal-content {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        max-height: 90vh; /* Maksimum tinggi modal 90% dari tinggi viewport */
    }

    .modal-header {
        background-color: #f8f9fc;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        padding: 1rem 1.5rem; /* Mengurangi padding */
    }

    .modal-header .modal-title {
        font-size: 1.1rem;
        font-weight: 600;
    }

    .modal-body {
        padding: 1.5rem;
        overflow-y: auto; /* Tambah scroll untuk modal body */
        max-height: calc(90vh - 110px); /* Tinggi maksimum body modal */
    }

    .modal-footer {
        background-color: #f8f9fc;
        border-top: 1px solid rgba(0,0,0,0.05);
        padding: 0.75rem 1.5rem; /* Mengurangi padding */
    }

    /* Form styling */
    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #e0e3e7;
        padding: 0.7rem 1rem; /* Sedikit mengurangi padding */
        font-size: 1rem;
    }

    .form-control-lg {
        font-size: 1.1rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78,115,223,0.25);
    }

    .form-label {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    /* Button styling */
    .btn-primary {
        padding: 0.5rem 1.25rem;
        font-weight: 500;
    }

    .btn-secondary {
        background-color: #eaecf4;
        border: none;
        color: #6e707e;
        padding: 0.5rem 1.25rem;
    }

    .btn-secondary:hover {
        background-color: #dde0e9;
    }

    /* Custom scrollbar styling */
    .custom-scroll {
        scrollbar-width: thin;
        scrollbar-color: #cbd5e0 #f8f9fc;
    }

    .custom-scroll::-webkit-scrollbar {
        width: 6px;
    }

    .custom-scroll::-webkit-scrollbar-track {
        background: #f8f9fc;
    }

    .custom-scroll::-webkit-scrollbar-thumb {
        background-color: #cbd5e0;
        border-radius: 3px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .dashboard-container {
            padding: 15px;
        }
    }

    /* Tambahkan style untuk modal */
    .modal-content {
        border-radius: 15px;
        border: none;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
        padding: 1.5rem;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        border-top: 1px solid rgba(0, 0, 0, 0.08);
        padding: 1.5rem;
    }

    .task-detail-content label {
        color: #666;
        font-size: 0.9rem;
    }

    .task-detail-content p {
        color: #2c3e50;
        font-size: 1rem;
    }

    .badge {
        padding: 0.5rem 1rem;
        font-weight: 500;
        border-radius: 6px;
    }
</style>
@endsection

@section('content')
<div class="dashboard-container">
    <div class="container-fluid">
        <!-- Calendar and Tasks Section -->
        <div class="row">
            <div class="col-md-8">
                <div class="task-calendar">
                    <div id="calendar"></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="action-buttons mb-3">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                        <i class="fas fa-plus"></i> Add New Task
                    </button>
                    <a href="{{ route('tasks.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-list"></i> View All Tasks
                    </a>
                </div>
                
                <!-- Task Statistics Card -->
                <div class="card task-stats border-0 mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="text-muted mb-0">Persentase Tugas</h6>
                            <h6 class="text-primary mb-0">
                                {{ number_format(($totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0), 1) }}%
                            </h6>
                        </div>
                        <div class="progress mb-3" style="height: 10px;">
                            <div class="progress-bar bg-primary" role="progressbar" 
                                 style="width: {{ $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0 }}%"
                                 aria-valuenow="{{ $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0 }}"
                                 aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between task-counts">
                            <div class="stat-item text-center">
                                <h4 class="mb-1">{{ $totalTasks }}</h4>
                                <span class="text-muted">Total Tasks</span>
                            </div>
                            <div class="stat-item text-center">
                                <h4 class="mb-1 text-success">{{ $completedTasks }}</h4>
                                <span class="text-muted">Completed</span>
                            </div>
                            <div class="stat-item text-center">
                                <h4 class="mb-1 text-warning">{{ $pendingTasks }}</h4>
                                <span class="text-muted">Pending</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card upcoming-deadlines border-0">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 d-flex align-items-center">
                            <i class="fas fa-hourglass-half text-warning me-2"></i>
                            Upcoming Deadlines
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        @if($upcomingDeadlines->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($upcomingDeadlines as $task)
                                    <div class="list-group-item">
                                        <div class="d-flex align-items-start gap-3">
                                            <!-- Checkbox di sebelah kiri -->
                                            <div class="form-check mt-1">
                                                <input class="form-check-input task-status" 
                                                       type="checkbox" 
                                                       value="{{ $task->id }}"
                                                       {{ $task->status === 'completed' ? 'checked' : '' }}
                                                       data-task-id="{{ $task->id }}">
                                            </div>
                                            
                                            <!-- Task details -->
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h6 class="mb-1 {{ $task->status === 'completed' ? 'task-completed' : '' }}">{{ $task->title }}</h6>
                                                        <small class="text-muted">
                                                            <i class="far fa-clock me-1"></i>
                                                            {{ $task->end_date->format('d M Y, H:i') }}
                                                            ({{ $task->end_date->diffForHumans() }})
                                                        </small>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge bg-{{ $task->priority === 'high' ? 'danger' : ($task->priority === 'medium' ? 'warning' : 'success') }}">
                                                            {{ ucfirst($task->priority) }}
                                                        </span>
                                                        <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                                <p class="text-muted mb-0">No upcoming deadlines!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    @if(str_contains(strtolower(session('success')), 'hapus'))
        <div class="alert alert-danger fade show" role="alert">
            {{ session('success') }}
        </div>
    @else
        <div class="alert alert-success fade show" role="alert">
            {{ session('success') }}
        </div>
    @endif
@endif

@if(session('error'))
    <div class="alert alert-danger fade show" role="alert">
        {{ session('error') }}
    </div>
@endif

<!-- Add Task Modal -->
<div class="modal fade" id="addTaskModal" tabindex="-1" aria-labelledby="addTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header py-3"> <!-- Mengurangi padding atas bawah -->
                <h5 class="modal-title" id="addTaskModalLabel">Add New Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body custom-scroll"> <!-- Tambah class untuk custom scroll -->
                <form id="addTaskForm" action="{{ route('tasks.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="title" class="form-label">Task Title</label>
                        <input type="text" class="form-control form-control-lg" id="title" name="title" required 
                               placeholder="Enter task title">
                    </div>
                    <div class="mb-4">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" 
                                  rows="4" placeholder="Enter task description"></textarea>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="start_date" class="form-label">Due Date</label>
                            <input type="datetime-local" class="form-control" id="start_date" 
                                   name="start_date" required>
                        </div>
                        <div class="col-md-6">
                            <label for="priority" class="form-label">Priority</label>
                            <select class="form-select" id="priority" name="priority" required>
                                <option value="low">Low Priority</option>
                                <option value="medium">Medium Priority</option>
                                <option value="high">High Priority</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer py-2"> <!-- Mengurangi padding atas bawah -->
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="addTaskForm" class="btn btn-primary px-4">Add Task</button>
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan modal di bagian bawah dashboard container -->
<div class="modal fade" id="taskDetailModal" tabindex="-1" aria-labelledby="taskDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="taskDetailModalLabel">Detail Tugas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="task-detail-content">
                    <div class="mb-3">
                        <label class="fw-bold">Judul Tugas:</label>
                        <p id="taskTitle" class="mb-2"></p>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Deskripsi:</label>
                        <p id="taskDescription" class="mb-2"></p>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Batas Waktu:</label>
                        <p id="taskDeadline" class="mb-2"></p>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Prioritas:</label>
                        <span id="taskPriority" class="badge"></span>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Status:</label>
                        <span id="taskStatus" class="badge bg-secondary"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="editTaskBtn">Edit Tugas</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 'auto',
        aspectRatio: 1.35,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth'
        },
        dayMaxEvents: true,
        views: {
            dayGridMonth: {
                dayMaxEvents: 2,
                titleFormat: { month: 'long', year: 'numeric' }
            }
        },
        buttonText: {
            today: 'Today',
            month: 'Month'
        },
        firstDay: 1,
        editable: true,
        selectable: true,
        selectMirror: true,
        events: {
            url: '{{ route('tasks.get') }}',
            method: 'GET',
            failure: function() {
                console.error('Failed to load tasks');
            }
        },
        eventContent: function(arg) {
            let titleEl = document.createElement('div');
            titleEl.className = 'd-flex align-items-center';
            titleEl.innerHTML = `
                <i class="fas fa-clock" style="margin-right: 4px;"></i>
                <span>${arg.event.title}</span>
            `;
            return { domNodes: [titleEl] }
        },

        eventDrop: function(info) {
            fetch(`/tasks/${info.event.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    end_date: info.event.start.toISOString().split('T')[0]
                })
            })
            .catch(error => {
                console.error('Error:', error);
                info.revert();
                alert('Error updating task!');
            });
        },

        eventClick: function(info) {
            // Mengambil data event yang diklik
            const event = info.event;
            
            // Mengisi data ke dalam modal
            document.getElementById('taskTitle').textContent = event.title;
            
            // Memastikan deskripsi tampil dengan benar
            const description = event.extendedProps.description || event.extendedProps.deskripsi || 'Tidak ada deskripsi';
            document.getElementById('taskDescription').textContent = description;
            
            // Menampilkan batas waktu
            document.getElementById('taskDeadline').textContent = formatDate(event.end || event.start);
            
            // Mengatur badge prioritas
            const priorityBadge = document.getElementById('taskPriority');
            priorityBadge.className = 'badge';
            if (event.classNames.includes('priority-high')) {
                priorityBadge.classList.add('bg-danger');
                priorityBadge.textContent = 'Tinggi';
            } else if (event.classNames.includes('priority-medium')) {
                priorityBadge.classList.add('bg-warning');
                priorityBadge.textContent = 'Sedang';
            } else {
                priorityBadge.classList.add('bg-success');
                priorityBadge.textContent = 'Rendah';
            }
            
            // Mengatur status tugas
            const statusBadge = document.getElementById('taskStatus');
            statusBadge.textContent = event.extendedProps.status || 'Dalam Progres';
            
            // Menambahkan event listener untuk tombol edit
            document.getElementById('editTaskBtn').onclick = function() {
                window.location.href = `/tasks/${event.id}/edit`;
            };
            
            // Menampilkan modal
            var taskModal = new bootstrap.Modal(document.getElementById('taskDetailModal'));
            taskModal.show();

            // Debug untuk melihat data event
            console.log('Event Data:', event);
            console.log('Extended Props:', event.extendedProps);
        }
    });
    
    calendar.render();
    
    // Fungsi untuk memformat tanggal
    function formatDate(date) {
        if (!date) return '';
        return new Date(date).toLocaleDateString('id-ID', {
            weekday: 'long',
            day: 'numeric',
            month: 'long',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }
});

// Update the checkbox event listener to also update the UI
document.querySelectorAll('.task-status').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const taskId = this.dataset.taskId;
        const taskTitle = this.closest('.list-group-item').querySelector('h6');
        
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
                if (this.checked) {
                    taskTitle.classList.add('task-completed');
                } else {
                    taskTitle.classList.remove('task-completed');
                }
                // Refresh halaman untuk memperbarui statistik
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

document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function() {
        const form = this.closest('.delete-form');
        
        Swal.fire({
            title: 'Delete Task?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});

// Function to auto-dismiss alerts
document.addEventListener('DOMContentLoaded', function() {
    // Get all alert elements
    const alerts = document.querySelectorAll('.alert');
    
    alerts.forEach(function(alert) {
        // Set timeout to remove alert after 3 seconds
        setTimeout(function() {
            // Add fade out animation
            alert.classList.remove('show');
            alert.classList.add('fade');
            
            // Remove alert from DOM after animation
            setTimeout(function() {
                alert.remove();
            }, 150);
        }, 2000);
    });
});

// Add this to your existing scripts section
document.addEventListener('DOMContentLoaded', function() {
    // Set default dates when modal opens
    $('#addTaskModal').on('show.bs.modal', function () {
        const now = new Date();
        const tomorrow = new Date(now);
        tomorrow.setDate(tomorrow.getDate() + 1);
        
        document.getElementById('start_date').value = now.toISOString().slice(0, 16);
        document.getElementById('end_date').value = tomorrow.toISOString().slice(0, 16);
    });

    // Form submission handling
    const addTaskForm = document.getElementById('addTaskForm');
    addTaskForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        fetch(this.action, {
            method: 'POST',
            body: new FormData(this),
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Close modal
                bootstrap.Modal.getInstance(document.getElementById('addTaskModal')).hide();
                // Reset form
                addTaskForm.reset();
                // Refresh page to show new task
                window.location.reload();
            } else {
                alert('Error adding task: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error adding task');
        });
    });
});
</script>
@endsection 