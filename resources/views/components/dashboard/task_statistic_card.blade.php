<style>
    .stats-card {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 0 10px 0 rgba(0,0,0,0.1);
    }
    .stats-card .card-header {
        border-bottom: 1px solid #e9ecef;
        position: relative;
        z-index: 2;
    }
    .stats-card .card-body {
        position: relative;
        z-index: 1;
    }
    .stats-card .progress {
        height: 10px;
    }
    .stats-card .stat-item h4 {
        font-size: 1.2rem;
        margin-bottom: 0;
    }
    .stats-card .stat-item span {
        font-size: 0.8rem;
    }
</style>

<div class="stats-card upcoming-tasks mb-3 px-3">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0">
            <i class="fas fa-chart-pie text-primary me-2"></i> Task Statistics
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="d-flex justify-content-between align-items-center mb-3 px-3 pt-3">
            <h6 class="text-muted mb-0">Persentase Tugas</h6>
            <h6 class="text-primary mb-0">
                {{ number_format(($totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0), 0) }}%
            </h6>
        </div>
        <div class="progress mb-3 mx-3">
            <div class="progress-bar bg-primary" role="progressbar" 
                 style="width: {{ $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0 }}%"
                 aria-valuenow="{{ $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0 }}"
                 aria-valuemin="0" aria-valuemax="100">
            </div>
        </div>
        <div class="d-flex justify-content-around px-3 pb-3">
            <div class="stat-item text-center">
                <h4 class="text-dark">{{ $totalTasks }}</h4>
                <span class="text-muted">Total Tasks</span>
            </div>
            <div class="stat-item text-center">
                <h4 class="text-success">{{ $completedTasks }}</h4>
                <span class="text-muted">Completed</span>
            </div>
            <div class="stat-item text-center">
                <h4 class="text-warning">{{ $pendingTasks }}</h4>
                <span class="text-muted">Pending</span>
            </div>
        </div>
    </div>
</div>