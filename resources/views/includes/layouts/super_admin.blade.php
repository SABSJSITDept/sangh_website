<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Sidebar Menu</title>

  <!-- Bootstrap & FontAwesome -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: #f4f4f4;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
       overflow-x: hidden;
    }

    .sidebar-layout {
      display: flex;
      flex-grow: 1;
      min-height: 0;
    }

    .sidebar {
  width: 180px;
  background-color: #fff;
  border-right: 1px solid #ddd;
  display: flex;
  flex-direction: column;
  min-height: 100vh; /* FULL HEIGHT */
  transition: all 0.3s ease;
  position: sticky;
  top: 0;
}


    .sidebar.collapsed {
      width: 80px;
    }

    .sidebar .brand {
      display: flex;
      align-items: center;
      padding: 20px;
      font-size: 1.25rem;
      font-weight: 600;
    }

    .sidebar .brand .logo {
      background-color: #6c63ff;
      color: #fff;
      border-radius: 50%;
      width: 42px;
      height: 42px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      margin-right: 10px;
    }

    .sidebar .menu {
      flex-grow: 1;
      padding: 0 15px;
    }

    .sidebar .menu a {
      display: flex;
      align-items: center;
      padding: 12px 10px;
      border-radius: 10px;
      text-decoration: none;
      color: #333;
      font-weight: 500;
      transition: background 0.2s ease;
    }

    .sidebar .menu a.active,
    .sidebar .menu a:hover {
      background-color: #6c63ff;
      color: #fff;
    }

    .sidebar .menu a i {
      width: 24px;
      text-align: center;
      margin-right: 10px;
    }

    .sidebar.collapsed .menu a span {
      display: none;
    }

    .sidebar .bottom-section {
      padding: 20px;
      border-top: 1px solid #ddd;
    }

    .toggle-btn {
      background: none;
      border: none;
      font-size: 20px;
      margin-left: auto;
      margin-right: 15px;
      margin-top: 10px;
      color: #6c63ff;
      cursor: pointer;
    }

    .main-content {
      flex-grow: 1;
      padding: 40px;
      background-color: #f4f4f4;
      overflow-y: auto;
  max-height: calc(100vh - 120px);
    }

    header {
      background: url('{{ asset('images/header.jpg') }}') no-repeat center center;
      background-size: cover;
      padding: 15px 30px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      color: black;
      flex-wrap: wrap;
    }

    .header-left {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .dark-mode-toggle-header {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .dark-mode-toggle-header span {
      font-weight: 500;
      font-size: 14px;
    }

    .switch {
      position: relative;
      display: inline-block;
      width: 60px;
      height: 24px;
    }

    .switch input { display: none; }

    .slider {
      position: absolute;
      cursor: pointer;
      inset: 0;
      background-color: #ccc;
      border-radius: 34px;
      transition: .4s;
    }

    .slider:before {
      position: absolute;
      content: "";
      height: 18px;
      width: 18px;
      left: 3px;
      bottom: 3px;
      background-color: white;
      transition: .4s;
      border-radius: 50%;
    }

    input:checked + .slider {
      background-color: #6c63ff;
    }

    input:checked + .slider:before {
      transform: translateX(36px);
    }

    footer {
      background-color: #333;
      color: #fff;
      text-align: center;
      padding: 15px;
    }
  </style>
</head>
<body>

<!-- HEADER -->
<header>
  <!-- Left: Logo and Name -->
  <div class="header-left">

  </div>

  <!-- Center: Title -->
  <h2 class="mb-0 text-center flex-grow-1" style="text-align: center;">Super Admin Dashboard Panel</h2>

  <!-- Right: Dark Mode & Logout -->

    <a href="{{ route('logout') }}" class="btn btn-light btn-sm fw-semibold ms-3">
      <i class="fas fa-sign-out-alt me-1"></i> Logout
    </a>
  </div>
</header>

<!-- SIDEBAR + MAIN LAYOUT -->
<div class="sidebar-layout" style="flex: 1;">

  <div class="sidebar" id="sidebar">
    <div class="brand">
      <div class="logo d-flex align-items-center gap-2">
        <span class="fw-bold sidebar-label"></span>
      </div>
      <button class="toggle-btn" onclick="toggleSidebar()"><i class="fas fa-angle-left"></i></button>
    </div>
    <div class="menu">
      <a href="#" class="active"><i class="fas fa-home"></i> <span>Dashboard</span></a>
      <a href="#"><i class="fas fa-chart-line"></i> <span>Revenue</span></a>
      <a href="#"><i class="fas fa-bell"></i> <span>Notifications</span></a>
      <a href="#"><i class="fas fa-chart-pie"></i> <span>Analytics</span></a>
      <a href="#"><i class="fas fa-heart"></i> <span>Likes</span></a>
      <a href="#"><i class="fas fa-wallet"></i> <span>Wallets</span></a>
    </div>
    <div class="bottom-section">
     <a href="{{ route('logout') }}" class="d-flex align-items-center"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
    </div>
  </div>

   {{-- Main Content --}}
             <div class="main-content">
                @yield('content') {{-- ← This is essential --}}
            </div>  
</div>

<!-- FOOTER -->
<footer>
  &copy; 2025 ABSJS. All rights reserved.
</footer>

<script>
  function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('collapsed');
  }

  function toggleDarkMode() {
    document.body.classList.toggle('bg-dark');
    document.body.classList.toggle('text-white');
  }
</script>
</body>
</html>
