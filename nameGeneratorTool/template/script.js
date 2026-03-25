document.addEventListener("DOMContentLoaded", () => {
  const hamburger = document.querySelector(".hamburger");
  const mobileMenu = document.getElementById("mobileMenu");
  const closeBtn = document.getElementById("closeMenu");
  const overlay = document.getElementById("overlay");

  if (hamburger && mobileMenu && overlay && closeBtn) {
    hamburger.addEventListener("click", () => {
      mobileMenu.classList.add("active");
      overlay.classList.add("active");
      document.body.style.overflow = "hidden"; // 🔒 disable background scroll
    });

    closeBtn.addEventListener("click", () => {
      mobileMenu.classList.remove("active");
      overlay.classList.remove("active");
      document.body.style.overflow = ""; // 🔓 enable scroll again
    });

    overlay.addEventListener("click", () => {
      mobileMenu.classList.remove("active");
      overlay.classList.remove("active");
      document.body.style.overflow = ""; // 🔓 enable scroll again
    });
  }
});
