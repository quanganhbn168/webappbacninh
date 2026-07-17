(() => {
  "use strict";

  const header = document.getElementById("siteHeader");
  const backToTop = document.getElementById("backToTop");
  const currentYear = document.getElementById("currentYear");
  const mobileMenu = document.getElementById("mobileMenu");
  const consultForm = document.getElementById("consultForm");
  const formSuccess = document.getElementById("formSuccess");

  if (window.AOS) {
    AOS.init({
      duration: 650,
      easing: "ease-out-cubic",
      once: true,
      offset: 60,
      disable: window.matchMedia("(prefers-reduced-motion: reduce)").matches,
    });
  }

  const updateScrollUi = () => {
    const scrolled = window.scrollY > 40;
    header?.classList.toggle("is-scrolled", scrolled);
    backToTop?.classList.toggle("is-visible", window.scrollY > 500);
  };

  updateScrollUi();
  window.addEventListener("scroll", updateScrollUi, { passive: true });

  backToTop?.addEventListener("click", () => {
    window.scrollTo({ top: 0, behavior: "smooth" });
  });

  if (currentYear) {
    currentYear.textContent = new Date().getFullYear();
  }

  document.querySelectorAll('#mobileMenu a[href^="#"]').forEach((link) => {
    link.addEventListener("click", () => {
      const instance = bootstrap.Offcanvas.getInstance(mobileMenu);
      instance?.hide();
    });
  });

  // Demo validation only. Khi đưa vào Laravel, bỏ preventDefault và gửi form về route thật.
  consultForm?.addEventListener("submit", (event) => {
    event.preventDefault();
    event.stopPropagation();

    if (!consultForm.checkValidity()) {
      consultForm.classList.add("was-validated");
      formSuccess?.classList.add("d-none");
      return;
    }

    consultForm.classList.add("was-validated");
    formSuccess?.classList.remove("d-none");
    formSuccess?.scrollIntoView({ behavior: "smooth", block: "nearest" });
  });
})();
