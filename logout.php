<?php
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="refresh" content="2;url=admin_login.php">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Logging Out...</title>
  <style>
    body {
      background: linear-gradient(to right, #001d3d, #002b5e);
      color: #00ffd0;
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      text-align: center;
      padding: 20px;
    }
    .message {
      background: rgba(255, 255, 255, 0.05);
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 0 20px rgba(0, 255, 208, 0.1);
    }
  </style>
</head>
<body>
  <div class="message">
    <h2>✅ You have been logged out.</h2>
    <p>Redirecting to login page...</p>
  </div>
</body>
</html>
