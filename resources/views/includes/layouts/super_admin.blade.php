<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>@yield('title', 'Super Admin Dashboard | Sadhumargi Jain Sangh')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts: Inter & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

    <style>
        :root {
            --primary-color: #f59e0b; /* Saffron/Amber */
            --primary-hover: #d97706;
            --bg-body: #f8fafc;
            --sidebar-bg: #0f172a; /* Deep Slate */
            --sidebar-width: 280px;
            --sidebar-collapsed: 85px;
            --header-height: 70px;
            --card-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            color: #1e293b;
            margin: 0;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, .site-title {
            font-family: 'Outfit', sans-serif;
        }

        /* Layout Structure */
        .layout-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styling */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1050;
            transition: var(--transition);
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed);
        }

        .sidebar-logo {
            height: var(--header-height);
            display: flex;
            align-items: center;
            padding: 0 25px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            overflow: hidden;
            white-space: nowrap;
        }

        .sidebar-logo img {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            margin-right: 12px;
        }

        .sidebar-logo span {
            color: #fff;
            font-weight: 700;
            font-size: 1.2rem;
            letter-spacing: 0.5px;
        }

        .sidebar-content {
            flex: 1;
            overflow-y: auto;
            padding: 20px 15px;
        }

        .sidebar-content::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-content::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
        }

        /* Nav Links */
        .nav-group-label {
            color: #64748b;
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 700;
            margin: 20px 10px 10px;
            letter-spacing: 1px;
            display: block;
        }

        .sidebar.collapsed .nav-group-label {
            display: none;
        }

        .nav-item {
            margin-bottom: 5px;
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: #94a3b8 !important;
            border-radius: 12px;
            text-decoration: none;
            transition: var(--transition);
            white-space: nowrap;
            position: relative;
        }

        .sidebar .nav-link i {
            font-size: 1.25rem;
            min-width: 35px;
            transition: var(--transition);
        }

        .sidebar .nav-link span {
            font-weight: 500;
            font-size: 0.95rem;
            opacity: 1;
            transition: var(--transition);
        }

        .sidebar.collapsed .nav-link span {
            opacity: 0;
            width: 0;
        }

        .sidebar .nav-link:hover {
            color: #fff !important;
            background: rgba(255,255,255,0.05);
        }

        .sidebar .nav-link.active {
            color: #fff !important;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }

        .sidebar .nav-link.active i {
            color: #fff !important;
        }

        .sidebar .nav-link.text-danger {
            color: #ef4444 !important;
        }

        /* Submenu */
        .submenu {
            list-style: none;
            padding-left: 35px;
            margin: 5px 0;
            display: none;
        }

        .submenu.show {
            display: block;
        }

        .submenu .nav-link {
            padding: 8px 15px;
            font-size: 0.85rem;
        }

        .submenu-arrow {
            margin-left: auto;
            font-size: 0.8rem;
            transition: var(--transition);
        }

        .rotate-arrow {
            transform: rotate(180deg);
        }

        /* Collapsed Sidebar Adjustments */
        .sidebar.collapsed .sidebar-logo span {
            display: none;
        }
        .sidebar.collapsed .submenu-arrow {
            display: none !important;
        }
        .sidebar.collapsed .submenu {
            display: none !important;
        }

        /* Header Styling */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            transition: var(--transition);
            min-width: 0;
        }

        .main-content.expanded {
            margin-left: var(--sidebar-collapsed);
        }

        header.topbar {
            height: var(--header-height);
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .toggle-sidebar {
            background: #f1f5f9;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            cursor: pointer;
            transition: var(--transition);
        }

        .toggle-sidebar:hover {
            background: #e2e8f0;
            color: var(--primary-color);
        }

        .header-search {
            flex: 1;
            max-width: 400px;
            margin: 0 30px;
            position: relative;
        }

        .header-search input {
            width: 100%;
            background: #f1f5f9;
            border: none;
            padding: 10px 15px 10px 40px;
            border-radius: 12px;
            font-size: 0.9rem;
            outline: none;
        }

        .header-search i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .notification-bell {
            position: relative;
            cursor: pointer;
            color: #64748b;
            font-size: 1.4rem;
        }

        .notification-dot {
            position: absolute;
            top: 3px;
            right: 3px;
            width: 8px;
            height: 8px;
            background: #ef4444;
            border: 2px solid #fff;
            border-radius: 50%;
        }

        .user-profile-dropdown {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 12px;
            transition: var(--transition);
        }

        .user-profile-dropdown:hover {
            background: #f8fafc;
        }

        .user-info {
            text-align: right;
        }

        .user-name {
            display: block;
            font-weight: 600;
            font-size: 0.9rem;
            color: #1e293b;
        }

        .user-role {
            display: block;
            font-size: 0.75rem;
            color: #64748b;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            object-fit: cover;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        /* Content Area */
        .content-body {
            padding: 30px;
            min-height: calc(100vh - var(--header-height) - 70px);
        }

        /* Footer */
        .footer {
            height: 70px;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            border-top: 1px solid #f1f5f9;
            color: #64748b;
            font-size: 0.85rem;
        }

        /* Mobile Adjustments */
        @media (max-width: 991px) {
            .sidebar {
                left: calc(-1 * var(--sidebar-width));
            }
            .sidebar.mobile-show {
                left: 0;
            }
            .main-content {
                margin-left: 0 !important;
            }
            .header-search {
                display: none;
            }
        }

        /* Utility Classes */
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: var(--card-shadow);
            border-radius: 16px;
        }

        .gradient-text {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn-premium {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-hover));
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 600;
            transition: var(--transition);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.2);
        }

        .btn-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(245, 158, 11, 0.3);
            color: #fff;
        }
    </style>

    @yield('jsp-header')
</head>
<body>

    <div class="layout-wrapper">
        <!-- SIDEBAR -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-logo">
                <img src="https://sadhumargi.in/images/logo.png" alt="Logo">
                <span>Sadhumargi</span>
            </div>

            <div class="sidebar-content">
                @if(auth()->user() && auth()->user()->role === 'super_admin')
                <span class="nav-group-label">Core Dashboard</span>
                <div class="nav-item">
                    <a href="{{ url('dashboard/super_admin') }}" class="nav-link {{ Request::is('dashboard/super_admin*') ? 'active' : '' }}">
                        <i class="bi bi-grid-fill"></i>
                        <span>Super Admin</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ url('dashboard/audit-logs') }}" class="nav-link {{ Request::is('dashboard/audit-logs*') ? 'active' : '' }}">
                        <i class="bi bi-list-check"></i>
                        <span>Audit Logs</span>
                    </a>
                </div>

                <span class="nav-group-label">Organization Units</span>
                <div class="nav-item">
                    <a href="{{ url('dashboard/shree_Sangh') }}" class="nav-link {{ Request::is('dashboard/shree_Sangh*') ? 'active' : '' }}">
                        <i class="bi bi-bank"></i>
                        <span>Shree Sangh</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ url('dashboard/mahila_samiti') }}" class="nav-link {{ Request::is('dashboard/mahila_samiti*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill"></i>
                        <span>Mahila Samiti</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ url('dashboard/yuva_sangh') }}" class="nav-link {{ Request::is('dashboard/yuva_sangh*') ? 'active' : '' }}">
                        <i class="bi bi-person-workspace"></i>
                        <span>Yuva Sangh</span>
                    </a>
                </div>

                <span class="nav-group-label">Publications</span>
                <div class="nav-item">
                    <a href="{{ url('dashboard/sahitya') }}" class="nav-link {{ Request::is('dashboard/sahitya') ? 'active' : '' }}">
                        <i class="bi bi-journal-bookmark-fill"></i>
                        <span>Shramnopasak</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ url('dashboard/sahitya_publication') }}" class="nav-link {{ Request::is('dashboard/sahitya_publication*') ? 'active' : '' }}">
                        <i class="bi bi-book-half"></i>
                        <span>Sahitya Pubs</span>
                    </a>
                </div>
                @endif

                @if(auth()->user() && auth()->user()->role === 'app_user')
                <span class="nav-group-label">Core Dashboard</span>
                <div class="nav-item">
                    <a href="{{ url('dashboard/app_user') }}" class="nav-link {{ Request::is('dashboard/app_user*') ? 'active' : '' }}">
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </div>

                <span class="nav-group-label">Management</span>
                <div class="nav-item">
                    <a href="javascript:void(0)" class="nav-link has-submenu" onclick="toggleSubmenu(this)">
                        <i class="bi bi-megaphone-fill"></i>
                        <span>Notifications</span>
                        <i class="bi bi-chevron-down submenu-arrow"></i>
                    </a>
                    <ul class="submenu">
                        <li><a href="{{ url('/send_notification-form') }}" class="nav-link">Send New</a></li>
                        <li><a href="{{ url('/view_notifications_all') }}" class="nav-link">History</a></li>
                        <li><a href="{{ url('/mobile_app_version') }}" class="nav-link">App Versions</a></li>
                    </ul>
                </div>

                <div class="nav-item">
                    <a href="javascript:void(0)" class="nav-link has-submenu" onclick="toggleSubmenu(this)">
                        <i class="bi bi-person-plus-fill"></i>
                        <span>Registrations</span>
                        <i class="bi bi-chevron-down submenu-arrow"></i>
                    </a>
                    <ul class="submenu">
                        <li><a href="{{ url('/app-registration') }}" class="nav-link">Add New</a></li>
                        <li><a href="{{ url('/registration-status') }}" class="nav-link">Status List</a></li>
                        <li><a href="{{ url('/app-opens-dashboard') }}" class="nav-link {{ Request::is('app-opens-dashboard*') ? 'active' : '' }}">App Open Logs</a></li>
                    </ul>
                </div>

                <div class="nav-item">
                    <a href="{{ url('/status') }}" class="nav-link {{ Request::is('status*') ? 'active' : '' }}">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>System Status</span>
                    </a>
                </div>
                @endif

                <div class="nav-item mt-4">
                    <a href="javascript:void(0)" onclick="logoutFunction()" class="nav-link text-danger">
                        <i class="bi bi-power"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <div class="main-content" id="mainContent">
            <header class="topbar">
                <div class="d-flex align-items-center">
                    <button class="toggle-sidebar" id="sidebarToggle">
                        <i class="bi bi-list"></i>
                    </button>
                    <div class="header-search">
                        <i class="bi bi-search"></i>
                        <input type="text" placeholder="Search for stats, reports, members...">
                    </div>
                </div>

                <div class="topbar-right">
                    <div class="notification-bell">
                        <i class="bi bi-bell"></i>
                        <span class="notification-dot"></span>
                    </div>

                    <div class="dropdown">
                        <div class="user-profile-dropdown" data-bs-toggle="dropdown">
                            <div class="user-info d-none d-md-block">
                                <span class="user-name">Hello {{ auth()->user()->name ?? 'Admin' }}</span>
                                <span class="user-role">{{ auth()->user() ? ucwords(str_replace('_', ' ', auth()->user()->role)) : 'Super User' }}</span>
                            </div>
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=f59e0b&color=fff" alt="User" class="user-avatar">
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2" style="border-radius: 12px;">
                            <li><a class="dropdown-item rounded-3 py-2" href="{{ url('/change-password_' . (auth()->user()->role ?? 'super_admin')) }}"><i class="bi bi-key me-2"></i> Change Password</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item rounded-3 py-2 text-danger" href="javascript:void(0)" onclick="logoutFunction()"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>
            </header>

            <main class="content-body">
                @yield('content')
            </main>

            <footer class="footer">
                <div>
                    <strong>Sadhumargi Admin</strong> © {{ date('Y') }} | Crafted for Excellence
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span><i class="bi bi-shield-check text-success me-1"></i> SABSJS IT CELL</span>
                    <button class="btn btn-sm btn-light rounded-pill px-3" data-bs-toggle="tooltip" data-bs-html="true" title="<b>Deepak Acharya</b><br><b>Aditya Acharya</b><br>📞 +91-9636501008">
                        <i class="bi bi-info-circle me-1"></i> Support
                    </button>
                </div>
            </footer>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const toggleBtn = document.getElementById('sidebarToggle');

        // Toggle Sidebar
        toggleBtn.addEventListener('click', () => {
            const isMobile = window.innerWidth <= 991;
            if (isMobile) {
                sidebar.classList.toggle('mobile-show');
            } else {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
            }
        });

        // Submenu Toggle
        function toggleSubmenu(element) {
            const submenu = element.nextElementSibling;
            const arrow = element.querySelector('.submenu-arrow');
            
            if (submenu.style.display === 'block') {
                submenu.style.display = 'none';
                arrow.classList.remove('rotate-arrow');
            } else {
                submenu.style.display = 'block';
                arrow.classList.add('rotate-arrow');
            }
        }

        // Logout
        function logoutFunction() {
            if(confirm('Are you sure you want to logout?')) {
                window.location.href = "{{ route('logout') }}";
            }
        }

        // Initialize Tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })

        // Close mobile sidebar on resize
        window.addEventListener('resize', () => {
            if (window.innerWidth > 991) {
                sidebar.classList.remove('mobile-show');
            }
        });
    </script>
</body>
</html>
