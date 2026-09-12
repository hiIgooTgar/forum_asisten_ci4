document.addEventListener("DOMContentLoaded", () => {
  const container = document.body;
  const targetElement = container.querySelector(".headline-animate-gsap");
  const brandChars = [];
  const regularChars = [];

  if (targetElement && typeof SplitType !== "undefined") {
    const split = new SplitType(targetElement, { types: "chars, words" });
    if (split.chars) {
      split.chars.forEach((char) => {
        char.classList.remove("text-transparent", "bg-clip-text");
        if (char.closest("span")) {
          char.classList.add(
            "bg-gradient-to-r",
            "from-[#0a2481]",
            "to-[#0037ff]",
            "bg-clip-text",
            "text-transparent",
            "inline-block",
          );
          brandChars.push(char);
        } else {
          char.style.color = "#0B0521";
          char.classList.add("inline-block");
          regularChars.push(char);
        }
      });
    }
  }

  if (typeof gsap !== "undefined") {
    const tl = gsap.timeline({ defaults: { ease: "power3.out" } });

    if (container.querySelector(".left-content-gsap")) {
      tl.from(".left-content-gsap", {
        x: -40,
        opacity: 0,
        duration: 1,
      });
    }

    if (regularChars.length > 0) {
      tl.from(
        regularChars,
        {
          y: 20,
          opacity: 0,
          duration: 0.5,
          stagger: 0.02,
          ease: "power2.out",
        },
        "-=0.6",
      );
    }

    if (brandChars.length > 0) {
      tl.from(
        brandChars,
        {
          duration: 0.6,
          opacity: 0,
          scale: 0.5,
          y: 30,
          rotationX: 90,
          transformOrigin: "50% 50%",
          ease: "back.out(1.5)",
          stagger: 0.04,
        },
        "-=0.3",
      );
    }

    const fadeUpElements = container.querySelectorAll(".fade-up-gsap");
    if (fadeUpElements.length > 0) {
      tl.from(
        fadeUpElements,
        {
          y: 15,
          opacity: 0,
          duration: 0.4,
          stagger: 0.08,
          ease: "power2.out",
        },
        "-=0.3",
      );
    }
  }
});
