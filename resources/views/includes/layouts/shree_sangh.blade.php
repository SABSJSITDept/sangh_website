<!DOCTYPE html>
<html lang="hi">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Shree Sangh Admin Dashboard')</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-w: 260px;
            --header-h: 64px;
            --footer-h: 48px;
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-secondary: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --gradient-success: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --gradient-sidebar: linear-gradient(180deg, #1e3c72 0%, #2a5298 50%, #1e3c72 100%);
            --gradient-topbar: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            --gradient-footer: linear-gradient(90deg, #434343 0%, #000000 100%);
            --muted-bg: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        * {
            box-sizing: border-box
        }

        html,
        body {
            height: 100%;
            margin: 0;
            font-family: Inter, "Segoe UI", Roboto, Arial, sans-serif;
            background: #f5f7fa;
            color: #102a43;
        }

        /* ============ SIDEBAR ============ */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: var(--sidebar-w);
            background: var(--gradient-sidebar);
            color: #e6eef8;
            padding: 18px 14px;
            transition: transform .32s ease, width .32s ease;
            z-index: 1200;
            overflow: hidden;
            box-shadow: 6px 0 30px rgba(30, 60, 114, 0.4);
        }

        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(180deg, rgba(102, 126, 234, 0.1) 0%, transparent 100%);
            pointer-events: none;
        }

        .sidebar.closed {
            transform: translateX(-110%);
        }

        /* Sidebar scrolling area */
        .sidebar-body {
            height: calc(100vh - 40px);
            overflow-y: auto;
            padding-bottom: 52px;
            padding-right: 6px;
            position: relative;
            z-index: 1;
        }

        .brand-compact {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            margin-bottom: 10px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .brand-compact img {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .brand-compact .title {
            font-weight: 700;
            font-size: 16px;
            color: #ffffff;
            letter-spacing: 0.3px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .brand-compact .sub {
            font-size: 13px;
            color: rgba(230, 238, 248, 0.9);
        }

        .sidebar .menu {
            margin-top: 14px;
        }

        .menu-section {
            margin-top: 12px;
            padding-top: 6px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .menu .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            border-radius: 10px;
            color: rgba(230, 238, 248, 0.95);
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            margin-bottom: 6px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            background: transparent;
            position: relative;
            overflow: hidden;
        }

        .menu .menu-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s;
        }

        .menu .menu-item:hover::before {
            left: 100%;
        }

        .menu .menu-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            text-decoration: none;
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .menu .menu-item.active {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.4) 0%, rgba(118, 75, 162, 0.4) 100%);
            color: #fff;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .menu .menu-item i {
            width: 22px;
            text-align: center;
            font-size: 18px;
            color: #cfe8ff;
            transition: transform 0.3s ease;
            font-size: 19px;
        }

        .menu .menu-item:hover i {
            transform: scale(1.1);
        }

        .submenu {
            padding-left: 36px;
            display: none;
        }

        .submenu.show {
            display: block;
            animation: slideDown 0.3s ease;
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

        .submenu a {
            display: block;
            padding: 8px 12px;
            color: rgba(220, 230, 245, 0.9);
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.2s ease;
            border-radius: 6px;
            margin: 2px 0;
        }

        .submenu a:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.08);
            padding-left: 16px;
        }

        /* Sidebar scrollbar (dark theme) */
        .sidebar-body::-webkit-scrollbar {
            width: 8px;
        }

        .sidebar-body::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
        }

        .sidebar-body::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, rgba(102, 126, 234, 0.6), rgba(118, 75, 162, 0.6));
            border-radius: 10px;
            border: 2px solid transparent;
            background-clip: padding-box;
        }

        .sidebar-body::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, rgba(102, 126, 234, 0.8), rgba(118, 75, 162, 0.8));
        }

        /* ============ MAIN (to the right of sidebar) ============ */
        .main {
            margin-left: var(--sidebar-w);
            transition: margin-left .32s ease;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .main.full {
            margin-left: 0;
        }

        /* ============ TOPBAR ============ */
        .topbar {
            height: var(--header-h);
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 0 20px;
            background: var(--gradient-topbar);
            border-bottom: none;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
            position: sticky;
            top: 0;
            z-index: 1100;
        }

        .toggle-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .toggle-btn {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            border: none;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            font-size: 18px;
            color: #fff;
            transition: all 0.3s ease;
        }

        .toggle-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.05);
        }

        .page-heading {
            font-size: 22px;
            font-weight: 700;
            color: #ffffff;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        /* ============ CONTENT ============ */
        .content-wrap {
            padding: 20px 28px 20px 28px;
            flex: 1;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            overflow-y: auto;
            height: calc(100vh - var(--header-h) - var(--footer-h));
        }

        /* Content scrollbar (light theme) */
        .content-wrap::-webkit-scrollbar {
            width: 10px;
        }

        .content-wrap::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.5);
            border-radius: 10px;
        }

        .content-wrap::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #667eea, #764ba2);
            border-radius: 10px;
            border: 2px solid transparent;
            background-clip: padding-box;
        }

        .content-wrap::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #764ba2, #667eea);
        }

        /* ============ FOOTER ============ */
        .site-footer {
            height: var(--footer-h);
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--gradient-footer);
            border-top: none;
            color: #fff;
            font-size: 14px;
            font-weight: 500;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.2);
        }

        /* responsive adjustments */
        @media (max-width: 1000px) {
            :root {
                --sidebar-w: 220px;
            }
        }

        @media (max-width: 780px) {
            .sidebar {
                width: 84%;
            }

            .main {
                margin-left: 0;
            }

            .content-wrap {
                height: calc(100vh - var(--header-h) - var(--footer-h));
            }
        }

        /* Caret rotation */
        .bi-caret-down-fill {
            transition: transform 0.3s ease;
        }

        /* Modern Card Styles with Gradients */
        .card {
            border: none;
            border-radius: 1.2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            background: #ffffff;
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.2);
        }

        /* Modern Buttons with Gradients */
        .btn {
            border-radius: 0.6rem;
            padding: 0.6rem 1.8rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .btn-primary {
            background: var(--gradient-primary);
            color: #fff;
        }

        .btn-success {
            background: var(--gradient-success);
            color: #fff;
        }

        .btn-danger {
            background: var(--gradient-secondary);
            color: #fff;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        /* Fix for Bootstrap collapse buttons */
        .btn-blank {
            background: transparent !important;
            border: none !important;
            padding: 0 !important;
        }

        .btn-blank::before {
            display: none;
        }

        /* Info button in footer */
        .btn-outline-dark {
            border: 2px solid rgba(255, 255, 255, 0.5);
            color: #fff;
            background: transparent;
        }

        .btn-outline-dark:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: #fff;
            color: #fff;
        }

        /* Toast Alerts - Higher z-index to appear above header */
        .toast-container,
        .Toastify__toast-container,
        .swal2-container,
        #toast-container {
            z-index: 9999 !important;
        }

        .toast,
        .Toastify__toast,
        .swal2-popup {
            z-index: 10000 !important;
        }
    </style>

</head>

<body>

    <!-- SIDEBAR -->
    <aside id="sidebar" class="sidebar" aria-label="Sidebar">
        <div class="brand-compact">
            <img src="{{ asset('images/logo.jpeg') }}" alt="logo">
            <div>
                <div class="title">SABSJS Shree Sangh</div>
                <div class="sub">Admin Dashboard</div>
            </div>
        </div>

        <!-- Separate scrolling body inside sidebar -->
        <div class="sidebar-body">
            <nav class="menu">
                <div class="menu-section">
                    <a href="{{ url('dashboard/shree_sangh') }}" class="menu-item">
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </div>

                <div class="menu-section">
                    <button
                        class="menu-item btn btn-blank w-100 d-flex justify-content-between align-items-center text-start"
                        type="button" data-bs-toggle="collapse" data-bs-target="#menuGeneralUpdates"
                        aria-expanded="false">
                        <span><i class="bi bi-calendar-day"></i> General Updates</span>
                        <i class="bi bi-caret-down-fill"></i>
                    </button>
                    <div id="menuGeneralUpdates" class="submenu collapse">
                        <a href="{{ url('/daily-thoughts') }}">
                            <i class="bi bi-lightbulb"></i> आज का विचार
                        </a>
                        <a href="{{ url('/dashboard/vihar-sewa') }}">
                            <i class="bi bi-geo-alt"></i> विहार जानकारी
                        </a>
                        <a href="{{ url('/news') }}">
                            <i class="bi bi-megaphone"></i> NEWS
                        </a>
                        <a href="{{ url('/shivir') }}">
                            <i class="bi bi-calendar-event"></i> शिविर
                        </a>
                        <a href="{{ url('/aavedan_patra') }}">
                            <i class="bi bi-file-earmark-text"></i> आवेदन पत्र
                        </a>
                    </div>
                </div>

                <div class="menu-section">
                    <button
                        class="menu-item btn btn-blank w-100 d-flex justify-content-between align-items-center text-start"
                        type="button" data-bs-toggle="collapse" data-bs-target="#menuKaryakarini" aria-expanded="false">
                        <span><i class="bi bi-diagram-3"></i> कार्यकारिणी</span>
                        <i class="bi bi-caret-down-fill"></i>
                    </button>
                    <div id="menuKaryakarini" class="submenu collapse">
                        <a href="{{ route('karyakarini.index') }}">
                            <i class="bi bi-house-door"></i> HOME
                        </a>
                        <a href="{{ url('/shree-sangh/ex-president') }}">
                            <i class="bi bi-person-check"></i> पूर्व अध्यक्ष
                        </a>
                        <a href="{{ url('/shree-sangh/karyakarini/pst') }}">
                            <i class="bi bi-person-video2"></i> PST
                        </a>
                        <a href="{{ url('/vp-sec') }}">
                            <i class="bi bi-person-badge"></i> VP/SEC सदस्य
                        </a>
                        <a href="{{ route('admin.it_cell') }}">
                            <i class="bi bi-cpu"></i> IT-CELL सदस्य
                        </a>
                        <a href="{{ url('/pravarti-sanyojak') }}">
                            <i class="bi bi-diagram-3-fill"></i> प्रवर्ती संयोजक
                        </a>
                        <a href="{{ url('/karyasamiti-sadasya') }}">
                            <i class="bi bi-people-fill"></i> कार्यसमिति सदस्य
                        </a>
                        <a href="{{ url('/sthayi_sampati_sanwardhan_samiti') }}">
                            <i class="bi bi-bank"></i> स्थायि सम्पति संवर्द्धन समित
                        </a>
                        <a href="{{ url('/sanyojan_mandal_antrastriya_sadasyata') }}">
                            <i class="bi bi-globe2"></i> संयोजन मंडल अंतरस्त्रिय सदस्यता
                        </a>
                        <a href="{{ url('/samta_jan_kalyan_pranayash') }}">
                            <i class="bi bi-activity"></i> समता जन कल्याण प्राणायास
                        </a>
                        <a href="{{ url('/padhadhikari_prashashan_karyashala') }}">
                            <i class="bi bi-file-earmark-pdf"></i> पदाधिकारी प्रशासन कार्यशाला
                        </a>
                    </div>
                </div>

                <div class="menu-section">
                    <button
                        class="menu-item btn btn-blank w-100 d-flex justify-content-between align-items-center text-start"
                        type="button" data-bs-toggle="collapse" data-bs-target="#menuSanghPravartiya"
                        aria-expanded="false">
                        <span><i class="bi bi-people"></i> संघ प्रवृत्तियाँ</span>
                        <i class="bi bi-caret-down-fill"></i>
                    </button>
                    <div id="menuSanghPravartiya" class="submenu collapse">
                        <a href="{{ route('dharmik_pravartiya') }}">
                            <i class="bi bi-person"></i> धार्मिक प्रवर्तियाँ
                        </a>
                        <a href="{{ route('jsp.dashboard') }}">
                            <i class="bi bi-person-plus"></i> JSP
                        </a>
                    </div>
                </div>

                <div class="menu-section">
                    <button
                        class="menu-item btn btn-blank w-100 d-flex justify-content-between align-items-center text-start"
                        type="button" data-bs-toggle="collapse" data-bs-target="#menuPhotoGallery"
                        aria-expanded="false">
                        <span><i class="bi bi-images"></i> Photo Gallery</span>
                        <i class="bi bi-caret-down-fill"></i>
                    </button>
                    <div id="menuPhotoGallery" class="submenu collapse">
                        <a href="{{ url('/photo_gallery') }}">
                            <i class="bi bi-cloud-upload-fill"></i> Add Event Photos
                        </a>
                        <a href="{{ url('/home_slider') }}">
                            <i class="bi bi-collection-play-fill"></i> Home Slider
                        </a>
                        <a href="{{ url('/mobile_slider') }}">
                            <i class="bi bi-phone-fill"></i> Mobile Slider Update
                        </a>
                    </div>
                </div>

                <div class="menu-section">
                    <button
                        class="menu-item btn btn-blank w-100 d-flex justify-content-between align-items-center text-start"
                        type="button" data-bs-toggle="collapse" data-bs-target="#menuNotifications"
                        aria-expanded="false">
                        <span><i class="bi bi-bell-fill"></i> Notifications</span>
                        <i class="bi bi-caret-down-fill"></i>
                    </button>
                    <div id="menuNotifications" class="submenu collapse">
                        <a href="{{ url('/send_notification-shree_sangh') }}">
                            <i class="bi bi-send-fill"></i> Send Notification
                        </a>
                        <a href="{{ url('/view_notifications_shree_sangh') }}">
                            <i class="bi bi-list-check"></i> View Notifications
                        </a>
                    </div>
                </div>

                <div class="menu-section">
                    <button
                        class="menu-item btn btn-blank w-100 d-flex justify-content-between align-items-center text-start"
                        type="button" data-bs-toggle="collapse" data-bs-target="#menuSecurity" aria-expanded="false">
                        <span><i class="bi bi-shield-lock-fill"></i> Security</span>
                        <i class="bi bi-caret-down-fill"></i>
                    </button>
                    <div id="menuSecurity" class="submenu collapse">
                        <a href="{{ url('/change-password_shree_sangh') }}">
                            <i class="bi bi-key-fill"></i> Change Password
                        </a>
                    </div>
                </div>

                <div style="height:14px"></div>

                <a href="{{ route('logout') }}" class="menu-item">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </a>
            </nav>
        </div> <!-- /.sidebar-body -->
    </aside>

    <!-- MAIN -->
    <div id="main" class="main">

        <!-- TOPBAR -->
        <header class="topbar">
            <div class="toggle-wrap">
                <button id="toggleBtn" class="toggle-btn" title="Toggle sidebar">
                    <i id="toggleIcon" class="bi bi-list"></i>
                </button>
            </div>

            <h3 class="page-heading">@yield('page-title', 'Shree Sangh Admin Dashboard')</h3>
        </header>

        @yield('jsp-header')

        <!-- CONTENT (has its own scroll) -->
        <div class="content-wrap">
            @yield('content')
        </div>

        <!-- FOOTER -->
        <footer class="site-footer">
            &copy; {{ date('Y') }} श्री अखिल भारतवर्षीय साधुमार्गी जैन संघ. केंद्र कार्यालय IT DEPARTMENT

            <!-- Info Button -->
            <button type="button" class="btn btn-sm btn-outline-dark ms-2" data-bs-toggle="modal"
                data-bs-target="#infoModal">
                <i class="bi bi-info-circle"></i>
            </button>
        </footer>

        <!-- Modal -->
        <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow">
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title" id="infoModalLabel"><i class="bi bi-person-circle"></i> Contact
                            Information</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Name:</strong> IT Department</p>
                        <p><strong>Mobile:</strong> +91 9636501008</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        (function () {
            const sidebar = document.getElementById('sidebar');
            const main = document.getElementById('main');
            const toggleBtn = document.getElementById('toggleBtn');
            const toggleIcon = document.getElementById('toggleIcon');

            toggleBtn.addEventListener('click', () => {
                const closed = sidebar.classList.toggle('closed');
                main.classList.toggle('full');
                toggleIcon.classList.toggle('bi-x-lg');
                toggleIcon.classList.toggle('bi-list');

                if (closed) {
                    document.querySelectorAll('.submenu.show').forEach(s => {
                        const bs = bootstrap.Collapse.getInstance(s);
                        if (bs) bs.hide();
                    });
                }
            });

            // rotate caret on collapse toggles for visual cue
            document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(btn => {
                btn.addEventListener('click', () => {
                    const icon = btn.querySelector('.bi-caret-down-fill');
                    const target = document.querySelector(btn.getAttribute('data-bs-target'));
                    setTimeout(() => {
                        if (target.classList.contains('show')) icon.style.transform = 'rotate(180deg)';
                        else icon.style.transform = 'rotate(0deg)';
                    }, 200);
                });
            });

            // responsive: hide sidebar by default on small screens
            function handleResize() {
                if (window.innerWidth < 780) {
                    sidebar.classList.add('closed');
                    main.classList.add('full');
                    toggleIcon.classList.remove('bi-list');
                    toggleIcon.classList.add('bi-x-lg');
                } else {
                    sidebar.classList.remove('closed');
                    main.classList.remove('full');
                    toggleIcon.classList.remove('bi-x-lg');
                    toggleIcon.classList.add('bi-list');
                }
            }
            handleResize();
            window.addEventListener('resize', handleResize);

            // Set active menu item based on current URL
            const currentUrl = window.location.href;
            document.querySelectorAll('.menu-item').forEach(item => {
                if (item.href && currentUrl.includes(item.href)) {
                    item.classList.add('active');
                }
            });

            document.querySelectorAll('.submenu a').forEach(item => {
                if (item.href && currentUrl.includes(item.href)) {
                    item.style.color = '#fff';
                    item.style.fontWeight = '600';
                    // Open parent submenu
                    const parentSubmenu = item.closest('.submenu');
                    if (parentSubmenu) {
                        parentSubmenu.classList.add('show');
                        const collapseBtn = document.querySelector(`[data-bs-target="#${parentSubmenu.id}"]`);
                        if (collapseBtn) {
                            const icon = collapseBtn.querySelector('.bi-caret-down-fill');
                            if (icon) icon.style.transform = 'rotate(180deg)';
                        }
                    }
                }
            });
        })();
    </script>

    @stack('scripts')
</body>

</html>