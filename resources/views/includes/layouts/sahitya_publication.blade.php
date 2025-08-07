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

    <style>
        :root {
            --sidebar-width: 220px;
            --sidebar-collapsed: 70px;
        }
        body {
            margin: 0;
            background: #fff;
            color: #23262f;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
.main-header {
    position: fixed;             /* ADD THIS */
    top: 0;                      /* ADD THIS */
    left: 0;                     /* ADD THIS */
    right: 0;                    /* ADD THIS */
   background: linear-gradient(to right, #ff6a00, #ee0979);
    color: #fff;
    padding: 7px 1rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 50px;
    z-index: 1040;
}

        .main-header .sidebar-toggle {
            background: none;
            color: #fff;
            font-size: 1.5rem;
            border: none;
            cursor: pointer;
            padding: 0;
        }
        .main-header b {
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .main-header img {
            border-radius: 50%;
            border: 2px solid #356ef9;
            width: 32px;
            height: 32px;
            object-fit: cover;
        }
        .layout {
    display: flex;
    padding-top: 50px; /* ADD THIS to push content below fixed header */
}

        .sidebar {
            width: var(--sidebar-collapsed);
            background: #181824;
            color: white;
            transition: width 0.3s ease;
            height: calc(100vh - 50px);
            position: fixed;
            top: 50px;
            left: 0;
            overflow-y: auto;
            z-index: 1030;
        }
        .sidebar.expanded {
            width: var(--sidebar-width);
        }
        .sidebar .profile {
            text-align: center;
            padding: 20px 0;
        }
        .sidebar .profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid #356ef9;
            margin-bottom: 5px;
        }
        .sidebar .profile .name,
        .sidebar .profile .role {
            display: none;
        }
        .sidebar.expanded .profile .name,
        .sidebar.expanded .profile .role {
            display: block;
            color: #9aa0b9;
            margin: 2px 0;
            font-size: 0.9rem;
        }
        .nav-link {
            color: #b6bbc7;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-radius: 6px;
            margin: 5px 8px;
            text-decoration: none;
        }
        .nav-link:hover, .nav-link.active {
            background: linear-gradient(90deg, #2760fe 0%, #3e85fa 100%);
            color: #fff;
        }
        .nav-link span {
            display: none;
        }
        .sidebar.expanded .nav-link span {
            display: inline;
        }
        .nav-item {
            width: 100%;
        }
        .nav-link.menu-item {
            background: none;
            cursor: pointer;
            justify-content: space-between;
        }
        .nav-link.menu-item span {
            display: inline;
        }
        .submenu {
            display: none;
            flex-direction: column;
            padding-left: 20px;
        }
        .submenu .nav-link i {
    font-size: 1rem;
}
 .sidebar:not(.expanded) .link-text {
        display: none !important;
    }
        .sidebar.expanded .submenu.show {
            display: flex;
        }
        .sidebar.expanded .submenu .nav-link {
            font-size: 0.9rem;
            padding: 7px 8px;
            color: #aab0c7;
        }
        .submenu-toggle {
            margin-left: auto;
            transition: transform 0.3s ease;
            font-size: 1rem;
        }
        .submenu-toggle.rotate {
            transform: rotate(180deg);
        }
        main.content {
            margin-left: var(--sidebar-collapsed);
            padding: 20px;
            transition: margin-left 0.3s ease;
            width: 100%;
            min-height: calc(100vh - 90px);
             padding-bottom: 60px;
        }
        .sidebar.expanded ~ main.content {
            margin-left: var(--sidebar-width);
        }
.footer {
    background: linear-gradient(to right, #7c3434, #c94b4b);
    padding: 0 20px;
    color: #fff;
    font-size: 0.9rem;
    border-top: 1px solid #222;
    position: fixed;
    bottom: 0;
    left: 0;
    height: 40px;
    line-height: 40px;
    z-index: 1040;
    margin-left: var(--sidebar-collapsed);
    width: calc(100% - var(--sidebar-collapsed));
    transition: margin-left 0.3s ease, width 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.sidebar.expanded ~ .footer {
    margin-left: var(--sidebar-width);
    width: calc(100% - var(--sidebar-width));
}

.footer-left {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.footer-right {
    position: relative;
}

.contact-info-icon {
    cursor: pointer;
    font-size: 1.2rem;
}

.contact-tooltip {
    display: none;
    position: absolute;
    bottom: 140%;
    right: 0;
    background-color: #fff;
    color: #333;
    padding: 8px 12px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0,0,0,0.2);
    white-space: nowrap;
    z-index: 1050;
    font-size: 0.85rem;
    min-width: 180px;
}

/* Show tooltip on hover */
.footer-right:hover .contact-tooltip {
    display: block;
}

        /* Mobile */
        @media (max-width: 991px) {
            .sidebar {
                left: -100%;
                width: var(--sidebar-width);
                position: fixed;
            }
            .sidebar.mobile-show {
                left: 0;
            }
            main.content, footer.footer {
                margin-left: 0 !important;
            }
            .backdrop {
                display: none;
                position: fixed;
                top: 50px;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.4);
                z-index: 1020;
            }
            .backdrop.show {
                display: block;
            }
        }
    </style>
</head>
<body>
    <!-- HEADER -->
    <header class="main-header">
        <button class="sidebar-toggle" id="sidebarToggle"><i class="bi bi-list"></i></button>
        <b><i class="bi bi-speedometer2"></i> श्री अखिल भारतवर्षीय साधुमार्गी जैन संघ </b>
        <img src="https://randomuser.me/api/portraits/men/41.jpg" alt="admin" />
    </header>

    <!-- BACKDROP for mobile -->
    <div class="backdrop" id="sidebarBackdrop"></div>

    <!-- LAYOUT -->
    <div class="layout">
        <!-- SIDEBAR -->
<!-- SIDEBAR -->
        <nav class="sidebar" id="sidebarMenu">
            <div class="profile">
                <img src="https://randomuser.me/api/portraits/men/41.jpg" alt="Admin" />
                <div class="name">Hello Admin</div>
                <div class="role">Shramnopasak Admin</div>
            </div>

<a href="{{ url('dashboard/sahitya_publication') }}" class="nav-link">
                <i class="bi bi-house-door"></i>
                <span class="link-text">HOME</span>
            </a>
            
   <a href="{{ url('/shramnopasak/all-view') }}" class="nav-link d-flex align-items-center">
    <i class="bi bi-journal-bookmark me-2"></i>
    <span class="link-text">SHRAMNOPASAK ALL BOOKS</span>
</a>


           <a href="{{ route('logout') }}" class="nav-link">
    <i class="bi bi-box-arrow-right"></i>
    <span class="link-text">Logout</span>
</a>

        </nav>


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

    </div>

    <!-- Scripts -->
    <script>
        const sidebar = document.getElementById('sidebarMenu');
        const toggleBtn = document.getElementById('sidebarToggle');
        const backdrop = document.getElementById('sidebarBackdrop');

        toggleBtn.addEventListener('click', () => {
            const isMobile = window.innerWidth <= 991;
            if (isMobile) {
                sidebar.classList.toggle('mobile-show');
                backdrop.classList.toggle('show');
            } else {
                sidebar.classList.toggle('expanded');
                if (!sidebar.classList.contains('expanded')) {
                    closeAllSubmenus();
                }
            }
        });

        backdrop.addEventListener('click', () => {
            sidebar.classList.remove('mobile-show');
            backdrop.classList.remove('show');
        });

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

            if (!isCollapsed) {
                isOpen ? closeSubmenu(submenu, arrow) : openSubmenu(submenu, arrow);
            }
        }

        function openSubmenu(submenu, arrow) {
            document.querySelectorAll('.submenu').forEach(s => s !== submenu && s.classList.remove('show'));
            document.querySelectorAll('.submenu-toggle').forEach(i => i !== arrow && i.classList.remove('rotate'));
            submenu.classList.add('show');
            arrow.classList.add('rotate');
        }

        function closeSubmenu(submenu, arrow) {
            submenu.classList.remove('show');
            arrow.classList.remove('rotate');
        }

        function closeAllSubmenus() {
            document.querySelectorAll('.submenu').forEach(s => s.classList.remove('show'));
            document.querySelectorAll('.submenu-toggle').forEach(i => i.classList.remove('rotate'));
        }
    </script>
    <script>
    const infoIcon = document.querySelector('.contact-info-icon');
    const tooltip = document.getElementById('contactTooltip');

    infoIcon.addEventListener('click', () => {
        tooltip.style.display = tooltip.style.display === 'block' ? 'none' : 'block';
    });

    // Optional: Hide when clicking outside
    document.addEventListener('click', function(event) {
        if (!infoIcon.contains(event.target) && !tooltip.contains(event.target)) {
            tooltip.style.display = 'none';
        }
    });
</script>

</body>
</html>
