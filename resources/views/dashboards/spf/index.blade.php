@extends('includes.layouts.spf')

@section('title', 'SPF Dashboard')

@section('content')
    <style>
        .dashboard-card {
            transition: all 0.3s ease;
            border: none;
            border-radius: 15px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            height: 100%;
            position: relative;
            z-index: 1;
        }

        .dashboard-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: var(--card-gradient);
            transition: height 0.3s ease;
            z-index: -1;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .dashboard-card:hover::before {
            height: 100%;
        }

        .dashboard-card:hover .card-icon {
            color: #fff !important;
            transform: scale(1.1);
        }

        .dashboard-card:hover .card-title,
        .dashboard-card:hover .card-text {
            color: #fff !important;
        }

        .dashboard-card:hover .btn-action {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            border-color: rgba(255, 255, 255, 0.5);
        }

        .card-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .btn-action {
            border-radius: 50px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        /* Gradients */
        .card-home {
            --card-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .card-committee {
            --card-gradient: linear-gradient(135deg, #ff9a9e 0%, #fecfef 99%, #fecfef 100%);
        }

        /* Adjusted for better contrast on hover, maybe darker? Let's try a different one */
        .card-committee-fix {
            --card-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .card-gallery {
            --card-gradient: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
        }

        /* Light, might need dark text on hover? No, let's use darker gradients for white text */
        .card-gallery-fix {
            --card-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }

        /* Still light. Let's go with deep colors */
        .card-gallery-deep {
            --card-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }

        .card-events {
            --card-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        }

        .card-projects {
            --card-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .card-password {
            --card-gradient: linear-gradient(135deg, #434343 0%, #000000 100%);
        }

        /* Text colors for initial state */
        .text-home {
            color: #667eea;
        }

        .text-committee {
            color: #f5576c;
        }

        .text-gallery {
            color: #11998e;
        }

        .text-events {
            color: #fa709a;
        }

        .text-projects {
            color: #4facfe;
        }

        .text-password {
            color: #434343;
        }
    </style>

    <div class="container-fluid py-4 px-4">

        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="fw-bold mb-1" style="color: #2c3e50;">Dashboard Overview</h2>
                <p class="text-muted mb-0">Welcome back, manage your SPF content efficiently.</p>
            </div>
            <div class="date-display text-muted">
                <i class="bi bi-calendar3 me-2"></i> {{ date('l, F j, Y') }}
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">

            <!-- Home Screen Updates -->
            <div class="col">
                <div class="card dashboard-card card-home p-4">
                    <div class="card-body text-center d-flex flex-column align-items-center justify-content-center">
                        <i class="bi bi-display card-icon text-home"></i>
                        <h4 class="card-title fw-bold mb-2 text-dark">Home Screen</h4>
                        <p class="card-text text-muted mb-4">Manage updates and content for the home screen.</p>
                        <a href="{{ url('/dashboard/spf/home') }}"
                            class="btn btn-outline-primary btn-action stretched-link">Manage Updates</a>
                    </div>
                </div>
            </div>

            <!-- SPF Committee -->
            <div class="col">
                <div class="card dashboard-card card-committee-fix p-4">
                    <div class="card-body text-center d-flex flex-column align-items-center justify-content-center">
                        <i class="bi bi-people-fill card-icon text-committee"></i>
                        <h4 class="card-title fw-bold mb-2 text-dark">SPF Committee</h4>
                        <p class="card-text text-muted mb-4">View and manage committee members and details.</p>
                        <a href="{{ url('/dashboard/spf/committee') }}"
                            class="btn btn-outline-danger btn-action stretched-link">Manage Committee</a>
                    </div>
                </div>
            </div>

            <!-- Gallery -->
            <div class="col">
                <div class="card dashboard-card card-gallery-deep p-4">
                    <div class="card-body text-center d-flex flex-column align-items-center justify-content-center">
                        <i class="bi bi-images card-icon text-gallery"></i>
                        <h4 class="card-title fw-bold mb-2 text-dark">Photo Gallery</h4>
                        <p class="card-text text-muted mb-4">Upload new photos or view existing gallery.</p>
                        <div class="d-flex gap-2 position-relative" style="z-index: 2;">
                            <a href="{{ url('/spf_photo_gallery') }}" class="btn btn-outline-success btn-action">Add
                                Photos</a>
                            <a href="{{ url('/spf_photo_gallery_view') }}" class="btn btn-outline-success btn-action">View
                                Gallery</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Events -->
            <div class="col">
                <div class="card dashboard-card card-events p-4">
                    <div class="card-body text-center d-flex flex-column align-items-center justify-content-center">
                        <i class="bi bi-calendar-event card-icon text-events"></i>
                        <h4 class="card-title fw-bold mb-2 text-dark">Events</h4>
                        <p class="card-text text-muted mb-4">Schedule and manage upcoming SPF events.</p>
                        <a href="{{ url('/dashboard/spf/events') }}"
                            class="btn btn-outline-warning btn-action stretched-link">Manage Events</a>
                    </div>
                </div>
            </div>

            <!-- Projects -->
            <div class="col">
                <div class="card dashboard-card card-projects p-4">
                    <div class="card-body text-center d-flex flex-column align-items-center justify-content-center">
                        <i class="bi bi-briefcase-fill card-icon text-projects"></i>
                        <h4 class="card-title fw-bold mb-2 text-dark">Projects</h4>
                        <p class="card-text text-muted mb-4">Track and update ongoing SPF projects.</p>
                        <a href="{{ url('/dashboard/spf/projects') }}"
                            class="btn btn-outline-info btn-action stretched-link">Manage Projects</a>
                    </div>
                </div>
            </div>

            <!-- Change Password -->
            <div class="col">
                <div class="card dashboard-card card-password p-4">
                    <div class="card-body text-center d-flex flex-column align-items-center justify-content-center">
                        <i class="bi bi-shield-lock-fill card-icon text-password"></i>
                        <h4 class="card-title fw-bold mb-2 text-dark">Security</h4>
                        <p class="card-text text-muted mb-4">Update your password and security settings.</p>
                        <a href="{{ url('/change-password_spf') }}"
                            class="btn btn-outline-dark btn-action stretched-link">Change Password</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection