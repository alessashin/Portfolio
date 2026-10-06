document.addEventListener('DOMContentLoaded', () => {
  const preloader = document.getElementById('preloader');

  if (preloader) {
    // Force hide after 2.5 seconds no matter what
    setTimeout(() => {
      preloader.classList.add('fade-out');
    }, 2500);
  }
});