const body = document.body;
const themeToggleButtons = document.querySelectorAll(".theme-toggle");
const typedElements = document.querySelectorAll(".typed-text");
const scrollRevealItems = document.querySelectorAll(".scroll-reveal");

function applyTheme(theme) {
  body.classList.toggle("dark-mode", theme === "dark");
  localStorage.setItem("smartAgTheme", theme);
  themeToggleButtons.forEach((button) => {
    button.textContent = theme === "dark" ? "☀️ Light mode" : "🌙 Dark mode";
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
  if (!window.IntersectionObserver) {
    scrollRevealItems.forEach((item) => item.classList.add("visible"));
    return;
  }
  const observer = new IntersectionObserver(revealOnScroll, {
    threshold: 0.12,
    rootMargin: "0px 0px -50px 0px",
  });
  scrollRevealItems.forEach((item) => observer.observe(item));

  // Immediately reveal items already in viewport
  setTimeout(() => {
    scrollRevealItems.forEach((item) => {
      const rect = item.getBoundingClientRect();
      if (rect.top < window.innerHeight && rect.bottom > 0) {
        item.classList.add("visible");
      }
    });
  }, 50);
}

function initTyping() {
  typedElements.forEach((el) => {
    const typedString = el.dataset.typed || "";
    const lines = typedString
      .split(";")
      .map((line) => line.trim())
      .filter(Boolean);
    let currentLine = 0;
    let currentIndex = 0;

    if (!lines.length) {
      return;
    }

    const cursor = document.createElement("span");
    cursor.className = "typed-cursor";
    el.after(cursor);

    function typeText() {
      const line = lines[currentLine];
      el.textContent = line.slice(0, currentIndex);
      if (currentIndex <= line.length) {
        currentIndex += 1;
        setTimeout(typeText, 70);
        return;
      }
      setTimeout(() => {
        currentIndex = 0;
        currentLine = (currentLine + 1) % lines.length;
        setTimeout(typeText, 1200);
      }, 1400);
    }

    typeText();
  });
}

document.addEventListener("DOMContentLoaded", () => {
  body.classList.add("page-loaded");
  loadTheme();
  initTyping();
  initScrollReveal();
  themeToggleButtons.forEach((button) =>
    button.addEventListener("click", () => {
      const nextTheme = body.classList.contains("dark-mode") ? "light" : "dark";
      applyTheme(nextTheme);
    }),
  );
});
