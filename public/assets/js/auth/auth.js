document.addEventListener("DOMContentLoaded", () => {
  document.addEventListener("click", (e) => {
    const toggleBtn = e.target.closest(".toggle-password-btn");
    if (!toggleBtn) return;

    e.preventDefault();

    const wrapper =
      toggleBtn.closest(".password-wrapper") || toggleBtn.parentElement;
    const passwordInput = wrapper ? wrapper.querySelector("input") : null;
    const icon = toggleBtn.querySelector("i");

    if (!passwordInput || !icon) return;
    const isPassword = passwordInput.getAttribute("type") === "password";

    if (isPassword) {
      passwordInput.setAttribute("type", "text");
      icon.classList.remove("fa-eye-slash");
      icon.classList.add("fa-eye");
    } else {
      passwordInput.setAttribute("type", "password");
      icon.classList.remove("fa-eye");
      icon.classList.add("fa-eye-slash");
    }
  });

  const slides = document.querySelectorAll(".banner-slide");
  const dots = document.querySelectorAll(".banner-dot");
  let currentSlide = 0;
  if (slides.length > 0) {
    const showSlide = (index) => {
      slides.forEach((slide, i) => {
        if (i === index) {
          slide.classList.remove("hidden", "opacity-0", "scale-95");
          slide.classList.add("block", "opacity-100", "scale-100");
        } else {
          slide.classList.remove("block", "opacity-100", "scale-100");
          slide.classList.add("hidden", "opacity-0", "scale-95");
        }
      });
      dots.forEach((dot, i) => {
        if (i === index) {
          dot.classList.add("bg-[#0037ff]", "w-[24px]");
          dot.classList.remove("bg-white/30", "w-[12px]");
        } else {
          dot.classList.remove("bg-[#0037ff]", "w-[24px]");
          dot.classList.add("bg-white/30", "w-[12px]");
        }
      });
    };

    dots.forEach((dot, index) => {
      dot.addEventListener("click", () => {
        currentSlide = index;
        showSlide(currentSlide);
      });
    });

    setInterval(() => {
      currentSlide = (currentSlide + 1) % slides.length;
      showSlide(currentSlide);
    }, 7000);
  }
});
