<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8" />
    <title>@yield('title', 'Mahila Samiti — Admin')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">

<style>
/* =============================================================
   DESIGN SYSTEM — MAHILA SAMITI PROFESSIONAL ADMIN
   Palette: Deep Slate sidebar · Rose accent · Clean white content
   ============================================================= */

/* ---------- TOKENS ---------- */
:root {
    /* Layout */
    --sb-w:   256px;
    --sb-col: 68px;
    --hdr-h:  56px;
    --ftr-h:  40px;

    /* Sidebar */
    --sb-bg:          #0f1623;   /* deep navy-slate */
    --sb-border:      rgba(255,255,255,0.055);
    --sb-text:        #94a3b8;   /* slate-400 */
    --sb-text-active: #f1f5f9;   /* slate-100 */
    --sb-label:       #475569;   /* slate-600 */
    --sb-hover-bg:    rgba(226,34,72,0.10);
    --sb-active-bg:   rgba(226,34,72,0.18);
    --sb-active-bar:  #e11d48;   /* rose-600 */

    /* Brand / Accent */
    --rose:      #e11d48;
    --rose-dark: #be123c;
    --rose-soft: rgba(225,29,72,0.10);

    /* Content */
    --bg:        #f8fafc;        /* slate-50 */
    --card:      #ffffff;
    --border:    #e2e8f0;        /* slate-200 */
    --text-1:    #0f172a;        /* slate-900 */
    --text-2:    #475569;        /* slate-600 */
    --text-3:    #94a3b8;        /* slate-400 */

    /* Shadows */
    --shadow-xs: 0 1px 3px rgba(15,23,42,0.06), 0 1px 2px rgba(15,23,42,0.04);
    --shadow-sm: 0 2px 8px rgba(15,23,42,0.08);
    --shadow-md: 0 4px 20px rgba(15,23,42,0.10);
    --shadow-rose: 0 4px 16px rgba(225,29,72,0.28);

    /* Transition */
    --ease: 0.22s cubic-bezier(.4,0,.2,1);
}

/* ---------- RESET ---------- */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

html, body {
    font-family: 'Inter', system-ui, sans-serif;
    background: var(--bg);
    color: var(--text-1);
    overflow-x: hidden;
    -webkit-font-smoothing: antialiased;
}

/* Scrollbar */
::-webkit-scrollbar { width: 5px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: rgba(148,163,184,0.3); border-radius: 10px; }
::-webkit-scrollbar-thumb:hover { background: var(--rose); }

/* =============================================================
   SIDEBAR
   ============================================================= */
#ms-sidebar {
    position: fixed !important;
    inset: 0 auto 0 0 !important;
    width: 256px !important;
    background: var(--sb-bg) !important;
    display: flex !important;
    flex-direction: column !important;
    transition: width var(--ease) !important;
    z-index: 1200 !important;
    overflow: hidden !important;
    border-right: 1px solid var(--sb-border) !important;
}
#ms-sidebar.sb-collapsed { width: 68px !important; }

/* ---- Logo / Brand bar ---- */
.sb-brand {
    height: var(--hdr-h);
    flex-shrink: 0;
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 0 18px;
    border-bottom: 1px solid var(--sb-border);
    overflow: hidden;
}
.sb-brand-logo {
    width: 32px; height: 32px; flex-shrink: 0;
    border-radius: 8px;
    background: var(--rose);
    display: flex; align-items: center; justify-content: center;
    font-size: 0.95rem; color: #fff;
    box-shadow: var(--shadow-rose);
    flex-shrink: 0;
}
.sb-brand-text {
    overflow: hidden;
    transition: opacity var(--ease), width var(--ease);
}
.sb-brand-name {
    font-family: 'Poppins', sans-serif;
    font-size: 0.82rem; font-weight: 700;
    color: #f1f5f9;
    white-space: nowrap;
    line-height: 1.2;
}
.sb-brand-sub {
    font-size: 0.65rem; font-weight: 500;
    color: var(--sb-label);
    white-space: nowrap;
    text-transform: uppercase; letter-spacing: 0.6px;
    margin-top: 1px;
}
#ms-sidebar.sb-collapsed .sb-brand-text { opacity: 0; width: 0; pointer-events: none; }

/* ---- User strip ---- */
.sb-user {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 16px 12px;
    border-bottom: 1px solid var(--sb-border);
    flex-shrink: 0;
    overflow: hidden;
}
.sb-avatar {
    width: 36px; height: 36px; flex-shrink: 0;
    border-radius: 10px;
    background: linear-gradient(135deg, #e11d48 0%, #f43f5e 100%);
    display: flex; align-items: center; justify-content: center;
    font-size: 0.92rem; color: #fff; font-weight: 700;
    letter-spacing: -0.5px;
    flex-shrink: 0;
}
.sb-user-info { overflow: hidden; transition: opacity var(--ease); }
.sb-user-name {
    font-size: 0.82rem; font-weight: 600;
    color: var(--sb-text-active);
    white-space: nowrap;
}
.sb-user-role {
    display: flex; align-items: center; gap: 5px;
    font-size: 0.67rem; color: var(--sb-label);
    margin-top: 2px; white-space: nowrap;
}
.sb-online-dot {
    width: 5px; height: 5px; border-radius: 50%;
    background: #22c55e; flex-shrink: 0;
}
#ms-sidebar.sb-collapsed .sb-user-info { opacity: 0; pointer-events: none; }

/* ---- Nav scroll area ---- */
.sb-nav {
    flex: 1;
    overflow-y: auto; overflow-x: hidden;
    padding: 10px 10px;
    scrollbar-width: thin;
    scrollbar-color: rgba(148,163,184,0.15) transparent;
}

/* ---- Section label ---- */
.sb-section {
    font-size: 0.60rem; font-weight: 700;
    letter-spacing: 1.4px; text-transform: uppercase;
    color: var(--sb-label);
    padding: 14px 8px 5px;
    white-space: nowrap;
    transition: opacity var(--ease);
}
#ms-sidebar.sb-collapsed .sb-section { opacity: 0; }

/* ---- Nav link ---- */
.sb-link {
    display: flex !important;
    align-items: center !important;
    gap: 10px !important;
    padding: 8px 10px !important;
    border-radius: 7px !important;
    color: var(--sb-text) !important;
    text-decoration: none !important;
    font-size: 0.835rem !important;
    font-weight: 500 !important;
    cursor: pointer !important;
    white-space: nowrap !important;
    min-height: 38px !important;
    background: transparent !important;
    border: none !important;
    width: 100% !important;
    transition: background var(--ease), color var(--ease) !important;
    margin-bottom: 1px !important;
    position: relative !important;
}
.sb-link:hover {
    background: var(--sb-hover-bg) !important;
    color: var(--sb-text-active) !important;
    text-decoration: none !important;
}
.sb-link.sb-active {
    background: var(--sb-active-bg) !important;
    color: #fda4af !important;   /* rose-300 */
    box-shadow: inset 3px 0 0 var(--sb-active-bar) !important;
}

/* ---- Icon ---- */
.sb-icon {
    font-size: 0.98rem !important;
    min-width: 20px !important;
    text-align: center !important;
    flex-shrink: 0 !important;
    opacity: 0.75;
}
.sb-link:hover .sb-icon,
.sb-link.sb-active .sb-icon { opacity: 1; }

/* ---- Text ---- */
.sb-txt {
    flex: 1 !important;
    transition: opacity var(--ease) !important;
    overflow: hidden !important;
}
#ms-sidebar.sb-collapsed .sb-txt { opacity: 0 !important; width: 0 !important; pointer-events: none !important; }

/* ---- Arrow ---- */
.sb-arrow {
    font-size: 0.65rem !important;
    color: var(--sb-label) !important;
    margin-left: auto !important;
    flex-shrink: 0 !important;
    transition: transform var(--ease), opacity var(--ease) !important;
}
.sb-arrow.open { transform: rotate(180deg) !important; color: var(--sb-text) !important; }
#ms-sidebar.sb-collapsed .sb-arrow { opacity: 0 !important; pointer-events: none !important; }

/* ---- Submenu ---- */
.sb-sub {
    display: none !important;
    flex-direction: column !important;
    padding-left: 12px !important;
    gap: 0 !important;
    margin-top: 1px !important;
    border-left: 1px solid var(--sb-border) !important;
    margin-left: 16px !important;
}
.sb-sub.open { display: flex !important; }
#ms-sidebar.sb-collapsed .sb-sub { display: none !important; }

.sb-sub .sb-link {
    padding: 6px 10px !important;
    font-size: 0.8rem !important;
    min-height: 32px !important;
    border-radius: 6px !important;
    color: var(--sb-label) !important;
}
.sb-sub .sb-link:hover { color: var(--sb-text-active) !important; }
.sb-sub .sb-link.sb-active { color: #fda4af !important; }

/* Collapsed tooltip */
#ms-sidebar.sb-collapsed .sb-link[data-tip]:hover::after {
    content: attr(data-tip);
    position: fixed;
    left: 76px;
    background: #1e293b;
    color: #f1f5f9;
    font-size: 0.77rem; font-weight: 500;
    white-space: nowrap;
    padding: 5px 12px;
    border-radius: 7px;
    z-index: 9999;
    box-shadow: var(--shadow-md);
    pointer-events: none;
    border: 1px solid rgba(255,255,255,0.08);
}

/* ---- Bottom bar (logout) ---- */
.sb-bottom {
    padding: 10px 10px;
    border-top: 1px solid var(--sb-border);
    flex-shrink: 0;
}
.sb-bottom .sb-link { color: #64748b !important; font-size: 0.82rem !important; }
.sb-bottom .sb-link:hover {
    background: rgba(239,68,68,0.08) !important;
    color: #f87171 !important;
}

/* =============================================================
   HEADER
   ============================================================= */
#ms-header {
    position: fixed !important;
    top: 0 !important;
    left: 256px !important;
    right: 0 !important;
    height: 56px !important;
    background: #ffffff !important;
    border-bottom: 1px solid var(--border) !important;
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    padding: 0 20px !important;
    z-index: 1100 !important;
    transition: left var(--ease) !important;
    box-shadow: var(--shadow-xs) !important;
}
body.sb-is-collapsed #ms-header { left: 68px !important; }

/* Toggle button */
.hdr-toggle {
    width: 34px; height: 34px;
    border-radius: 8px;
    background: transparent;
    border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; color: var(--text-2);
    cursor: pointer;
    transition: all var(--ease);
    flex-shrink: 0;
}
.hdr-toggle:hover { background: var(--rose-soft); border-color: var(--rose); color: var(--rose); }

/* Breadcrumb / Title */
.hdr-title {
    display: flex; align-items: center; gap: 8px;
    margin-left: 12px;
}
.hdr-title-text {
    font-size: 0.875rem; font-weight: 600; color: var(--text-1);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    max-width: 340px;
}
.hdr-badge {
    font-size: 0.62rem; font-weight: 700; letter-spacing: 0.6px;
    text-transform: uppercase;
    background: var(--rose);
    color: #fff;
    padding: 2px 8px; border-radius: 20px;
    flex-shrink: 0;
}

.hdr-left { display: flex; align-items: center; }
.hdr-right { display: flex; align-items: center; gap: 8px; }

/* Clock chip */
.hdr-clock {
    display: flex; align-items: center; gap: 6px;
    padding: 5px 12px;
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: 20px;
    font-size: 0.77rem; font-weight: 600; color: var(--text-2);
}
.hdr-clock i { color: var(--rose); font-size: 0.75rem; }

/* Logout button */
.hdr-logout {
    display: flex; align-items: center; gap: 6px;
    padding: 6px 14px;
    background: var(--rose);
    border: none; border-radius: 8px;
    color: #fff; font-size: 0.78rem; font-weight: 600;
    cursor: pointer;
    box-shadow: 0 1px 4px rgba(225,29,72,0.3);
    transition: all var(--ease);
    white-space: nowrap;
}
.hdr-logout:hover {
    background: var(--rose-dark);
    box-shadow: var(--shadow-rose);
    transform: translateY(-1px);
}

/* Divider */
.hdr-divider {
    width: 1px; height: 20px;
    background: var(--border);
    flex-shrink: 0;
}

/* =============================================================
   CONTENT
   ============================================================= */
#ms-content {
    margin-left: 256px !important;
    margin-top: 56px !important;
    padding: 24px 24px 60px !important;
    min-height: 100vh !important;
    background: var(--bg) !important;
    transition: margin-left var(--ease) !important;
}
body.sb-is-collapsed #ms-content { margin-left: 68px !important; }

/* =============================================================
   FOOTER
   ============================================================= */
#ms-footer {
    position: fixed !important;
    bottom: 0 !important; left: 256px !important; right: 0 !important;
    height: 40px !important;
    background: #ffffff !important;
    border-top: 1px solid var(--border) !important;
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    padding: 0 20px !important;
    z-index: 1000 !important;
    transition: left var(--ease) !important;
}
body.sb-is-collapsed #ms-footer { left: 68px !important; }

.ftr-left {
    display: flex; align-items: center; gap: 6px;
    font-size: 0.72rem; color: var(--text-3); font-weight: 500;
}
.ftr-left i { color: var(--rose); font-size: 0.7rem; }
.ftr-right { position: relative; }

.ftr-support-btn {
    display: flex; align-items: center; gap: 5px;
    padding: 3px 11px;
    border: 1px solid var(--border);
    border-radius: 20px;
    background: transparent;
    font-size: 0.71rem; font-weight: 600; color: var(--text-3);
    cursor: pointer;
    transition: all var(--ease);
}
.ftr-support-btn:hover { border-color: var(--rose); color: var(--rose); background: var(--rose-soft); }
.ftr-support-btn i { font-size: 0.7rem; }

.ftr-tooltip {
    display: none;
    position: absolute; bottom: calc(100% + 8px); right: 0;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 10px; padding: 14px 18px;
    box-shadow: var(--shadow-md);
    min-width: 210px;
    z-index: 1500;
    border-top: 2px solid var(--rose);
}
.ftr-right:hover .ftr-tooltip { display: block; }
.ftr-tooltip .ft-name { font-size: 0.84rem; font-weight: 700; color: var(--text-1); margin-bottom: 2px; }
.ftr-tooltip .ft-sub  { font-size: 0.73rem; color: var(--text-3); margin-bottom: 8px; }
.ftr-tooltip .ft-phone { font-size: 0.8rem; font-weight: 600; color: var(--rose); display: flex; align-items: center; gap: 6px; }

/* =============================================================
   MOBILE BACKDROP
   ============================================================= */
#ms-backdrop {
    display: none;
    position: fixed; inset: 0;
    background: rgba(15,22,36,0.55);
    backdrop-filter: blur(4px);
    z-index: 1150;
}
#ms-backdrop.show { display: block; }

/* =============================================================
   RESPONSIVE
   ============================================================= */
@media (max-width: 991px) {
    #ms-sidebar {
        left: -256px !important;
        width: 256px !important;
        transition: left var(--ease) !important;
        box-shadow: none !important;
    }
    #ms-sidebar.sb-mobile-open {
        left: 0 !important;
        box-shadow: 4px 0 32px rgba(15,22,36,0.35) !important;
    }
    #ms-header  { left: 0 !important; width: 100% !important; }
    #ms-footer  { left: 0 !important; width: 100% !important; }
    #ms-content { margin-left: 0 !important; padding: 16px 14px 56px !important; }

    .hdr-title-text { max-width: 150px; font-size: 0.78rem; }
    .hdr-clock      { display: none; }
    .hdr-logout span { display: none; }
    .hdr-logout     { padding: 6px 10px; }

    body.sb-is-collapsed #ms-header  { left: 0 !important; }
    body.sb-is-collapsed #ms-content { margin-left: 0 !important; }
    body.sb-is-collapsed #ms-footer  { left: 0 !important; }
}
</style>
</head>
<body>

<!-- Backdrop -->
<div id="ms-backdrop"></div>

<!-- ==========================================================
     SIDEBAR
     ========================================================== -->
<nav id="ms-sidebar">

    <!-- Brand -->
    <div class="sb-brand">
        <div class="sb-brand-logo">🌸</div>
        <div class="sb-brand-text">
            <div class="sb-brand-name">Mahila Samiti</div>
            <div class="sb-brand-sub">Admin Panel</div>
        </div>
    </div>

    <!-- User -->
    <div class="sb-user">
        <div class="sb-avatar">A</div>
        <div class="sb-user-info">
            <div class="sb-user-name">Hello, Admin</div>
            <div class="sb-user-role">
                <span class="sb-online-dot"></span>
                Mahila Samiti
            </div>
        </div>
    </div>

    <!-- Nav -->
    <div class="sb-nav">

        <!-- Main -->
        <div class="sb-section">Main</div>
        <a href="{{ url('/dashboard/mahila_samiti') }}" class="sb-link" data-tip="Dashboard">
            <i class="bi bi-grid-1x2-fill sb-icon"></i>
            <span class="sb-txt">Dashboard</span>
        </a>

        <!-- Updates -->
        <div class="sb-section">Updates</div>
        <div class="sb-link" onclick="toggleSub(this)" data-tip="General Updates">
            <i class="bi bi-collection-fill sb-icon"></i>
            <span class="sb-txt">General Updates</span>
            <i class="bi bi-chevron-down sb-arrow"></i>
        </div>
        <div class="sb-sub">
            <a href="{{ url('/mahila_events') }}"        class="sb-link"><span class="sb-txt">Events</span></a>
            <a href="{{ url('/mahila_aavedan_patra') }}" class="sb-link"><span class="sb-txt">आवेदन पत्र</span></a>
            <a href="{{ url('/mahila_prativedan') }}"    class="sb-link"><span class="sb-txt">प्रतिवेदन</span></a>
            <a href="{{ url('/mahila_description') }}"   class="sb-link"><span class="sb-txt">Description</span></a>
            <a href="{{ url('/mahila_pravartiya') }}"    class="sb-link"><span class="sb-txt">प्रवर्तिया</span></a>
        </div>

        <!-- Karyakarini -->
        <div class="sb-section">कार्यकारिणी</div>
        <div class="sb-link" onclick="toggleSub(this)" data-tip="कार्यकारिणी">
            <i class="bi bi-diagram-3 sb-icon"></i>
            <span class="sb-txt">कार्यकारिणी</span>
            <i class="bi bi-chevron-down sb-arrow"></i>
        </div>
        <div class="sb-sub">
            <a href="{{ url('/mahila_ex_president') }}"       class="sb-link"><span class="sb-txt">पूर्व अध्यक्ष</span></a>
            <a href="{{ url('/mahila_pst') }}"                class="sb-link"><span class="sb-txt">PST · पदाधिकारी</span></a>
            <a href="{{ url('/mahila_vp_sec') }}"             class="sb-link"><span class="sb-txt">VP / SEC</span></a>
            <a href="{{ url('/mahila_pravarti_sanyojika') }}" class="sb-link"><span class="sb-txt">प्रवर्ती संयोजक</span></a>
            <a href="{{ url('/mahila_ksm_members') }}"        class="sb-link"><span class="sb-txt">कार्यसमिति सदस्य</span></a>
        </div>

        <!-- Media -->
        <div class="sb-section">Media</div>
        <div class="sb-link" onclick="toggleSub(this)" data-tip="Photo & Slider">
            <i class="bi bi-images sb-icon"></i>
            <span class="sb-txt">Photo & Slider</span>
            <i class="bi bi-chevron-down sb-arrow"></i>
        </div>
        <div class="sb-sub">
            <a href="{{ url('/photo_gallery_mahila_samiti') }}" class="sb-link"><span class="sb-txt">Photo Gallery</span></a>
            <a href="{{ url('/mahila_slider') }}"               class="sb-link"><span class="sb-txt">Mahila Slider</span></a>
            <a href="{{ url('/mahila_mobile_slider') }}"        class="sb-link"><span class="sb-txt">Mobile Slider</span></a>
            <a href="{{ url('/mahila_home_slider') }}"          class="sb-link"><span class="sb-txt">Home Slider</span></a>
        </div>

        <!-- Notifications -->
        <div class="sb-section">Notifications</div>
        <div class="sb-link" onclick="toggleSub(this)" data-tip="Notifications">
            <i class="bi bi-bell sb-icon"></i>
            <span class="sb-txt">App Notifications</span>
            <i class="bi bi-chevron-down sb-arrow"></i>
        </div>
        <div class="sb-sub">
            <a href="{{ url('/send_notification-mahila_Samiti') }}"  class="sb-link"><span class="sb-txt">Send Notification</span></a>
            <a href="{{ url('/view_notifications_mahila_samiti') }}"  class="sb-link"><span class="sb-txt">View Notifications</span></a>
        </div>

        <!-- Account -->
        <div class="sb-section">Account</div>
        <a href="{{ url('/change-password_mahila_samiti') }}" class="sb-link" data-tip="Change Password">
            <i class="bi bi-shield-lock sb-icon"></i>
            <span class="sb-txt">Change Password</span>
        </a>

    </div><!-- /sb-nav -->

    <!-- Logout -->
    <div class="sb-bottom">
        <a href="javascript:void(0)" onclick="msLogout()" class="sb-link" data-tip="Logout">
            <i class="bi bi-arrow-right-square sb-icon"></i>
            <span class="sb-txt">Sign Out</span>
        </a>
    </div>

</nav>

<!-- ==========================================================
     HEADER
     ========================================================== -->
<header id="ms-header">
    <div class="hdr-left">
        <button class="hdr-toggle" id="ms-toggle" title="Toggle Sidebar">
            <i class="bi bi-layout-sidebar"></i>
        </button>
        <div class="hdr-title">
            <div class="hdr-title-text">
                श्री अ.भा. साधुमार्गी जैन महिला समिति
            </div>
            <span class="hdr-badge">Admin</span>
        </div>
    </div>

    <div class="hdr-right">
        <div class="hdr-clock">
            <i class="bi bi-clock"></i>
            <span id="ms-clock">--:--</span>
        </div>
        <div class="hdr-divider"></div>
        <button class="hdr-logout" onclick="msLogout()">
            <i class="bi bi-power"></i>
            <span>Logout</span>
        </button>
    </div>
</header>

@yield('jsp-header')

<!-- ==========================================================
     CONTENT
     ========================================================== -->
<main id="ms-content">
    @yield('content')
</main>

<!-- ==========================================================
     FOOTER
     ========================================================== -->
<footer id="ms-footer">
    <div class="ftr-left">
        <i class="bi bi-c-circle-fill"></i>
        {{ date('Y') }} · SABSJS IT Cell · Mahila Samiti Admin
    </div>
    <div class="ftr-right">
        <div class="ftr-support-btn">
            <i class="bi bi-headset"></i>
            IT Support
        </div>
        <div class="ftr-tooltip">
            <div class="ft-name">Deepak Acharya</div>
            <div class="ft-sub">Aditya Acharya &nbsp;·&nbsp; SABSJS IT Cell</div>
            <div class="ft-phone"><i class="bi bi-telephone-fill"></i> +91&nbsp;96365&nbsp;01008</div>
        </div>
    </div>
</footer>

<!-- ==========================================================
     SCRIPTS
     ========================================================== -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function () {
    var sb      = document.getElementById('ms-sidebar');
    var toggle  = document.getElementById('ms-toggle');
    var bd      = document.getElementById('ms-backdrop');

    function isMob() { return window.innerWidth <= 991; }

    /* Restore state on desktop */
    if (!isMob() && localStorage.getItem('ms_col') === '1') {
        sb.classList.add('sb-collapsed');
        document.body.classList.add('sb-is-collapsed');
    }

    /* Toggle */
    toggle.addEventListener('click', function () {
        if (isMob()) {
            sb.classList.toggle('sb-mobile-open');
            bd.classList.toggle('show');
        } else {
            var c = sb.classList.toggle('sb-collapsed');
            document.body.classList.toggle('sb-is-collapsed', c);
            localStorage.setItem('ms_col', c ? '1' : '0');
        }
    });

    /* Close mobile sidebar on backdrop */
    bd.addEventListener('click', function () {
        sb.classList.remove('sb-mobile-open');
        bd.classList.remove('show');
    });

    /* Resize */
    window.addEventListener('resize', function () {
        if (!isMob()) {
            sb.classList.remove('sb-mobile-open');
            bd.classList.remove('show');
        }
    });

    /* Submenu */
    window.toggleSub = function (el) {
        if (!isMob() && sb.classList.contains('sb-collapsed')) {
            sb.classList.remove('sb-collapsed');
            document.body.classList.remove('sb-is-collapsed');
            localStorage.setItem('ms_col', '0');
            setTimeout(function () { _openSub(el); }, 240);
            return;
        }
        var sub   = el.nextElementSibling;
        var arrow = el.querySelector('.sb-arrow');
        if (!sub) return;
        sub.classList.contains('open') ? _closeSub(sub, arrow) : _openSub(el);
    };
    function _openSub(el) {
        var sub = el.nextElementSibling, arrow = el.querySelector('.sb-arrow');
        if (sub)   sub.classList.add('open');
        if (arrow) arrow.classList.add('open');
    }
    function _closeSub(sub, arrow) {
        if (sub)   sub.classList.remove('open');
        if (arrow) arrow.classList.remove('open');
    }

    /* Auto-active + auto-open parent */
    var path = window.location.pathname;
    document.querySelectorAll('#ms-sidebar a.sb-link').forEach(function (a) {
        var href = (a.getAttribute('href') || '').trim();
        if (!href || href === 'javascript:void(0)') return;
        /* exact match or ends-with segment match */
        var seg = href.split('/').filter(Boolean).pop();
        if (seg && path.includes(seg)) {
            a.classList.add('sb-active');
            var parentSub = a.closest('.sb-sub');
            if (parentSub) {
                parentSub.classList.add('open');
                var t = parentSub.previousElementSibling;
                if (t) { var ar = t.querySelector('.sb-arrow'); if (ar) ar.classList.add('open'); }
            }
        }
    });

    /* Logout */
    window.msLogout = function () { window.location.href = '{{ route("logout") }}'; };

    /* Live clock */
    function tick() {
        var el = document.getElementById('ms-clock');
        if (!el) return;
        var n = new Date();
        el.textContent = ('0'+n.getHours()).slice(-2) + ':' + ('0'+n.getMinutes()).slice(-2);
    }
    tick();
    setInterval(tick, 30000);
})();
</script>

</body>
</html>
