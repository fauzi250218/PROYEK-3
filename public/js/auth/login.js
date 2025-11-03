// =========================================
// LOGIN PAGE INTERACTIONS
// =========================================

// Tambah efek aktif saat input fokus
document.querySelectorAll('.form-control').forEach((input) => {
  input.addEventListener('focus', () => {
    input.closest('.input-group').classList.add('focus-active');
  });
  input.addEventListener('blur', () => {
    input.closest('.input-group').classList.remove('focus-active');
  });
});

// Fade-in halus saat halaman selesai dimuat
window.addEventListener('load', () => {
  document.body.style.opacity = '1';
  document.body.style.transition = 'opacity 1s ease';
});

// Tambah tombol show/hide password
const passwordField = document.querySelector('#password');
if (passwordField) {
  const icon = document.createElement('i');
  icon.classList.add('bi', 'bi-eye', 'position-absolute', 'end-0', 'top-50', 'translate-middle-y', 'me-3', 'text-muted');
  icon.style.cursor = 'pointer';
  passwordField.parentNode.style.position = 'relative';
  passwordField.parentNode.appendChild(icon);

  icon.addEventListener('click', () => {
    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordField.setAttribute('type', type);
    icon.classList.toggle('bi-eye');
    icon.classList.toggle('bi-eye-slash');
  });
}
