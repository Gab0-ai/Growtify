<?php
session_start();
require 'db.php'; // This defines $pdo

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $age = isset($_POST['age']) ? (int)$_POST['age'] : null;
    $goal = $_POST['goal'] ?? '';
    $weight = isset($_POST['weight']) ? (float)$_POST['weight'] : null;
    $height_cm = isset($_POST['height']) ? (float)$_POST['height'] : null;
    $target_weight = isset($_POST['target_weight']) ? (float)$_POST['target_weight'] : null;

    $height_m = $height_cm / 100;
    $bmi = ($height_m > 0) ? round($weight / ($height_m * $height_m), 2) : null;

    $stmt = $pdo->prepare("SELECT user_id FROM user_profiles WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $profileExists = $stmt->fetch();

    if ($profileExists) {
        $update = $pdo->prepare("UPDATE user_profiles SET 
            first_name = ?, last_name = ?, gender = ?, age = ?, goal = ?, weight = ?, height = ?, bmi = ?, target_weight = ?
            WHERE user_id = ?");
        $update->execute([
            $first_name, $last_name, $gender, $age, $goal,
            $weight, $height_cm, $bmi, $target_weight, $user_id
        ]);
    } else {
        $insert = $pdo->prepare("INSERT INTO user_profiles 
            (user_id, first_name, last_name, gender, age, goal, weight, height, bmi, target_weight) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $insert->execute([
            $user_id, $first_name, $last_name, $gender, $age,
            $goal, $weight, $height_cm, $bmi, $target_weight
        ]);
    }

    header("Location: dashboard.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Growtify</title>
  <link rel="shortcut icon" href="assets/images/icon.png" type="image/x-icon" />
  <link rel="stylesheet" href="styles/aboutuser.css" />
</head>
<body>
  <form method="POST" action="aboutuser.php">
    <div class="form-wrapper">

      <!-- LEFT: User Info -->
      <div class="card left">
        <h2 class="title">Tell us about yourself</h2>
        <div class="form-row">
          <input type="text" name="first_name" placeholder="First name" required />
          <input type="text" name="last_name" placeholder="Last name" required />
        </div>
        <div class="form-row">
          <select name="gender" required>
            <option value="">Gender</option>
            <option value="Female">Female</option>
            <option value="Male">Male</option>
          </select>
          <input type="number" name="age" placeholder="Age" required />
        </div>
        <label>What is your goal?</label>
        <div class="goal-tags">
          <span class="goal">Stay fit and healthy</span>
          <span class="goal">Build muscle</span>
          <span class="goal">Gain energy</span>
          <span class="goal">Healthy meal plans</span>
          <span class="goal">Lose weight</span>
          <span class="goal">Gain confidence</span>
        </div>
          <input type="hidden" name="goal" id="goalInput" />
      </div>

      <!-- RIGHT TOP: BMI -->
      <div class="card right-top">
        <h3 class="title small">Your BMI Index</h3>
        <p class="desc">BMI is based on height & weight.</p>
        <div class="form-row">
          <input type="number" name="weight" id="bmiWeight" placeholder="Weight (kg)" required />
          <input type="number" name="height" id="bmiHeight" placeholder="Height (cm)" required />
        </div>
        <div class="bmi-bar">
          <div class="bmi-zone underweight">Under</div>
          <div class="bmi-zone normal">Normal</div>
          <div class="bmi-zone overweight">Over</div>
          <div class="bmi-zone obese">Obese</div>
        </div>
        <div id="bmi-value" class="bmi-result">--</div>
        <div id="bmi-feedback" class="bmi-feedback"></div>
      </div>

      <!-- RIGHT BOTTOM: Target -->
      <div class="card right-bottom">
        <h2>Target Weight</h2>
        <p>How do you see your target weight?</p>
        <input type="number" name="target_weight" placeholder="Target weight (kg)" required />
      </div>

      <!-- Confirm Button -->
      <button type="submit" class="confirm-btn">Confirm</button>
    </div>
  </form>


  <script>
  document.addEventListener("DOMContentLoaded", function () {
  // === Goal selection ===
  const goalTags = document.querySelectorAll(".goal");
  const goalInput = document.getElementById("goalInput");

  goalTags.forEach(tag => {
    tag.addEventListener("click", function () {
      this.classList.toggle("active");

      const selectedGoals = Array.from(goalTags)
        .filter(t => t.classList.contains("active"))
        .map(t => t.textContent.trim());

      goalInput.value = selectedGoals.join(", ");
    });
  });

  // === BMI Calculation and Feedback ===
  const weightInput = document.getElementById("bmiWeight");
  const heightInput = document.getElementById("bmiHeight");
  const bmiValue = document.getElementById("bmi-value");
  const feedback = document.getElementById("bmi-feedback");

  function updateBMI() {
    const weight = parseFloat(weightInput?.value);
    const heightCm = parseFloat(heightInput?.value);

    if (weight > 0 && heightCm > 0) {
      const heightM = heightCm / 100;
      const bmi = weight / (heightM * heightM);
      const rounded = bmi.toFixed(2);
      if (bmiValue) bmiValue.textContent = `BMI: ${rounded}`;

      // Reset feedback classes
      feedback.classList.remove("red", "yellow", "green");

      // Provide detailed feedback
      if (bmi < 18.5) {
        feedback.textContent = "Underweight: Your BMI is on lower side. Nourishing your body with balanced  meals can help you reach a healthier weight.";
        feedback.classList.add("red");
      } else if (bmi < 25) {
        feedback.textContent = "Normal weight: You’re in a healthy weight range! Keep up your balanced habits with good nutrition and regular movement to maintain your well - being.";
        feedback.classList.add("green");
      } else if (bmi < 30) {
        feedback.textContent = "Overweight: Your BMI is slightly above the recommended range. Small changes, like adding more movement and mindful eating, can help you feel your best.";
        feedback.classList.add("yellow");
      } else {
        feedback.textContent = "Obese: Your BMI suggests a higher weight than recommended, which may impact your health.";
        feedback.classList.add("red");
      }
    } else {
      bmiValue.textContent = "--";
      feedback.textContent = "";
      feedback.classList.remove("red", "yellow", "green");
    }
  }

  if (weightInput && heightInput) {
    weightInput.addEventListener("input", updateBMI);
    heightInput.addEventListener("input", updateBMI);
  }
});

</script>

</body>
</html>
