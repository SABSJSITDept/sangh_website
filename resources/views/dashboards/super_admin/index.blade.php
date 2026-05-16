@extends('includes.layouts.super_admin')

@section('title', 'Super Admin Dashboard | Overview')

@section('content')
<div class="container-fluid px-0">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-end mb-5">
        <div>
            <h2 class="fw-bold mb-1">Super Admin Dashboard</h2>
            <p class="text-muted mb-0">Welcome back, Admin. Here's what's happening today.</p>
        </div>
        <div class="d-none d-md-block">
            <div class="glass-card px-4 py-2 d-flex align-items-center">
                <i class="bi bi-calendar3 me-2 text-primary"></i>
                <span class="fw-medium text-dark">{{ date('D, M d, Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Quick Stats Row -->
    <div class="row g-4 mb-5">
        <div class="col-xl-3 col-md-6">
            <div class="glass-card p-4 h-100 border-start border-4 border-primary">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted small text-uppercase fw-bold mb-1">Total Members</p>
                        <h3 class="fw-bold mb-0">12,450</h3>
                        <span class="text-success small fw-medium"><i class="bi bi-arrow-up"></i> 12% increase</span>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded-3">
                        <i class="bi bi-people-fill text-primary fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="glass-card p-4 h-100 border-start border-4 border-success">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted small text-uppercase fw-bold mb-1">Active Centers</p>
                        <h3 class="fw-bold mb-0">84</h3>
                        <span class="text-muted small fw-medium">Across all states</span>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded-3">
                        <i class="bi bi-geo-alt-fill text-success fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="glass-card p-4 h-100 border-start border-4 border-warning">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted small text-uppercase fw-bold mb-1">Sahitya Pubs</p>
                        <h3 class="fw-bold mb-0">342</h3>
                        <span class="text-warning small fw-medium">12 Pending review</span>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-3 rounded-3">
                        <i class="bi bi-book-half text-warning fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="glass-card p-4 h-100 border-start border-4 border-info">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted small text-uppercase fw-bold mb-1">App Users</p>
                        <h3 class="fw-bold mb-0">5,210</h3>
                        <span class="text-info small fw-medium">v2.1.0 Latest</span>
                    </div>
                    <div class="bg-info bg-opacity-10 p-3 rounded-3">
                        <i class="bi bi-phone-fill text-info fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Entities Grid -->
    <h4 class="fw-bold mb-4">Organizational Units</h4>
    <div class="row g-4 mb-5">
        <!-- Sahitya -->
        <div class="col-lg-4 col-md-6">
            <a href="{{ route('dashboard.sahitya') }}" class="text-decoration-none h-100 d-block">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden entity-card">
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <div class="icon-shape bg-indigo bg-opacity-10 rounded-4 text-indigo p-3 d-inline-flex align-items-center justify-content-center">
                                <i class="bi bi-journal-bookmark-fill fs-2"></i>
                            </div>
                        </div>
                        <h5 class="fw-bold text-dark">Shramnopasak</h5>
                        <p class="text-muted small">Manage publications, articles, and literary content for Shramnopasak.</p>
                        <div class="d-flex align-items-center text-indigo fw-bold mt-auto">
                            Go to Dashboard <i class="bi bi-arrow-right ms-2"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Sahitya Publication -->
        <div class="col-lg-4 col-md-6">
            <a href="{{ route('dashboard.sahitya_publication') }}" class="text-decoration-none h-100 d-block">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden entity-card">
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <div class="icon-shape bg-purple bg-opacity-10 rounded-4 text-purple p-3 d-inline-flex align-items-center justify-content-center">
                                <i class="bi bi-book-half fs-2"></i>
                            </div>
                        </div>
                        <h5 class="fw-bold text-dark">Sahitya Publication</h5>
                        <p class="text-muted small">Access and monitor all publication activities and distributions.</p>
                        <div class="d-flex align-items-center text-purple fw-bold mt-auto">
                            Go to Dashboard <i class="bi bi-arrow-right ms-2"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Yuva Sangh -->
        <div class="col-lg-4 col-md-6">
            <a href="{{ route('dashboard.yuva_sangh') }}" class="text-decoration-none h-100 d-block">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden entity-card">
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <div class="icon-shape bg-orange bg-opacity-10 rounded-4 text-orange p-3 d-inline-flex align-items-center justify-content-center">
                                <i class="bi bi-person-workspace fs-2"></i>
                            </div>
                        </div>
                        <h5 class="fw-bold text-dark">Yuva Sangh</h5>
                        <p class="text-muted small">Manage youth activities, events, and member registrations.</p>
                        <div class="d-flex align-items-center text-orange fw-bold mt-auto">
                            Go to Dashboard <i class="bi bi-arrow-right ms-2"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Mahila Samiti -->
        <div class="col-lg-4 col-md-6">
            <a href="{{ route('dashboard.mahila_samiti') }}" class="text-decoration-none h-100 d-block">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden entity-card">
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <div class="icon-shape bg-rose bg-opacity-10 rounded-4 text-rose p-3 d-inline-flex align-items-center justify-content-center">
                                <i class="bi bi-people-fill fs-2"></i>
                            </div>
                        </div>
                        <h5 class="fw-bold text-dark">Mahila Samiti</h5>
                        <p class="text-muted small">Oversee Mahila Samiti operations and membership records.</p>
                        <div class="d-flex align-items-center text-rose fw-bold mt-auto">
                            Go to Dashboard <i class="bi bi-arrow-right ms-2"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Shree Sangh -->
        <div class="col-lg-4 col-md-6">
            <a href="{{ route('dashboard.shree_sangh') }}" class="text-decoration-none h-100 d-block">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden entity-card">
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <div class="icon-shape bg-amber bg-opacity-10 rounded-4 text-amber p-3 d-inline-flex align-items-center justify-content-center">
                                <i class="bi bi-bank fs-2"></i>
                            </div>
                        </div>
                        <h5 class="fw-bold text-dark">Shree Sangh</h5>
                        <p class="text-muted small">Central administrative portal for main Sangh activities.</p>
                        <div class="d-flex align-items-center text-amber fw-bold mt-auto">
                            Go to Dashboard <i class="bi bi-arrow-right ms-2"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-dark text-white p-4">
                <h5 class="fw-bold mb-3">Quick Actions</h5>
                <div class="d-grid gap-3">
                    <a href="{{ url('/send_notification-form') }}" class="btn btn-outline-light border-0 bg-white bg-opacity-10 text-start py-2 px-3 rounded-3">
                        <i class="bi bi-send me-2"></i> Send Notification
                    </a>
                    <a href="{{ url('/app-registration') }}" class="btn btn-outline-light border-0 bg-white bg-opacity-10 text-start py-2 px-3 rounded-3">
                        <i class="bi bi-person-plus me-2"></i> Register New App
                    </a>
                    <a href="{{ url('/status') }}" class="btn btn-outline-light border-0 bg-white bg-opacity-10 text-start py-2 px-3 rounded-3">
                        <i class="bi bi-shield-check me-2"></i> Check System Status
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
    .bg-orange { background-color: #f97316 !important; }
    .text-orange { color: #f97316 !important; }
    .bg-rose { background-color: #f43f5e !important; }
    .text-rose { color: #f43f5e !important; }
    .bg-amber { background-color: #f59e0b !important; }
    .text-amber { color: #f59e0b !important; }

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
