const exerciseButtons = document.querySelectorAll(".exercise-btn");
const videoWrappers = document.querySelectorAll(".video-wrapper");
const videos = document.querySelectorAll("video");

for (let i = 0; i < exerciseButtons.length; i++) {
  exerciseButtons[i].addEventListener("click", () => {
    exerciseButtons[i].classList.toggle("active");
    videoWrappers[i].classList.toggle("show-video");
    exerciseButtons[i];
    if (exerciseButtons[i].classList.contains("active")) {
      exerciseButtons[i].style.color = "var(--black)";
      videos[i].play();
    } else {
      exerciseButtons[i].style.color = "";
      videos[i].pause();
    }
  });
}
