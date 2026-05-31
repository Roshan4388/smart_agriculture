document.addEventListener("DOMContentLoaded", () => {
  const elements = document.querySelectorAll(".scroll-reveal");

  elements.forEach((el) => {
    el.classList.add("visible");
  });
});

document.addEventListener("DOMContentLoaded", () => {
  const elements = document.querySelectorAll(".scroll-reveal");

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("visible");
      }
    });
  });

  elements.forEach((el) => observer.observe(el));
});
