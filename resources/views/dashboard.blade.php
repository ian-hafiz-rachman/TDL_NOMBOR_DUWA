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
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='400' height='400' viewBox='0 0 400 400'%3E%3Cstyle%3E.todo-list %7B fill: none; stroke: %230099ff; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; stroke-opacity: 0.1;%7D%3C/style%3E%3Cg class='todo-list'%3E%3Cpath d='M50 50h100v120H50z' transform='rotate(-2 100 100)' /%3E%3Cpath d='M55 70h90 M55 90h90 M55 110h90 M55 130h90' transform='rotate(-2 100 100)' /%3E%3Cpath d='M60 75l-5-5 M60 95l-5-5 M60 115l-5-5 M60 135l-5-5' transform='rotate(-2 100 100)' /%3E%3Cpath d='M200 80h80v100h-80z' transform='rotate(3 240 130)' /%3E%3Cpath d='M205 100h70 M205 120h70 M205 140h70 M205 160h70' transform='rotate(3 240 130)' /%3E%3C/g%3E%3C/svg%3E");
    }

    /* Stats Cards Styling */
    .stats-card {
        transition: none !important;
        border-radius: 15px;
        overflow: hidden;
        margin-bottom: 30px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(0, 0, 0, 0.08);
        box-shadow: none;
    }
    
    .stats-card:hover {
        transform: none !important;
        box-shadow: none !important;
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
        box-shadow: none;
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
        box-shadow: none;
        padding: 15px; /* Mengurangi padding */
        border-radius: 15px;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    /* View Container */
    .view-container {
        position: relative;
        height: 1000px !important; /* Tingkatkan lagi tinggi */
        min-height: 1000px !important;
        overflow: visible;
    }

    /* Calendar View */
    #calendarView {
        height: 100% !important;
        min-height: 1000px !important; /* Tingkatkan lagi tinggi minimum */
    }

    /* Calendar Grid */
    .fc {
        background: white !important;
        border-radius: 16px !important;
        padding: 24px !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08) !important;
        border: 1px solid #e9ecef !important;
        min-height: 1000px !important; /* Tingkatkan lagi tinggi minimum */
        height: 100% !important;
    }

    .fc .fc-toolbar.fc-header-toolbar {
        margin-bottom: 0.5rem !important; /* Mengurangi margin header */
    }

    .fc .fc-daygrid-day {
        transition: all 0.3s ease !important;
        min-height: 160px !important; /* Tingkatkan lagi tinggi minimum */
        height: 160px !important; /* Tingkatkan lagi tinggi */
    }

    .fc .fc-daygrid-day-frame {
        min-height: 160px !important; /* Tingkatkan lagi tinggi minimum */
        height: 100% !important;
    }

    .fc-view-harness {
        height: auto !important;
        min-height: 900px !important; /* Tingkatkan lagi tinggi minimum */
    }

    .fc-daygrid-body {
        min-height: 900px !important; /* Tingkatkan lagi tinggi minimum */
    }

    /* Row spacing */
    .fc-theme-standard td, .fc-theme-standard th {
        padding: 1px !important; /* Mengurangi padding antar sel */
    }

    /* Header cells */
    .fc-col-header-cell {
        padding: 6px 0 !important; /* Mengurangi padding header */
    }

    /* Row Container */
    .row {
        min-height: 1100px; /* Tingkatkan lagi tinggi minimum */
    }

    /* Action Buttons Container */
    .action-buttons {
        height: 60px; /* Tinggi tetap untuk action buttons */
        margin-bottom: var(--spacing-base);
    }

    /* Upcoming Deadlines Card */
    .upcoming-deadlines {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(0, 0, 0, 0.08);
        box-shadow: none;
        height: auto; /* Ubah dari fixed height */
        margin-top: var(--spacing-base);
    }
    
    .upcoming-deadlines .card-header {
        padding: var(--spacing-base);
        border-bottom: 1px solid rgba(0,0,0,0.05);
        position: sticky;
        top: 0;
        background: white;
        z-index: 10;
    }
    
    .upcoming-deadlines .card-body {
        padding: 0;
        height: 280px; /* Tinggi tetap untuk 4 item */
        overflow-y: auto; /* Tambahkan scroll jika konten melebihi tinggi */
    }
    
    .upcoming-deadlines .list-group {
        height: 280px; /* Sama dengan card-body */
        overflow: hidden; /* Sembunyikan overflow */
    }
    
    .upcoming-deadlines .list-group-item {
        padding: 15px;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        transition: none !important;
        height: 70px; /* Tinggi tetap untuk setiap item */
        min-height: 70px; /* Pastikan tinggi minimum */
        max-height: 70px; /* Pastikan tinggi maksimum */
        display: flex;
        align-items: center;
        box-sizing: border-box;
    }
    
    .upcoming-deadlines .list-group-item:hover {
        background-color: white !important;
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
        box-shadow: none;
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
        box-shadow: none;
        margin-bottom: 20px;
    }

    #calendar {
        height: 100% !important;
        min-height: 1000px !important; /* Tingkatkan lagi tinggi minimum */
        font-family: 'Nunito', sans-serif;
    }

    /* Stats card styles */
    .stats-card {
        transition: none !important;
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
        transform: none !important;
        box-shadow: none !important;
    }
    .stats-card .card-body {
        padding: 1.5rem;
    }
    /* Calendar Styling */
    .fc-header-toolbar {
        margin-bottom: 1rem !important;
        padding: 0 !important;
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
        transition: none !important;
    }
    .fc-daygrid-day:hover {
        background-color: white !important;
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
        box-shadow: none;
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
        height: 150px !important;
    }
    .fc-daygrid-day-frame {
        min-height: 100% !important;
        padding: 8px !important;
        display: flex !important;
        flex-direction: column !important;
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
        padding: 4px 8px !important;
        font-size: 0.85em !important;
        white-space: normal !important;
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
        box-shadow: none;
        border-radius: 15px;
        margin-bottom: var(--spacing-base);
        padding: 1.5rem;
    }

    .task-stats .card-body {
        padding: 0; /* Remove default card-body padding since we added padding to parent */
    }

    .task-stats h6 {
        font-size: 0.9rem;
        font-weight: 600;
        color: #2d3748;
    }

    .task-stats .progress {
        background-color: #f0f2f5;
        border-radius: 10px;
        margin: 1rem 0;
    }

    .task-stats .progress-bar {
        background-color: var(--primary-color);
        border-radius: 10px;
    }

    .task-stats .task-counts {
        margin-top: 1.5rem;
    }

    .task-stats .stat-item {
        text-align: center;
    }

    .task-stats .stat-item h4 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.3rem;
        color: #2d3748;
    }

    .task-stats .stat-item .text-success {
        color: var(--success-color) !important;
    }

    .task-stats .stat-item .text-warning {
        color: var(--warning-color) !important;
    }

    .task-stats .stat-item span {
        font-size: 0.85rem;
        color: #6c757d;
    }

    /* Update upcoming deadlines card styling */
    .upcoming-deadlines {
        background: white;
        border-radius: 15px;
        box-shadow: none;
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
        transition: none !important;
    }
    
    .upcoming-deadlines .list-group-item:hover {
        background-color: white !important;
    }
    
    /* Checkbox styling */
    .form-check-input {
        width: 1.2rem;
        height: 1.2rem;
        margin-top: 0.2rem;
        cursor: pointer !important;
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
        transition: none !important;
    }

    /* Update stats card styling */
    .task-stats {
        background: white;
        border-radius: 15px;
        box-shadow: none;
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
        transform: none !important;
        transition: none !important;
    }

    /* Modal styling */
    .modal-content {
        border: none;
        border-radius: 15px;
        box-shadow: none;
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
        box-shadow: none;
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
        box-shadow: none;
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
        box-shadow: none;
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
        box-shadow: none;
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
        box-shadow: none;
        transition: none !important;
    }

    .stats-card:hover {
        transform: none !important;
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
        transition: none !important;
    }

    #statisticsView {
        padding: 20px 0;
    }

    /* Tambahan style untuk kalender */
    .fc-view-harness {
        height: calc(100% - 50px) !important;
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
        min-height: 100px !important;
        height: auto !important;
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
        max-height: none !important;
        overflow: visible !important;
    }

    .fc-daygrid-more-link {
        margin: 2px 0 !important;
        padding: 2px 4px !important;
        font-size: 0.85em !important;
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
        box-shadow: none;
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
        cursor: pointer !important;
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
        box-shadow: none;
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
        transition: none !important;
    }

    .upcoming-tasks .list-group-item:hover {
        background-color: white !important;
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
        cursor: pointer !important;
        border: 2px solid #dee2e6;
    }

    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .form-check-input:focus {
        border-color: var(--primary-color);
        box-shadow: none;
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
        box-shadow: none;
    }

    .overdue-tasks .text-danger {
        font-weight: 500;
    }

    .overdue-tasks .list-group-item:hover {
        background-color: white !important;
    }

    /* Reset hover effects tapi pertahankan shadow */
    .upcoming-tasks .list-group-item:hover,
    .fc-event:hover,
    .badge:hover,
    .btn:hover,
    .form-check-input:hover {
        transform: none !important;
        background-color: inherit !important;
        cursor: default !important;
    }

    /* Pertahankan shadow pada cards */
    .stats-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        margin-bottom: 30px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05) !important;
        transition: none !important;
    }

    /* Kecuali untuk checkbox tetap bisa diklik */
    .form-check-input {
        cursor: pointer !important;
    }

    /* Reset hover pada calendar events */
    .fc-event {
        cursor: default !important;
    }

    /* Reset hover pada buttons */
    .btn {
        transition: none !important;
    }

    /* Reset hover pada list items tapi pertahankan background */
    .list-group-item {
        transition: none !important;
        background-color: white !important;
    }

    /* Reset hover pada badges */
    .badge {
        transition: none !important;
    }

    /* Pastikan shadow tetap ada dalam kondisi apapun */
    .stats-card,
    .stats-card:hover {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        margin-bottom: 30px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05) !important;
    }

    /* Reset hover effects */
    .upcoming-tasks .list-group-item:hover,
    .fc-event:hover,
    .badge:hover,
    .btn:hover,
    .form-check-input:hover {
        transform: none !important;
        background-color: inherit !important;
        cursor: default !important;
    }

    /* Kecuali untuk checkbox tetap bisa diklik */
    .form-check-input {
        cursor: pointer !important;
    }

    /* Reset hover pada calendar events */
    .fc-event {
        cursor: default !important;
    }

    /* Reset hover pada buttons */
    .btn {
        transition: none !important;
    }

    /* Reset hover pada list items */
    .list-group-item {
        transition: none !important;
        background-color: white !important;
    }

    /* Reset hover pada badges */
    .badge {
        transition: none !important;
    }

    /* Pastikan shadow tetap ada untuk semua cards */
    .upcoming-tasks,
    .upcoming-tasks:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05) !important;
    }

    /* Styling untuk cards */
    .stats-card,
    .stats-card:hover {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        margin-bottom: 30px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05) !important;
    }

    /* Reset hover effects untuk non-button elements */
    .upcoming-tasks .list-group-item:hover,
    .fc-event:hover,
    .badge:hover {
        transform: none !important;
        background-color: inherit !important;
        cursor: default !important;
    }

    /* Hover effects untuk buttons */
    .btn {
        transition: all 0.3s ease !important;
    }

    .btn:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1) !important;
    }

    /* Specific button hover effects */
    .btn-primary:hover {
        background-color: #0056b3 !important;
    }

    .btn-success:hover {
        background-color: #1e7e34 !important;
    }

    .btn-danger:hover {
        background-color: #bd2130 !important;
    }

    .btn-warning:hover {
        background-color: #d39e00 !important;
    }

    .btn-info:hover {
        background-color: #117a8b !important;
    }

    /* Toggle view buttons hover */
    #calendarViewBtn:hover,
    #statsViewBtn:hover {
        background-color: #f8f9fa !important;
        color: #0056b3 !important;
    }

    /* Add task button hover */
    .add-task-btn:hover {
        background-color: #0056b3 !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 5px 15px rgba(0, 123, 255, 0.2) !important;
    }

    /* Checkbox styling */
    .form-check-input {
        cursor: pointer !important;
    }

    /* Reset hover pada list items */
    .list-group-item {
        transition: none !important;
        background-color: white !important;
    }

    /* Reset hover pada badges */
    .badge {
        transition: none !important;
    }

    /* Pastikan shadow tetap ada */
    .upcoming-tasks,
    .upcoming-tasks:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05) !important;
    }

    /* Close button hover effect */
    .btn-close:hover {
        opacity: 1 !important;
        transform: rotate(90deg) !important;
        transition: all 0.3s ease !important;
    }

    /* Toggle view buttons styling */
    #calendarViewBtn,
    #statsViewBtn {
        padding: 10px 20px;
        border-radius: 6px;
        transition: all 0.3s ease !important;
        font-weight: 500;
        border: 1px solid #e9ecef !important;
        margin: 0 5px;
        font-size: 15px;
        min-width: 140px;  /* Menambahkan lebar minimum */
    }

    /* Default state */
    #calendarViewBtn,
    #statsViewBtn {
        background-color: #ffffff;
        color: #6c757d;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05) !important;
    }

    /* Active state untuk Calendar View */
    #calendarViewBtn.active {
        background-color: #0d6efd !important;
        color: #ffffff !important;
        font-weight: 600;
        border-color: #0d6efd !important;
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.3) !important;
    }

    /* Active state untuk Statistics View */
    #statsViewBtn.active {
        background-color: #0d6efd !important;
        color: #ffffff !important;
        font-weight: 600;
        border-color: #0d6efd !important;
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.3) !important;
    }

    /* Hover state */
    #calendarViewBtn:hover,
    #statsViewBtn:hover {
        background-color: #0b5ed7 !important;
        color: #ffffff !important;
        transform: translateY(-2px);
        border-color: #0b5ed7 !important;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.4) !important;
    }

    /* Container untuk toggle buttons */
    .view-toggle-container {
        background-color: #f8f9fa;
        padding: 6px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: inline-flex;
        gap: 10px;
    }

    /* Today button styling untuk konsistensi */
    .fc-today-button {
        padding: 8px 16px !important;
        font-size: 14px !important;
        border-radius: 6px !important;
    }

    /* Icon styling */
    #calendarViewBtn i,
    #statsViewBtn i {
        margin-right: 8px;
        font-size: 16px;
    }

    /* Styling untuk tombol navigasi kalender */
    .fc-prev-button,
    .fc-next-button,
    .fc-today-button {
        padding: 8px 16px !important;
        font-size: 14px !important;
        border-radius: 6px !important;
        background-color: #ffffff !important;
        color: #6c757d !important;
        border: 1px solid #e9ecef !important;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05) !important;
        transition: all 0.3s ease !important;
    }

    .fc-prev-button:hover,
    .fc-next-button:hover,
    .fc-today-button:hover {
        background-color: #0b5ed7 !important;
        color: #ffffff !important;
        transform: translateY(-2px);
        border-color: #0b5ed7 !important;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.4) !important;
    }

    .fc-prev-button:active,
    .fc-next-button:active,
    .fc-today-button:active {
        transform: translateY(0);
    }

    /* Pastikan icon pada tombol prev/next terlihat */
    .fc-icon {
        color: currentColor !important;
    }

    /* Calendar navigation styling */
    .fc .fc-toolbar {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        margin-bottom: 20px !important;
    }

    .fc .fc-toolbar-title {
        font-size: 20px !important;
        font-weight: 600 !important;
        margin: 0 15px !important;
    }

    .fc .fc-prev-button,
    .fc .fc-next-button {
        background-color: #fff !important;
        border: 1px solid #e9ecef !important;
        color: #6c757d !important;
        width: 36px !important;
        height: 36px !important;
        padding: 0 !important;
        border-radius: 6px !important;
    }

    .fc .fc-today-button {
        background-color: #0d6efd !important;
        border: none !important;
        padding: 8px 16px !important;
        border-radius: 6px !important;
        font-weight: 500 !important;
    }

    /* Hover effects */
    .fc .fc-prev-button:hover,
    .fc .fc-next-button:hover {
        background-color: #0b5ed7 !important;
        color: #fff !important;
        border-color: #0b5ed7 !important;
    }

    .fc .fc-today-button:hover {
        background-color: #0b5ed7 !important;
        transform: translateY(-2px);
    }

    /* Hover effects untuk task items */
    .list-group-item {
        transition: all 0.2s ease !important;
        border-radius: 8px !important;
        margin-bottom: 4px !important;
        border: 1px solid transparent !important;
    }

    .list-group-item:hover {
        transform: translateX(5px) !important;
        background-color: #f8f9fa !important;
        border-color: #e9ecef !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05) !important;
    }

    /* Badge priority styling - tanpa hover effect */
    .badge {
        transition: none !important;
        pointer-events: none !important;
    }

    /* Hover effect untuk judul tugas */
    .task-title {
        transition: color 0.2s ease !important;
    }

    .list-group-item:hover .task-title {
        color: #0d6efd !important;
    }

    /* Style untuk tanggal */
    .list-group-item small {
        transition: color 0.2s ease !important;
        color: #dc3545 !important;
        font-weight: normal !important;
        font-size: 0.875rem !important; /* Menyamakan ukuran font */
    }

    .list-group-item:hover small {
        color: #dc3545 !important;
        font-weight: 600 !important;
    }

    /* Hover effect untuk checkbox */
    .form-check-input {
        transition: all 0.2s ease !important;
        cursor: pointer !important;
    }

    .form-check-input:hover {
        border-color: #0d6efd !important;
        transform: scale(1.1) !important;
    }

    /* Mempertahankan warna background saat checked dan hover */
    .form-check-input:checked {
        background-color: #0d6efd !important;
        border-color: #0d6efd !important;
    }

    .form-check-input:checked:hover {
        background-color: #0b5ed7 !important;
        border-color: #0b5ed7 !important;
    }

    /* Container styling */
    .upcoming-tasks,
    .overdue-tasks {
        border-radius: 12px !important;
        overflow: hidden !important;
    }

    /* List group container */
    .list-group {
        padding: 8px !important;
    }

    /* Memastikan ukuran font konsisten di semua bagian */
    .upcoming-tasks small,
    .overdue-tasks small {
        font-size: 0.875rem !important; /* 14px */
        line-height: 1.5 !important;
    }

    /* Calendar Container Styling */
    .fc {
        background: white !important;
        border-radius: 16px !important;
        padding: 24px !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08) !important;
        border: 1px solid #e9ecef !important;
        min-height: 1000px !important; /* Tingkatkan lagi tinggi minimum */
        height: 100% !important;
    }

    /* Header Cells (Hari) Styling */
    .fc-col-header {
        margin-bottom: 10px !important;
    }

    .fc-col-header-cell {
        padding: 15px 0 !important;
        background: transparent !important;
        border: none !important;
    }

    .fc-col-header-cell-cushion {
        color: #2c3e50 !important;
        font-weight: 600 !important;
        font-size: 1rem !important;
        text-decoration: none !important;
        text-transform: uppercase !important;
        letter-spacing: 1px !important;
    }

    /* Day Cells Styling */
    .fc-daygrid-day {
        transition: all 0.3s ease !important;
        min-height: 160px !important; /* Tingkatkan lagi tinggi minimum */
        height: 160px !important; /* Tingkatkan lagi tinggi */
    }

    .fc-daygrid-day-frame {
        padding: 12px !important;
        border-radius: 12px !important;
        transition: all 0.3s ease !important;
    }

    .fc-daygrid-day-frame:hover {
        background-color: #f8f9fa !important;
        transform: scale(1.02) !important;
    }

    .fc-daygrid-day-number {
        font-size: 1.1rem !important;
        color: #2c3e50 !important;
        text-decoration: none !important;
        font-weight: 500 !important;
        padding: 8px !important;
    }

    /* Today Styling */
    .fc-day-today .fc-daygrid-day-frame {
        background-color: #e3efff !important;
        border-radius: 12px !important;
    }

    .fc-day-today .fc-daygrid-day-number {
        color: #0d6efd !important;
        font-weight: 700 !important;
        background: #ffffff !important;
        border-radius: 50% !important;
        width: 32px !important;
        height: 32px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        margin: 4px !important;
    }

    /* Event Styling */
    .fc-event {
        border: none !important;
        padding: 6px 12px !important;
        margin: 3px 0 !important;
        border-radius: 8px !important;
        font-size: 0.9rem !important;
        font-weight: 500 !important;
        cursor: pointer !important;
        transition: all 0.3s ease !important;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08) !important;
    }

    .fc-event:hover {
        transform: translateY(-2px) scale(1.02) !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12) !important;
    }

    /* Other Month Days */
    .fc-day-other .fc-daygrid-day-frame {
        opacity: 0.4 !important;
        background-color: #fafbfc !important;
    }

    /* Weekend Styling */
    .fc-day-sat .fc-daygrid-day-frame, 
    .fc-day-sun .fc-daygrid-day-frame {
        background-color: #f8f9fa !important;
    }

    /* Event Colors by Priority */
    .fc-event.high-priority {
        background: linear-gradient(45deg, #ff6b6b, #ff8787) !important;
        color: white !important;
    }

    .fc-event.medium-priority {
        background: linear-gradient(45deg, #ffd43b, #ffa94d) !important;
        color: #212529 !important;
    }

    .fc-event.low-priority {
        background: linear-gradient(45deg, #69db7c, #38d9a9) !important;
        color: white !important;
    }

    /* More Link Styling */
    .fc-more-link {
        background: #f8f9fa !important;
        color: #495057 !important;
        font-weight: 600 !important;
        padding: 2px 8px !important;
        border-radius: 4px !important;
        margin-top: 4px !important;
    }

    /* Border Styling */
    .fc td {
        border-color: #f1f3f5 !important;
    }

    /* Popover Styling */
    .fc-popover {
        border: none !important;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12) !important;
        border-radius: 12px !important;
        overflow: hidden !important;
        max-height: 400px !important;
        overflow-y: auto !important;
    }

    .fc-popover-header {
        background: #f8f9fa !important;
        padding: 12px 16px !important;
        font-weight: 600 !important;
        border-bottom: 1px solid #e9ecef !important;
    }

    .fc-popover-body {
        padding: 12px !important;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .fc {
            padding: 16px !important;
        }
        
        .fc-daygrid-day {
            min-height: 100px !important;
        }
        
        .fc-daygrid-day-number {
            font-size: 0.9rem !important;
        }
        
        .fc-event {
            font-size: 0.8rem !important;
            padding: 4px 8px !important;
        }
    }

    /* Overdue Tasks Card */
    .stats-card.upcoming-tasks {
        height: auto;
        margin-bottom: 20px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05) !important;
    }

    .stats-card.upcoming-tasks .card-header {
        position: sticky;
        top: 0;
        background: white;
        z-index: 10;
        padding: 15px;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }

    .stats-card.upcoming-tasks .card-body {
        padding: 15px; /* Tambahkan padding untuk card body */
    }

    /* Custom scrollbar styling */
    .card-body::-webkit-scrollbar {
        width: 6px;
    }

    .card-body::-webkit-scrollbar-track {
        background: #f8f9fc;
    }

    .card-body::-webkit-scrollbar-thumb {
        background-color: #cbd5e0;
        border-radius: 3px;
    }

    /* List item styling */
    .list-group-item {
        padding: 15px;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        transition: all 0.2s ease !important;
        min-height: 70px; /* Tinggi minimum untuk list item */
    }

    .list-group-item:last-child {
        border-bottom: none;
    }

    /* Hover effect untuk list items */
    .list-group-item:hover {
        background-color: #f8f9fa !important;
        transform: translateX(5px) !important;
    }

    /* Overdue Tasks Styling */
    .overdue-tasks-container {
        height: 350px; /* Fixed height container */
        min-height: 350px; /* Minimum height */
        max-height: 350px; /* Maximum height */
        overflow-y: auto; /* Enable vertical scrolling */
        scrollbar-width: thin;
        scrollbar-color: #cbd5e0 #f8f9fc;
    }

    .overdue-tasks-container::-webkit-scrollbar {
        width: 6px;
    }

    .overdue-tasks-container::-webkit-scrollbar-track {
        background: #f8f9fc;
    }

    .overdue-tasks-container::-webkit-scrollbar-thumb {
        background-color: #cbd5e0;
        border-radius: 3px;
    }

    /* Ensure consistent height for list items */
    .overdue-tasks-container .list-group-item {
        height: 70px;
        min-height: 70px;
        max-height: 70px;
        display: flex;
        align-items: center;
        box-sizing: border-box;
    }

    /* Empty state styling */
    .overdue-tasks-container .list-group-item .text-center {
        width: 100%;
        padding: 20px 0;
    }

    /* Empty state styling */
    .empty-state {
        height: 100%;
        min-height: 250px;
        padding: 30px 20px;
        text-align: center;
        background-color: #f9f9f9;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .empty-state:hover {
        background-color: #f0f0f0;
    }

    .empty-state i {
        opacity: 0.7;
    }

    .empty-state h6 {
        font-weight: 600;
        margin-bottom: 5px;
    }

    .empty-state p {
        max-width: 80%;
        margin: 0 auto;
    }
</style>
@endsection

@section('content')
<div class="dashboard-container">
    <div class="container-fluid">
        <!-- Calendar and Tasks Section -->
        <div class="row" style="min-height: calc(100vh - 100px);">
            <div class="col-md-8">
                <div class="task-calendar">
                    <!-- Header dengan toggle buttons -->
                    <div class="d-flex justify-content-end align-items-center mb-4">
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
                <div class="stats-card upcoming-tasks mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 shadow-sm" style="border-bottom: none; position: relative; z-index: 2;">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-pie text-primary me-2"></i> Task Statistics
                        </h5>
                    </div>
                    <div class="card-body p-0" style="position: relative; z-index: 1; margin-top: -1px;">
                        <div class="d-flex justify-content-between align-items-center mb-3 px-3 pt-3">
                            <h6 class="text-muted mb-0">Persentase Tugas</h6>
                            <h6 class="text-primary mb-0">
                                {{ number_format(($totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0), 1) }}%
                            </h6>
                        </div>
                        <div class="progress mb-3 mx-3" style="height: 10px;">
                            <div class="progress-bar bg-primary" role="progressbar" 
                                 style="width: {{ $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0 }}%"
                                 aria-valuenow="{{ $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0 }}"
                                 aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between task-counts px-3 pb-3">
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
                
                <!-- Upcoming Deadlines Card -->
                <div class="stats-card upcoming-tasks mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 shadow-sm" style="border-bottom: none; position: relative; z-index: 2;">
                        <h5 class="mb-0">
                            <i class="fas fa-hourglass-half text-info me-2"></i> Tenggat Mendatang
                        </h5>
                    </div>
                    <div class="card-body p-0" style="position: relative; z-index: 1; margin-top: -1px;">
                        <div class="list-group list-group-flush overdue-tasks-container">
                            @if($upcomingDeadlines->isEmpty())
                            <div class="d-flex flex-column align-items-center justify-content-center" style="height: 300px; padding: 20px;">
                                <i class="fas fa-calendar-check text-primary mb-3" style="font-size: 3rem;"></i>
                                <h6 class="text-muted">Tidak ada tenggat mendatang</h6>
                                <p class="text-muted small">Anda tidak memiliki tugas yang perlu dikerjakan dalam waktu dekat</p>
                            </div>
                            @else
                                @foreach($upcomingDeadlines as $task)
                                <div class="list-group-item position-relative">
                                    <div class="d-flex align-items-center">
                                        <div class="form-check me-3">
                                            <input type="checkbox" 
                                                class="form-check-input task-checkbox" 
                                                id="task-{{ $task->id }}"
                                                data-task-id="{{ $task->id }}"
                                                {{ $task->status === 'completed' ? 'checked' : '' }}>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 task-title">{{ $task->title }}</h6>
                                            <small class="text-danger">
                                                Tenggat: {{ \Carbon\Carbon::parse($task->end_date)->format('d M Y') }}
                                            </small>
                                        </div>
                                    </div>
                                    <span class="badge bg-{{ $task->priority === 'high' ? 'danger' : ($task->priority === 'medium' ? 'warning' : 'success') }} position-absolute" style="right: 15px; top: 50%; transform: translateY(-50%);">
                                        {{ $task->priority === 'high' ? 'Tinggi' : ($task->priority === 'medium' ? 'Sedang' : 'Rendah') }}
                                    </span>
                                </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Overdue Tasks Card -->
                <div class="stats-card upcoming-tasks">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 shadow-sm" style="border-bottom: none; position: relative; z-index: 2;">
                        <h5 class="mb-0">
                            <i class="fas fa-exclamation-circle text-danger me-2"></i> Tugas Terlewat
                        </h5>
                    </div>
                    <div class="card-body p-0" style="position: relative; z-index: 1; margin-top: -1px;">
                        <div class="list-group list-group-flush overdue-tasks-container">
                            @if($overdueTasks->isEmpty())
                            <div class="d-flex flex-column align-items-center justify-content-center" style="height: 300px; padding: 20px;">
                                <i class="fas fa-trophy text-warning mb-3" style="font-size: 3rem;"></i>
                                <h6 class="text-muted">Tidak ada tugas terlewat</h6>
                                <p class="text-muted small">Semua tugas Anda sudah dikerjakan tepat waktu!</p>
                            </div>
                            @else
                                @foreach($overdueTasks as $task)
                                <div class="list-group-item position-relative">
                                    <div class="d-flex align-items-center">
                                        <div class="form-check me-3">
                                            <input type="checkbox" 
                                                class="form-check-input task-checkbox" 
                                                id="task-{{ $task->id }}"
                                                data-task-id="{{ $task->id }}"
                                                {{ $task->status === 'completed' ? 'checked' : '' }}>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 task-title">{{ $task->title }}</h6>
                                            <small class="text-danger">
                                                Tenggat: {{ \Carbon\Carbon::parse($task->end_date)->format('d M Y') }}
                                            </small>
                                        </div>
                                    </div>
                                    <span class="badge bg-{{ $task->priority === 'high' ? 'danger' : ($task->priority === 'medium' ? 'warning' : 'success') }} position-absolute" style="right: 15px; top: 50%; transform: translateY(-50%);">
                                        {{ $task->priority === 'high' ? 'Tinggi' : ($task->priority === 'medium' ? 'Sedang' : 'Rendah') }}
                                    </span>
                                </div>
                                @endforeach
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
                dayMaxEvents: false,
                eventDisplay: 'block',
                displayEventTime: false,
                firstDay: 1,
                // Styling untuk calendar
                dayCellClassNames: 'calendar-day',
                dayHeaderClassNames: 'calendar-header',
                viewClassNames: 'calendar-view'
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