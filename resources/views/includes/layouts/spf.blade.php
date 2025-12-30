<!DOCTYPE html>
<html lang="hi">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SPF Admin Dashboard')</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-w: 260px;
            --header-h: 64px;
            --footer-h: 48px;
            --navy-1: #2d3561;
            --navy-2: #1e2347;
            --muted-bg: #f3f7fb;
        }

        * {
            box-sizing: border-box
        }

        html,
        body {
            height: 100%;
            margin: 0;
            font-family: Inter, "Segoe UI", Roboto, Arial, sans-serif;
            background: var(--muted-bg);
            color: #102a43;
        }

        /* ============ SIDEBAR ============ */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: var(--sidebar-w);
            background: linear-gradient(180deg, var(--navy-1), var(--navy-2));
            color: #e6eef8;
            padding: 18px 14px;
            transition: transform .32s ease, width .32s ease;
            z-index: 1200;
            overflow: hidden;
            box-shadow: 6px 0 20px rgba(3, 12, 27, 0.25);
        }

        .sidebar.closed {
            transform: translateX(-110%);
        }

        /* Sidebar scrolling area */
        .sidebar-body {
            height: calc(100vh - 40px);
            overflow-y: auto;
            padding-bottom: 52px;
            padding-right: 6px;
        }

        .brand-compact {
            display: flex;
            align-items: center;
            gap: 10px;
            padding-bottom: 10px;
        }

        .brand-compact img {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.06);
        }

        .brand-compact .title {
            font-weight: 700;
            font-size: 14px;
            color: #ffffff;
            letter-spacing: 0.2px;
        }

        .brand-compact .sub {
            font-size: 12px;
            color: rgba(230, 238, 248, 0.8);
        }

        .sidebar .menu {
            margin-top: 14px;
        }

        .menu-section {
            margin-top: 12px;
            padding-top: 6px;
            border-top: 1px solid rgba(255, 255, 255, 0.03);
        }

        .menu .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px;
            border-radius: 8px;
            color: rgba(230, 238, 248, 0.95);
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
            background: transparent;
        }

        .menu .menu-item:hover {
            background: rgba(255, 255, 255, 0.04);
            color: #fff;
            text-decoration: none;
        }

        .menu .menu-item.active {
            background: rgba(102, 126, 234, 0.3);
            color: #fff;
        }

        .menu .menu-item i {
            width: 22px;
            text-align: center;
            font-size: 18px;
            color: #cfe8ff;
        }

        .submenu {
            padding-left: 36px;
            display: none;
        }

        .submenu.show {
            display: block;
        }

        .submenu a {
            display: block;
            padding: 8px 0;
            color: rgba(220, 230, 245, 0.9);
            text-decoration: none;
            font-weight: 500;
            font-size: 13px;
            transition: all 0.2s ease;
        }

        .submenu a:hover {
            color: #fff;
        }

        /* Sidebar scrollbar (dark theme) */
        .sidebar-body::-webkit-scrollbar {
            width: 10px;
        }

        .sidebar-body::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-body::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 8px;
            border: 2px solid transparent;
            background-clip: padding-box;
        }

        .sidebar-body::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.12);
        }

        /* ============ MAIN (to the right of sidebar) ============ */
        .main {
            margin-left: var(--sidebar-w);
            transition: margin-left .32s ease;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .main.full {
            margin-left: 0;
        }

        /* ============ TOPBAR ============ */
        .topbar {
            height: var(--header-h);
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 0 20px;
            background: #ffffff;
            border-bottom: 3px solid #000;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            position: sticky;
            top: 0;
            z-index: 1100;
        }

        .toggle-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .toggle-btn {
            width: 42px;
            height: 42px;
            border-radius: 8px;
            border: none;
            background: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 6px rgba(11, 20, 30, 0.06);
            cursor: pointer;
            font-size: 18px;
        }

        .page-heading {
            font-size: 20px;
            font-weight: 700;
            color: #0b2a44;
            margin: 0;
        }

        /* ============ CONTENT ============ */
        .content-wrap {
            padding: 20px 28px 20px 28px;
            flex: 1;
            background: var(--muted-bg);
            overflow-y: auto;
            height: calc(100vh - var(--header-h) - var(--footer-h));
        }

        /* Content scrollbar (light theme) */
        .content-wrap::-webkit-scrollbar {
            width: 10px;
        }

        .content-wrap::-webkit-scrollbar-track {
            background: transparent;
        }

        .content-wrap::-webkit-scrollbar-thumb {
            background: rgba(15, 32, 48, 0.12);
            border-radius: 8px;
            border: 2px solid transparent;
            background-clip: padding-box;
        }

        .content-wrap::-webkit-scrollbar-thumb:hover {
            background: rgba(15, 32, 48, 0.18);
        }

        /* ============ FOOTER ============ */
        .site-footer {
            height: var(--footer-h);
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            border-top: 3px solid #000;
            color: #333;
            font-size: 13px;
            font-weight: 500;
        }

        /* responsive adjustments */
        @media (max-width: 1000px) {
            :root {
                --sidebar-w: 220px;
            }
        }

        @media (max-width: 780px) {
            .sidebar {
                width: 84%;
            }

            .main {
                margin-left: 0;
            }

            .content-wrap {
                height: calc(100vh - var(--header-h) - var(--footer-h));
            }
        }

        /* Caret rotation */
        .bi-caret-down-fill {
            transition: transform 0.3s ease;
        }

        /* Modern Card Styles */
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94), box-shadow 0.3s ease;
            background: #ffffff;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
        }

        /* Modern Buttons */
        .btn {
            border-radius: 0.5rem;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            transition: all 0.2s ease;
            border: none;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* Fix for Bootstrap collapse buttons */
        .btn-blank {
            background: transparent !important;
            border: none !important;
            padding: 0 !important;
        }
    </style>

</head>

<body>

    <!-- SIDEBAR -->
    <aside id="sidebar" class="sidebar" aria-label="Sidebar">
        <div class="brand-compact">
            <img src="{{ asset('images/logo.jpeg') }}" alt="logo">
            <div>
                <div class="title">SABSJS SPF</div>
                <div class="sub">Admin Dashboard</div>
            </div>
        </div>

        <!-- Separate scrolling body inside sidebar -->
        <div class="sidebar-body">
            <nav class="menu">
                <div class="menu-section">
                    <a href="{{ url('/dashboard/spf') }}" class="menu-item">
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </div>

                <div class="menu-section">
                    <a href="{{ url('/dashboard/spf/home') }}" class="menu-item">
                        <i class="bi bi-phone-fill"></i>
                        <span>Home Screen</span>
                    </a>
                </div>

                <div class="menu-section">
                    <a href="{{ url('/dashboard/spf/committee') }}" class="menu-item">
                        <i class="bi bi-people-fill"></i>
                        <span>Committee</span>
                    </a>
                </div>

                <div class="menu-section">
                    <button
                        class="menu-item btn btn-blank w-100 d-flex justify-content-between align-items-center text-start"
                        type="button" data-bs-toggle="collapse" data-bs-target="#menuGallery" aria-expanded="false">
                        <span><i class="bi bi-images"></i> Gallery</span>
                        <i class="bi bi-caret-down-fill"></i>
                    </button>
                    <div id="menuGallery" class="submenu collapse">
                        <a href="{{ url('/spf_photo_gallery') }}">
                            <i class="bi bi-cloud-upload-fill"></i> Add Photos
                        </a>
                        <a href="{{ url('/spf_photo_gallery_view') }}">
                            <i class="bi bi-image-fill"></i> View Photos
                        </a>
                        <a href="{{ url('/dashboard/spf/slider') }}">
                            <i class="bi bi-collection-play-fill"></i> Slider
                        </a>
                    </div>
                </div>

                <div class="menu-section">
                    <a href="{{ url('/dashboard/spf/events') }}" class="menu-item">
                        <i class="bi bi-calendar-event-fill"></i>
                        <span>Events</span>
                    </a>
                </div>

                <div class="menu-section">
                    <a href="{{ url('/dashboard/spf/projects') }}" class="menu-item">
                        <i class="bi bi-kanban-fill"></i>
                        <span>Projects</span>
                    </a>
                </div>

                <div class="menu-section">
                    <a href="{{ url('/dashboard/spf/safarnama') }}" class="menu-item">
                        <i class="bi bi-file-earmark-pdf-fill"></i>
                        <span>Safarnama</span>
                    </a>
                </div>

                <div class="menu-section">
                    <a href="{{ url('/dashboard/spf/downloads') }}" class="menu-item">
                        <i class="bi bi-cloud-arrow-down-fill"></i>
                        <span>Downloads</span>
                    </a>
                </div>

                <div class="menu-section">
                    <button
                        class="menu-item btn btn-blank w-100 d-flex justify-content-between align-items-center text-start"
                        type="button" data-bs-toggle="collapse" data-bs-target="#menuNotifications"
                        aria-expanded="false">
                        <span><i class="bi bi-bell-fill"></i> Notifications</span>
                        <i class="bi bi-caret-down-fill"></i>
                    </button>
                    <div id="menuNotifications" class="submenu collapse">
                        <a href="{{ url('/send_notification-spf') }}">
                            <i class="bi bi-send-fill"></i> Send Notification
                        </a>
                        <a href="{{ url('/view_notifications_spf') }}">
                            <i class="bi bi-list-check"></i> View Notifications
                        </a>
                    </div>
                </div>

                <div class="menu-section">
                    <button
                        class="menu-item btn btn-blank w-100 d-flex justify-content-between align-items-center text-start"
                        type="button" data-bs-toggle="collapse" data-bs-target="#menuSecurity" aria-expanded="false">
                        <span><i class="bi bi-shield-lock-fill"></i> Security</span>
                        <i class="bi bi-caret-down-fill"></i>
                    </button>
                    <div id="menuSecurity" class="submenu collapse">
                        <a href="{{ url('/change-password_spf') }}">
                            <i class="bi bi-key-fill"></i> Change Password
                        </a>
                    </div>
                </div>

                <div style="height:14px"></div>

                <a href="{{ url('/logout') }}" class="menu-item">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </nav>
        </div> <!-- /.sidebar-body -->
    </aside>

    <!-- MAIN -->
    <div id="main" class="main">

        <!-- TOPBAR -->
        <header class="topbar">
            <div class="toggle-wrap">
                <button id="toggleBtn" class="toggle-btn" title="Toggle sidebar">
                    <i id="toggleIcon" class="bi bi-list"></i>
                </button>
            </div>

            <h3 class="page-heading">@yield('page-title', 'SPF Admin Dashboard')</h3>
        </header>

        <!-- CONTENT (has its own scroll) -->
        <div class="content-wrap">
            @yield('content')
        </div>

        <!-- FOOTER -->
        <footer class="site-footer">
            &copy; {{ date('Y') }} श्री अखिल भारतवर्षीय साधुमार्गी जैन संघ. केंद्र कार्यालय IT DEPARTMENT

            <!-- Info Button -->
            <button type="button" class="btn btn-sm btn-outline-dark ms-2" data-bs-toggle="modal"
                data-bs-target="#infoModal">
                <i class="bi bi-info-circle"></i>
            </button>
        </footer>

        <!-- Modal -->
        <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow">
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title" id="infoModalLabel"><i class="bi bi-person-circle"></i> Contact
                            Information</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Name:</strong> IT Department</p>
                        <p><strong>Mobile:</strong> +91 9636501008</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        (function () {
            const sidebar = document.getElementById('sidebar');
            const main = document.getElementById('main');
            const toggleBtn = document.getElementById('toggleBtn');
            const toggleIcon = document.getElementById('toggleIcon');

            toggleBtn.addEventListener('click', () => {
                const closed = sidebar.classList.toggle('closed');
                main.classList.toggle('full');
                toggleIcon.classList.toggle('bi-x-lg');
                toggleIcon.classList.toggle('bi-list');

                if (closed) {
                    document.querySelectorAll('.submenu.show').forEach(s => {
                        const bs = bootstrap.Collapse.getInstance(s);
                        if (bs) bs.hide();
                    });
                }
            });

            // rotate caret on collapse toggles for visual cue
            document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(btn => {
                btn.addEventListener('click', () => {
                    const icon = btn.querySelector('.bi-caret-down-fill');
                    const target = document.querySelector(btn.getAttribute('data-bs-target'));
                    setTimeout(() => {
                        if (target.classList.contains('show')) icon.style.transform = 'rotate(180deg)';
                        else icon.style.transform = 'rotate(0deg)';
                    }, 200);
                });
            });

            // responsive: hide sidebar by default on small screens
            function handleResize() {
                if (window.innerWidth < 780) {
                    sidebar.classList.add('closed');
                    main.classList.add('full');
                    toggleIcon.classList.remove('bi-list');
                    toggleIcon.classList.add('bi-x-lg');
                } else {
                    sidebar.classList.remove('closed');
                    main.classList.remove('full');
                    toggleIcon.classList.remove('bi-x-lg');
                    toggleIcon.classList.add('bi-list');
                }
            }
            handleResize();
            window.addEventListener('resize', handleResize);

            // Set active menu item based on current URL
            const currentUrl = window.location.href;
            document.querySelectorAll('.menu-item').forEach(item => {
                if (item.href && currentUrl.includes(item.href)) {
                    item.classList.add('active');
                }
            });

            document.querySelectorAll('.submenu a').forEach(item => {
                if (item.href && currentUrl.includes(item.href)) {
                    item.style.color = '#fff';
                    item.style.fontWeight = '600';
                    // Open parent submenu
                    const parentSubmenu = item.closest('.submenu');
                    if (parentSubmenu) {
                        parentSubmenu.classList.add('show');
                        const collapseBtn = document.querySelector(`[data-bs-target="#${parentSubmenu.id}"]`);
                        if (collapseBtn) {
                            const icon = collapseBtn.querySelector('.bi-caret-down-fill');
                            if (icon) icon.style.transform = 'rotate(180deg)';
                        }
                    }
                }
            });
        })();
    </script>

    @stack('scripts')
</body>

</html>