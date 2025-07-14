const activePage = window.location.pathname;
const links = document.querySelectorAll("nav a");

links.forEach((link) => {
  const url = new URL(link.href).pathname;

  if (activePage === url) link.classList.add("activeHeaderLink");
});
