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
        background: white;
        border-radius: 15px;
        margin-bottom: 20px;
        border: 1px solid rgba(0, 0, 0, 0.08);
    }
    
    .stats-card .card-header {
        background: white;
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
        padding: 1rem 1.5rem;
    }
    
    .stats-card .card-header h5 {
        color: #2c3e50;
        font-weight: 600;
        margin: 0;
    }
    
    .stats-card .card-body {
        padding: 1.5rem;
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
        padding: 20px;
        border-radius: 15px;
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

    .statistics-container {
        padding: 20px;
        background: white;
        border-radius: 15px;
        min-height: 600px;
    }

    .stats-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease;
    }

    .stats-card:hover {
        transform: translateY(-5px);
    }

    .card-title {
        color: #2c3e50;
        font-size: 1.1rem;
        font-weight: 600;
    }

    .btn-group .btn {
        padding: 8px 16px;
    }

    .btn-group .btn.active {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .view-container {
        position: relative;
        height: 600px; /* Tinggi tetap */
        overflow: hidden; /* Mencegah scroll */
    }

    .view-content {
        position: absolute;
        width: 100%;
        top: 0;
        left: 0;
        transition: opacity 0.3s ease;
    }

    #statisticsView {
        padding: 20px 0;
    }

    /* Tambahan style untuk kalender */
    .fc-view-harness {
        height: 100% !important;
    }

    .fc .fc-daygrid-body {
        height: 100% !important;
    }

    .fc .fc-daygrid-body-balanced .fc-daygrid-day-events {
        position: relative !important;
    }

    .current-month {
        font-size: 1.2rem;
        font-weight: 600;
        color: #2c3e50;
        min-width: 150px;
        text-align: center;
    }

    .fc-theme-standard td, .fc-theme-standard th {
        border: 1px solid #e3e6f0;
    }

    .fc-daygrid-day {
        height: 90px !important; /* Tinggi tetap untuk setiap sel */
        max-height: 90px !important;
    }

    .fc-daygrid-day-frame {
        height: 100% !important;
        min-height: unset !important;
        display: flex;
        flex-direction: column;
    }

    .fc-daygrid-day-top {
        flex: 0 0 auto;
        padding: 4px;
    }

    .fc-daygrid-day-events {
        flex: 1;
        min-height: 0;
        max-height: 50px; /* Batasi tinggi area event */
        overflow: hidden; /* Sembunyikan event yang berlebih */
    }

    .fc-daygrid-more-link {
        font-size: 0.8em;
        color: var(--primary-color);
        text-decoration: none;
    }

    .fc-day-today {
        background: rgba(0, 153, 255, 0.05) !important;
    }

    .fc td, .fc th {
        border: 1px solid #e3e6f0 !important;
    }

    /* Style untuk notifikasi */
    .notification {
        position: fixed;
        top: 80px;
        right: 20px;
        z-index: 1000;
        min-width: 300px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        animation: slideIn 0.5s ease-out;
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

    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }

    .task-completed {
        text-decoration: line-through;
        color: #6c757d;
    }

    .form-check-input {
        cursor: pointer;
    }

    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    /* Upcoming Tasks Styling */
    .upcoming-tasks {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        margin-bottom: 30px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .upcoming-tasks .card-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
        padding: 1rem 1.5rem;
    }

    .upcoming-tasks .card-header h5 {
        color: #2c3e50;
        font-weight: 600;
    }

    .upcoming-tasks .list-group-item {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        transition: background-color 0.3s ease;
    }

    .upcoming-tasks .list-group-item:hover {
        background-color: rgba(0, 0, 0, 0.01);
    }

    .upcoming-tasks .list-group-item:last-child {
        border-bottom: none;
    }

    .upcoming-tasks h6 {
        font-size: 0.95rem;
        font-weight: 500;
        color: #2c3e50;
        margin: 0;
    }

    .upcoming-tasks .text-muted {
        font-size: 0.85rem;
    }

    .upcoming-tasks .badge {
        padding: 0.5em 0.8em;
        font-weight: 500;
        font-size: 0.75rem;
    }

    /* Checkbox Styling */
    .form-check-input {
        width: 1.2rem;
        height: 1.2rem;
        margin-top: 0.2rem;
        cursor: pointer;
        border: 2px solid #dee2e6;
    }

    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .form-check-input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(0, 153, 255, 0.25);
    }

    /* Task completed style */
    .task-completed {
        text-decoration: line-through;
        color: #6c757d;
    }

    /* Badge colors */
    .badge.bg-danger {
        background-color: #e74a3b !important;
    }

    .badge.bg-warning {
        background-color: #f6c23e !important;
        color: #2c3e50;
    }

    .badge.bg-success {
        background-color: #1cc88a !important;
    }

    /* Tambahan style untuk overdue tasks */
    .overdue-tasks {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        margin-bottom: 30px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .overdue-tasks .text-danger {
        font-weight: 500;
    }

    .overdue-tasks .list-group-item:hover {
        background-color: rgba(0, 0, 0, 0.01);
    }

    /* Reset dan perbaikan style */
    .stats-card {
        background: white;
        border-radius: 15px;
        margin-bottom: 20px;
        border: 1px solid rgba(0, 0, 0, 0.08);
    }

    .stats-card .card-header {
        background: white;
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
        padding: 1rem 1.5rem;
    }

    .stats-card .card-header h5 {
        color: #2c3e50;
        font-weight: 600;
        margin: 0;
    }

    .stats-card .card-body {
        padding: 1.5rem;
    }

    .list-group-item {
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1rem 1.5rem;
        background-color: transparent;
        transition: none;
    }

    .list-group-item:last-child {
        border-bottom: none;
    }

    /* Menghilangkan hover effect */
    .list-group-item:hover,
    .upcoming-tasks .list-group-item:hover,
    .stats-card:hover {
        background-color: transparent !important;
        transform: none !important;
        box-shadow: none !important;
    }

    /* Perbaikan tampilan chart */
    canvas {
        width: 100% !important;
        height: 300px !important;
    }

    /* Task styling */
    .task-completed {
        text-decoration: line-through;
        color: #6c757d;
    }

    .form-check-input {
        cursor: pointer;
        border: 2px solid #dee2e6;
    }

    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    /* Badge styling */
    .badge {
        padding: 0.5em 0.8em;
        font-weight: 500;
        font-size: 0.75rem;
    }

    .badge.bg-danger {
        background-color: #e74a3b !important;
    }

    .badge.bg-warning {
        background-color: #f6c23e !important;
        color: #2c3e50;
    }

    .badge.bg-success {
        background-color: #1cc88a !important;
    }

    /* Text colors */
    .text-danger {
        color: #e74a3b !important;
    }

    .text-muted {
        color: #6c757d !important;
    }

    /* Override any bootstrap or other framework hover effects */
    * {
        transition: none !important;
    }

    .stats-card,
    .upcoming-tasks,
    .list-group-item,
    .card {
        transition: none !important;
        transform: none !important;
        box-shadow: none !important;
    }

    /* Statistics View Container */
    #statisticsView {
        height: calc(100vh - 100px); /* Sesuaikan dengan header Anda */
        overflow-y: auto;
        padding: 20px;
    }

    /* Chart Containers */
    .chart-wrapper {
        position: relative;
        width: 350px; /* Fixed width */
        height: 350px; /* Fixed height - same as width for perfect circle */
        margin: 0 auto;
    }

    .chart-wrapper-large {
        position: relative;
        width: 100%;
        height: 400px;
        margin: 0 auto;
    }

    /* Scrollbar Styling */
    #statisticsView::-webkit-scrollbar {
        width: 8px;
    }

    #statisticsView::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    #statisticsView::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }

    #statisticsView::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    /* Ensure the canvas maintains aspect ratio */
    .chart-wrapper canvas {
        max-width: 100%;
        max-height: 100%;
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
                    <!-- Header dengan toggle buttons -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex align-items-center gap-2">
                            <button class="btn btn-outline-secondary" id="prevBtn">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <h4 class="current-month mb-0 mx-3" id="currentMonth"></h4>
                            <button class="btn btn-outline-secondary" id="nextBtn">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                            <button class="btn btn-primary ms-2" id="todayBtn">Today</button>
                        </div>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-primary active" id="calendarViewBtn">
                                <i class="fas fa-calendar-alt"></i> Calendar
                            </button>
                            <button type="button" class="btn btn-primary" id="statsViewBtn">
                                <i class="fas fa-chart-bar"></i> Statistics
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
                                <!-- Priority Distribution -->
                                <div class="col-md-6">
                                    <div class="stats-card">
                                        <div class="card-header">
                                            <h5 class="mb-0">Task Distribution by Priority</h5>
                                        </div>
                                        <div class="card-body d-flex justify-content-center align-items-center">
                                            <div class="chart-wrapper">
                                                <canvas id="priorityChart"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Completion Rate -->
                                <div class="col-md-6">
                                    <div class="stats-card">
                                        <div class="card-header">
                                            <h5 class="mb-0">Task Completion Rate</h5>
                                        </div>
                                        <div class="card-body d-flex justify-content-center align-items-center">
                                            <div class="chart-wrapper">
                                                <canvas id="completionChart"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Weekly Overview -->
                                <div class="col-md-12">
                                    <div class="stats-card">
                                        <div class="card-header">
                                            <h5 class="mb-0">Weekly Task Overview</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="chart-wrapper-large">
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
                
                <div class="stats-card upcoming-tasks">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0">Upcoming Deadlines</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @foreach($upcomingDeadlines as $task)
                            <div class="list-group-item border-0 py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="form-check me-3">
                                            <input type="checkbox" 
                                                class="form-check-input task-checkbox" 
                                                id="task-{{ $task->id }}"
                                                data-task-id="{{ $task->id }}"
                                                {{ $task->status === 'completed' ? 'checked' : '' }}>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 {{ $task->status === 'completed' ? 'task-completed' : '' }}">
                                                {{ $task->title }}
                                            </h6>
                                            <small class="text-muted">
                                                Due: {{ \Carbon\Carbon::parse($task->end_date)->format('M d, Y') }}
                                            </small>
                                        </div>
                                    </div>
                                    <span class="badge bg-{{ $task->priority === 'high' ? 'danger' : ($task->priority === 'medium' ? 'warning' : 'success') }}">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Overdue Tasks Card -->
                <div class="stats-card upcoming-tasks mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Overdue Tasks</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @foreach($overdueTasks as $task)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="form-check me-3">
                                            <input type="checkbox" 
                                                class="form-check-input task-checkbox" 
                                                id="task-{{ $task->id }}"
                                                data-task-id="{{ $task->id }}"
                                                {{ $task->status === 'completed' ? 'checked' : '' }}>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 {{ $task->status === 'completed' ? 'task-completed' : '' }}">
                                                {{ $task->title }}
                                            </h6>
                                            <small class="text-danger">
                                                Due: {{ \Carbon\Carbon::parse($task->end_date)->format('M d, Y') }}
                                            </small>
                                        </div>
                                    </div>
                                    <span class="badge bg-{{ $task->priority === 'high' ? 'danger' : ($task->priority === 'medium' ? 'warning' : 'success') }}">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                            @if($overdueTasks->isEmpty())
                            <div class="list-group-item">
                                <div class="text-center text-muted">
                                    No overdue tasks
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
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
                height: 650,
                headerToolbar: false,
                events: "{{ route('tasks.get') }}",
                dayMaxEvents: true,
                eventDisplay: 'block',
                displayEventTime: false,
                firstDay: 1
            });
        }
        calendar.render();
    }

    // Initialize Charts
    function initializeCharts() {
        // Set default Chart.js options for better quality
        Chart.defaults.font.size = 13;
        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.plugins.tooltip.padding = 10;
        Chart.defaults.plugins.tooltip.backgroundColor = 'rgba(0,0,0,0.8)';
        Chart.defaults.plugins.tooltip.titleFont.size = 14;
        Chart.defaults.plugins.tooltip.bodyFont.size = 13;
        
        // Priority Chart
        new Chart(document.getElementById('priorityChart'), {
            type: 'doughnut',
            data: {
                labels: ['High', 'Medium', 'Low'],
                datasets: [{
                    data: [{{ $highPriorityCount }}, {{ $mediumPriorityCount }}, {{ $lowPriorityCount }}],
                    backgroundColor: ['#e74a3b', '#f6c23e', '#1cc88a'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                cutout: '60%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            font: {
                                size: 13
                            }
                        }
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
                    backgroundColor: ['#1cc88a', '#858796'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            font: {
                                size: 13
                            }
                        }
                    }
                }
            }
        });

        // Weekly Chart
        new Chart(document.getElementById('weeklyChart'), {
            type: 'bar',
            data: {
                labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                datasets: [{
                    label: 'Tasks',
                    data: {{ json_encode($weeklyTaskCounts) }},
                    backgroundColor: '#0099ff',
                    borderRadius: 6,
                    barThickness: 40,
                    maxBarThickness: 50
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: {
                                size: 13
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 13
                            }
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
        initializeCalendar();
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
        checkbox.addEventListener('change', function() {
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
});
</script>
@endsection