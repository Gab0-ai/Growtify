<?php
// Enable errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require 'db.php';

$signup_error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $terms = isset($_POST["terms"]);

    if (!$terms) {
        $signup_error = "You must accept the terms.";
    } elseif ($password !== $confirm_password) {
        $signup_error = "Passwords do not match.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $signup_error = "Invalid email format.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);

        if ($stmt->fetch()) {
            $signup_error = "Username or email already exists.";
        } else {
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");

            if ($stmt->execute([$username, $email, $password_hash])) {
                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['username'] = $username;
                header("Location: aboutuser.php");
                exit();
            } else {
                $signup_error = "Signup failed. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Growtify - Sign Up</title>
  <link rel="stylesheet" href="styles/signup.css">
</head>
<body>
  <div class="signup-container">
    <h1 class="signup-title">Sign Up</h1>

    <?php if ($signup_error): ?>
      <p class="error-message"><?= htmlspecialchars($signup_error) ?></p>
    <?php endif; ?>

    <form class="signup-form" method="POST" action="signup.php">
      <label for="username">Username:</label>
      <input type="text" name="username" id="username" required>

      <label for="email">Email:</label>
      <input type="email" name="email" id="email" required>

      <label for="password">Password:</label>
      <input type="password" name="password" id="password" required>

      <label for="confirm_password">Confirm Password:</label>
      <input type="password" name="confirm_password" id="confirm_password" required>

      <div class="checkbox-group">
        <input type="checkbox" name="terms" id="terms" required>
        <label for="terms">I agree to the <span class="terms-green">Terms</span></label>
      </div>

      <button type="submit" class="signup-btn">Sign Up</button>
    </form>

    <p class="already-account">Already have an account? <a href="login.php">Log In</a></p>
  </div>
</body>
</html>
