<?php
session_start();
require_once 'db.php'; // Uses $pdo from db.php

if (!isset($_SESSION['user_id'])) {
  header("Location: ../login.php");
  exit();
}

$user_id = $_SESSION['user_id'];
$target_weight = 'Not set';

$stmt = $pdo->prepare("SELECT target_weight FROM user_profiles WHERE user_id = ?");
$stmt->execute([$user_id]);
$row = $stmt->fetch();

if ($row && isset($row['target_weight'])) {
  $target_weight = htmlspecialchars($row['target_weight']) . " kg";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="shortcut icon" href="./assets/images/icon.png" type="image/x-icon" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" />
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="./styles/variables.css" />
  <link rel="stylesheet" href="./styles/exercises.css" />
  <link rel="stylesheet" href="./styles/header.css" />
  <link rel="stylesheet" href="./styles/footer.css" />
  <link rel="stylesheet" href="./styles/dashboardx.css" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <title>Growtify</title>
</head>

<body class="dashboard-body">
  <div class="header-bg"></div>

  <header>
    <a href="./dashboard.php" class="brand">
      <img src="./assets/images/icon.png" alt="Growtify Logo" class="brand-logo" />
      <span class="brand-name">Growtify</span>
    </a>
    <nav>
      <a href="./exercises/underweight.html">Underweight</a>
      <a href="./exercises/overweight.html">Overweight</a>
      <a href="./profile.php">Profile</a>
      <a href="./logout.php" class="login-btn">Logout</a>
    </nav>
  </header>

  <div class="dashboard-grid" style="padding-top: 80px;">
    <div class="chart-card">
      <h2>Weight Progress</h2>
      <canvas id="weightChart" width="400" height="300"></canvas>
    </div>

    <div class="center-panel">
      <div class="progress-box">
        <h3>Your Progress:</h3>
        <div class="progress-bar-container">
          <div class="progress-bar-inner">
            <span class="progress-label">0%</span>
          </div>
        </div>
      </div>
      <div class="goal-box">
        <span>🎯 Target Weight</span><br>
        <strong><?php echo $target_weight; ?></strong>
      </div>
    </div>

    <div class="routine-card">
      <h2>Weekly Routine</h2>
      <ul id="checklist">
        <li><label><input type="checkbox"> Day 1: Full Body Strength</label></li>
        <li><label><input type="checkbox"> Day 2: Strength Training</label></li>
        <li><label><input type="checkbox"> Day 3: Active Recovery</label></li>
        <li><label><input type="checkbox"> Day 4: Stationary Cycling</label></li>
        <li><label><input type="checkbox"> Day 5: Strength Training</label></li>
        <li><label><input type="checkbox"> Day 6: Low Impact Aerobics</label></li>
        <li><label><input type="checkbox"> Day 7: Rest or Recreation</label></li>
      </ul>
    </div>
  </div>

  <footer>
    <div class="footer-website-info">
      <div class="footer-brand-contact">
        <a href="/growtify/dashboard.php" class="brand-logo">
          <img src="./assets/images/growtify.png" alt="Growtify Logo" class="footer-logo">
        </a>
        <hr class="footer-line" />
        <p class="footer-contact">Contact us: 0912 345 6789</p>
      </div>

      <div class="links">
        <h3>Links</h3>
        <a href="/growtify/dashboard.php">Home</a>
       
      </div>

      <div class="chat-box-section">
        <label for="chat-box" class="chat-label">Email Us</label>
        <textarea id="chat-box" class="chat-box" rows="10" cols="30" placeholder="Type your message..."></textarea>
        <button type="button" class="send-button" onclick="sendMessage()">Send</button>
      </div>
    </div>

    <div class="icons">
      <a href="https://www.youtube.com/watch?v=hPr-Yc92qaY"><i class="fa-brands fa-facebook"></i></a>
    <a href="https://www.youtube.com/watch?v=hPr-Yc92qaY"><i class="fa-brands fa-instagram"></i></a>
    <a href="https://www.youtube.com/watch?v=hPr-Yc92qaY"><i class="fa-brands fa-youtube"></i></a>
    <a href="https://www.youtube.com/watch?v=hPr-Yc92qaY"><i class="fa-brands fa-twitter"></i></a>
    <a href="https://www.youtube.com/watch?v=hPr-Yc92qaY"><i class="fa-brands fa-tiktok"></i></a>
    <a href="https://www.youtube.com/watch?v=hPr-Yc92qaY"><i class="fa-brands fa-snapchat"></i></a>
    </div>

    <div id="footer-copyright">
      <p>&copy; <span class="footer-year"></span> Growtify Group</p>
    </div>
  </footer>

  <script>
    document.querySelector('.footer-year').textContent = new Date().getFullYear();

    function sendMessage() {
      const message = document.getElementById("chat-box").value.trim();
      if (message) {
        alert("Your message has been sent!");
        document.getElementById("chat-box").value = "";
      } else {
        alert("Please enter a message before sending.");
      }
    }
  </script>
  <script src="./dashboard/dashboard.js"></script>
</body>
</html>
