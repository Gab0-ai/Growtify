<?php
session_start();
require_once 'db.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $admin_username = $_POST['username'] ?? '';
    $admin_password = $_POST['password'] ?? '';

    // Simple hardcoded admin check
    if ($admin_username === 'admin' && $admin_password === 'admin123') {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "Invalid credentials!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <style>
        body { font-family: Arial; background: #f2f2f2; }
        .login-box {
            background: white; padding: 20px; width: 300px; margin: 100px auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1); border-radius: 10px;
        }
        input[type="text"], input[type="password"] {
            width: 100%; padding: 10px; margin: 10px 0;
        }
        button { width: 100%; padding: 10px; background: #28a745; color: white; border: none; }
        .error { color: red; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Admin Login</h2>
        <?php if ($error) echo "<p class='error'>" . htmlspecialchars($error) . "</p>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required />
            <input type="password" name="password" placeholder="Password" required />
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
