const ctx = document.getElementById('weightChart').getContext('2d');

new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
    datasets: [
      {
        label: 'Current Weight (kg)',
        data: [50, 49.5, 49.2, 49.1, 48.9, 48.8, 48.5],
        backgroundColor: ['#F8EDE3', '#DFD3C3', '#A76545', '#F8EDE3', '#DFD3C3', '#A76545', '#F8EDE3'],
        borderColor: '#a76545',
        borderWidth: 1,
        borderRadius: 5,
      },
      {
        label: 'Goal Weight (kg)',
        type: 'line',
        data: [45, 45, 45, 45, 45, 45, 45],
        borderColor: '#8b5e3c',
        borderWidth: 2,
        pointRadius: 0,
        fill: false,
      }
    ]
  },
  options: {
    responsive: true,
    plugins: {
      legend: {
        labels: {
          color: '#fff'
        }
      }
    },
    scales: {
      x: {
        ticks: { color: '#ffffff' },
        grid: { color: '#ffffff33' }
      },
      y: {
        ticks: { color: '#ffffff' },
        grid: { color: '#ffffff33' }
      }
    }
  }
});

// Checklist Progress
const checkboxes = document.querySelectorAll('#checklist input[type="checkbox"]');
const bar = document.querySelector('.progress-bar-inner');
const label = document.querySelector('.progress-label');

function updateProgress() {
  const total = checkboxes.length;
  const checked = [...checkboxes].filter(cb => cb.checked).length;
  const percent = Math.round((checked / total) * 100);
  bar.style.width = percent + '%';
  label.textContent = percent + '%';
}

checkboxes.forEach(box => {
  box.addEventListener('change', () => {
    const li = box.closest('li');
    if (box.checked) {
      li.style.backgroundColor = '#4ade80';
      li.style.color = '#1f2937';
    } else {
      li.style.backgroundColor = 'transparent';
      li.style.color = '#ffffff';
    }
    updateProgress();
  });
});

// Initial state
updateProgress();
