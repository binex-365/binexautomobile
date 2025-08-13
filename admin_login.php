<?php
session_start();

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $credentials = require 'admin_credentials.php';

    if ($username === $credentials['username'] && password_verify($password, $credentials['password'])) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin_dashboard.php');
        exit;
    } else {
        $error = "Incorrect password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login - Genkada</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    body {
      height: 100vh;
      background: linear-gradient(135deg, #001d3d, #003566);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }
    .login-box {
      background: rgba(255, 255, 255, 0.05);
      padding: 40px 30px;
      border-radius: 16px;
      box-shadow: 0 0 25px rgba(0,255,208,0.12);
      backdrop-filter: blur(10px);
      width: 100%;
      max-width: 400px;
      color: white;
      animation: fadeIn 1s ease-in-out;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: scale(0.95); }
      to { opacity: 1; transform: scale(1); }
    }
    @keyframes shake {
      0% { transform: translateX(0); }
      20% { transform: translateX(-8px); }
      40% { transform: translateX(8px); }
      60% { transform: translateX(-8px); }
      80% { transform: translateX(8px); }
      100% { transform: translateX(0); }
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #00ffd0;
      text-shadow: 0 0 6px #00ffd0;
    }
    input {
      width: 100%;
      padding: 12px 14px;
      margin-bottom: 20px;
      border-radius: 10px;
      border: none;
      background: rgba(255, 255, 255, 0.1);
      color: white;
      font-size: 15px;
    }
    input:focus {
      outline: none;
      background: rgba(255, 255, 255, 0.2);
      box-shadow: 0 0 10px #00ffd0;
    }
    button {
      width: 100%;
      background: linear-gradient(to right, #00ffd0, #00bfff);
      color: black;
      font-weight: bold;
      border: none;
      padding: 12px;
      border-radius: 30px;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    button:hover {
      box-shadow: 0 0 20px #00ffd0;
      transform: scale(1.03);
    }
    .error {
      background-color: rgba(255, 0, 0, 0.1);
      color: #ff4d4d;
      padding: 10px;
      text-align: center;
      border-radius: 8px;
      margin-bottom: 20px;
      border: 1px solid #ff4d4d;
      animation: shake 1.5s;
    }
    @media (max-width: 500px) {
      .login-box {
        padding: 30px 20px;
      }
    }
  </style>
</head>
<body>
  <div class="login-box">
    <h2>Genkada Admin Login</h2>
    <form method="POST">
      <input type="text" name="username" placeholder="Username" required />
      <input type="password" name="password" placeholder="Password" required />
      <?php if ($error): ?>
        <div class="error"><?php echo $error; ?></div>
      <?php endif; ?>
      <button type="submit">Login</button>
    </form>
  </div>
</body>
</html>