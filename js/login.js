document.addEventListener("DOMContentLoaded", function () {
  const loginForm = document.getElementById("loginForm");

  loginForm.addEventListener("submit", function (event) {
    event.preventDefault();

    const username = document.getElementById("username").value.trim();
    const password = document.getElementById("password").value;

    if (username && password) {
      // Simulated login success – redirect to dashboard
      window.location.href = "aboutuser.html";
    } else {
      alert("Please enter both username and password.");
    }
  });
});
