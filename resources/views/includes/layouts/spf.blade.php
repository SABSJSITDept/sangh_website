<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>@yield('title', 'Admin Panel')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />


    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        /* ========== MODERN PROFESSIONAL UI/UX DESIGN ========== */

        /* ===== CSS Variables - Premium Color Palette ===== */
        :root {
            /* Sidebar Dimensions */
            --sidebar-width: 260px;
            --sidebar-collapsed: 80px;

            /* Premium Color Palette */
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --dark-gradient: linear-gradient(135deg, #1e1e2e 0%, #2d2d44 100%);
            --header-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --footer-gradient: linear-gradient(135deg, #434343 0%, #000000 100%);

            /* Neutral Colors */
            --bg-primary: #f5f7fa;
            --bg-secondary: #ffffff;
            --bg-dark: #1a1a2e;
            --bg-darker: #16213e;
            --text-primary: #2d3748;
            --text-secondary: #718096;
            --text-light: #a0aec0;
            --text-white: #ffffff;

            /* Accent Colors */
            --accent-purple: #667eea;
            --accent-pink: #f093fb;
            --accent-blue: #4facfe;
            --accent-orange: #fa709a;

            /* Shadows */
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.07);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
            --shadow-xl: 0 20px 25px rgba(0, 0, 0, 0.15);
            --shadow-2xl: 0 25px 50px rgba(0, 0, 0, 0.25);
            --shadow-glow: 0 0 20px rgba(102, 126, 234, 0.3);

            /* Border Radius */
            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 16px;
            --radius-xl: 24px;

            /* Transitions */
            --transition-fast: 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-base: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-slow: 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ===== Global Styles ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--bg-primary);
            color: var(--text-primary);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-size: 15px;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* ===== HEADER - Premium Design ===== */
        .main-header {
            position: fixed;
            top: 0;
            left: var(--sidebar-collapsed);
            right: 0;
            background: var(--header-gradient);
            color: var(--text-white);
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 70px;
            z-index: 1040;
            transition: all var(--transition-base);
            width: calc(100% - var(--sidebar-collapsed));
            box-shadow: var(--shadow-lg);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar.expanded~.main-header {
            left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
        }

        .main-header .sidebar-toggle {
            background: rgba(255, 255, 255, 0.15);
            color: var(--text-white);
            font-size: 1.5rem;
            border: none;
            cursor: pointer;
            padding: 10px 14px;
            border-radius: var(--radius-md);
            transition: all var(--transition-fast);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .main-header .sidebar-toggle:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .main-header b {
            font-size: 1.3rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
            font-family: 'Poppins', sans-serif;
            letter-spacing: 0.3px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .main-header b i {
            font-size: 1.5rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }
        }

        /* ===== SIDEBAR - Modern Premium Design ===== */
        .sidebar {
            width: var(--sidebar-collapsed);
            background: var(--dark-gradient);
            color: var(--text-white);
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            transition: all var(--transition-base);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow-y: auto;
            overflow-x: hidden;
            box-shadow: var(--shadow-xl);
            border-right: 1px solid rgba(255, 255, 255, 0.05);
            z-index: 1050;
        }

        /* Custom Scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(102, 126, 234, 0.5);
            border-radius: 10px;
            transition: all var(--transition-fast);
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(102, 126, 234, 0.8);
        }

        .sidebar.expanded {
            width: var(--sidebar-width);
        }

        /* Profile Section */
        .sidebar .profile {
            text-align: center;
            padding: 25px 15px;
            background: rgba(255, 255, 255, 0.03);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            position: relative;
            overflow: hidden;
        }

        .sidebar .profile::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--primary-gradient);
        }

        .sidebar .profile img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 3px solid var(--accent-purple);
            box-shadow: 0 0 20px rgba(102, 126, 234, 0.4);
            transition: all var(--transition-base);
            object-fit: cover;
        }

        .sidebar.expanded .profile img:hover {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 0 30px rgba(102, 126, 234, 0.6);
        }

        .sidebar .profile .name,
        .sidebar .profile .role {
            display: none;
        }

        .sidebar.expanded .profile .name,
        .sidebar.expanded .profile .role {
            display: block;
            margin-top: 12px;
            animation: fadeIn 0.4s ease-in;
        }

        .sidebar.expanded .profile .name {
            color: var(--text-white);
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 4px;
            font-family: 'Poppins', sans-serif;
        }

        .sidebar.expanded .profile .role {
            color: var(--text-light);
            font-size: 0.85rem;
            font-weight: 400;
            background: rgba(102, 126, 234, 0.2);
            padding: 4px 12px;
            border-radius: var(--radius-sm);
            display: inline-block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Navigation Items */
        .sidebar .nav-item {
            margin: 6px 12px;
        }

        .nav-link {
            min-height: 48px;
            color: rgba(255, 255, 255, 0.7);
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 14px;
            border-radius: var(--radius-md);
            text-decoration: none;
            user-select: none;
            white-space: nowrap;
            transition: all var(--transition-fast);
            position: relative;
            overflow: hidden;
            font-weight: 500;
            font-size: 0.95rem;
        }

        /* Hover Effect with Gradient */
        .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--primary-gradient);
            transform: scaleY(0);
            transition: transform var(--transition-fast);
        }

        .nav-link:hover::before,
        .nav-link.active::before {
            transform: scaleY(1);
        }

        .nav-link:hover,
        .nav-link.active {
            background: rgba(102, 126, 234, 0.15);
            color: var(--text-white);
            transform: translateX(4px);
            box-shadow: var(--shadow-md);
        }

        .nav-link i {
            min-width: 24px;
            font-size: 1.3rem;
            transition: all var(--transition-fast);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nav-link:hover i {
            transform: scale(1.15);
            color: var(--accent-blue);
        }

        /* Hide text when collapsed */
        .nav-link span {
            display: none;
            font-family: 'Inter', sans-serif;
        }

        .sidebar.expanded .nav-link span {
            display: inline;
        }

        /* Collapsed state - center icons */
        .sidebar:not(.expanded) .nav-link {
            justify-content: center;
            padding: 14px;
        }

        .sidebar.expanded .nav-link {
            justify-content: flex-start;
        }

        /* Menu items with submenus */
        .nav-link.menu-item {
            cursor: pointer;
        }

        .sidebar:not(.expanded) .submenu-toggle {
            display: none;
        }

        /* Submenu */
        .submenu {
            display: none;
            flex-direction: column;
            gap: 4px;
            padding: 8px 0 8px 20px;
            margin-left: 12px;
            border-left: 2px solid rgba(102, 126, 234, 0.3);
        }

        .sidebar.expanded .submenu.show {
            display: flex;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .submenu .nav-link {
            font-size: 0.9rem;
            padding: 10px 16px;
            min-height: 40px;
            margin: 0;
        }

        .submenu .nav-link i {
            font-size: 1.1rem;
        }

        /* Submenu toggle arrow */
        .submenu-toggle {
            margin-left: auto;
            transition: transform var(--transition-base);
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.5);
        }

        .submenu-toggle.rotate {
            transform: rotate(180deg);
            color: var(--accent-blue);
        }

        /* ===== MAIN CONTENT - Premium Layout ===== */
        main.content {
            margin-left: var(--sidebar-collapsed);
            padding: 30px;
            padding-top: 100px;
            transition: all var(--transition-base);
            min-height: calc(100vh - 70px);
            padding-bottom: 80px;
            background: var(--bg-primary);
            overflow-x: hidden;
        }

        .sidebar.expanded~main.content {
            margin-left: var(--sidebar-width);
        }

        /* ===== FOOTER - Modern Design ===== */
        .footer {
            background: var(--footer-gradient);
            padding: 0 30px;
            color: var(--text-white);
            font-size: 0.9rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            position: fixed;
            bottom: 0;
            left: 0;
            height: 60px;
            z-index: 1040;
            margin-left: var(--sidebar-collapsed);
            width: calc(100% - var(--sidebar-collapsed));
            transition: all var(--transition-base);
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 -4px 6px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }

        .sidebar.expanded~.footer {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
        }

        .footer-left {
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .footer-right {
            position: relative;
        }

        .contact-info-icon {
            cursor: pointer;
            font-size: 1.3rem;
            padding: 8px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            transition: all var(--transition-fast);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .contact-info-icon:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: rotate(15deg) scale(1.1);
        }

        .contact-tooltip {
            display: none;
            position: absolute;
            bottom: 140%;
            right: 0;
            background: var(--bg-secondary);
            color: var(--text-primary);
            padding: 16px 20px;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-2xl);
            white-space: nowrap;
            z-index: 1050;
            font-size: 0.9rem;
            min-width: 220px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            animation: tooltipFadeIn 0.3s ease-out;
        }

        @keyframes tooltipFadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .contact-tooltip::after {
            content: '';
            position: absolute;
            bottom: -8px;
            right: 20px;
            width: 16px;
            height: 16px;
            background: var(--bg-secondary);
            transform: rotate(45deg);
            border-right: 1px solid rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .footer-right:hover .contact-tooltip {
            display: block;
        }

        /* Logout Button */
        .main-header .btn-logout {
            background: rgba(255, 255, 255, 0.15) !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
            color: var(--text-white) !important;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: var(--radius-md);
            transition: all var(--transition-fast);
            backdrop-filter: blur(10px);
            font-size: 0.95rem;
            font-family: 'Inter', sans-serif;
        }

        .main-header .btn-logout:hover {
            background: rgba(255, 255, 255, 0.25) !important;
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        /* Layout expanded states */
        .layout.expanded .main-header {
            left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
        }

        .layout.expanded .footer {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
        }

        .layout.expanded main.content {
            margin-left: var(--sidebar-width);
        }

        /* ===== MOBILE RESPONSIVE ===== */
        @media (max-width: 991px) {
            .sidebar {
                position: fixed;
                top: 70px;
                left: -280px;
                height: calc(100vh - 70px);
                width: 280px;
                transition: left var(--transition-base);
                z-index: 2000;
            }

            .sidebar.mobile-show {
                left: 0;
            }

            .backdrop {
                display: none;
                position: fixed;
                top: 70px;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.6);
                backdrop-filter: blur(4px);
                z-index: 1900;
            }

            .backdrop.show {
                display: block;
            }

            .main-header {
                left: 0 !important;
                width: 100% !important;
                z-index: 2100;
                height: 70px;
            }

            .main-header b {
                font-size: 0.85rem;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 60%;
            }

            .main-header .btn-logout {
                font-size: 0.8rem;
                padding: 8px 12px;
            }

            main.content {
                margin-left: 0 !important;
                width: 100% !important;
                padding: 20px 15px;
                padding-top: 90px;
            }

            .footer {
                margin-left: 0 !important;
                width: 100% !important;
                height: 60px;
                padding: 0 15px;
                font-size: 0.75rem;
            }

            .sidebar .nav-link {
                padding: 14px 20px;
                font-size: 1rem;
            }

            .submenu .nav-link {
                padding: 12px 20px;
            }
        }

        /* ===== UTILITY ANIMATIONS ===== */
        @keyframes shimmer {
            0% {
                background-position: -1000px 0;
            }

            100% {
                background-position: 1000px 0;
            }
        }

        .loading-shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 1000px 100%;
            animation: shimmer 2s infinite;
        }
    </style>


</head>

<body>




    <!-- BACKDROP for mobile -->
    <div class="backdrop" id="sidebarBackdrop"></div>

    <!-- LAYOUT -->
    <!-- <div class="layout"> -->
    <!-- SIDEBAR -->
    <nav class="sidebar expanded" id="sidebarMenu">

        <div>
            <div class="profile">
                <img src="https://randomuser.me/api/portraits/men/41.jpg" alt="Admin" />
                <div class="name">Hello Admin</div>
                <div class="role">Spf</div>
            </div>

            <!-- Dashboard button without submenu -->
            <div class="nav-item">
                <a href="{{ url('/dashboard/spf') }}" class="nav-link">
                    <i class="bi bi-house-door"></i>
                    <span class="link-text">HOME</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ url('/dashboard/spf/home') }}" class="nav-link">
                    <i class="bi bi-display"></i>
                    <span class="link-text">Home Screen updates</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ url('/dashboard/spf/committee') }}" class="nav-link">
                    <i class="bi bi-people-fill"></i>
                    <span class="link-text">SPF Committee</span>
                </a>
            </div>

            <div class="nav-link menu-item d-flex align-items-center" onclick="toggleSubmenu(this)">
                <i class="bi bi-images"></i>
                <span class="link-text"> Gallery</span>
                <i class="bi bi-chevron-down submenu-toggle"></i>
            </div>


            <div class="submenu">
                <a href="{{ url('/spf_photo_gallery') }}" class="nav-link">
                    <i class="bi bi-camera-fill"></i>
                    <span>Add Photos</span>
                </a>

                <a href="{{ url('/spf_photo_gallery_view') }}" class="nav-link">
                    <i class="bi bi-eye-fill"></i>
                    <span>View Photos</span>
                </a>

                <a href="{{ url('/dashboard/spf/slider') }}" class="nav-link">
                    <i class="bi bi-eye-fill"></i>
                    <span>Slider</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ url('/dashboard/spf/events') }}" class="nav-link">
                    <i class="bi bi-calendar-event"></i>
                    <span class="link-text">Events</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ url('/dashboard/spf/projects') }}" class="nav-link">
                    <i class="bi bi-calendar-event"></i>
                    <span class="link-text">projects</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ url('/dashboard/spf/safarnama') }}" class="nav-link">
                    <i class="bi bi-file-earmark-pdf"></i>
                    <span class="link-text">Safarnama</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ url('/dashboard/spf/downloads') }}" class="nav-link">
                    <i class="bi bi-download"></i>
                    <span class="link-text">Downloads</span>
                </a>
            </div>

            <div class="nav-link menu-item d-flex align-items-center" onclick="toggleSubmenu(this)">
                <i class="bi bi-bell-fill"></i>
                <span class="link-text">Notifications</span>
                <i class="bi bi-chevron-down submenu-toggle"></i>
            </div>

            <div class="submenu">
                <a href="{{ url('/send_notification-spf') }}" class="nav-link">
                    <i class="bi bi-send-fill"></i>
                    <span>Send Notification</span>
                </a>

                <a href="{{ url('/view_notifications_spf') }}" class="nav-link">
                    <i class="bi bi-eye-fill"></i>
                    <span>View Notifications</span>
                </a>
            </div>

            <div class="nav-item">
                <div class="nav-link menu-item d-flex align-items-center" onclick="toggleSubmenu(this)">
                    <i class="bi bi-shield-lock-fill"></i>
                    <span class="link-text"> Change Password</span>
                    <i class="bi bi-chevron-down submenu-toggle"></i>
                </div>
                <div class="submenu">
                    <a href="{{ url('/change-password_spf') }}" class="nav-link">
                        <i class="bi bi-key-fill"></i>
                        <span>Change Password</span>
                    </a>
                </div>



                <!-- Logout as last menu item -->
                <div class="nav-item">
                    <a href="javascript:void(0)" onclick="logoutFunction()" class="nav-link">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </a>
                </div>

            </div>
        </div>

        <!-- Logout fixed at bottom -->

    </nav>
    <header class="main-header">
        <button class="sidebar-toggle" id="sidebarToggle"><i class="bi bi-list"></i></button>
        <b><i class="bi bi-speedometer2"></i> श्री अखिल भारतवर्षीय साधुमार्गी जैन संघ </b>
        <button class="btn btn-logout" onclick="window.location.href='/logout'">
            Logout
        </button>
    </header>

    <!-- MAIN -->
    <main class="content">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="footer-left">
            Admin Panel © {{ date('Y') }} | SABSJS IT CELL
        </div>
        <div class="footer-right">
            <i class="bi bi-info-circle-fill contact-info-icon" tabindex="0"></i>
            <div class="contact-tooltip" id="contactTooltip">
                <strong>Contact:</strong><br>
                Deepak Acharya<br>
                Aditya Acharya<br>
                📞 +91-9636501008
            </div>
        </div>
    </footer>


    <!-- Scripts -->
    <script>
        const sidebar = document.getElementById('sidebarMenu');
        const toggleBtn = document.getElementById('sidebarToggle');
        const backdrop = document.getElementById('sidebarBackdrop');

        // ✅ Toggle sidebar on desktop/mobile
        toggleBtn.addEventListener('click', () => {
            const isMobile = window.innerWidth <= 991;

            if (isMobile) {
                sidebar.classList.toggle('mobile-show');
                backdrop.classList.toggle('show');
            } else {
                sidebar.classList.toggle('expanded');

                // ✅ Add layout class toggle here
                if (sidebar.classList.contains('expanded')) {
                    document.querySelector('.layout').classList.add('expanded');
                } else {
                    document.querySelector('.layout').classList.remove('expanded');
                }

                if (!sidebar.classList.contains('expanded')) {
                    closeAllSubmenus();
                }
            }
        });


        // ✅ Click on backdrop closes mobile sidebar
        backdrop.addEventListener('click', closeMobileSidebar);

        function closeMobileSidebar() {
            sidebar.classList.remove('mobile-show');
            backdrop.classList.remove('show');
        }

        // ✅ Toggle submenu when menu item is clicked
        function toggleSubmenu(element) {
            const submenu = element.nextElementSibling;
            const arrow = element.querySelector('.submenu-toggle');
            const isOpen = submenu.classList.contains('show');
            const isCollapsed = !sidebar.classList.contains('expanded');
            const isMobile = window.innerWidth <= 991;

            if (isCollapsed && !isMobile) {
                sidebar.classList.add('expanded');
                setTimeout(() => openSubmenu(submenu, arrow), 300);
                return;
            }

            if (isOpen) {
                closeSubmenu(submenu, arrow);
            } else {
                openSubmenu(submenu, arrow);
            }
        }

        // ✅ Open submenu
        function openSubmenu(submenu, arrow) {
            submenu.classList.add('show');
            if (arrow) arrow.classList.add('rotate');
        }

        // ✅ Close submenu
        function closeSubmenu(submenu, arrow) {
            submenu.classList.remove('show');
            if (arrow) arrow.classList.remove('rotate');
        }

        // ✅ Close all submenus
        function closeAllSubmenus() {
            document.querySelectorAll('.submenu').forEach(s => s.classList.remove('show'));
            document.querySelectorAll('.submenu-toggle').forEach(i => i.classList.remove('rotate'));
        }

        // ✅ Logout button action
        function logoutFunction() {
            window.location.href = "{{ route('logout') }}";
        }

        // ✅ Tooltip logic for contact info icon
        const infoIcon = document.querySelector('.contact-info-icon');
        const tooltip = document.getElementById('contactTooltip');

        if (infoIcon && tooltip) {
            infoIcon.addEventListener('click', () => {
                tooltip.style.display = tooltip.style.display === 'block' ? 'none' : 'block';
            });

            document.addEventListener('click', (event) => {
                if (!infoIcon.contains(event.target) && !tooltip.contains(event.target)) {
                    tooltip.style.display = 'none';
                }
            });
        }

        // ✅ Auto-close mobile sidebar if window resized to desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth > 991) {
                closeMobileSidebar();
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>