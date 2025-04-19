<div class="col-md-8 mb-4">
    <div class="card shadow h-100">
        <div class="card-body task-calendar">
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
            <div class="view-container">
            <!-- Calendar View -->
                <div id="calendarView" class="view-content">
                    <div id="calendar"></div>
            </div>

            <!-- Statistics View -->
                <div id="statisticsView" class="view-content" style="display: none;">
                    <div class="row g-4">
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
                                <h5 class="card-title mb-4">Statistik Tugas Mingguan</h5>
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
    
    .task-calendar {
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .view-container {
        flex: 1;
        display: flex;
        flex-direction: column;
        min-height: 0;
    }

    #calendarView {
        flex: 1;
        display: flex;
        flex-direction: column;
        min-height: 0;
    }

    #calendar {
        flex: 1;
        min-height: 0;
    }

    .fc {
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .fc .fc-view-harness {
        flex: 1 1 auto;
        min-height: 0;
    }

    .fc .fc-daygrid {
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .fc .fc-daygrid-body {
        flex: 1 1 auto;
        height: auto !important;
    }

    .fc .fc-scroller {
        height: 100% !important;
        overflow: hidden auto;
    }

    .fc .fc-scroller-liquid-absolute {
        position: relative !important;
        top: 0 !important;
        right: 0 !important;
        left: 0 !important;
        bottom: 0 !important;
    }

    .fc-direction-ltr .fc-daygrid-event.fc-event-end {
        margin-right: 4px;
    }

    .fc table {
        height: 100%;
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
        height: auto !important;
    }

    .fc .fc-daygrid-day-frame {
        height: 100% !important;
        min-height: 150px !important;
        display: flex;
        flex-direction: column;
    }

    .fc .fc-daygrid-day-top {
        flex: 0 0 auto;
        padding: 4px 8px;
    }

    .fc .fc-daygrid-day-events {
        flex: 1;
        min-height: 0;
        margin: 0 !important;
        padding: 0 4px;
    }

    .fc .fc-daygrid-body-balanced .fc-daygrid-day-events {
        position: relative !important;
        min-height: 0;
    }

    .fc .fc-daygrid-day-bottom {
        padding: 2px 4px;
    }

    .fc table {
        height: 100%;
    }

    .fc-theme-standard td {
        border: 1px solid #e3e6f0;
    }

    .fc .fc-scrollgrid-sync-table {
        height: 100% !important;
    }

    .fc-view-harness, 
    .fc-view-harness-active {
        height: 100% !important;
    }

    .fc-daygrid-body,
    .fc-scrollgrid-sync-table,
    .fc-col-header,
    .fc-scrollgrid {
        width: 100% !important;
    }

    .fc-scrollgrid-section-body table, 
    .fc-scrollgrid-section-header table {
        width: 100% !important;
        height: 100% !important;
    }

    .fc .fc-scrollgrid-section-liquid {
        height: 100%;
    }

    .fc-scrollgrid-sync-table {
        min-height: 0 !important;
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

    /* Calendar Cell Styling */
    .fc .fc-daygrid-day {
        height: auto !important;
    }

    .fc .fc-daygrid-day-frame {
        height: 100% !important;
        min-height: 150px !important;
        display: flex;
        flex-direction: column;
    }

    .fc .fc-daygrid-day-top {
        flex: 0 0 auto;
        padding: 4px 8px;
    }

    .fc .fc-daygrid-day-events {
        flex: 1;
        min-height: 0;
        margin: 0 !important;
        padding: 0 4px;
    }

    /* Event Styling */
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

    /* Priority Colors */
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

    /* Calendar Structure */
    .fc-scrollgrid-sync-table {
        height: 100% !important;
    }

    .fc-view-harness, 
    .fc-view-harness-active {
        height: 100% !important;
    }

    .fc-daygrid-body,
    .fc-scrollgrid-sync-table,
    .fc-col-header,
    .fc-scrollgrid {
        width: 100% !important;
    }

    .fc-scrollgrid-section-body table, 
    .fc-scrollgrid-section-header table {
        width: 100% !important;
        height: 100% !important;
    }

    .fc .fc-scrollgrid-section-liquid {
        height: 100%;
    }

    /* Other Calendar Elements */
    .fc-theme-standard td {
        border: 1px solid #e3e6f0;
    }

    .fc .fc-day-today {
        background-color: rgba(78, 115, 223, 0.05) !important;
    }

    .fc-daygrid-event-dot {
        display: none !important;
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

    .fc-scrollgrid-section-body table, 
    .fc-scrollgrid-section-header table {
        width: 100% !important;
        height: 100% !important;
    }

    .fc-scrollgrid-section table {
        height: 100%;
    }

    .fc .fc-scrollgrid-section-liquid > td {
        height: 150px !important;
    }

    .fc .fc-daygrid-body {
        min-height: 150px !important;
    }

    .fc .fc-daygrid-body table {
        min-height: 150px !important;
    }

    .fc tr {
        min-height: 150px !important;
    }
</style>