// Highlight menu aktif
document.querySelectorAll('.nav-link').forEach(link => {
  link.addEventListener('click', function () {
    document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
    this.classList.add('active');
  });
});

// Validasi form Bootstrap
(() => {
  const form = document.querySelector('#contactForm');
  form.addEventListener('submit', event => {
    if (!form.checkValidity()) {
      event.preventDefault();
      event.stopPropagation();
    } else {
      event.preventDefault();
      alert('Pesan berhasil dikirim! (simulasi)');
      form.reset();
    }
    form.classList.add('was-validated');
  });
})();
