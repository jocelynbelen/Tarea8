// Para aparecer o desaparecer la notificación
window.addEventListener('load', () => {
  const n = document.getElementById('notifications');
  if (n && n.textContent.trim() !== '') {
    n.style.opacity = 1;
    setTimeout(() => { n.style.opacity = 0; }, 4000);
  }
});
