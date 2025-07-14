<?php
session_start();
require 'db.php'; // this must define $pdo

$login_error = ""; // Initialize the error variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT id, username, password_hash FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user["password_hash"])) {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        header("Location: dashboard.php");
        exit();
    } else {
        $login_error = "Invalid username or password.";
    }
}
?>

  
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Growtify – Log In</title>
  <link rel="stylesheet" href="styles/login.css" />
  <link rel="shortcut icon" href="assets/images/gro.png" type="image/x-icon" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <div class="login-container">
    <a href="index.html" class="back-button"><i class="fas fa-arrow-left"></i> Back</a>

    <div class="ellipse">
      <img src="assets/images/growtify.png" alt="Growtify Logo" class="logo" />
    </div>

    <h1 class="login-title">Log in</h1>

    <?php if ($login_error): ?>
      <p style="color:red;"><?= $login_error ?></p>
    <?php endif; ?>

    <form class="login-form" action="login.php" method="POST">
      <label for="username">Username</label>
      <input type="text" id="username" name="username" placeholder="Your username" required />

      <label for="password">Password</label>
      <input type="password" id="password" name="password" placeholder="Your password" required />

      <a href="#" class="forgot-password">Forgot password?</a>

      <button type="submit" class="login-btn">Login</button>
    </form>

    <!-- Sign Up as Link -->
    <p class="signup-text" style="text-align: center; margin-top: 1rem;">
      No account? <a href="signup.php" class="signup-link">Sign up</a>
    </p>
  </div>
</body>
</html>
