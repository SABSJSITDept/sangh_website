<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>SSO Login - ABSJS</title>
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
      height: 100vh;
    }

    .login-wrapper {
      width: 900px;
      height: 500px;
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

    .right-panel input[type="email"],
    .right-panel input[type="password"] {
      padding: 12px 15px;
      border-radius: 30px;
      border: none;
      background: #f5f5f5;
      font-size: 14px;
      color: #333;
      box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
    }

    .right-panel .error {
      color: #ff4d4d;
      text-align: center;
      font-size: 14px;
    }

    .login-btn {
      background: linear-gradient(to right, #00aaff, #007ecc);
      color: white;
      padding: 12px;
      border: none;
      border-radius: 25px;
      font-size: 16px;
      cursor: pointer;
    }

    .login-btn:hover {
      background: linear-gradient(to right, #008ecc, #0066aa);
    }

    .right-panel a {
      text-decoration: none;
      color: #007ecc;
      font-size: 14px;
      text-align: center;
      margin-top: 10px;
    }

    .footer {
      position: absolute;
      bottom: 20px;
      font-size: 13px;
      color: #555;
      text-align: center;
      width: 100%;
    }
  </style>
</head>
<body>

<div class="login-wrapper">
  <!-- Left panel with logo and welcome -->
  <div class="left-panel">
    <img src="{{ asset('images/logo.jpeg') }}" alt="Logo">
    <h1>WELCOME BACK !</h1>
    <p>Enter your ID and Password to continue</p>
  </div>

  <!-- Right panel with login form -->
  <div class="right-panel">
    <h2>SIGN IN</h2>
    <small>TO ACCESS THE PORTAL</small>

   <form method="POST" action="/login" class="login-form">
      @csrf
      @if(session('error'))
        <div class="error">{{ session('error') }}</div>
      @endif

      <input type="email" name="email" placeholder="Email Address" required>
      <input type="password" name="password" placeholder="Password" required>

      <button type="submit" class="login-btn">Login</button>
    </form>

  </div>
</div>

<!-- Footer -->
<div class="footer">
  &copy; {{ date('Y') }} SABSJS IT DEPARTMENT. All rights reserved.
</div>

</body>
</html>
