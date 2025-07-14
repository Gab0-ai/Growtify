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

  // === BMI Calculation ===
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
      if (bmiValue) bmiValue.textContent = rounded;

      // Clear previous classes
      feedback.classList.remove("red", "yellow", "green");

      if (bmi < 18.5) {
        feedback.textContent = "Underweight";
        feedback.classList.add("red");
      } else if (bmi < 25) {
        feedback.textContent = "Normal weight";
        feedback.classList.add("green");
      } else if (bmi < 30) {
        feedback.textContent = "Overweight";
        feedback.classList.add("yellow");
      } else {
        feedback.textContent = "Obese";
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
