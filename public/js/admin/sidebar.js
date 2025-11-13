// public/js/admin/sidebar.js
document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("sidebar");
    const toggleBtn = document.getElementById("sidebarToggle");

    if (sidebar && toggleBtn) {
        toggleBtn.addEventListener("click", () => {
            sidebar.classList.toggle("collapsed");
        });
    }
});
