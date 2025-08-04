<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>@yield('title', 'Admin Panel')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />

    <style>
        :root {
            --sidebar-width: 150px;
        }

        body {
            margin: 0;
            background: #fff;
            color: #23262f;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

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
            padding-top: 50px; /* space for header */
        }

        .sidebar {
            width: var(--sidebar-width);
            background: #181824;
            color: white;
            height: calc(100vh - 50px);
            position: fixed;
            top: 50px;
            left: 0;
            overflow-y: auto;
            z-index: 1030;
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

        .nav-link:hover,
        .nav-link.active {
            background: linear-gradient(90deg, #2760fe 0%, #3e85fa 100%);
            color: #fff;
        }

        .nav-link span {
            display: inline;
        }

        .nav-item {
            width: 100%;
        }

        main.content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            width: 100%;
            min-height: calc(100vh - 90px);
            padding-bottom: 60px;
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
            left: var(--sidebar-width);
            right: 0;
            height: 40px;
            line-height: 40px;
            z-index: 1040;
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        @media (max-width: 991px) {
            .sidebar {
                position: fixed;
                left: 0;
                width: var(--sidebar-width);
                height: calc(100vh - 50px);
                z-index: 1030;
            }

            main.content,
            footer.footer {
                margin-left: 0 !important;
            }
        }
    </style>
</head>
<body>
    <!-- HEADER -->
    <header class="main-header">
        <b><i class="bi bi-speedometer2"></i> श्री अखिल भारतवर्षीय साधुमार्गी जैन संघ </b>
        <img src="https://randomuser.me/api/portraits/men/41.jpg" alt="admin" />
    </header>

    <!-- LAYOUT -->
    <div class="layout">
        <!-- SIDEBAR -->
        <nav class="sidebar" id="sidebarMenu">
            <div class="profile">
                <img src="https://randomuser.me/api/portraits/men/41.jpg" alt="Admin" />
                <div class="name">Hello Admin</div>
                <div class="role">Sahitya Publication</div>
            </div>

            <a href="{{ url('dashboard/sahitya_publication') }}" class="nav-link">
                <i class="bi bi-house-door"></i>
                <span class="link-text">HOME</span>
            </a>

            <a href="#" class="nav-link">
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
</body>
</html>
