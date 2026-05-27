<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8" />
    <title>@yield('title', 'Mahila Samiti Admin')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">

<style>
/* ============================================================
   DESIGN TOKENS
   ============================================================ */
:root {
    --sidebar-w: 260px;
    --sidebar-collapsed: 72px;
    --header-h: 58px;
    --footer-h: 42px;

    /* Brand palette */
    --brand-from:  #c94b4b;
    --brand-mid:   #ee0979;
    --brand-to:    #ff6a00;
    --brand-grad:  linear-gradient(135deg, var(--brand-from) 0%, var(--brand-mid) 50%, var(--brand-to) 100%);
    --brand-soft:  rgba(238,9,121,0.08);

    --sidebar-bg:    #140d1f;
    --sidebar-item:  rgba(255,255,255,0.06);
    --sidebar-hover: rgba(238,9,121,0.18);
    --sidebar-active:rgba(238,9,121,0.25);
    --sidebar-text:  rgba(255,255,255,0.72);
    --sidebar-muted: rgba(255,255,255,0.38);

    --content-bg: #f0f2f8;
    --card-bg:    #ffffff;
    --text-dark:  #1a1f36;
    --text-mid:   #5b6178;

    --radius-lg: 16px;
    --radius-md: 10px;
    --shadow-sm: 0 2px 12px rgba(0,0,0,0.07);
    --shadow-md: 0 6px 24px rgba(0,0,0,0.12);
    --shadow-brand: 0 8px 32px rgba(238,9,121,0.25);

    --transition: 0.28s cubic-bezier(.4,0,.2,1);
}

/* ============================================================
   RESET / BASE
   ============================================================ */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body {
    font-family: 'Inter', sans-serif;
    background: var(--content-bg);
    color: var(--text-dark);
    overflow-x: hidden;
}

/* ============================================================
   SCROLLBAR
   ============================================================ */
::-webkit-scrollbar { width: 6px; height: 6px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: rgba(238,9,121,0.3); border-radius: 8px; }
::-webkit-scrollbar-thumb:hover { background: var(--brand-mid); }

/* ============================================================
   SIDEBAR
   ============================================================ */
.sidebar {
    position: fixed;
    top: 0; left: 0;
    height: 100vh;
    width: var(--sidebar-w);
    background: var(--sidebar-bg);
    display: flex;
    flex-direction: column;
    transition: width var(--transition);
    z-index: 1100;
    overflow: hidden;
}
.sidebar.collapsed { width: var(--sidebar-collapsed); }

/* ---- Brand strip at top ---- */
.sidebar-brand {
    height: var(--header-h);
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 0 18px;
    background: var(--brand-grad);
    flex-shrink: 0;
    overflow: hidden;
    position: relative;
}
.sidebar-brand::after {
    content: '';
    position: absolute;
    right: -20px; top: -20px;
    width: 80px; height: 80px;
    border-radius: 50%;
    background: rgba(255,255,255,0.08);
}
.sidebar-brand-icon {
    width: 34px; height: 34px;
    background: rgba(255,255,255,0.2);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem;
    color: #fff;
    flex-shrink: 0;
    backdrop-filter: blur(4px);
    border: 1px solid rgba(255,255,255,0.25);
}
.sidebar-brand-text {
    font-family: 'Poppins', sans-serif;
    font-size: 0.78rem;
    font-weight: 700;
    color: #fff;
    line-height: 1.25;
    white-space: nowrap;
    opacity: 1;
    transition: opacity var(--transition), width var(--transition);
}
.sidebar.collapsed .sidebar-brand-text { opacity: 0; width: 0; }

/* ---- User info strip ---- */
.sidebar-user {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 18px 16px 14px;
    border-bottom: 1px solid rgba(255,255,255,0.06);
    overflow: hidden;
    flex-shrink: 0;
}
.sidebar-user-avatar {
    width: 40px; height: 40px;
    border-radius: 12px;
    background: var(--brand-grad);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem;
    color: #fff;
    flex-shrink: 0;
    border: 2px solid rgba(238,9,121,0.4);
    box-shadow: 0 0 12px rgba(238,9,121,0.3);
}
.sidebar-user-info { overflow: hidden; white-space: nowrap; }
.sidebar-user-info .name {
    font-weight: 700;
    font-size: 0.85rem;
    color: #fff;
}
.sidebar-user-info .role {
    font-size: 0.72rem;
    color: var(--sidebar-muted);
    display: flex;
    align-items: center;
    gap: 4px;
    margin-top: 1px;
}
.sidebar-user-info .role::before {
    content: '';
    width: 6px; height: 6px;
    border-radius: 50%;
    background: #22c55e;
    flex-shrink: 0;
}
.sidebar.collapsed .sidebar-user-info { display: none; }

/* ---- Nav area ---- */
.sidebar-nav {
    flex: 1;
    overflow-y: auto;
    overflow-x: hidden;
    padding: 12px 10px;
    scrollbar-width: thin;
    scrollbar-color: rgba(238,9,121,0.3) transparent;
}

/* ---- Nav section label ---- */
.nav-section-label {
    font-size: 0.65rem;
    font-weight: 700;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    color: var(--sidebar-muted);
    padding: 14px 10px 6px;
    white-space: nowrap;
    overflow: hidden;
}
.sidebar.collapsed .nav-section-label { opacity: 0; }

/* ---- Nav item ---- */
.nav-item { margin: 2px 0; }

/* ---- Nav link ---- */
.nav-link {
    display: flex;
    align-items: center;
    gap: 12px;
    color: var(--sidebar-text);
    text-decoration: none;
    padding: 10px 12px;
    border-radius: 10px;
    transition: all 0.2s;
    cursor: pointer;
    white-space: nowrap;
    position: relative;
    min-height: 42px;
    background: transparent;
    border: none;
    width: 100%;
}
.nav-link i.nav-icon {
    font-size: 1.05rem;
    min-width: 22px;
    text-align: center;
    flex-shrink: 0;
}
.nav-link .nav-text {
    font-size: 0.855rem;
    font-weight: 500;
    flex: 1;
    opacity: 1;
    transition: opacity var(--transition);
}
.sidebar.collapsed .nav-link .nav-text { opacity: 0; width: 0; overflow: hidden; }

.nav-link:hover {
    background: var(--sidebar-hover);
    color: #fff;
}
.nav-link.active {
    background: var(--sidebar-active);
    color: #fff;
    box-shadow: inset 3px 0 0 var(--brand-mid);
}

/* ---- Arrow toggle ---- */
.nav-arrow {
    font-size: 0.7rem;
    margin-left: auto;
    transition: transform 0.2s;
    flex-shrink: 0;
    color: var(--sidebar-muted);
}
.nav-arrow.open { transform: rotate(180deg); color: #fff; }
.sidebar.collapsed .nav-arrow { display: none; }

/* ---- Submenu ---- */
.submenu {
    display: none;
    flex-direction: column;
    padding-left: 14px;
    gap: 2px;
    margin-top: 2px;
}
.submenu.show { display: flex; }
.sidebar.collapsed .submenu { display: none !important; }

.submenu .nav-link {
    padding: 8px 12px;
    font-size: 0.82rem;
    min-height: 36px;
    border-radius: 8px;
}
.submenu .nav-link::before {
    content: '';
    width: 5px; height: 5px;
    border-radius: 50%;
    background: var(--sidebar-muted);
    flex-shrink: 0;
    transition: background 0.2s;
}
.submenu .nav-link:hover::before,
.submenu .nav-link.active::before { background: var(--brand-mid); }

/* ---- Sidebar bottom ---- */
.sidebar-bottom {
    padding: 12px 10px;
    border-top: 1px solid rgba(255,255,255,0.06);
    flex-shrink: 0;
}
.sidebar-bottom .nav-link {
    color: #fb7185;
}
.sidebar-bottom .nav-link:hover {
    background: rgba(251,113,133,0.15);
    color: #fb7185;
}

/* Tooltip when collapsed */
.sidebar.collapsed .nav-link[data-label]::after {
    content: attr(data-label);
    position: absolute;
    left: calc(var(--sidebar-collapsed) + 8px);
    background: #2d1f3d;
    color: #fff;
    font-size: 0.78rem;
    white-space: nowrap;
    padding: 5px 12px;
    border-radius: 8px;
    pointer-events: none;
    opacity: 0;
    transition: opacity 0.15s;
    z-index: 9999;
    box-shadow: var(--shadow-md);
}
.sidebar.collapsed .nav-link[data-label]:hover::after { opacity: 1; }

/* ============================================================
   HEADER
   ============================================================ */
.main-header {
    position: fixed;
    top: 0;
    left: var(--sidebar-w);
    right: 0;
    height: var(--header-h);
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 24px;
    z-index: 1000;
    transition: left var(--transition);
    box-shadow: 0 1px 0 rgba(0,0,0,0.08), var(--shadow-sm);
}
.sidebar.collapsed ~ .main-header { left: var(--sidebar-collapsed); }

.header-left {
    display: flex;
    align-items: center;
    gap: 14px;
}
.header-toggle {
    width: 36px; height: 36px;
    border-radius: 10px;
    background: var(--brand-soft);
    border: none;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem;
    color: var(--brand-mid);
    cursor: pointer;
    transition: all 0.2s;
    flex-shrink: 0;
}
.header-toggle:hover {
    background: var(--brand-mid);
    color: #fff;
    box-shadow: 0 4px 12px rgba(238,9,121,0.3);
}
.header-title {
    font-family: 'Poppins', sans-serif;
    font-size: 0.92rem;
    font-weight: 700;
    color: var(--text-dark);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 360px;
}
.header-title span {
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.header-badge {
    background: var(--brand-grad);
    color: #fff;
    font-size: 0.68rem;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 20px;
    letter-spacing: 0.3px;
}

.header-right {
    display: flex;
    align-items: center;
    gap: 10px;
}

/* Datetime pill */
.header-datetime {
    background: #f4f5f9;
    border-radius: 20px;
    padding: 5px 14px;
    font-size: 0.78rem;
    color: var(--text-mid);
    display: flex;
    align-items: center;
    gap: 6px;
    font-weight: 500;
}
.header-datetime i { color: var(--brand-mid); }

/* Logout btn */
.btn-logout {
    display: flex;
    align-items: center;
    gap: 6px;
    background: var(--brand-grad);
    border: none;
    color: #fff;
    padding: 7px 16px;
    border-radius: 10px;
    font-size: 0.82rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    box-shadow: 0 2px 8px rgba(238,9,121,0.3);
    white-space: nowrap;
}
.btn-logout:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 16px rgba(238,9,121,0.45);
}

/* ============================================================
   LAYOUT WRAPPER
   ============================================================ */
.layout-wrapper {
    display: flex;
}

/* ============================================================
   MAIN CONTENT
   ============================================================ */
main.content {
    margin-left: var(--sidebar-w);
    margin-top: var(--header-h);
    padding: 28px 28px calc(var(--footer-h) + 28px);
    min-height: 100vh;
    width: 100%;
    transition: margin-left var(--transition);
    background: var(--content-bg);
}
.sidebar.collapsed ~ main.content { margin-left: var(--sidebar-collapsed); }

/* ============================================================
   FOOTER
   ============================================================ */
.main-footer {
    position: fixed;
    bottom: 0;
    left: var(--sidebar-w);
    right: 0;
    height: var(--footer-h);
    background: var(--sidebar-bg);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 20px;
    z-index: 1000;
    transition: left var(--transition);
    border-top: 1px solid rgba(255,255,255,0.05);
}
.sidebar.collapsed ~ .main-footer { left: var(--sidebar-collapsed); }
.footer-left {
    font-size: 0.75rem;
    color: rgba(255,255,255,0.5);
    display: flex;
    align-items: center;
    gap: 6px;
}
.footer-left i { color: var(--brand-mid); }
.footer-right {
    position: relative;
}
.footer-contact-btn {
    background: rgba(255,255,255,0.07);
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 8px;
    padding: 4px 12px;
    color: rgba(255,255,255,0.65);
    font-size: 0.73rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s;
}
.footer-contact-btn:hover { background: rgba(238,9,121,0.2); color: #fff; }
.contact-tooltip {
    display: none;
    position: absolute;
    bottom: calc(100% + 10px);
    right: 0;
    background: #fff;
    color: var(--text-dark);
    border-radius: 12px;
    padding: 14px 18px;
    box-shadow: var(--shadow-md);
    white-space: nowrap;
    z-index: 1500;
    font-size: 0.82rem;
    min-width: 200px;
    border-top: 3px solid var(--brand-mid);
}
.contact-tooltip .ct-name {
    font-weight: 700;
    font-size: 0.85rem;
    color: var(--text-dark);
    margin-bottom: 2px;
}
.contact-tooltip .ct-phone {
    color: var(--brand-mid);
    font-weight: 600;
}
.footer-right:hover .contact-tooltip { display: block; }

/* ============================================================
   MOBILE BACKDROP
   ============================================================ */
.backdrop {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    backdrop-filter: blur(3px);
    z-index: 1050;
}
.backdrop.show { display: block; }

/* ============================================================
   RESPONSIVE
   ============================================================ */
@media (max-width: 991px) {
    .sidebar {
        left: calc(-1 * var(--sidebar-w));
        width: var(--sidebar-w);
        transition: left var(--transition);
    }
    .sidebar.mobile-open { left: 0; }

    .main-header {
        left: 0 !important;
        width: 100% !important;
    }
    .header-title { max-width: 180px; font-size: 0.78rem; }
    .header-datetime { display: none; }
    .btn-logout { padding: 6px 10px; font-size: 0.75rem; }

    main.content { margin-left: 0 !important; padding: 18px 14px calc(var(--footer-h) + 18px); }
    .main-footer { left: 0 !important; width: 100% !important; font-size: 0.68rem; }
    .sidebar.collapsed ~ main.content { margin-left: 0; }
}

/* ============================================================
   ACTIVE PAGE DETECTION (JS driven)
   ============================================================ */
.nav-link.page-active {
    background: var(--sidebar-active) !important;
    color: #fff !important;
    box-shadow: inset 3px 0 0 var(--brand-mid);
}
</style>

</head>
<body>

<!-- BACKDROP (mobile) -->
<div class="backdrop" id="sidebarBackdrop"></div>

<!-- ================================================================
     SIDEBAR
     ================================================================ -->
<nav class="sidebar" id="sidebarMenu">

    <!-- Brand strip -->
    <div class="sidebar-brand">
        <div class="sidebar-brand-icon">🌸</div>
        <div class="sidebar-brand-text">महिला समिति<br><span style="font-weight:400;font-size:0.68rem;opacity:0.8;">Admin Panel</span></div>
    </div>

    <!-- User info -->
    <div class="sidebar-user">
        <div class="sidebar-user-avatar"><i class="bi bi-person-fill"></i></div>
        <div class="sidebar-user-info">
            <div class="name">Hello, Admin</div>
            <div class="role">Mahila Samiti</div>
        </div>
    </div>

    <!-- Navigation -->
    <div class="sidebar-nav">

        <!-- Dashboard -->
        <div class="nav-section-label">MAIN</div>
        <div class="nav-item">
            <a href="{{ url('/dashboard/mahila_samiti') }}" class="nav-link" data-label="Dashboard">
                <i class="bi bi-speedometer2 nav-icon"></i>
                <span class="nav-text">Dashboard</span>
            </a>
        </div>

        <!-- General Updates -->
        <div class="nav-section-label">UPDATES</div>

        <div class="nav-item">
            <div class="nav-link" onclick="toggleSubmenu(this)" data-label="General Updates">
                <i class="bi bi-calendar-event-fill nav-icon"></i>
                <span class="nav-text">General Updates</span>
                <i class="bi bi-chevron-down nav-arrow"></i>
            </div>
            <div class="submenu">
                <div class="nav-item">
                    <a href="{{ url('/mahila_events') }}" class="nav-link">
                        <span class="nav-text">Events</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ url('/mahila_aavedan_patra') }}" class="nav-link">
                        <span class="nav-text">आवेदन पत्र</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ url('/mahila_prativedan') }}" class="nav-link">
                        <span class="nav-text">प्रतिवेदन</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ url('/mahila_description') }}" class="nav-link">
                        <span class="nav-text">Description</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Karyakarini -->
        <div class="nav-section-label">कार्यकारिणी</div>

        <div class="nav-item">
            <div class="nav-link" onclick="toggleSubmenu(this)" data-label="कार्यकारिणी">
                <i class="bi bi-diagram-3-fill nav-icon"></i>
                <span class="nav-text">कार्यकारिणी</span>
                <i class="bi bi-chevron-down nav-arrow"></i>
            </div>
            <div class="submenu">
                <div class="nav-item">
                    <a href="{{ url('/mahila_ex_president') }}" class="nav-link">
                        <span class="nav-text">पूर्व अध्यक्ष</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ url('/mahila_pst') }}" class="nav-link">
                        <span class="nav-text">PST (पदाधिकारी)</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ url('/mahila_vp_sec') }}" class="nav-link">
                        <span class="nav-text">VP/SEC सदस्य</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ url('/mahila_pravarti_sanyojika') }}" class="nav-link">
                        <span class="nav-text">प्रवर्ती संयोजक</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ url('/mahila_ksm_members') }}" class="nav-link">
                        <span class="nav-text">कार्यसमिति सदस्य</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Photo & Slider -->
        <div class="nav-section-label">MEDIA</div>

        <div class="nav-item">
            <div class="nav-link" onclick="toggleSubmenu(this)" data-label="Photo & Slider">
                <i class="bi bi-images nav-icon"></i>
                <span class="nav-text">Photo & Slider</span>
                <i class="bi bi-chevron-down nav-arrow"></i>
            </div>
            <div class="submenu">
                <div class="nav-item">
                    <a href="{{ url('/photo_gallery_mahila_samiti') }}" class="nav-link">
                        <span class="nav-text">Photo Gallery</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ url('/mahila_slider') }}" class="nav-link">
                        <span class="nav-text">Mahila Slider</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ url('/mahila_mobile_slider') }}" class="nav-link">
                        <span class="nav-text">Mobile App Slider</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ url('/mahila_home_slider') }}" class="nav-link">
                        <span class="nav-text">Home Slider</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Notifications -->
        <div class="nav-section-label">NOTIFICATIONS</div>

        <div class="nav-item">
            <div class="nav-link" onclick="toggleSubmenu(this)" data-label="Notifications">
                <i class="bi bi-bell-fill nav-icon"></i>
                <span class="nav-text">App Notifications</span>
                <i class="bi bi-chevron-down nav-arrow"></i>
            </div>
            <div class="submenu">
                <div class="nav-item">
                    <a href="{{ url('/send_notification-mahila_Samiti') }}" class="nav-link">
                        <span class="nav-text">Send Notification</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="{{ url('/view_notifications_mahila_samiti') }}" class="nav-link">
                        <span class="nav-text">View Notifications</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Account -->
        <div class="nav-section-label">ACCOUNT</div>

        <div class="nav-item">
            <a href="{{ url('/change-password_mahila_samiti') }}" class="nav-link" data-label="Change Password">
                <i class="bi bi-shield-lock-fill nav-icon"></i>
                <span class="nav-text">Change Password</span>
            </a>
        </div>

    </div><!-- /sidebar-nav -->

    <!-- Bottom: Logout -->
    <div class="sidebar-bottom">
        <div class="nav-item">
            <a href="javascript:void(0)" onclick="doLogout()" class="nav-link" data-label="Logout">
                <i class="bi bi-box-arrow-right nav-icon"></i>
                <span class="nav-text">Logout</span>
            </a>
        </div>
    </div>

</nav>

<!-- ================================================================
     HEADER
     ================================================================ -->
<header class="main-header" id="mainHeader">
    <div class="header-left">
        <button class="header-toggle" id="sidebarToggle" title="Toggle Sidebar">
            <i class="bi bi-list"></i>
        </button>
        <div class="header-title">
            <span>
                🌸 श्री अ.भा. साधुमार्गी जैन महिला समिति
                <span class="header-badge">ADMIN</span>
            </span>
        </div>
    </div>
    <div class="header-right">
        <div class="header-datetime">
            <i class="bi bi-clock-fill"></i>
            <span id="liveTime">--:--</span>
        </div>
        <button class="btn-logout" onclick="doLogout()">
            <i class="bi bi-box-arrow-right"></i>
            <span>Logout</span>
        </button>
    </div>
</header>

@yield('jsp-header')

<!-- ================================================================
     MAIN CONTENT
     ================================================================ -->
<main class="content" id="mainContent">
    @yield('content')
</main>

<!-- ================================================================
     FOOTER
     ================================================================ -->
<footer class="main-footer" id="mainFooter">
    <div class="footer-left">
        <i class="bi bi-heart-fill"></i>
        Admin Panel &copy; {{ date('Y') }} &nbsp;|&nbsp; SABSJS IT CELL
    </div>
    <div class="footer-right">
        <div class="footer-contact-btn">
            <i class="bi bi-headset"></i>
            <span>IT Support</span>
        </div>
        <div class="contact-tooltip">
            <div class="ct-name"><i class="bi bi-person-fill me-1" style="color:#ee0979;"></i> Deepak Acharya</div>
            <div style="font-size:0.75rem;color:#8b92a9;margin-bottom:8px;">Aditya Acharya</div>
            <div class="ct-phone"><i class="bi bi-telephone-fill me-1"></i> +91-9636501008</div>
        </div>
    </div>
</footer>

<!-- ================================================================
     SCRIPTS
     ================================================================ -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
const sidebar   = document.getElementById('sidebarMenu');
const toggleBtn = document.getElementById('sidebarToggle');
const backdrop  = document.getElementById('sidebarBackdrop');
const isMobile  = () => window.innerWidth <= 991;

/* ---- Toggle sidebar ---- */
toggleBtn.addEventListener('click', () => {
    if (isMobile()) {
        sidebar.classList.toggle('mobile-open');
        backdrop.classList.toggle('show');
    } else {
        sidebar.classList.toggle('collapsed');
        localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed') ? '1' : '0');
    }
});

/* ---- Close mobile sidebar on backdrop click ---- */
backdrop.addEventListener('click', () => {
    sidebar.classList.remove('mobile-open');
    backdrop.classList.remove('show');
});

/* ---- Restore collapsed state on desktop ---- */
window.addEventListener('resize', () => {
    if (!isMobile()) {
        sidebar.classList.remove('mobile-open');
        backdrop.classList.remove('show');
    }
});
if (!isMobile() && localStorage.getItem('sidebarCollapsed') === '1') {
    sidebar.classList.add('collapsed');
}

/* ---- Submenu toggle ---- */
function toggleSubmenu(el) {
    const submenu = el.nextElementSibling;
    const arrow   = el.querySelector('.nav-arrow');
    if (!submenu) return;

    // If sidebar is collapsed on desktop, expand it first
    if (!isMobile() && sidebar.classList.contains('collapsed')) {
        sidebar.classList.remove('collapsed');
        localStorage.setItem('sidebarCollapsed', '0');
        setTimeout(() => openSubmenu(submenu, arrow), 300);
        return;
    }
    submenu.classList.contains('show') ? closeSubmenu(submenu, arrow) : openSubmenu(submenu, arrow);
}

function openSubmenu(submenu, arrow) {
    submenu.classList.add('show');
    if (arrow) arrow.classList.add('open');
}
function closeSubmenu(submenu, arrow) {
    submenu.classList.remove('show');
    if (arrow) arrow.classList.remove('open');
}

/* ---- Auto-mark active link ---- */
const currentPath = window.location.pathname;
document.querySelectorAll('.nav-link[href]').forEach(link => {
    if (link.getAttribute('href') && currentPath.includes(link.getAttribute('href').split('/').pop())) {
        link.classList.add('page-active');
        // Open parent submenu
        const parentSubmenu = link.closest('.submenu');
        if (parentSubmenu) {
            parentSubmenu.classList.add('show');
            const parentArrow = parentSubmenu.previousElementSibling?.querySelector('.nav-arrow');
            if (parentArrow) parentArrow.classList.add('open');
        }
    }
});

/* ---- Logout ---- */
function doLogout() {
    window.location.href = "{{ route('logout') }}";
}

/* ---- Live clock ---- */
function updateClock() {
    const now = new Date();
    const h = String(now.getHours()).padStart(2,'0');
    const m = String(now.getMinutes()).padStart(2,'0');
    const el = document.getElementById('liveTime');
    if (el) el.textContent = h + ':' + m;
}
updateClock();
setInterval(updateClock, 30000);
</script>

</body>
</html>
