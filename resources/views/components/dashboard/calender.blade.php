<div class="col-md-8">
    <div class="card shadow mb-4" style="min-height: calc(100vh - 200px);">
        <div class="card-body task-calendar" style="height: 100%; display: flex; flex-direction: column;">
            <!-- Header dengan toggle buttons -->
            <div class="d-flex justify-content-end align-items-center mb-4">
                <div class="btn-group view-toggle" role="group">
                    <button type="button" class="btn btn-primary active" id="calendarViewBtn">
                        <i class="fas fa-calendar-alt me-2"></i> Calendar
                    </button>
                    <button type="button" class="btn btn-primary" id="statsViewBtn">
                        <i class="fas fa-chart-bar me-2"></i> Statistics
                    </button>
                </div>
            </div>

            <!-- Container untuk Calendar dan Statistics -->
            <div class="view-container" style="flex: 1; display: flex; flex-direction: column;">
                <!-- Calendar View -->
                <div id="calendarView" class="view-content" style="flex: 1;">
                    <div id="calendar" style="height: 100%;"></div>
                </div>

                <!-- Statistics View -->
                <div id="statisticsView" class="view-content" style="display: none; flex: 1;">
                    <div class="row g-4" style="height: 100%;">
                        <div class="col-md-6">
                            <div class="stats-card h-100">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">Task Distribution by Priority</h5>
                                    <canvas id="priorityChart"></canvas>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="stats-card h-100">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">Task Completion Rate</h5>
                                    <canvas id="completionChart"></canvas>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-12 mt-4">
                            <div class="stats-card">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">Weekly Task Overview</h5>
                                    <canvas id="weeklyChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        background-color: #fff;
        border: none;
        border-radius: 10px;
    }
    
    .stats-card {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: rgba(58, 59, 69, 0.15);
    }

    /* View Toggle Button Styling */
    .view-toggle .btn {
        padding: 0.5rem 1.25rem;
        font-weight: 500;
        border: none;
        transition: all 0.2s;
    }

    .view-toggle .btn.active {
        background-color: #4e73df;
        box-shadow: 0 2px 8px rgba(78, 115, 223, 0.25);
    }

    .view-toggle .btn:not(.active) {
        background-color: #eaecf4;
        color: #6e707e;
    }

    .view-toggle .btn:hover:not(.active) {
        background-color: #dddfeb;
    }

    /* Calendar Styling */
    .fc {
        height: 100%;
    }

    .fc .fc-toolbar.fc-header-toolbar {
        margin-bottom: 1rem;
    }

    .fc .fc-toolbar-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2c3e50;
    }

    .fc .fc-button-primary {
        background-color: #4e73df;
        border-color: #4e73df;
        font-weight: 500;
        padding: 0.35rem 0.75rem;
        transition: all 0.2s;
    }

    .fc .fc-button-primary:hover {
        background-color: #2e59d9;
        border-color: #2e59d9;
        box-shadow: 0 2px 8px rgba(78, 115, 223, 0.25);
    }

    .fc .fc-button-primary:disabled {
        background-color: #4e73df;
        border-color: #4e73df;
        opacity: 0.65;
    }

    .fc .fc-daygrid-day {
        height: 150px !important;
        max-height: 150px !important;
        min-height: 150px !important;
    }

    .fc .fc-daygrid-day-frame {
        height: 100% !important;
        max-height: 150px !important;
        display: flex !important;
        flex-direction: column !important;
        overflow: hidden !important;
    }

    .fc .fc-daygrid-day-top {
        flex: 0 0 auto !important;
        padding: 4px 8px !important;
        z-index: 2 !important;
    }

    .fc .fc-daygrid-day-events {
        flex: 1 1 auto !important;
        position: relative !important;
        padding: 2px 4px !important;
        overflow-y: auto !important;
        max-height: calc(150px - 30px) !important;
        margin-right: 2px !important;
    }

    .fc .fc-daygrid-more-link {
        position: absolute !important;
        bottom: 0 !important;
        left: 0 !important;
        right: 0 !important;
        background: rgba(255,255,255,0.9) !important;
        padding: 2px 4px !important;
        text-align: center !important;
        font-size: 0.8em !important;
        color: #4e73df !important;
        cursor: pointer !important;
        z-index: 3 !important;
    }

    .fc .fc-daygrid-body {
        width: 100% !important;
    }

    .fc .fc-daygrid-body-balanced .fc-daygrid-day-events {
        position: relative !important;
    }

    .fc .fc-daygrid-body-natural .fc-daygrid-day-frame {
        height: 150px !important;
        max-height: 150px !important;
        min-height: 150px !important;
    }

    /* Week row height */
    .fc-dayGridWeek-view .fc-daygrid-body-balanced .fc-scrollgrid-sync-table {
        height: 150px !important;
    }

    .fc .fc-scroller-liquid-absolute {
        position: relative !important;
    }

    .fc-daygrid-day-frame:hover .fc-daygrid-day-events {
        overflow-y: auto !important;
    }

    /* Custom scrollbar */
    .fc-daygrid-day-events::-webkit-scrollbar {
        width: 4px !important;
    }

    .fc-daygrid-day-events::-webkit-scrollbar-track {
        background: #f8f9fc !important;
    }

    .fc-daygrid-day-events::-webkit-scrollbar-thumb {
        background: #4e73df !important;
        border-radius: 4px !important;
    }

    .fc-daygrid-day-events::-webkit-scrollbar-thumb:hover {
        background: #2e59d9 !important;
    }

    .fc .fc-daygrid-day-number {
        font-size: 0.85rem;
        font-weight: 500;
        color: #5a5c69;
        padding: 4px;
    }

    .fc .fc-day-today {
        background-color: rgba(78, 115, 223, 0.05) !important;
    }

    .fc .fc-event {
        margin: 1px 0 !important;
        padding: 2px 8px !important;
        border-radius: 4px !important;
        box-shadow: 0 1px 2px rgba(0,0,0,0.1) !important;
        border: none !important;
        min-height: 22px !important;
        line-height: 1.2 !important;
    }

    .fc .fc-event:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.15) !important;
        transition: all 0.2s ease;
        filter: brightness(95%);
    }

    .fc .fc-event-title {
        font-size: 0.8rem !important;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        padding: 1px 0;
    }

    .fc-daygrid-event {
        white-space: nowrap;
        position: relative;
        display: block;
        font-size: 0.8em;
        line-height: 1.2;
    }

    .fc-daygrid-block-event .fc-event-time {
        font-weight: normal !important;
        font-size: 0.8rem !important;
    }

    .fc-daygrid-event-dot {
        display: none !important;
    }

    /* Priority Colors for Events */
    .priority-high {
        background-color: #ff4444 !important;
        color: white !important;
        border-left: 4px solid #cc0000 !important;
        font-weight: 600 !important;
    }

    .priority-medium {
        background-color: #ffa726 !important;
        color: white !important;
        border-left: 4px solid #fb8c00 !important;
        font-weight: 600 !important;
    }

    .priority-low {
        background-color: #66bb6a !important;
        color: white !important;
        border-left: 4px solid #43a047 !important;
        font-weight: 600 !important;
    }

    /* Statistics Cards */
    .stats-card .card-title {
        color: #4e73df;
        font-size: 1.1rem;
        font-weight: 600;
    }

    .stats-card .card-body {
        padding: 1.5rem;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .fc .fc-toolbar {
            flex-direction: column;
            gap: 1rem;
        }

        .fc .fc-toolbar-title {
            font-size: 1.1rem;
        }

        .view-toggle .btn {
            padding: 0.4rem 1rem;
            font-size: 0.9rem;
        }
    }

    /* Calendar Table Styling */
    .fc-theme-standard td, .fc-theme-standard th {
        border: 1px solid #e3e6f0;
    }

    .fc-theme-standard thead tr th {
        background-color: #f8f9fc;
        padding: 8px 0;
        font-weight: 600;
        color: #4e73df;
    }

    .fc-daygrid-day {
        transition: background-color 0.2s;
    }

    .fc-daygrid-day:hover {
        background-color: #f8f9fc;
    }

    .fc-day-today {
        background-color: rgba(78, 115, 223, 0.05) !important;
    }

    .fc-day-today .fc-daygrid-day-number {
        background-color: #4e73df;
        color: white !important;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 2px;
    }

    .fc-day-other .fc-daygrid-day-number {
        color: #858796 !important;
        opacity: 0.6;
    }

    .fc-daygrid-day-events {
        margin-top: 4px;
    }

    .fc-daygrid-event-harness {
        margin-top: 1px !important;
    }

    .fc-h-event {
        background-color: transparent;
        border: none;
    }

    .fc-day-sat, .fc-day-sun {
        background-color: #fafbff;
    }

    /* Header Navigation Buttons */
    .fc .fc-button-group {
        gap: 5px;
    }

    .fc .fc-button-primary {
        box-shadow: 0 2px 4px rgba(78, 115, 223, 0.15);
    }

    .fc .fc-button-primary:not(:disabled):active,
    .fc .fc-button-primary:not(:disabled).fc-button-active {
        background-color: #2e59d9;
        border-color: #2e59d9;
    }

    /* Month Title */
    .fc .fc-toolbar-title {
        position: relative;
        padding-bottom: 5px;
    }

    .fc .fc-toolbar-title:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 40px;
        height: 2px;
        background-color: #4e73df;
    }
</style>