<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8" />
    <title>@yield('title', 'Mahila Samiti Admin')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap 5 — loaded ONCE here; pages must NOT reload it -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">

<style>
/* =========================================================
   CSS VARIABLES
   ========================================================= */
:root {
    --sb-width:     248px;   /* sidebar expanded */
    --sb-collapsed: 72px;    /* sidebar collapsed */
    --hdr-height:   58px;
    --ftr-height:   42px;
    --brand-from:   #c94b4b;
    --brand-mid:    #ee0979;
    --brand-to:     #ff6a00;
    --sb-bg:        #140d1f;
    --sb-text:      rgba(255,255,255,0.72);
    --sb-muted:     rgba(255,255,255,0.38);
    --sb-hover:     rgba(238,9,121,0.18);
    --sb-active:    rgba(238,9,121,0.28);
    --content-bg:   #f0f2f8;
    --transition:   0.28s cubic-bezier(.4,0,.2,1);
}

/* =========================================================
   RESET
   ========================================================= */
*, *::before, *::after { box-sizing: border-box; }

html, body {
    margin: 0; padding: 0;
    font-family: 'Inter', sans-serif;
    background: var(--content-bg);
    color: #1a1f36;
    overflow-x: hidden;
}

/* Scrollbar */
::-webkit-scrollbar { width: 5px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: rgba(238,9,121,0.35); border-radius: 8px; }

/* =========================================================
   SIDEBAR  — fixed left panel
   Positioning done with HARD px values to avoid CSS-var
   overrides from inner-page stylesheets.
   ========================================================= */
#ms-sidebar {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    height: 100vh !important;
    width: 248px !important;          /* == --sb-width */
    background: #140d1f !important;
    display: flex !important;
    flex-direction: column !important;
    transition: width 0.28s cubic-bezier(.4,0,.2,1) !important;
    z-index: 1200 !important;
    overflow: hidden !important;
}
#ms-sidebar.sb-collapsed {
    width: 72px !important;           /* == --sb-collapsed */
}

/* ---- Brand strip ---- */
#ms-sidebar .sb-brand {
    height: 58px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 0 16px;
    background: linear-gradient(135deg, #c94b4b 0%, #ee0979 55%, #ff6a00 100%);
    overflow: hidden;
    position: relative;
}
#ms-sidebar .sb-brand::after {
    content: '';
    position: absolute;
    right: -24px; top: -24px;
    width: 90px; height: 90px;
    border-radius: 50%;
    background: rgba(255,255,255,0.07);
    pointer-events: none;
}
#ms-sidebar .sb-brand-icon {
    width: 34px; height: 34px;
    border-radius: 10px;
    background: rgba(255,255,255,0.18);
    border: 1px solid rgba(255,255,255,0.28);
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; color: #fff;
    flex-shrink: 0;
}
#ms-sidebar .sb-brand-text {
    font-family: 'Poppins', sans-serif;
    font-size: 0.78rem; font-weight: 700;
    color: #fff; line-height: 1.25;
    white-space: nowrap;
    transition: opacity 0.2s;
}
#ms-sidebar.sb-collapsed .sb-brand-text { opacity: 0; pointer-events: none; }

/* ---- User strip ---- */
#ms-sidebar .sb-user {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 14px 12px;
    border-bottom: 1px solid rgba(255,255,255,0.07);
    flex-shrink: 0;
    overflow: hidden;
}
#ms-sidebar .sb-user-avatar {
    width: 38px; height: 38px;
    border-radius: 10px;
    background: linear-gradient(135deg,#ee0979,#ff6a00);
    border: 2px solid rgba(238,9,121,0.5);
    box-shadow: 0 0 10px rgba(238,9,121,0.3);
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; color: #fff;
    flex-shrink: 0;
}
#ms-sidebar .sb-user-info {
    overflow: hidden; white-space: nowrap;
    transition: opacity 0.2s;
}
#ms-sidebar .sb-user-info .sb-name {
    font-weight: 700; font-size: 0.83rem; color: #fff;
}
#ms-sidebar .sb-user-info .sb-role {
    font-size: 0.7rem; color: var(--sb-muted);
    display: flex; align-items: center; gap: 4px; margin-top: 2px;
}
#ms-sidebar .sb-user-info .sb-role::before {
    content: ''; width: 6px; height: 6px;
    border-radius: 50%; background: #22c55e; flex-shrink: 0;
}
#ms-sidebar.sb-collapsed .sb-user-info { opacity: 0; pointer-events: none; }

/* ---- Nav scroll area ---- */
#ms-sidebar .sb-nav {
    flex: 1;
    overflow-y: auto; overflow-x: hidden;
    padding: 10px 8px;
    scrollbar-width: thin;
    scrollbar-color: rgba(238,9,121,0.25) transparent;
}

/* ---- Section label ---- */
#ms-sidebar .sb-section {
    font-size: 0.62rem; font-weight: 700;
    letter-spacing: 1.2px; text-transform: uppercase;
    color: var(--sb-muted);
    padding: 13px 8px 5px;
    white-space: nowrap;
    transition: opacity 0.2s;
}
#ms-sidebar.sb-collapsed .sb-section { opacity: 0; }

/* ---- Nav link base ---- */
#ms-sidebar .sb-link {
    display: flex !important;
    align-items: center !important;
    gap: 12px !important;
    padding: 10px 11px !important;
    border-radius: 10px !important;
    color: var(--sb-text) !important;
    text-decoration: none !important;
    cursor: pointer !important;
    white-space: nowrap !important;
    min-height: 42px !important;
    background: transparent !important;
    border: none !important;
    width: 100% !important;
    transition: background 0.18s, color 0.18s !important;
    margin: 1px 0 !important;
    position: relative !important;
}
#ms-sidebar .sb-link:hover {
    background: rgba(238,9,121,0.18) !important;
    color: #fff !important;
    text-decoration: none !important;
}
#ms-sidebar .sb-link.sb-active {
    background: rgba(238,9,121,0.28) !important;
    color: #fff !important;
    box-shadow: inset 3px 0 0 #ee0979 !important;
}

/* ---- Icon in nav ---- */
#ms-sidebar .sb-link .sb-icon {
    font-size: 1.05rem;
    min-width: 22px;
    text-align: center;
    flex-shrink: 0;
}

/* ---- Text in nav — hide when collapsed ---- */
#ms-sidebar .sb-link .sb-txt {
    font-size: 0.845rem; font-weight: 500;
    flex: 1;
    transition: opacity 0.2s;
    white-space: nowrap;
    overflow: hidden;
}
#ms-sidebar.sb-collapsed .sb-link .sb-txt { opacity: 0; pointer-events: none; }

/* ---- Arrow ---- */
#ms-sidebar .sb-arrow {
    font-size: 0.68rem; color: var(--sb-muted);
    transition: transform 0.2s, opacity 0.2s;
    flex-shrink: 0; margin-left: auto;
}
#ms-sidebar .sb-arrow.open { transform: rotate(180deg); color: #fff; }
#ms-sidebar.sb-collapsed .sb-arrow { opacity: 0; pointer-events: none; }

/* ---- Submenu ---- */
#ms-sidebar .sb-sub {
    display: none; flex-direction: column;
    padding-left: 14px; gap: 1px; margin-top: 1px;
}
#ms-sidebar .sb-sub.open { display: flex; }
#ms-sidebar.sb-collapsed .sb-sub { display: none !important; }

#ms-sidebar .sb-sub .sb-link {
    padding: 8px 10px !important;
    font-size: 0.81rem !important;
    min-height: 36px !important;
    border-radius: 8px !important;
}
#ms-sidebar .sb-sub .sb-link::before {
    content: '';
    width: 5px; height: 5px; border-radius: 50%;
    background: var(--sb-muted);
    flex-shrink: 0;
    transition: background 0.18s;
}
#ms-sidebar .sb-sub .sb-link:hover::before,
#ms-sidebar .sb-sub .sb-link.sb-active::before { background: #ee0979; }

/* ---- Tooltip (collapsed mode) ---- */
#ms-sidebar.sb-collapsed .sb-link[data-tip]:hover::after {
    content: attr(data-tip);
    position: fixed;
    left: 80px;
    background: #2a1040;
    color: #fff;
    font-size: 0.77rem;
    white-space: nowrap;
    padding: 5px 13px;
    border-radius: 8px;
    z-index: 9999;
    box-shadow: 0 4px 16px rgba(0,0,0,0.3);
    pointer-events: none;
}

/* ---- Bottom (logout) ---- */
#ms-sidebar .sb-bottom {
    padding: 10px 8px;
    border-top: 1px solid rgba(255,255,255,0.07);
    flex-shrink: 0;
}
#ms-sidebar .sb-bottom .sb-link { color: #fb7185 !important; }
#ms-sidebar .sb-bottom .sb-link:hover {
    background: rgba(251,113,133,0.15) !important;
    color: #fb7185 !important;
}

/* =========================================================
   HEADER — fixed top bar
   ========================================================= */
#ms-header {
    position: fixed !important;
    top: 0 !important;
    left: 248px !important;            /* == sb-width */
    right: 0 !important;
    height: 58px !important;
    background: #fff !important;
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    padding: 0 22px !important;
    z-index: 1100 !important;
    border-bottom: 1px solid #e8eaf0 !important;
    box-shadow: 0 1px 8px rgba(0,0,0,0.07) !important;
    transition: left 0.28s cubic-bezier(.4,0,.2,1) !important;
}
body.sb-is-collapsed #ms-header { left: 72px !important; }

#ms-header .hdr-left {
    display: flex; align-items: center; gap: 12px;
}
#ms-header .hdr-toggle {
    width: 36px; height: 36px;
    border-radius: 10px;
    background: rgba(238,9,121,0.08);
    border: none; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.15rem; color: #ee0979;
    transition: all 0.18s;
    flex-shrink: 0;
}
#ms-header .hdr-toggle:hover {
    background: #ee0979; color: #fff;
    box-shadow: 0 3px 10px rgba(238,9,121,0.35);
}
#ms-header .hdr-title {
    font-family: 'Poppins', sans-serif;
    font-size: 0.9rem; font-weight: 700;
    color: #1a1f36;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    max-width: 380px;
    display: flex; align-items: center; gap: 6px;
}
#ms-header .hdr-badge {
    background: linear-gradient(135deg,#ee0979,#ff6a00);
    color: #fff; font-size: 0.65rem; font-weight: 700;
    padding: 2px 8px; border-radius: 20px; letter-spacing: 0.3px;
}
#ms-header .hdr-right {
    display: flex; align-items: center; gap: 10px;
}
#ms-header .hdr-time {
    background: #f3f4f8;
    border-radius: 20px; padding: 5px 14px;
    font-size: 0.78rem; color: #5b6178; font-weight: 500;
    display: flex; align-items: center; gap: 6px;
}
#ms-header .hdr-time i { color: #ee0979; }
#ms-header .hdr-logout {
    display: flex; align-items: center; gap: 6px;
    background: linear-gradient(135deg,#ee0979,#ff6a00);
    border: none; color: #fff;
    padding: 7px 16px; border-radius: 10px;
    font-size: 0.81rem; font-weight: 600;
    cursor: pointer; white-space: nowrap;
    box-shadow: 0 2px 8px rgba(238,9,121,0.3);
    transition: all 0.18s;
}
#ms-header .hdr-logout:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 16px rgba(238,9,121,0.45);
}

/* =========================================================
   MAIN CONTENT
   ========================================================= */
#ms-content {
    margin-left: 248px;               /* == sb-width */
    margin-top: 58px;                 /* == hdr-height */
    padding: 26px 26px 70px;
    min-height: 100vh;
    background: #f0f2f8;
    transition: margin-left 0.28s cubic-bezier(.4,0,.2,1);
}
body.sb-is-collapsed #ms-content { margin-left: 72px; }

/* =========================================================
   FOOTER
   ========================================================= */
#ms-footer {
    position: fixed !important;
    bottom: 0 !important;
    left: 248px !important;
    right: 0 !important;
    height: 42px !important;
    background: #140d1f !important;
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    padding: 0 20px !important;
    z-index: 1000 !important;
    border-top: 1px solid rgba(255,255,255,0.06) !important;
    transition: left 0.28s cubic-bezier(.4,0,.2,1) !important;
}
body.sb-is-collapsed #ms-footer { left: 72px !important; }

#ms-footer .ftr-left {
    font-size: 0.73rem; color: rgba(255,255,255,0.5);
    display: flex; align-items: center; gap: 6px;
}
#ms-footer .ftr-left i { color: #ee0979; }
#ms-footer .ftr-right { position: relative; }
#ms-footer .ftr-btn {
    background: rgba(255,255,255,0.07);
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 8px; padding: 4px 12px;
    color: rgba(255,255,255,0.6); font-size: 0.72rem;
    cursor: pointer;
    display: flex; align-items: center; gap: 6px;
    transition: all 0.18s;
}
#ms-footer .ftr-btn:hover { background: rgba(238,9,121,0.2); color: #fff; }
#ms-footer .ftr-tooltip {
    display: none;
    position: absolute; bottom: calc(100% + 8px); right: 0;
    background: #fff; color: #1a1f36;
    border-radius: 12px; padding: 14px 18px;
    box-shadow: 0 6px 24px rgba(0,0,0,0.15);
    white-space: nowrap; z-index: 1500;
    font-size: 0.82rem;
    border-top: 3px solid #ee0979;
    min-width: 200px;
}
#ms-footer .ftr-right:hover .ftr-tooltip { display: block; }

/* =========================================================
   MOBILE BACKDROP
   ========================================================= */
#ms-backdrop {
    display: none;
    position: fixed; inset: 0;
    background: rgba(0,0,0,0.55);
    backdrop-filter: blur(3px);
    z-index: 1150;
}
#ms-backdrop.show { display: block; }

/* =========================================================
   RESPONSIVE
   ========================================================= */
@media (max-width: 991px) {
    #ms-sidebar {
        left: -248px !important;
        width: 248px !important;
        transition: left 0.28s cubic-bezier(.4,0,.2,1) !important;
    }
    #ms-sidebar.sb-mobile-open { left: 0 !important; }

    #ms-header {
        left: 0 !important;
        width: 100% !important;
    }
    #ms-header .hdr-title { max-width: 170px; font-size: 0.76rem; }
    #ms-header .hdr-time { display: none; }
    #ms-header .hdr-logout { padding: 5px 10px; font-size: 0.74rem; }

    #ms-content {
        margin-left: 0 !important;
        padding: 16px 12px 60px !important;
    }
    #ms-footer {
        left: 0 !important;
        width: 100% !important;
    }
    body.sb-is-collapsed #ms-header { left: 0 !important; }
    body.sb-is-collapsed #ms-content { margin-left: 0 !important; }
    body.sb-is-collapsed #ms-footer  { left: 0 !important; }
}
</style>
</head>
<body>

<!-- Mobile backdrop -->
<div id="ms-backdrop"></div>

<!-- =====================================================
     SIDEBAR
     ===================================================== -->
<nav id="ms-sidebar">

    <!-- Brand -->
    <div class="sb-brand">
        <div class="sb-brand-icon">🌸</div>
        <div class="sb-brand-text">महिला समिति<br><span style="font-weight:400;font-size:0.67rem;opacity:0.8;">Admin Panel</span></div>
    </div>

    <!-- User -->
    <div class="sb-user">
        <div class="sb-user-avatar"><i class="bi bi-person-fill"></i></div>
        <div class="sb-user-info">
            <div class="sb-name">Hello, Admin</div>
            <div class="sb-role">Mahila Samiti</div>
        </div>
    </div>

    <!-- Navigation -->
    <div class="sb-nav">

        <div class="sb-section">MAIN</div>
        <a href="{{ url('/dashboard/mahila_samiti') }}" class="sb-link" data-tip="Dashboard">
            <i class="bi bi-speedometer2 sb-icon"></i>
            <span class="sb-txt">Dashboard</span>
        </a>

        <div class="sb-section">UPDATES</div>
        <div class="sb-link" onclick="toggleSub(this)" data-tip="General Updates">
            <i class="bi bi-calendar-event-fill sb-icon"></i>
            <span class="sb-txt">General Updates</span>
            <i class="bi bi-chevron-down sb-arrow"></i>
        </div>
        <div class="sb-sub">
            <a href="{{ url('/mahila_events') }}" class="sb-link"><span class="sb-txt">Events</span></a>
            <a href="{{ url('/mahila_aavedan_patra') }}" class="sb-link"><span class="sb-txt">आवेदन पत्र</span></a>
            <a href="{{ url('/mahila_prativedan') }}" class="sb-link"><span class="sb-txt">प्रतिवेदन</span></a>
            <a href="{{ url('/mahila_description') }}" class="sb-link"><span class="sb-txt">Description</span></a>
            <a href="{{ url('/mahila_pravartiya') }}" class="sb-link"><span class="sb-txt">प्रवर्तिया</span></a>
        </div>


        <div class="sb-section">कार्यकारिणी</div>
        <div class="sb-link" onclick="toggleSub(this)" data-tip="कार्यकारिणी">
            <i class="bi bi-diagram-3-fill sb-icon"></i>
            <span class="sb-txt">कार्यकारिणी</span>
            <i class="bi bi-chevron-down sb-arrow"></i>
        </div>
        <div class="sb-sub">
            <a href="{{ url('/mahila_ex_president') }}" class="sb-link"><span class="sb-txt">पूर्व अध्यक्ष</span></a>
            <a href="{{ url('/mahila_pst') }}" class="sb-link"><span class="sb-txt">PST (पदाधिकारी)</span></a>
            <a href="{{ url('/mahila_vp_sec') }}" class="sb-link"><span class="sb-txt">VP/SEC सदस्य</span></a>
            <a href="{{ url('/mahila_pravarti_sanyojika') }}" class="sb-link"><span class="sb-txt">प्रवर्ती संयोजक</span></a>
            <a href="{{ url('/mahila_ksm_members') }}" class="sb-link"><span class="sb-txt">कार्यसमिति सदस्य</span></a>
        </div>

        <div class="sb-section">MEDIA</div>
        <div class="sb-link" onclick="toggleSub(this)" data-tip="Photo & Slider">
            <i class="bi bi-images sb-icon"></i>
            <span class="sb-txt">Photo & Slider</span>
            <i class="bi bi-chevron-down sb-arrow"></i>
        </div>
        <div class="sb-sub">
            <a href="{{ url('/photo_gallery_mahila_samiti') }}" class="sb-link"><span class="sb-txt">Photo Gallery</span></a>
            <a href="{{ url('/mahila_slider') }}" class="sb-link"><span class="sb-txt">Mahila Slider</span></a>
            <a href="{{ url('/mahila_mobile_slider') }}" class="sb-link"><span class="sb-txt">Mobile App Slider</span></a>
            <a href="{{ url('/mahila_home_slider') }}" class="sb-link"><span class="sb-txt">Home Slider</span></a>
        </div>

        <div class="sb-section">NOTIFICATIONS</div>
        <div class="sb-link" onclick="toggleSub(this)" data-tip="Notifications">
            <i class="bi bi-bell-fill sb-icon"></i>
            <span class="sb-txt">App Notifications</span>
            <i class="bi bi-chevron-down sb-arrow"></i>
        </div>
        <div class="sb-sub">
            <a href="{{ url('/send_notification-mahila_Samiti') }}" class="sb-link"><span class="sb-txt">Send Notification</span></a>
            <a href="{{ url('/view_notifications_mahila_samiti') }}" class="sb-link"><span class="sb-txt">View Notifications</span></a>
        </div>

        <div class="sb-section">ACCOUNT</div>
        <a href="{{ url('/change-password_mahila_samiti') }}" class="sb-link" data-tip="Change Password">
            <i class="bi bi-shield-lock-fill sb-icon"></i>
            <span class="sb-txt">Change Password</span>
        </a>

    </div><!-- /sb-nav -->

    <div class="sb-bottom">
        <a href="javascript:void(0)" onclick="msLogout()" class="sb-link" data-tip="Logout">
            <i class="bi bi-box-arrow-right sb-icon"></i>
            <span class="sb-txt">Logout</span>
        </a>
    </div>

</nav>

<!-- =====================================================
     HEADER
     ===================================================== -->
<header id="ms-header">
    <div class="hdr-left">
        <button class="hdr-toggle" id="ms-toggle" title="Toggle Sidebar">
            <i class="bi bi-list"></i>
        </button>
        <div class="hdr-title">
            🌸 श्री अ.भा. साधुमार्गी जैन महिला समिति
            <span class="hdr-badge">ADMIN</span>
        </div>
    </div>
    <div class="hdr-right">
        <div class="hdr-time">
            <i class="bi bi-clock-fill"></i>
            <span id="ms-clock">--:--</span>
        </div>
        <button class="hdr-logout" onclick="msLogout()">
            <i class="bi bi-box-arrow-right"></i>
            <span>Logout</span>
        </button>
    </div>
</header>

@yield('jsp-header')

<!-- =====================================================
     MAIN CONTENT
     ===================================================== -->
<main id="ms-content">
    @yield('content')
</main>

<!-- =====================================================
     FOOTER
     ===================================================== -->
<footer id="ms-footer">
    <div class="ftr-left">
        <i class="bi bi-heart-fill"></i>
        Admin Panel &copy; {{ date('Y') }} &nbsp;|&nbsp; SABSJS IT CELL
    </div>
    <div class="ftr-right">
        <div class="ftr-btn">
            <i class="bi bi-headset"></i>
            <span>IT Support</span>
        </div>
        <div class="ftr-tooltip">
            <div style="font-weight:700;font-size:0.85rem;color:#1a1f36;margin-bottom:4px;">
                <i class="bi bi-person-fill" style="color:#ee0979;"></i> Deepak Acharya
            </div>
            <div style="font-size:0.75rem;color:#8b92a9;margin-bottom:8px;">Aditya Acharya</div>
            <div style="color:#ee0979;font-weight:600;font-size:0.82rem;">
                <i class="bi bi-telephone-fill"></i> +91-9636501008
            </div>
        </div>
    </div>
</footer>

<!-- =====================================================
     SCRIPTS
     ===================================================== -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function () {
    var sidebar   = document.getElementById('ms-sidebar');
    var header    = document.getElementById('ms-header');
    var content   = document.getElementById('ms-content');
    var footer    = document.getElementById('ms-footer');
    var backdrop  = document.getElementById('ms-backdrop');
    var toggleBtn = document.getElementById('ms-toggle');

    function isMobile() { return window.innerWidth <= 991; }

    /* ---- Restore collapsed state (desktop only) ---- */
    if (!isMobile() && localStorage.getItem('ms_sb_collapsed') === '1') {
        sidebar.classList.add('sb-collapsed');
        document.body.classList.add('sb-is-collapsed');
    }

    /* ---- Toggle ---- */
    toggleBtn.addEventListener('click', function () {
        if (isMobile()) {
            sidebar.classList.toggle('sb-mobile-open');
            backdrop.classList.toggle('show');
        } else {
            var collapsed = sidebar.classList.toggle('sb-collapsed');
            document.body.classList.toggle('sb-is-collapsed', collapsed);
            localStorage.setItem('ms_sb_collapsed', collapsed ? '1' : '0');
        }
    });

    /* ---- Backdrop closes mobile sidebar ---- */
    backdrop.addEventListener('click', function () {
        sidebar.classList.remove('sb-mobile-open');
        backdrop.classList.remove('show');
    });

    /* ---- Resize handler ---- */
    window.addEventListener('resize', function () {
        if (!isMobile()) {
            sidebar.classList.remove('sb-mobile-open');
            backdrop.classList.remove('show');
        }
    });

    /* ---- Submenu toggle ---- */
    window.toggleSub = function (el) {
        /* On desktop+collapsed: expand sidebar first */
        if (!isMobile() && sidebar.classList.contains('sb-collapsed')) {
            sidebar.classList.remove('sb-collapsed');
            document.body.classList.remove('sb-is-collapsed');
            localStorage.setItem('ms_sb_collapsed', '0');
            setTimeout(function () { openSub(el); }, 300);
            return;
        }
        var sub   = el.nextElementSibling;
        var arrow = el.querySelector('.sb-arrow');
        if (!sub) return;
        if (sub.classList.contains('open')) {
            sub.classList.remove('open');
            if (arrow) arrow.classList.remove('open');
        } else {
            openSub(el);
        }
    };

    function openSub(el) {
        var sub   = el.nextElementSibling;
        var arrow = el.querySelector('.sb-arrow');
        if (sub)   sub.classList.add('open');
        if (arrow) arrow.classList.add('open');
    }

    /* ---- Auto-open submenu for active page ---- */
    var path = window.location.pathname;
    document.querySelectorAll('#ms-sidebar a.sb-link').forEach(function (link) {
        var href = link.getAttribute('href') || '';
        if (href && href !== '/' && path.endsWith(href.replace(/^.*\//, '/'))) {
            link.classList.add('sb-active');
            var parentSub = link.closest('.sb-sub');
            if (parentSub) {
                parentSub.classList.add('open');
                var parentToggle = parentSub.previousElementSibling;
                if (parentToggle) {
                    var parentArrow = parentToggle.querySelector('.sb-arrow');
                    if (parentArrow) parentArrow.classList.add('open');
                }
            }
        }
    });

    /* ---- Logout ---- */
    window.msLogout = function () {
        window.location.href = "{{ route('logout') }}";
    };

    /* ---- Live clock ---- */
    function tick() {
        var el = document.getElementById('ms-clock');
        if (!el) return;
        var n = new Date();
        el.textContent = String(n.getHours()).padStart(2,'0') + ':' + String(n.getMinutes()).padStart(2,'0');
    }
    tick();
    setInterval(tick, 30000);
})();
</script>

</body>
</html>
