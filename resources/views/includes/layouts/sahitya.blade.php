<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>@yield('title', 'SABSJS Admin Panel')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />

    <style>
        :root {
            --sidebar-width: 170px;
            --sidebar-collapsed: 100px;
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
            transition: width 0.0s ease;
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
            padding: 10px 38px;
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
        .sidebar:not(.expanded) .nav-link span {
         display: none !important;
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
            padding: 0px 0px;
            color: #aab0c7;
        }
        .submenu-toggle {
            margin-left: 0px;
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
        footer.footer {
            background: #181824;
            padding: 10px 20px;
            color: #999;
            text-align: right;
            font-size: 0.9rem;
            border-top: 1px solid #222;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 40px;
            line-height: 40px;
            z-index: 1040;
            margin-left: var(--sidebar-collapsed);
            transition: margin-left 0.3s ease;
            display: flex;
    align-items: center;
    justify-content: flex-end;
        }
        .sidebar.expanded ~ footer.footer {
            margin-left: var(--sidebar-width);
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
        <div class="role">SuperUser</div>
    </div>

    <!-- Home - No Submenu -->
    <div class="nav-item">
        <a href="{{ url('dashboard/shree_sangh') }}" class="nav-link">
            <i class="bi bi-house-door"></i>
            <span class="link-text">Home</span>
        </a>
    </div>

    <!-- Dashboard -->
    <div class="nav-item">
        <div class="nav-link menu-item d-flex align-items-center" onclick="toggleSubmenu(this)">
            <i class="bi bi-speedometer2"></i>
            <span class="link-text">Dashboard</span>
        </div>
        <div class="submenu">
           
            <a href="{{ url('/dashboard/shree_sangh/daily-thoughts') }}" class="nav-link">
                <i class="bi bi-lightbulb"></i>
                <span class="link-text">आज का विचार</span>
            </a>
            <a href="{{ url('/dashboard/vihar-sewa') }}" class="nav-link">
                <i class="bi bi-geo-alt"></i>
                <span class="link-text">विहार जानकारी</span>
            </a>
            <a href="{{ url('/news') }}" class="nav-link">
                <i class="bi bi-megaphone"></i>
                <span class="link-text">NEWS</span>
            </a>
            <a href="{{ url('/shivir') }}" class="nav-link">
                <i class="bi bi-calendar-event"></i>
                <span class="link-text">शिविर</span>
            </a>
            <a href="{{ url('/aavedan_patra') }}" class="nav-link">
                <i class="bi bi-file-earmark-text"></i>
                <span class="link-text">आवेदन पत्र</span>
            </a>
        </div>
    </div>

    <!-- कार्यकारिणी -->
    <div class="nav-item">
        <div class="nav-link menu-item d-flex align-items-center" onclick="toggleSubmenu(this)">
            <i class="bi bi-diagram-3"></i>
            <span class="link-text">कार्यकारिणी</span>
        </div>
        <div class="submenu">
            <a href="{{ route('karyakarini.index') }}" class="nav-link">
                <i class="bi bi-house-door"></i>
                <span class="link-text">HOME</span>
            </a>
            <a href="{{ url('/shree-sangh/ex-president') }}" class="nav-link">
                <i class="bi bi-person-check"></i>
                <span class="link-text">पूर्व अध्यक्ष</span>
            </a>
            <a href="{{ url('/shree-sangh/karyakarini/pst') }}" class="nav-link">
                <i class="bi bi-person-video2"></i>
                <span class="link-text">PST</span>
            </a>
            <a href="{{ url('/vp-sec') }}" class="nav-link">
                <i class="bi bi-person-badge"></i>
                <span class="link-text">VP/SEC सदस्य</span>
            </a>
            <a href="{{ route('admin.it_cell') }}" class="nav-link">
                <i class="bi bi-cpu"></i>
                <span class="link-text">IT-CELL सदस्य</span>
            </a>
            <a href="{{ url('/pravarti-sanyojak') }}" class="nav-link">
                <i class="bi bi-diagram-3-fill"></i>
                <span class="link-text">प्रवर्ती संयोजक</span>
            </a>
            <a href="{{ url('/karyasamiti-sadasya') }}" class="nav-link">
                <i class="bi bi-people-fill"></i>
                <span class="link-text">कार्यसमिति सदस्य</span>
            </a>
            <a href="{{ url('/sthayi_sampati_sanwardhan_samiti') }}" class="nav-link">
                <i class="bi bi-bank"></i>
                <span class="link-text">स्थायि सम्पति संवर्द्धन समित</span>
            </a>
            <a href="{{ url('/sanyojan_mandal_antrastriya_sadasyata') }}" class="nav-link">
                <i class="bi bi-globe2"></i>
                <span class="link-text">संयोजन मंडल अंतरस्त्रिय सदस्यता</span>
            </a>
            <a href="{{ url('/samta_jan_kalyan_pranayash') }}" class="nav-link">
                <i class="bi bi-activity"></i>
                <span class="link-text">समता जन कल्याण प्राणायास</span>
            </a>
            <a href="{{ url('/padhadhikari_prashashan_karyashala') }}" class="nav-link">
                <i class="bi bi-file-earmark-pdf"></i>
                <span class="link-text">पदाधिकारी प्रशासन कार्यशाला</span>
            </a>
        </div>
    </div>

    <!-- Users -->
    <div class="nav-item">
        <div class="nav-link menu-item d-flex align-items-center" onclick="toggleSubmenu(this)">
            <i class="bi bi-people"></i>
            <span class="link-text">Users</span>
        </div>
        <div class="submenu">
            <a href="#" class="nav-link">
                <i class="bi bi-person"></i>
                <span class="link-text">All Users</span>
            </a>
            <a href="#" class="nav-link">
                <i class="bi bi-person-plus"></i>
                <span class="link-text">Add User</span>
            </a>
            <a href="#" class="nav-link">
                <i class="bi bi-shield-lock"></i>
                <span class="link-text">Roles</span>
            </a>
        </div>
    </div>

    <!-- Settings -->
    <div class="nav-item">
        <div class="nav-link menu-item d-flex align-items-center" onclick="toggleSubmenu(this)">
            <i class="bi bi-gear"></i>
            <span class="link-text">Settings</span>
        </div>
        <div class="submenu">
            <a href="#" class="nav-link">
                <i class="bi bi-lock"></i>
                <span class="link-text">Privacy</span>
            </a>
        </div>
    </div>

    <!-- Logout -->
    <a href="{{ route('logout') }}" class="nav-link">
        <i class="bi bi-box-arrow-right"></i>
        <span class="link-text">Logout</span>
    </a>
</nav>



        <!-- MAIN -->
        <main class="content">
            @yield('content')
        </main>
    </div>

    <!-- FOOTER -->
    <footer class="footer">
        Admin Panel &copy; {{ date('Y') }} | SABSJS IT CELL
    </footer>

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
    @yield('scripts')

</body>
</html>
