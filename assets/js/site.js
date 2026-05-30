const body = document.body;
const themeToggleButtons = document.querySelectorAll(".theme-toggle");
const scrollRevealItems = document.querySelectorAll(".scroll-reveal");

function applyTheme(theme) {
  body.classList.toggle("dark-mode", theme === "dark");
  localStorage.setItem("smartAgTheme", theme);
  themeToggleButtons.forEach((button) => {
    button.textContent = theme === "dark" ? "Light mode" : "Dark mode";
  });
}

function loadTheme() {
  const savedTheme = localStorage.getItem("smartAgTheme");
  if (savedTheme) {
    applyTheme(savedTheme);
    return;
  }
  const prefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
  applyTheme(prefersDark ? "dark" : "light");
}

function revealOnScroll(entries) {
  entries.forEach((entry) => {
    if (entry.isIntersecting) {
      entry.target.classList.add("visible");
    }
  });
}

function initScrollReveal() {
  if (!scrollRevealItems.length) {
    return;
  }
  if (!window.IntersectionObserver) {
    scrollRevealItems.forEach((item) => item.classList.add("visible"));
    return;
  }
  const observer = new IntersectionObserver(revealOnScroll, {
    threshold: 0.12,
    rootMargin: "0px 0px -50px 0px",
  });
  scrollRevealItems.forEach((item) => observer.observe(item));
}

document.addEventListener("DOMContentLoaded", () => {
  try {
    body.classList.add("page-loaded");
    loadTheme();
    initScrollReveal();
    themeToggleButtons.forEach((button) =>
      button.addEventListener("click", () => {
        const nextTheme = body.classList.contains("dark-mode")
          ? "light"
          : "dark";
        applyTheme(nextTheme);
      }),
    );
  } catch (error) {
    console.warn("Smart Agriculture UI initialization warning:", error);
  }
});
