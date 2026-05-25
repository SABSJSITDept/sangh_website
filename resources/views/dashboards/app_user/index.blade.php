@extends('includes.layouts.super_admin')

@section('title', 'App User Dashboard | Overview')

@section('content')
<div class="container-fluid px-0">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-end mb-5">
        <div>
            <h2 class="fw-bold mb-1">App User Dashboard</h2>
            <p class="text-muted mb-0">Manage application logs, registrations, versions, and notifications.</p>
        </div>
        <div class="d-none d-md-block">
            <div class="glass-card px-4 py-2 d-flex align-items-center">
                <i class="bi bi-calendar3 me-2 text-primary"></i>
                <span class="fw-medium text-dark">{{ date('D, M d, Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Quick Actions / Entities Grid -->
    <h4 class="fw-bold mb-4">Management Modules</h4>
    <div class="row g-4 mb-5">
        <!-- App Open Logs -->
        <div class="col-lg-4 col-md-6">
            <a href="{{ url('/app-opens-dashboard') }}" class="text-decoration-none h-100 d-block">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden entity-card">
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <div class="icon-shape bg-primary bg-opacity-10 rounded-4 text-primary p-3 d-inline-flex align-items-center justify-content-center">
                                <i class="bi bi-phone-vibrate fs-2"></i>
                            </div>
                        </div>
                        <h5 class="fw-bold text-dark">App Open Logs</h5>
                        <p class="text-muted small">Monitor real-time logs when members open the mobile application.</p>
                        <div class="d-flex align-items-center text-primary fw-bold mt-auto">
                            Go to Logs <i class="bi bi-arrow-right ms-2"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- App Registrations -->
        <div class="col-lg-4 col-md-6">
            <a href="{{ url('/app-registration') }}" class="text-decoration-none h-100 d-block">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden entity-card">
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <div class="icon-shape bg-success bg-opacity-10 rounded-4 text-success p-3 d-inline-flex align-items-center justify-content-center">
                                <i class="bi bi-person-plus-fill fs-2"></i>
                            </div>
                        </div>
                        <h5 class="fw-bold text-dark">App Registrations</h5>
                        <p class="text-muted small">Add and manage mobile application user registrations and credentials.</p>
                        <div class="d-flex align-items-center text-success fw-bold mt-auto">
                            Manage Registrations <i class="bi bi-arrow-right ms-2"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Notifications -->
        <div class="col-lg-4 col-md-6">
            <a href="{{ url('/send_notification-form') }}" class="text-decoration-none h-100 d-block">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden entity-card">
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <div class="icon-shape bg-info bg-opacity-10 rounded-4 text-info p-3 d-inline-flex align-items-center justify-content-center">
                                <i class="bi bi-megaphone-fill fs-2"></i>
                            </div>
                        </div>
                        <h5 class="fw-bold text-dark">Broadcast Notifications</h5>
                        <p class="text-muted small">Send push notifications to mobile application user groups and view history.</p>
                        <div class="d-flex align-items-center text-info fw-bold mt-auto">
                            Send Notifications <i class="bi bi-arrow-right ms-2"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- App Versions -->
        <div class="col-lg-4 col-md-6">
            <a href="{{ url('/mobile_app_version') }}" class="text-decoration-none h-100 d-block">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden entity-card">
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <div class="icon-shape bg-purple bg-opacity-10 rounded-4 text-purple p-3 d-inline-flex align-items-center justify-content-center">
                                <i class="bi bi-git fs-2"></i>
                            </div>
                        </div>
                        <h5 class="fw-bold text-dark">App Versions</h5>
                        <p class="text-muted small">Update and manage Android/iOS application build versions and release logs.</p>
                        <div class="d-flex align-items-center text-purple fw-bold mt-auto">
                            Manage Versions <i class="bi bi-arrow-right ms-2"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- System Status -->
        <div class="col-lg-4 col-md-6">
            <a href="{{ url('/status') }}" class="text-decoration-none h-100 d-block">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden entity-card">
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <div class="icon-shape bg-warning bg-opacity-10 rounded-4 text-warning p-3 d-inline-flex align-items-center justify-content-center">
                                <i class="bi bi-check-circle-fill fs-2"></i>
                            </div>
                        </div>
                        <h5 class="fw-bold text-dark">System Status</h5>
                        <p class="text-muted small">Check server health, database connectivity, and backend system status.</p>
                        <div class="d-flex align-items-center text-warning fw-bold mt-auto">
                            Check Status <i class="bi bi-arrow-right ms-2"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Support Info -->
        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-dark text-white p-4">
                <h5 class="fw-bold mb-3">Quick Links</h5>
                <div class="d-grid gap-3">
                    <a href="{{ url('/registration-status') }}" class="btn btn-outline-light border-0 bg-white bg-opacity-10 text-start py-2 px-3 rounded-3">
                        <i class="bi bi-card-checklist me-2"></i> Registration Status List
                    </a>
                    <a href="{{ url('/view_notifications_all') }}" class="btn btn-outline-light border-0 bg-white bg-opacity-10 text-start py-2 px-3 rounded-3">
                        <i class="bi bi-clock-history me-2"></i> Notification History
                    </a>
                    <a href="{{ url('/change-password_app_user') }}" class="btn btn-outline-light border-0 bg-white bg-opacity-10 text-start py-2 px-3 rounded-3">
                        <i class="bi bi-key me-2"></i> Change Password
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-indigo { background-color: #6366f1 !important; }
    .text-indigo { color: #6366f1 !important; }
    .bg-purple { background-color: #a855f7 !important; }
    .text-purple { color: #a855f7 !important; }
    
    .entity-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid #f1f5f9 !important;
    }

    .entity-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
        border-color: #e2e8f0 !important;
    }

    .icon-shape {
        width: 64px;
        height: 64px;
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        border-radius: 20px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection
