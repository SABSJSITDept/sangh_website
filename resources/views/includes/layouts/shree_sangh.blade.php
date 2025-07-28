<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script>
  const originalFetch = window.fetch;
  window.fetch = function(url, options = {}) {
    const token = document.querySelector('meta[name="csrf-token"]')?.content;

  if (!(options.body instanceof FormData)) {
  options.headers = {
    'X-CSRF-TOKEN': token,
    'X-Requested-With': 'XMLHttpRequest',
    ...(options.headers || {})
  };
}

    return originalFetch(url, options);
  };
</script>

  
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
  height: 100vh; /* FULL HEIGHT */
  overflow-y: auto; /* ADD THIS */
  position: sticky;
  top: 0;
  transition: all 0.3s ease;
}

.sidebar::-webkit-scrollbar {
  width: 6px;
}

.sidebar::-webkit-scrollbar-thumb {
  background-color: #bbb;
  border-radius: 3px;
}

.sidebar::-webkit-scrollbar-thumb:hover {
  background-color: #888;
}


    .sidebar.collapsed {
      width: 90px;
    }

    .sidebar .brand {
      display: flex;
      align-items: center;
      padding: 20px;
      font-size: 1.25rem;
      font-weight: 600;
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
@import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;600&display=swap');
.header-title {
  font-family: 'Bebas Neue', cursive;
  font-size: 34px;
  letter-spacing: 1.5px;
  color: #2e1a04;
  text-shadow: 1px 1px 3px rgba(255, 255, 255, 0.8);
}

.logout-btn {
  background-color: rgba(255, 255, 255, 0.9);
  color: #3a2e17;
  border: 1px solid #e4cfa3;
  border-radius: 25px;
  padding: 6px 16px;
  transition: all 0.3s ease;
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.logout-btn:hover {
  background-color: #fbe6c2;
  transform: scale(1.05);
}

.logo {
  width: 40px;
  height: auto;
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
.brand .btn {
  width: 32px;
  height: 32px;
  padding: 0;
  display: flex;
  align-items: center;
  justify-content: center;
}
  </style>
</head>
<body>

<!-- HEADER -->
<header class="custom-header d-flex align-items-center justify-content-between px-4 py-3 shadow-sm">
  <!-- Left: Logo (if needed) -->
  <div class="header-left d-flex align-items-center">
    <!-- <img src="your-logo.png" alt="Logo" class="logo me-2"> -->
  </div>

  <!-- Center: Title -->
  <h2 class="header-title text-center m-0 flex-grow-1">
    ABSJS Dashboard Panel
  </h2>

  <!-- Right: Logout -->
  <div class="header-right d-flex align-items-center">
    <a href="{{ route('logout') }}" class="btn logout-btn btn-sm fw-semibold">
      <i class="fas fa-sign-out-alt me-1"></i> Logout
    </a>
  </div>
</header>


<!-- SIDEBAR + MAIN LAYOUT -->
<div class="sidebar-layout" style="flex: 1;">
  <div class="sidebar" id="sidebar">
    
    <!-- Top Brand and Toggle Section -->
    <div class="brand d-flex justify-content-between align-items-center px-3 py-2">
      
      <!-- Toggle + Back Button -->
      <div class="d-flex gap-2">
        <button class="btn btn-outline-secondary btn-sm rounded-circle" onclick="goBack()" title="Back">
          <i class="fas fa-arrow-left"></i>
        </button>
        <button class="btn btn-outline-primary btn-sm rounded-circle" onclick="toggleSidebar()" title="Toggle Sidebar">
          <i class="fas fa-bars"></i>
        </button>
      </div>
    </div>

<!-- Sidebar Menu -->
<div class="menu">

  <!-- Dashboard -->
  <div class="menu-item">
    <a href="#dashboardSubmenu" data-bs-toggle="collapse" class="{{ Request::is('dashboard/shree_sangh') ? 'active' : '' }}">
      <i class="fas fa-home"></i> <span>Dashboard</span>
    </a>
    <div class="collapse" id="dashboardSubmenu">
      <ul class="submenu">
        <li><a href="{{ url('dashboard/shree_sangh') }}" >HOME</a></li>
        <li><a href="{{ url('/dashboard/shree_sangh/daily-thoughts') }}">आज का विचार</a></li>
        <li><a href="{{ url('/dashboard/vihar-sewa') }}">विहार जानकारी </a></li>
        <li><a href="#">Users</a></li>
        <li><a href="#">Logs</a></li>
      </ul>
    </div>
  </div>

  <!-- Karyakarini -->
  <div class="menu-item">
    <a href="#karyakariniSubmenu" data-bs-toggle="collapse" class="{{ Request::is('karyakarini*') ? 'active' : '' }}">
      <i class="fas fa-chart-line"></i> <span>कार्यकारिणी</span>
    </a>
    <div class="collapse" id="karyakariniSubmenu">
      <ul class="submenu">
        <li><a href="{{ route('karyakarini.index') }}">HOME</a></li>
        <li><a href="{{ url('/shree-sangh/ex-president') }}">पूर्व अध्यक्ष</a></li>
        <li><a href="{{ url('/shree-sangh/karyakarini/pst') }}">PST</a></li>
        <li><a href="{{ url('/vp-sec') }}">VP/SEC सदस्य</a></li>
        <li><a href="{{ route('admin.it_cell') }}">IT-CELL सदस्य </a></li>
        <li><a href="{{ url('/pravarti-sanyojak') }}">प्रवर्ती संयोजक</a></li>
        <li><a href="{{ url('/karyasamiti-sadasya') }}">कार्यसमिति सदस्य</a></li>
        <li><a href="{{ url('/sthayi_sampati_sanwardhan_samiti') }}">स्थायि सम्पति संवर्द्धन समित</a></li>
        <li><a href="{{ url('/sanyojan_mandal_antrastriya_sadasyata') }}">संयोजन मंडल अंतरस्त्रिय सदस्यता</a></li>
        <li><a href="{{ url('/samta_jan_kalyan_pranayash') }}">समता जन कल्याण प्राणायास</a></li>
      </ul>
    </div>
  </div>

  <!-- Notifications -->
  <div class="menu-item">
    <a href="#notificationSubmenu" data-bs-toggle="collapse">
      <i class="fas fa-bell"></i> <span>Notifications</span>
    </a>
    <div class="collapse" id="notificationSubmenu">
      <ul class="submenu">
        <li><a href="#">All</a></li>
        <li><a href="#">Unread</a></li>
        <li><a href="#">Sent</a></li>
        <li><a href="#">Settings</a></li>
        <li><a href="#">Templates</a></li>
      </ul>
    </div>
  </div>

  <!-- Analytics -->
  <div class="menu-item">
    <a href="#analyticsSubmenu" data-bs-toggle="collapse">
      <i class="fas fa-chart-pie"></i> <span>Analytics</span>
    </a>
    <div class="collapse" id="analyticsSubmenu">
      <ul class="submenu">
        <li><a href="#">Traffic</a></li>
        <li><a href="#">Engagement</a></li>
        <li><a href="#">Reports</a></li>
        <li><a href="#">Conversions</a></li>
        <li><a href="#">Sources</a></li>
      </ul>
    </div>
  </div>

  <!-- Likes -->
  <div class="menu-item">
    <a href="#likesSubmenu" data-bs-toggle="collapse">
      <i class="fas fa-heart"></i> <span>Likes</span>
    </a>
    <div class="collapse" id="likesSubmenu">
      <ul class="submenu">
        <li><a href="#">All Likes</a></li>
        <li><a href="#">Top Liked</a></li>
        <li><a href="#">By Users</a></li>
        <li><a href="#">By Posts</a></li>
        <li><a href="#">Archived</a></li>
      </ul>
    </div>
  </div>

  <!-- Wallets -->
  <div class="menu-item">
    <a href="#walletsSubmenu" data-bs-toggle="collapse">
      <i class="fas fa-wallet"></i> <span>Wallets</span>
    </a>
    <div class="collapse" id="walletsSubmenu">
      <ul class="submenu">
        <li><a href="#">Overview</a></li>
        <li><a href="#">Add Funds</a></li>
        <li><a href="#">Withdraw</a></li>
        <li><a href="#">Transactions</a></li>
        <li><a href="#">Settings</a></li>
      </ul>
    </div>
  </div>

</div>


    <!-- Bottom Logout -->
    <div class="bottom-section">
      <a href="{{ route('logout') }}" class="d-flex align-items-center">
        <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
      </a>
    </div>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    @yield('content')
  </div>
</div>

<!-- FOOTER -->
<footer>
  &copy; 2025 ABSJS IT DEPARTMENT. All rights reserved.
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('collapsed');
  }



  function goBack() {
    window.history.back();
  }
</script>
@yield('scripts')

</body>
</html>
