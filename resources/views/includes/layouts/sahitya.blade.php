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
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

:root {
    --sidebar-width: 260px;
    --sidebar-collapsed: 80px;
    --sidebar-bg: #0f172a;
    --sidebar-hover: #1e293b;
    --sidebar-text: #94a3b8;
    --sidebar-text-active: #ffffff;
    --header-bg: #ffffff;
    --header-text: #334155;
    --footer-bg: #ffffff;
    --primary-color: #3b82f6;
    --bg-light: #f8fafc;
}

body {
    margin: 0;
    background: var(--bg-light);
    color: #334155;
    font-family: 'Inter', sans-serif;
}

/* Header */
.main-header {
    position: fixed;
    top: 0;
    left: var(--sidebar-collapsed);
    right: 0;
    background: var(--header-bg);
    color: var(--header-text);
    padding: 0 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 64px;
    z-index: 1040;
    transition: left 0.3s ease, width 0.3s ease;
    width: calc(100% - var(--sidebar-collapsed));
    box-sizing: border-box;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    border-bottom: 1px solid #f1f5f9;
}

.sidebar.expanded ~ .main-header {
    left: var(--sidebar-width);
    width: calc(100% - var(--sidebar-width));
}

.main-header .sidebar-toggle {
    background: none;
    color: var(--header-text);
    font-size: 1.5rem;
    border: none;
    cursor: pointer;
    padding: 0;
    transition: color 0.2s;
}
.main-header .sidebar-toggle:hover {
    color: var(--primary-color);
}
.main-header b {
    font-size: 1.15rem;
    display: flex;
    align-items: center;
    gap: 12px;
    font-weight: 600;
}
.main-header img {
    border-radius: 50%;
    width: 36px;
    height: 36px;
    object-fit: cover;
    border: 2px solid #e2e8f0;
}

/* Layout */
.layout {
    display: flex;
    padding-top: 64px;
}

/* Sidebar */
.sidebar {
    width: var(--sidebar-collapsed);
    background: var(--sidebar-bg);
    color: #fff;
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    transition: width 0.3s ease;
    display: flex;
    flex-direction: column;
    overflow-y: auto;
    scrollbar-width: none;
    box-shadow: 4px 0 15px rgba(0,0,0,0.05);
    z-index: 1050;
}
.sidebar::-webkit-scrollbar {
    display: none;
}
.sidebar.expanded {
    width: var(--sidebar-width);
}

.sidebar .profile {
    text-align: center;
    padding: 24px 0 20px;
    border-bottom: 1px solid rgba(255,255,255,0.08);
    margin-bottom: 16px;
}
.sidebar .profile img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    border: 2px solid rgba(255,255,255,0.2);
    transition: transform 0.3s, border-color 0.3s;
}
.sidebar .profile img:hover {
    transform: scale(1.05);
    border-color: var(--primary-color);
}
.sidebar .profile .name {
    display: none;
    color: #f8fafc;
    font-weight: 600;
    margin-top: 12px;
    font-size: 0.95rem;
    letter-spacing: 0.3px;
}
.sidebar .profile .role {
    display: none;
    color: var(--sidebar-text);
    font-size: 0.8rem;
    margin-top: 4px;
}
.sidebar.expanded .profile .name,
.sidebar.expanded .profile .role {
    display: block;
}

/* Nav Items */
.sidebar .nav-item {
    margin: 4px 16px;
}
.sidebar .nav-link {
    display: flex;
    align-items: center;
    gap: 16px;
    color: var(--sidebar-text);
    text-decoration: none;
    padding: 12px 16px;
    border-radius: 8px;
    transition: all 0.2s ease;
    font-size: 0.92rem;
    font-weight: 500;
    white-space: nowrap;
}
.sidebar .nav-link i {
    font-size: 1.25rem;
    min-width: 24px;
    text-align: center;
    transition: color 0.2s;
}
.sidebar .nav-link span {
    display: none;
    opacity: 0;
    transition: opacity 0.3s;
}
.sidebar.expanded .nav-link span {
    display: block;
    opacity: 1;
}

.sidebar .nav-link:hover,
.sidebar .nav-link.active {
    background: var(--sidebar-hover);
    color: var(--sidebar-text-active);
}
.sidebar .nav-link:hover i,
.sidebar .nav-link.active i {
    color: var(--primary-color);
}

/* Submenu */
.submenu {
    display: none;
    flex-direction: column;
    padding-left: 42px;
    margin-top: 6px;
    gap: 4px;
}
.sidebar.expanded .submenu.show {
    display: flex;
}
.submenu .nav-link {
    padding: 8px 12px;
    font-size: 0.88rem;
    color: #64748b;
    border-radius: 6px;
}
.submenu .nav-link:hover {
    color: #e2e8f0;
    background: transparent;
}
.submenu .nav-link:hover i {
    color: var(--sidebar-text-active);
}

.nav-link.menu-item {
    cursor: pointer;
    justify-content: space-between;
}
.submenu-toggle {
    margin-left: auto;
    transition: transform 0.3s ease;
    font-size: 0.8rem;
}
.submenu-toggle.rotate {
    transform: rotate(180deg);
}

/* Main Content */
main.content {
    margin-left: var(--sidebar-collapsed);
    padding: 30px;
    padding-top: 94px;
    transition: margin-left 0.3s ease;
    min-height: calc(100vh - 64px);
    padding-bottom: 80px;
    background: var(--bg-light);
}
.sidebar.expanded ~ main.content {
    margin-left: var(--sidebar-width);
}

/* Footer */
.footer {
    background: var(--footer-bg);
    padding: 0 30px;
    color: #64748b;
    font-size: 0.88rem;
    border-top: 1px solid #e2e8f0;
    position: fixed;
    bottom: 0;
    left: var(--sidebar-collapsed);
    height: 56px;
    width: calc(100% - var(--sidebar-collapsed));
    transition: margin-left 0.3s ease, width 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-sizing: border-box;
    z-index: 1030;
}
.sidebar.expanded ~ .footer {
    margin-left: var(--sidebar-width);
    width: calc(100% - var(--sidebar-width));
}
.footer-left {
    font-weight: 500;
}
.footer-right {
    position: relative;
}
.contact-info-icon {
    cursor: pointer;
    font-size: 1.2rem;
    color: var(--primary-color);
    transition: opacity 0.2s;
}
.contact-info-icon:hover {
    opacity: 0.8;
}
.contact-tooltip {
    display: none;
    position: absolute;
    bottom: 160%;
    right: 0;
    background-color: var(--sidebar-bg);
    color: #f8fafc;
    padding: 14px 20px;
    border-radius: 8px;
    box-shadow: 0 10px 25px -5px rgba(0,0,0,0.2);
    white-space: nowrap;
    z-index: 1050;
    font-size: 0.85rem;
    line-height: 1.6;
}
.footer-right:hover .contact-tooltip {
    display: block;
}

.main-header .btn-logout {
    background-color: transparent !important;
    border: 1px solid #e2e8f0;
    color: var(--header-text) !important;
    font-weight: 500;
    padding: 8px 16px;
    border-radius: 6px;
    transition: all 0.2s;
    font-size: 0.88rem;
    display: flex;
    align-items: center;
    gap: 8px;
}
.main-header .btn-logout:hover {
    background-color: #f8fafc !important;
    border-color: #cbd5e1;
    color: #0f172a !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.02);
}

/* Mobile Adjustments */
@media (max-width: 991px) {
    .sidebar {
        top: 64px;
        left: -300px;
        width: 300px;
        height: calc(100vh - 64px);
        z-index: 2000;
        box-shadow: 4px 0 24px rgba(0,0,0,0.15);
    }
    .sidebar.mobile-show {
        left: 0;
    }
    .main-header {
        left: 0 !important;
        width: 100% !important;
        z-index: 2100;
        padding: 0 1rem;
    }
    .main-header b {
        font-size: 0.95rem;
    }
    main.content {
        margin-left: 0 !important;
        width: 100% !important;
        padding: 20px;
        padding-top: 84px;
    }
    .footer {
        margin-left: 0 !important;
        width: 100% !important;
        padding: 0 1rem;
        flex-direction: row;
        justify-content: space-between;
    }
    .backdrop {
        display: none;
        position: fixed;
        top: 64px;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(15, 23, 42, 0.5);
        backdrop-filter: blur(3px);
        z-index: 1900;
        transition: opacity 0.3s;
    }
    .backdrop.show {
        display: block;
    }
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
                    <div class="role">SuperUser</div>
                </div>

                <!-- Dashboard button without submenu -->
                <div class="nav-item">
                      <a href="{{ url('dashboard/sahitya') }}" class="nav-link">
                        <i class="bi bi-speedometer2"></i>
                        <span class="link-text">HOME</span>
                    </a>
                </div>

                <div class="nav-item">
                     <a href="{{ url('/shramnopasak/all-view') }}"  class="nav-link">
                        <i class="bi bi-speedometer2"></i>
                        <span class="link-text">SHRAMNOPASAK ALL BOOKS</span>
                    </a>
                </div>
             
                <div class="nav-item">
                     <a href="{{ route('shramnopasak.daily_news') }}"  class="nav-link">
                        <i class="bi bi-newspaper"></i>
                        <span class="link-text">Daily News</span>
                    </a>
                </div>

                <div class="nav-item">
                     <a href="{{ route('shramnopasak.news_comments') }}"  class="nav-link">
                        <i class="bi bi-chat-dots"></i>
                        <span class="link-text">News Comments</span>
                    </a>
                </div>

                <div class="nav-item">
                     <a href="{{ route('shramnopasak.news_advertisement') }}"  class="nav-link">
                        <i class="bi bi-megaphone"></i>
                        <span class="link-text">News Advertisement</span>
                    </a>
                </div>
             
                <div class="nav-item">
                     <a href="{{ url('/chaturmas-suchi') }}"  class="nav-link">
                        <i class="bi bi-speedometer2"></i>
                        <span class="link-text">CHATURMAS SHUCIYA</span>
                    </a>
                </div>

               <div class="nav-item">
                     <a href="{{ url('/pakhi') }}"  class="nav-link">
                        <i class="bi bi-speedometer2"></i>
                        <span class="link-text">Pakhi Ka paana</span>
                    </a>
                </div>

                 <div class="nav-item">
    <div class="nav-link menu-item d-flex align-items-center" onclick="toggleSubmenu(this)">
        <i class="bi bi-diagram-3"></i>
        <span class="link-text"> Change Password</span>
        <i class="bi bi-chevron-down submenu-toggle"></i>
    </div>
    <div class="submenu">
                         <a href="{{ url('/change-password_shramnopasak') }}" class="nav-link">
                            <i class="bi bi-lock"></i>
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

</body>
</html> 
