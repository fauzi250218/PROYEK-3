// Efek halus saat scroll dan interaksi hover
document.addEventListener('DOMContentLoaded', () => {
    const rows = document.querySelectorAll('.table tbody tr');

    rows.forEach(row => {
        row.addEventListener('mouseenter', () => {
            row.style.transition = 'all 0.15s ease';
            row.style.transform = 'scale(1.01)';
        });

        row.addEventListener('mouseleave', () => {
            row.style.transform = 'scale(1)';
        });
    });

    // Animasi masuk halaman
    const wrapper = document.querySelector('.table-wrapper');
    if (wrapper) {
        wrapper.style.opacity = 0;
        wrapper.style.transform = 'translateY(20px)';
        setTimeout(() => {
            wrapper.style.transition = 'all 0.6s ease';
            wrapper.style.opacity = 1;
            wrapper.style.transform = 'translateY(0)';
        }, 150);
    }
});
