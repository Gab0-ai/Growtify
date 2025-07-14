<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$successMessage = '';

// Fetch user credentials
$stmt = $pdo->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch user profile
$stmt = $pdo->prepare("SELECT * FROM user_profiles WHERE user_id = ?");
$stmt->execute([$userId]);
$profile = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $password = $_POST["password"] ?? '';
    $firstName = trim($_POST["first_name"] ?? '');
    $lastName = trim($_POST["last_name"] ?? '');
    $gender = $_POST["gender"] ?? '';
    $age = $_POST["age"] ?? null;
    $weight = $_POST["weight"] ?? null;
    $height = $_POST["height"] ?? null;
    $bmi = $height ? $weight / (($height / 100) ** 2) : null;
    $targetWeight = $_POST["target_weight"] ?? null;
    $goal = isset($_POST["goal"]) ? implode(", ", (array)$_POST["goal"]) : '';

    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, password_hash = ? WHERE id = ?");
        $stmt->execute([$username, $email, $hashedPassword, $userId]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
        $stmt->execute([$username, $email, $userId]);
    }

    $stmt = $pdo->prepare("SELECT user_id FROM user_profiles WHERE user_id = ?");
    $stmt->execute([$userId]);

    if ($stmt->rowCount() > 0) {
        $stmt = $pdo->prepare("UPDATE user_profiles SET first_name=?, last_name=?, gender=?, age=?, goal=?, weight=?, height=?, bmi=?, target_weight=? WHERE user_id=?");
        $stmt->execute([$firstName, $lastName, $gender, $age, $goal, $weight, $height, $bmi, $targetWeight, $userId]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO user_profiles (user_id, first_name, last_name, gender, age, goal, weight, height, bmi, target_weight) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$userId, $firstName, $lastName, $gender, $age, $goal, $weight, $height, $bmi, $targetWeight]);
    }

    $successMessage = "Profile updated successfully!";

    // Refresh user data after update
    $stmt = $pdo->prepare("SELECT username, email FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT * FROM user_profiles WHERE user_id = ?");
    $stmt->execute([$userId]);
    $profile = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="shortcut icon" href="./assets/images/icon.png" type="image/x-icon" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="./styles/variables.css" />
  <link rel="stylesheet" href="./styles/exercises.css" />
  <link rel="stylesheet" href="./styles/header.css" />
  <link rel="stylesheet" href="./styles/footer.css" />
  <link rel="stylesheet" href="./styles/profile.css" />
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

  <main class="main-container" style="padding-top: 80px;">
    <h1>Your Profile</h1>

    <?php if ($successMessage): ?>
      <p class="text-green-600 text-center mb-4 font-semibold"><?= $successMessage ?></p>
    <?php endif; ?>

    <form method="POST" class="profile-form">
      <div class="credentials-box">
        <h2>Account Credentials</h2>
        <label>Username</label>
        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required disabled>

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required disabled>

        <label>Password (Leave blank to keep current)</label>
        <input type="password" name="password" placeholder="New Password" disabled>
      </div>

      <div class="personal-box">
        <label>First Name</label>
        <input type="text" name="first_name" value="<?= htmlspecialchars($profile['first_name'] ?? '') ?>" disabled>

        <label>Last Name</label>
        <input type="text" name="last_name" value="<?= htmlspecialchars($profile['last_name'] ?? '') ?>" disabled>

        <label>Gender</label>
        <select name="gender" disabled>
          <option value="">Select Gender</option>
          <option value="Male" <?= ($profile['gender'] ?? '') === 'Male' ? 'selected' : '' ?>>Male</option>
          <option value="Female" <?= ($profile['gender'] ?? '') === 'Female' ? 'selected' : '' ?>>Female</option>
        </select>

        <label>Age</label>
        <input type="number" name="age" value="<?= htmlspecialchars($profile['age'] ?? '') ?>" disabled>

        <label>Weight (kg)</label>
        <input type="number" name="weight" step="0.1" value="<?= htmlspecialchars($profile['weight'] ?? '') ?>" disabled>

        <label>Height (cm)</label>
        <input type="number" name="height" step="0.1" value="<?= htmlspecialchars($profile['height'] ?? '') ?>" disabled>

        <label>Target Weight (kg)</label>
        <input type="number" name="target_weight" step="0.1" value="<?= htmlspecialchars($profile['target_weight'] ?? '') ?>" disabled>

        <label>What is your goal?</label>
        <div class="goal-tags">
          <?php
            $selectedGoals = explode(", ", $profile['goal'] ?? '');
            $goals = ["Stay fit and healthy", "Build muscle", "Gain energy", "Healthy meal plans", "Lose weight", "Gain confidence"];
            foreach ($goals as $goalOption):
          ?>
            <span class="goal <?= in_array($goalOption, $selectedGoals) ? 'selected' : '' ?>"><?= $goalOption ?></span>
          <?php endforeach; ?>
        </div>
        <?php foreach ($goals as $goalOption): ?>
          <input type="checkbox" name="goal[]" value="<?= $goalOption ?>" class="goal-input" style="display:none" <?= in_array($goalOption, $selectedGoals) ? 'checked' : '' ?> disabled>
        <?php endforeach; ?>
      </div>

      <div class="form-buttons">
        <button type="button" class="edit-btn" onclick="enableEdit()">Edit Profile</button>
        <button type="submit" class="save-btn" disabled>Save Profile</button>
      </div>
    </form>
  </main>

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
        <a href="./">Home</a>
        
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
    function enableEdit() {
      document.querySelectorAll('.profile-form input, .profile-form select').forEach(el => el.disabled = false);
      document.querySelector('.save-btn').disabled = false;
    }

    document.querySelectorAll('.goal').forEach((el, index) => {
      el.addEventListener('click', () => {
        const checkbox = document.querySelectorAll('.goal-input')[index];
        if (checkbox.disabled) return;
        el.classList.toggle('selected');
        checkbox.checked = !checkbox.checked;
      });
    });

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
</body>
</html>
