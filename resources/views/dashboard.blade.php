@extends('layouts.app')

@section('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<style>
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
</style>
@endsection

@section('content')
<div class="dashboard-container">
    <div class="container-fluid">
        <!-- Calendar and Tasks Section -->
        <div class="row" style="min-height: calc(100vh - 100px);">
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
@endsection

@section('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
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
            type: 'bar',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Tasks',
                    data: {{ json_encode($weeklyTaskCounts) }},
                    backgroundColor: '#0099ff'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
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

    // Task completion toggle
    document.querySelectorAll('.task-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function(e) {
            e.stopPropagation(); // Stop event from bubbling up to parent
            const taskId = this.dataset.taskId;
            const token = document.querySelector('meta[name="csrf-token"]').content;

            this.disabled = true;

            fetch(`/tasks/${taskId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(
                        data.status === 'completed' 
                            ? 'Task marked as completed!' 
                            : 'Task marked as pending!',
                        'success'
                    );
                    
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    this.checked = !this.checked;
                    showNotification(data.message || 'Failed to update task status!', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.checked = !this.checked;
                showNotification('Error updating task status!', 'error');
            })
            .finally(() => {
                this.disabled = false;
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
                window.location.href = `/tasks/${taskId}/edit`;
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

    // Notification function
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show notification`;
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.animation = 'slideOut 0.5s ease-out forwards';
            setTimeout(() => {
                notification.remove();
            }, 500);
        }, 3000);
    }

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
});
</script>
@endsection