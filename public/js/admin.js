document.addEventListener("DOMContentLoaded", function () {
  const sidebar = document.getElementById("sidebar");
  const toggleBtn = document.getElementById("sidebarToggle");
  const overlay = document.getElementById("sidebarOverlay");

  let state = localStorage.getItem("sidebarState");

  // Terapkan state di JS (sinkronisasi)
  if (state === "collapsed") {
    sidebar.classList.add("collapsed");
  } else if (state === "expanded") {
    sidebar.classList.remove("collapsed");
  } else if (state === "open") {
    sidebar.classList.add("open");
    overlay.classList.add("show");
  }

  // Hapus init classes setelah load
  document.documentElement.classList.remove("sidebar-init-collapsed");
  document.documentElement.classList.remove("sidebar-init-open");

  function openMobile() {
    sidebar.classList.add("open");
    overlay.classList.add("show");
    localStorage.setItem("sidebarState", "open");
  }
  function closeMobile() {
    sidebar.classList.remove("open");
    overlay.classList.remove("show");
    localStorage.setItem("sidebarState", "closed");
  }
  function toggleDesktopCollapse() {
    sidebar.classList.toggle("collapsed");
    if (sidebar.classList.contains("collapsed")) {
      localStorage.setItem("sidebarState", "collapsed");
    } else {
      localStorage.setItem("sidebarState", "expanded");
    }
  }

  toggleBtn.addEventListener("click", function () {
    if (window.innerWidth >= 992) {
      toggleDesktopCollapse();
    } else {
      sidebar.classList.contains("open") ? closeMobile() : openMobile();
    }
  });

  overlay.addEventListener("click", closeMobile);

  window.addEventListener("resize", function () {
    if (window.innerWidth >= 992) {
      closeMobile(); // jaga2 overlay ilang
    }
  });
});
