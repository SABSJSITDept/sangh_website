<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create User - ABSJS</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
    }

    body {
      background-color: #f0f4f8;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      padding: 20px;
    }

    .register-wrapper {
      width: 900px;
      min-height: 550px;
      display: flex;
      background: #fff;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
      border-radius: 15px;
      overflow: hidden;
    }

    .left-panel {
      flex: 1;
      background: linear-gradient(to bottom right, #00aaff, #007ecc);
      padding: 50px 30px;
      color: white;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }

    .left-panel img {
      width: 200px;
      height: 180px;
      margin-bottom: 20px;
    }

    .left-panel h1 {
      font-size: 26px;
      margin-bottom: 10px;
    }

    .left-panel p {
      font-size: 16px;
      text-align: center;
    }

    .right-panel {
      flex: 1;
      padding: 50px 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .right-panel h2 {
      font-size: 24px;
      margin-bottom: 8px;
      text-align: center;
      font-weight: bold;
    }

    .right-panel small {
      text-align: center;
      display: block;
      margin-bottom: 30px;
      color: #555;
    }

    .right-panel form {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .right-panel input[type="text"],
    .right-panel input[type="email"],
    .right-panel input[type="password"],
    .right-panel select {
      padding: 12px 15px;
      border-radius: 30px;
      border: none;
      background: #f5f5f5;
      font-size: 14px;
      color: #333;
      box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
    }

    .right-panel select {
      cursor: pointer;
      appearance: none;
      background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
      background-repeat: no-repeat;
      background-position: right 15px center;
      background-size: 20px;
      padding-right: 40px;
    }

    .right-panel .error {
      color: #ff4d4d;
      text-align: center;
      font-size: 14px;
    }

    .right-panel .success {
      color: #4CAF50;
      text-align: center;
      font-size: 14px;
    }

    .register-btn {
      background: linear-gradient(to right, #00aaff, #007ecc);
      color: white;
      padding: 12px;
      border: none;
      border-radius: 25px;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .register-btn:hover {
      background: linear-gradient(to right, #008ecc, #0066aa);
    }

    .right-panel a {
      text-decoration: none;
      color: #007ecc;
      font-size: 14px;
      text-align: center;
      margin-top: 10px;
    }

    .right-panel a:hover {
      text-decoration: underline;
    }

    .footer {
      position: absolute;
      bottom: 20px;
      font-size: 13px;
      color: #555;
      text-align: center;
      width: 100%;
    }

    @media (max-width: 768px) {
      .register-wrapper {
        flex-direction: column;
        width: 100%;
        max-width: 500px;
      }
      
      .left-panel {
        padding: 30px 20px;
      }
      
      .left-panel h1 {
        font-size: 22px;
      }
    }
  </style>
</head>
<body>

<div class="register-wrapper">
  <!-- Left panel with logo and welcome -->
  <div class="left-panel">
    <img src="{{ asset('images/logo.jpeg') }}" alt="Logo">
    <h1>CREATE NEW USER</h1>
    <p>Register a new user to access the portal</p>
  </div>

  <!-- Right panel with registration form -->
  <div class="right-panel">
    <h2>SIGN UP</h2>
    <small>CREATE NEW ACCOUNT</small>

    <form method="POST" action="/register" class="register-form">
      @csrf
      
      @if(session('error'))
        <div class="error">{{ session('error') }}</div>
      @endif

      @if(session('success'))
        <div class="success">{{ session('success') }}</div>
      @endif

      @if($errors->any())
        <div class="error">
          @foreach ($errors->all() as $error)
            {{ $error }}<br>
          @endforeach
        </div>
      @endif

      <input type="text" name="name" placeholder="Full Name" value="{{ old('name') }}" required>
      <input type="email" name="email" placeholder="Email Address" value="{{ old('email') }}" required>
      <input type="password" name="password" placeholder="Password (minimum 8 characters)" required minlength="8">
      <input type="password" name="password_confirmation" placeholder="Confirm Password" required minlength="8">
      
      <select name="role" required>
        <option value="">Select Role</option>
        <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
        <option value="shree_sangh" {{ old('role') == 'shree_sangh' ? 'selected' : '' }}>Shree Sangh</option>
        <option value="yuva_sangh" {{ old('role') == 'yuva_sangh' ? 'selected' : '' }}>Yuva Sangh</option>
        <option value="mahila_samiti" {{ old('role') == 'mahila_samiti' ? 'selected' : '' }}>Mahila Samiti</option>
        <option value="sahitya" {{ old('role') == 'sahitya' ? 'selected' : '' }}>Sahitya</option>
        <option value="spf" {{ old('role') == 'spf' ? 'selected' : '' }}>SPF</option>
        <option value="sahitya_publication" {{ old('role') == 'sahitya_publication' ? 'selected' : '' }}>Sahitya Publication</option>
      </select>

      <button type="submit" class="register-btn">Create User</button>
    </form>

    <a href="/">Already have an account? Sign In</a>
  </div>
</div>

<!-- Footer -->
<div class="footer">
  &copy; {{ date('Y') }} SABSJS IT DEPARTMENT. All rights reserved.
</div>

</body>
</html>
