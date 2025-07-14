// Animate team cards on hover
document.querySelectorAll('.benefit-card').forEach(card => {
  card.addEventListener('mouseenter', () => {
    card.style.boxShadow = '0 8px 24px rgba(0, 0, 0, 0.2)';
  });

  card.addEventListener('mouseleave', () => {
    card.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.15)';
  });
});

// Dynamically update footer year
const yearEl = document.querySelector('.footer-year');
if (yearEl) {
  yearEl.textContent = new Date().getFullYear();
}
