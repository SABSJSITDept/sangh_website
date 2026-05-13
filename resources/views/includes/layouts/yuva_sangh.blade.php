<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>@yield('title', 'Yuva Sangh Admin')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        :root {
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 88px;
            --header-height: 70px;
            --primary-color: #6366f1;
            --primary-hover: #4f46e5;
            --sidebar-bg: #0f172a;
            --sidebar-text: #94a3b8;
            --sidebar-text-active: #ffffff;
            --bg-color: #f8fafc;
            --card-bg: #ffffff;
            --border-color: #e2e8f0;
            --glass-bg: rgba(255, 255, 255, 0.8);
            --transition-speed: 0.3s;
        }

        body {
            margin: 0;
            background-color: var(--bg-color);
            color: #1e293b;
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6, .outfit-font {
            font-family: 'Outfit', sans-serif;
        }

        /* ==== Header ==== */
        .main-header {
            position: fixed;
            top: 0;
            right: 0;
            height: var(--header-height);
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            z-index: 1000;
            transition: all var(--transition-speed) cubic-bezier(0.4, 0, 0.2, 1);
            width: calc(100% - var(--sidebar-collapsed-width));
            left: var(--sidebar-collapsed-width);
        }

        .sidebar.expanded ~ .main-header {
            width: calc(100% - var(--sidebar-width));
            left: var(--sidebar-width);
        }

        .header-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #334155;
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 0;
        }

        .header-title i {
            color: var(--primary-color);
            font-size: 1.4rem;
        }

        .btn-logout {
            background: #fee2e2;
            color: #ef4444;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-logout:hover {
            background: #fecaca;
            transform: translateY(-1px);
        }

        .sidebar-toggle {
            background: #f1f5f9;
            border: none;
            color: #64748b;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .sidebar-toggle:hover {
            background: #e2e8f0;
            color: var(--primary-color);
        }

        /* ==== Sidebar ==== */
        .sidebar {
            width: var(--sidebar-collapsed-width);
            background: var(--sidebar-bg);
            color: var(--sidebar-text);
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 1100;
            transition: all var(--transition-speed) cubic-bezier(0.4, 0, 0.2, 1);
            overflow-y: auto;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.1);
        }

        .sidebar.expanded {
            width: var(--sidebar-width);
        }

        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        /* Profile Section */
        .profile-section {
            padding: 24px 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            margin-bottom: 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: all var(--transition-speed);
        }

        .profile-img-wrapper {
            position: relative;
            width: 48px;
            height: 48px;
            transition: all var(--transition-speed);
        }

        .sidebar.expanded .profile-img-wrapper {
            width: 64px;
            height: 64px;
        }

        .profile-section img {
            width: 100%;
            height: 100%;
            border-radius: 16px;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.1);
        }

        .profile-info {
            margin-top: 12px;
            text-align: center;
            opacity: 0;
            visibility: hidden;
            height: 0;
            transition: all 0.2s;
        }

        .sidebar.expanded .profile-info {
            opacity: 1;
            visibility: visible;
            height: auto;
        }

        .profile-name {
            color: #fff;
            font-weight: 600;
            font-size: 1rem;
            margin: 0;
        }

        .profile-role {
            color: var(--sidebar-text);
            font-size: 0.8rem;
            margin: 2px 0 0;
        }

        /* Navigation */
        .nav-container {
            flex: 1;
            padding: 0 12px 24px;
        }

        .nav-group-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #475569;
            margin: 20px 12px 8px;
            display: none;
        }

        .sidebar.expanded .nav-group-label {
            display: block;
        }

        .nav-item {
            margin-bottom: 4px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px;
            color: var(--sidebar-text);
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.2s;
            white-space: nowrap;
            position: relative;
            gap: 16px;
        }

        .nav-link i:first-child {
            font-size: 1.4rem;
            min-width: 24px;
            display: flex;
            justify-content: center;
            transition: all 0.2s;
        }

        .nav-link span {
            font-weight: 500;
            font-size: 0.95rem;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.2s;
        }

        .sidebar.expanded .nav-link span {
            opacity: 1;
            visibility: visible;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.05);
            color: var(--sidebar-text-active);
        }

        .nav-link.active {
            background: var(--primary-color);
            color: #fff;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .nav-link.menu-item .submenu-toggle {
            margin-left: auto;
            font-size: 0.8rem;
            transition: transform 0.3s;
            display: none;
        }

        .sidebar.expanded .nav-link.menu-item .submenu-toggle {
            display: block;
        }

        .submenu-toggle.rotate {
            transform: rotate(180deg);
        }

        /* Submenu */
        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
            padding-left: 48px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .sidebar.expanded .submenu.show {
            max-height: 500px;
            margin-top: 4px;
            margin-bottom: 8px;
        }

        .submenu .nav-link {
            padding: 8px 12px;
            font-size: 0.85rem;
            background: transparent !important;
            box-shadow: none !important;
        }

        .submenu .nav-link i {
            font-size: 1rem;
        }

        .submenu .nav-link:hover {
            color: #fff;
            padding-left: 16px;
        }

        /* ==== Main Content ==== */
        main.content {
            margin-top: var(--header-height);
            margin-left: var(--sidebar-collapsed-width);
            padding: 2rem;
            transition: all var(--transition-speed) cubic-bezier(0.4, 0, 0.2, 1);
            min-height: calc(100vh - var(--header-height));
        }

        .sidebar.expanded ~ main.content {
            margin-left: var(--sidebar-width);
        }

        /* ==== Footer ==== */
        .footer {
            margin-left: var(--sidebar-collapsed-width);
            height: 60px;
            background: #fff;
            border-top: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            color: #64748b;
            font-size: 0.85rem;
            transition: all var(--transition-speed) cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar.expanded ~ .footer {
            margin-left: var(--sidebar-width);
        }

        .footer-info {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .contact-trigger {
            color: var(--primary-color);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 500;
            cursor: pointer;
        }

        /* ==== Mobile Styling ==== */
        @media (max-width: 991px) {
            .sidebar {
                left: calc(-1 * var(--sidebar-width));
                width: var(--sidebar-width) !important;
            }

            .sidebar.mobile-show {
                left: 0;
            }

            .main-header {
                left: 0 !important;
                width: 100% !important;
                padding: 0 1rem;
            }

            main.content {
                margin-left: 0 !important;
                padding: 1.5rem 1rem;
            }

            .footer {
                margin-left: 0 !important;
                flex-direction: column;
                height: auto;
                padding: 1.5rem 1rem;
                gap: 12px;
                text-align: center;
            }

            .sidebar.expanded .nav-link span,
            .nav-link span,
            .profile-info,
            .nav-link.menu-item .submenu-toggle {
                opacity: 1;
                visibility: visible;
                height: auto;
                display: block;
            }

            .backdrop {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(15, 23, 42, 0.5);
                backdrop-filter: blur(4px);
                z-index: 1050;
            }

            .backdrop.show {
                display: block;
            }

            .header-title {
                font-size: 0.85rem;
                max-width: 180px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
        }

        /* Micro-interactions */
        .hover-scale {
            transition: transform 0.2s;
        }
        .hover-scale:hover {
            transform: scale(1.02);
        }

        /* Custom Scrollbar for Main Content */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: var(--bg-color);
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>

    @yield('jsp-header')
</head>
<body>
    
    <!-- Mobile Backdrop -->
    <div class="backdrop" id="sidebarBackdrop"></div>

    <!-- SIDEBAR -->
    <nav class="sidebar" id="sidebarMenu">
        <div class="profile-section">
            <div class="profile-img-wrapper">
                <img src="https://ui-avatars.com/api/?name=Admin&background=6366f1&color=fff" alt="Admin" />
            </div>
            <div class="profile-info">
                <p class="profile-name">Hello, Admin</p>
                <p class="profile-role">Yuva Sangh Panel</p>
            </div>
        </div>

        <div class="nav-container">
            <div class="nav-group-label">Core</div>
            <div class="nav-item">
                <a href="{{ url('/dashboard/yuva_sangh') }}" class="nav-link {{ Request::is('dashboard/yuva_sangh') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Dashboard</span>
                </a>
            </div>

            <div class="nav-group-label">Management</div>
            
            <!-- General Updates -->
            <div class="nav-item">
                <div class="nav-link menu-item" onclick="toggleSubmenu(this)">
                    <i class="bi bi-megaphone-fill"></i>
                    <span>General Updates</span>
                    <i class="bi bi-chevron-down submenu-toggle"></i>
                </div>
                <div class="submenu">
                    <a href="{{ url('/yuva_news') }}" class="nav-link">
                        <i class="bi bi-dot"></i>
                        <span>News Updates</span>
                    </a>
                    <a href="{{ url('/yuva_sangh_pravartiya') }}" class="nav-link">
                        <i class="bi bi-dot"></i>
                        <span>Pravartiya</span>
                    </a>
                    <a href="{{ url('/yuva_content') }}" class="nav-link">
                        <i class="bi bi-dot"></i>
                        <span>Content Manager</span>
                    </a>
                    <a href="{{ route('daily.panchang') }}" class="nav-link">
                        <i class="bi bi-dot"></i>
                        <span>Daily Panchang</span>
                    </a>
                </div>
            </div>

            <!-- कार्यकारिणी -->
            <div class="nav-item">
                <div class="nav-link menu-item" onclick="toggleSubmenu(this)">
                    <i class="bi bi-people-fill"></i>
                    <span>कार्यकारिणी</span>
                    <i class="bi bi-chevron-down submenu-toggle"></i>
                </div>
                <div class="submenu">
                    <a href="{{ url('/yuva_ex_president') }}" class="nav-link">
                        <i class="bi bi-dot"></i>
                        <span>पूर्व अध्यक्ष</span>
                    </a>
                    <a href="{{ url('/yuva_pst') }}" class="nav-link">
                        <i class="bi bi-dot"></i>
                        <span>PST Members</span>
                    </a>
                    <a href="{{ url('/yuva_vp_sec') }}" class="nav-link">
                        <i class="bi bi-dot"></i>
                        <span>VP/SEC सदस्य</span>
                    </a>
                </div>
            </div>

            <!-- Media & App -->
            <div class="nav-group-label">Media & App</div>
            
            <div class="nav-item">
                <div class="nav-link menu-item" onclick="toggleSubmenu(this)">
                    <i class="bi bi-images"></i>
                    <span>Photo Gallery</span>
                    <i class="bi bi-chevron-down submenu-toggle"></i>
                </div>
                <div class="submenu">
                    <a href="{{ url('/photo_gallery_yuva_sangh') }}" class="nav-link">
                        <i class="bi bi-dot"></i>
                        <span>Gallery Photos</span>
                    </a>
                    <a href="{{ url('/yuva_home_slider') }}" class="nav-link">
                        <i class="bi bi-dot"></i>
                        <span>Home Page Slider</span>
                    </a>
                    <a href="{{ url('/yuva_main_home_slider') }}" class="nav-link">
                        <i class="bi bi-dot"></i>
                        <span>Shree Sangh Slider</span>
                    </a>
                    <a href="{{ url('/yuva_mobile_slider') }}" class="nav-link">
                        <i class="bi bi-dot"></i>
                        <span>Mobile App Slider</span>
                    </a>
                </div>
            </div>

            <div class="nav-item">
                <div class="nav-link menu-item" onclick="toggleSubmenu(this)">
                    <i class="bi bi-bell-fill"></i>
                    <span>Notifications</span>
                    <i class="bi bi-chevron-down submenu-toggle"></i>
                </div>
                <div class="submenu">
                    <a href="{{ url('/send_notification-yuva_sangh') }}" class="nav-link">
                        <i class="bi bi-dot"></i>
                        <span>Send New</span>
                    </a>
                    <a href="{{ url('/view_notifications_yuva_sangh') }}" class="nav-link">
                        <i class="bi bi-dot"></i>
                        <span>History</span>
                    </a>
                </div>
            </div>

            <div class="nav-group-label">Account</div>
            <div class="nav-item">
                <a href="{{ url('/change-password_yuva_sangh') }}" class="nav-link">
                    <i class="bi bi-shield-lock-fill"></i>
                    <span>Security</span>
                </a>
            </div>

            <div class="nav-item mt-4">
                <a href="javascript:void(0)" onclick="logoutFunction()" class="nav-link text-danger">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Sign Out</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- HEADER -->
    <header class="main-header">
        <div class="d-flex align-items-center gap-3">
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>
            <h1 class="header-title d-none d-md-flex">
                <i class="bi bi-patch-check-fill"></i>
                साधुमार्गी जैन समता युवा संघ
            </h1>
            <h1 class="header-title d-md-none">
                युवा संघ एडमिन
            </h1>
        </div>

        <div class="d-flex align-items-center gap-3">
            <button class="btn-logout" onclick="logoutFunction()">
                <i class="bi bi-power"></i>
                <span class="d-none d-sm-inline">Logout</span>
            </button>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="content">
        <div class="container-fluid p-0">
            @yield('content')
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="footer">
        <div>
            Admin Panel &copy; {{ date('Y') }} | <span class="fw-bold text-dark">SABSJS IT CELL</span>
        </div>
        <div class="footer-info">
            <div class="contact-trigger" id="contactInfoBtn">
                <i class="bi bi-headset"></i> Support Helpdesk
            </div>
        </div>
    </footer>



    <script>
        const sidebar = document.getElementById('sidebarMenu');
        const toggleBtn = document.getElementById('sidebarToggle');
        const backdrop = document.getElementById('sidebarBackdrop');

        // Sidebar Toggle Logic
        toggleBtn.addEventListener('click', () => {
            const isMobile = window.innerWidth <= 991;
            if (isMobile) {
                sidebar.classList.toggle('mobile-show');
                backdrop.classList.toggle('show');
            } else {
                sidebar.classList.toggle('expanded');
                if (!sidebar.classList.contains('expanded')) {
                    document.querySelectorAll('.submenu').forEach(s => s.classList.remove('show'));
                    document.querySelectorAll('.submenu-toggle').forEach(i => i.classList.remove('rotate'));
                }
            }
        });

        backdrop.addEventListener('click', () => {
            sidebar.classList.remove('mobile-show');
            backdrop.classList.remove('show');
        });

        // Submenu Logic
        function toggleSubmenu(element) {
            const submenu = element.nextElementSibling;
            const arrow = element.querySelector('.submenu-toggle');
            const isExpanded = sidebar.classList.contains('expanded');
            const isMobile = window.innerWidth <= 991;

            if (!isExpanded && !isMobile) {
                sidebar.classList.add('expanded');
                // Small delay to let sidebar expand before showing submenu
                setTimeout(() => {
                    submenu.classList.add('show');
                    arrow.classList.add('rotate');
                }, 200);
                return;
            }

            submenu.classList.toggle('show');
            arrow.classList.toggle('rotate');
        }

        function logoutFunction() {
            if(confirm('Are you sure you want to logout?')) {
                window.location.href = "{{ route('logout') }}";
            }
        }

        // Tooltip replacement with a simple Modal/Alert for support
        document.getElementById('contactInfoBtn').addEventListener('click', () => {
            alert("Support Helpdesk:\nDeepak Acharya\nAditya Acharya\n\nCall: +91-9636501008");
        });

        // Initialize active state based on current URL if needed
        document.addEventListener('DOMContentLoaded', () => {
            const currentPath = window.location.pathname;
            document.querySelectorAll('.nav-link').forEach(link => {
                if (link.getAttribute('href') && currentPath.includes(link.getAttribute('href'))) {
                    // link.classList.add('active'); // Already handled by Blade for direct links
                }
            });
        });
    </script>
</body>
</html>
