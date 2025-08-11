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
/* ==== YOUR CSS EXACTLY AS GIVEN ==== */
:root {
    --sidebar-width: 220px;
    --sidebar-collapsed: 100px;
}

body {
    margin: 0;
    background: #fff;
    color: #23262f;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Header */
.main-header {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
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

/* Layout wrapper */
.layout {
    display: flex;
    padding-top: 50px; /* push content below fixed header */
}

/* Sidebar */
.sidebar {
    width: var(--sidebar-collapsed, 80px);
    background: #1e1e2d;
    color: #fff;
    position: fixed;
    top: 50px;
    left: 0;
    height: calc(100vh - 50px);
    transition: width 0.3s ease;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #444 transparent;
}
.sidebar::-webkit-scrollbar {
    width: 6px;
}
.sidebar::-webkit-scrollbar-thumb {
    background-color: #444;
    border-radius: 3px;
}
.sidebar.expanded {
    width: var(--sidebar-width);
}
.sidebar .nav-link span {
    white-space: normal;
    word-break: break-word;
    line-height: 1.2;
}

.sidebar .profile {
    text-align: center;
    padding: 15px 0;
}
.sidebar .profile img {
    width: 50px;    
    height: 50px;
    border-radius: 50%;
    border: 2px solid #00d1ff;
    /* margin-bottom: 5px; */
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
    font-size: 0.8rem;
    /* user-select: none; */
    margin-top: 5px;
}

/* Nav items spacing */
.sidebar .nav-item {
    margin: 4px 0;
}
.sidebar .nav-link {
    display: flex;
    align-items: center;
    gap: 12px;
    color: #ccc;
    text-decoration: none;
    padding: 10px;
    border-radius: 6px;
    margin: 0 8px;
    transition: background 0.2s, color 0.2s;
}
.sidebar .nav-link i {
    font-size: 1.2rem;
}
/* Nav links */
.nav-link {
    min-height: 40px; 
    line-height: 40px; 
    color: #b6bbc7;
    padding: 4px 6px;
    display: flex;
    align-items: center;
    gap: 10px;
    border-radius: 4px;
    margin: 2px 6px;
    text-decoration: none;
    user-select: none;
    white-space: nowrap;
    box-sizing: border-box;
    width: calc(100% - 12px);
}

/* Hover/active */
.sidebar .nav-link:hover,
.sidebar .nav-link.active {
    background: #2a2a40;
    color: #fff;
}
/* Hide text collapsed */
.nav-link span {
    display: none;
}
.sidebar.expanded .nav-link span {
    display: inline;
}

/* Menu items with submenus */
.nav-link.menu-item {
    background: none;
    cursor: pointer;
    justify-content: space-between;
}

/* Icons alignment collapsed */
.sidebar:not(.expanded) .nav-link {
    justify-content: center;
    gap: 0;
    padding: 10px 0;
}
.sidebar:not(.expanded) .submenu-toggle {
    display: none;
}
.sidebar.expanded .nav-link {
    justify-content: flex-start;
    gap: 10px;
}
.sidebar:not(.expanded) .submenu .nav-link {
    justify-content: center !important;
    padding: 8px 0 !important;
}

/* Submenu */
.submenu {
    display: none;
    flex-direction: column;
    padding-left: 20px;
    gap: 4px;
}
.sidebar.expanded .submenu.show {
    display: flex;
}
.submenu .nav-link {
    font-size: 0.9rem;
    padding: 3px 6px;
    color: #aab0c7;
    border-radius: 4px;
    min-height: 36px;
    line-height: 36px;
    margin: 2px 6px;
    width: calc(100% - 12px);
}

/* Icons */
.nav-link i {
    min-width: 15px;
    font-size: 1.2rem;
}

/* Submenu toggle arrow */
.submenu-toggle {
    margin-left: auto;
    transition: transform 0.3s ease;
    font-size: 1rem;
    user-select: none;
}
.submenu-toggle.rotate {
    transform: rotate(180deg);
}

/* Main content */
main.content {
    margin-left: var(--sidebar-collapsed);
    padding: 20px;
    transition: margin-left 0.3s ease;
    width: 100%;
    min-height: calc(100vh - 90px);
    padding-bottom: 60px;
    background: #f8f9fa;
}
.sidebar.expanded ~ main.content {
    margin-left: var(--sidebar-width);
}

/* Footer */
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
.main-header .btn-logout {
    background-color: #343a40 !important;
    border: none;
    color: #fff !important;
    font-weight: bold;
    padding: 5px 12px;
    border-radius: 4px;
}
.main-header .btn-logout:hover {
    background-color: #23272b !important;
}

/* Tooltip hover */
.footer-right:hover .contact-tooltip {
    display: block;
}

/* Mobile */
@media (max-width: 991px) {
    .sidebar {
        left: -100%;
        width: var(--sidebar-width);
        position: fixed;
        overflow-y: auto;
    }
    .sidebar.mobile-show {
        left: 0;
    }
    main.content,
    .footer {
        margin-left: 0 !important;
        width: 100% !important;
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
 

<header class="main-header">
    <button class="sidebar-toggle" id="sidebarToggle"><i class="bi bi-list"></i></button>
    <b><i class="bi bi-speedometer2"></i> श्री अखिल भारतवर्षीय साधुमार्गी जैन संघ </b>
    <button class="btn btn-logout" onclick="window.location.href='/logout'">
        Logout
    </button>
</header>


    <!-- BACKDROP for mobile -->
    <div class="backdrop" id="sidebarBackdrop"></div>

    <!-- LAYOUT -->
    <div class="layout">
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
                     <a href="{{ url('dashboard/shree_sangh') }}" class="nav-link">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                </div>

                <!-- Daily with submenu -->
                <div class="nav-item">
                    <div class="nav-link menu-item d-flex align-items-center" onclick="toggleSubmenu(this)">
                        <i class="bi bi-calendar-day"></i>
                        <span>General Updates</span>
                        <i class="bi bi-chevron-down submenu-toggle"></i>
                    </div>
                    <div class="submenu">
                       
                        <a href="{{ url('/daily-thoughts') }}" class="nav-link">
                            <i class="bi bi-lightbulb"></i>
                            <span>आज का विचार</span>
                        </a>
                        <a href="{{ url('/dashboard/vihar-sewa') }}" class="nav-link">
                            <i class="bi bi-geo-alt"></i>
                            <span>विहार जानकारी</span>
                        </a>
                        <a href="{{ url('/news') }}" class="nav-link">
                            <i class="bi bi-megaphone"></i>
                            <span>NEWS</span>
                        </a>
                        <a href="{{ url('/shivir') }}" class="nav-link">
                            <i class="bi bi-calendar-event"></i>
                            <span>शिविर</span>
                        </a>
                        <a href="{{ url('/aavedan_patra') }}" class="nav-link">
                            <i class="bi bi-file-earmark-text"></i>
                            <span>आवेदन पत्र</span>
                        </a>
                        
                    </div>
                </div>

 <div class="nav-item">
    <div class="nav-link menu-item d-flex align-items-center" onclick="toggleSubmenu(this)">
        <i class="bi bi-diagram-3"></i>
        <span class="link-text">कार्यकारिणी</span>
        <i class="bi bi-chevron-down submenu-toggle"></i>
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
 </div>                <!-- Users -->
                <div class="nav-item">
                    <div class="nav-link menu-item d-flex align-items-center" onclick="toggleSubmenu(this)">
                        <i class="bi bi-people"></i>
                        <span>संघ प्रवृत्तियाँ</span>
                        <i class="bi bi-chevron-down submenu-toggle"></i>
                    </div>
                    <div class="submenu">
                        <a href="{{ route('dharmik_pravartiya') }}" class="nav-link">
                            <i class="bi bi-person"></i>
                            <span>धार्मिक प्रवर्तियाँ</span>
                        </a>
                        <a href="{{ route('jsp.dashboard') }}" class="nav-link">
                            <i class="bi bi-person-plus"></i>
                            <span>JSP</span>
                        </a>
                       
                    </div>
                </div>

                <!-- Settings -->
                <div class="nav-item">
                    <div class="nav-link menu-item d-flex align-items-center" onclick="toggleSubmenu(this)">
                        <i class="bi bi-gear"></i>
                        <span>Photo Gallery</span>
                        <i class="bi bi-chevron-down submenu-toggle"></i>
                    </div>
                    <div class="submenu">
                        <a href="{{ url('/photo_gallery') }}" class="nav-link">
                            <i class="bi bi-person-circle"></i>
                            <span>Add Event Photos</span>
                        </a>
                         <a href="{{ url('/home_slider') }}" class="nav-link">
                            <i class="bi bi-lock"></i>
                            <span>Home Slider</span>
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

// ✅ Toggle sidebar on desktop/mobile
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
