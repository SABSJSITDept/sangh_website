@extends('includes.layouts.yuva_sangh')

@section('title', 'Yuva Sangh Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 1.5rem; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);">
                <div class="card-body p-4 p-md-5 text-white position-relative">
                    <div class="row align-items-center">
                        <div class="col-lg-8 position-relative" style="z-index: 1;">
                            <h1 class="display-6 fw-bold mb-2 outfit-font">Welcome back, Admin! 👋</h1>
                            <p class="lead mb-4 opacity-75">Manage your Yuva Sangh activities, news, and members from this central command center.</p>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ url('/yuva_news') }}" class="btn btn-light rounded-pill px-4 fw-600 shadow-sm">
                                    <i class="bi bi-plus-lg me-2"></i> Post New Update
                                </a>
                                <a href="{{ url('/photo_gallery_yuva_sangh') }}" class="btn btn-outline-light rounded-pill px-4 fw-600">
                                    <i class="bi bi-image me-2"></i> Upload Gallery
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-4 d-none d-lg-block text-end position-relative" style="z-index: 1;">
                            <i class="bi bi-buildings opacity-25" style="font-size: 8rem;"></i>
                        </div>
                    </div>
                    <!-- Decorative background elements -->
                    <div class="position-absolute top-0 end-0 p-5 opacity-10">
                        <i class="bi bi-hexagon-fill display-1"></i>
                    </div>
                    <div class="position-absolute bottom-0 start-0 p-4 opacity-10">
                        <i class="bi bi-circle-fill display-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Section (Clickable Cards) -->
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xl-3">
            <a href="{{ url('/yuva_news') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 hover-scale cursor-pointer" style="border-radius: 1rem;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="bg-primary-subtle p-3 rounded-4">
                                <i class="bi bi-newspaper text-primary fs-4"></i>
                            </div>
                            <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">Live</span>
                        </div>
                        <h3 class="fw-bold outfit-font mb-1 text-dark">News</h3>
                        <p class="text-muted small mb-0">Latest community updates</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-xl-3">
            <a href="{{ url('/photo_gallery_yuva_sangh') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 hover-scale cursor-pointer" style="border-radius: 1rem;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="bg-info-subtle p-3 rounded-4">
                                <i class="bi bi-images text-info fs-4"></i>
                            </div>
                            <span class="badge bg-info-subtle text-info rounded-pill px-3 py-2">Photos</span>
                        </div>
                        <h3 class="fw-bold outfit-font mb-1 text-dark">Gallery</h3>
                        <p class="text-muted small mb-0">Capture memories</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-xl-3">
            <a href="{{ url('/yuva_pst') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 hover-scale cursor-pointer" style="border-radius: 1rem;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="bg-warning-subtle p-3 rounded-4">
                                <i class="bi bi-people-fill text-warning fs-4"></i>
                            </div>
                            <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2">Members</span>
                        </div>
                        <h3 class="fw-bold outfit-font mb-1 text-dark">PST/Exec</h3>
                        <p class="text-muted small mb-0">Management team</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-xl-3">
            <a href="{{ url('/view_notifications_yuva_sangh') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 hover-scale cursor-pointer" style="border-radius: 1rem;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="bg-danger-subtle p-3 rounded-4">
                                <i class="bi bi-bell-fill text-danger fs-4"></i>
                            </div>
                            <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2">App</span>
                        </div>
                        <h3 class="fw-bold outfit-font mb-1 text-dark">Notify</h3>
                        <p class="text-muted small mb-0">Send app alerts</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="row g-4">
        <!-- Quick Links -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 1.25rem;">
                <div class="card-header bg-transparent border-0 p-4">
                    <h5 class="fw-bold outfit-font mb-0">Quick Management Links</h5>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="row g-3">
                        <div class="col-6">
                            <a href="{{ url('/yuva_sangh_pravartiya') }}" class="text-decoration-none">
                                <div class="p-3 border rounded-4 text-center hover-bg-light transition-all h-100 d-flex flex-column align-items-center justify-content-center">
                                    <i class="bi bi-broadcast fs-3 text-primary d-block mb-2"></i>
                                    <span class="text-dark fw-medium small">Pravartiya</span>
                                </div>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ url('/yuva_pst') }}" class="text-decoration-none">
                                <div class="p-3 border rounded-4 text-center hover-bg-light transition-all h-100 d-flex flex-column align-items-center justify-content-center">
                                    <i class="bi bi-person-badge fs-3 text-indigo d-block mb-2"></i>
                                    <span class="text-dark fw-medium small">PST Details</span>
                                </div>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ url('/yuva_mobile_slider') }}" class="text-decoration-none">
                                <div class="p-3 border rounded-4 text-center hover-bg-light transition-all h-100 d-flex flex-column align-items-center justify-content-center">
                                    <i class="bi bi-phone fs-3 text-success d-block mb-2"></i>
                                    <span class="text-dark fw-medium small">App Slider</span>
                                </div>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ url('/view_notifications_yuva_sangh') }}" class="text-decoration-none">
                                <div class="p-3 border rounded-4 text-center hover-bg-light transition-all h-100 d-flex flex-column align-items-center justify-content-center">
                                    <i class="bi bi-eye fs-3 text-secondary d-block mb-2"></i>
                                    <span class="text-dark fw-medium small">View Notify</span>
                                </div>
                            </a>
                        </div>
                    </div>

                    <hr class="my-4 opacity-5">

                    <div class="bg-light p-3 rounded-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-white p-2 rounded-3 shadow-sm">
                                <i class="bi bi-calendar-check text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 outfit-font fw-bold">दैनिक पंचांग</h6>
                                <p class="small text-muted mb-0">Update daily panchang details</p>
                            </div>
                            <a href="{{ route('daily.panchang') }}" class="ms-auto btn btn-primary btn-sm rounded-pill px-3 shadow-sm">Manage</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity / Info -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 1.25rem;">
                <div class="card-header bg-transparent border-0 p-4">
                    <h5 class="fw-bold outfit-font mb-0">System Information</h5>
                </div>
                <div class="card-body p-4 pt-0">
                    <ul class="list-unstyled mb-0">
                        <li class="d-flex align-items-start gap-3 mb-4">
                            <div class="bg-success-subtle p-2 rounded-circle">
                                <i class="bi bi-check2 text-success"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 outfit-font fw-bold">Last News Update</h6>
                                <p class="small text-muted mb-0">The portal news section is synced with the mobile application.</p>
                            </div>
                        </li>
                        <li class="d-flex align-items-start gap-3 mb-4">
                            <div class="bg-primary-subtle p-2 rounded-circle">
                                <i class="bi bi-info-circle text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 outfit-font fw-bold">Photo Optimization</h6>
                                <p class="small text-muted mb-0">Recommended photo size for home sliders is 1920x1080px for best quality.</p>
                            </div>
                        </li>
                        <li class="d-flex align-items-start gap-3">
                            <div class="bg-warning-subtle p-2 rounded-circle">
                                <i class="bi bi-shield-lock text-warning"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 outfit-font fw-bold">Security Tip</h6>
                                <p class="small text-muted mb-0">Don't forget to update your password regularly from the Account section.</p>
                            </div>
                        </li>
                    </ul>

                    <div class="mt-4 p-4 rounded-4" style="background: #f8fafc; border: 1px dashed #e2e8f0;">
                        <p class="text-center small text-muted mb-0">
                            Need help with the admin panel?<br>
                            <span class="fw-bold text-dark">Contact IT Support at +91-9636501008</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-bg-light:hover {
        background-color: #f1f5f9 !important;
        border-color: #6366f1 !important;
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    .cursor-pointer {
        cursor: pointer;
    }
    .transition-all {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .fw-600 {
        font-weight: 600;
    }
    .text-indigo {
        color: #4f46e5;
    }
    .hover-scale:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
    }
</style>
@endsection
