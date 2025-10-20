document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const icon = sidebarToggle.querySelector('i');

    // ✅ Toggle sidebar + animasi ikon
    sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('open');
        icon.classList.toggle('bi-list');
        icon.classList.toggle('bi-x');
    });

    // caret rotation animation
    const collapseEl = document.getElementById('kelasMenu');
    const caretIcon = document.querySelector('.caret-icon');
    if (collapseEl && caretIcon) {
        collapseEl.addEventListener('show.bs.collapse', () => {
            caretIcon.style.transform = 'rotate(180deg)';
        });
        collapseEl.addEventListener('hide.bs.collapse', () => {
            caretIcon.style.transform = 'rotate(0deg)';
        });
    }

    // ✅ Parent menu tetap aktif saat submenu aktif
    const submenuLinks = document.querySelectorAll('.submenu .nav-link');
    submenuLinks.forEach(link => {
        if (link.classList.contains('active')) {
            const parentMainLink = link.closest('.nav-item').querySelector('.main-link');
            if (parentMainLink) parentMainLink.classList.add('active');
        }
    });
});
