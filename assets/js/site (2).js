const body = document.body;
const themeToggleButtons = document.querySelectorAll(".theme-toggle");
const scrollRevealItems = document.querySelectorAll(".scroll-reveal");
const translations = {
  en: {
    "nav.home": "Home",
    "nav.dashboard": "Dashboard",
    "nav.sensorData": "Sensor Data",
    "nav.cropReport": "Crop Report",
    "nav.alerts": "Alerts",
    "nav.landMap": "Land Map",
    "nav.cropMonitoring": "Crop Monitoring",
    "nav.cropLibrary": "Crop Library",
    "nav.diseaseGuide": "Disease Guide",
    "nav.expertConsult": "Expert Consult",
    "nav.farmerGroups": "Farmer Groups",
    "nav.marketplace": "View Marketplace",
    "nav.expertGroups": "Expert Groups",
    "nav.marketStatus": "Market Status",
    "nav.weather": "Weather",
    "nav.reports": "Reports",
    "nav.settings": "Settings",
    "nav.logout": "Logout",
    "nav.login": "Login",
    "nav.register": "Register",
    "nav.admin": "Admin",
    "theme.auto": "Auto (system)",
    "theme.light": "Light",
    "theme.dark": "Dark",
    "theme.green": "Green farm theme",
    "theme.darkMode": "Dark mode",
    "theme.lightMode": "Light mode",
    "settings.title": "Settings",
    "settings.subtitle": "Configure your Smart Agriculture dashboard and farm preferences from one place.",
    "settings.appearance": "Appearance",
    "settings.account": "Account",
    "settings.language": "Language",
    "settings.notifications": "Notifications",
    "settings.security": "Security",
    "settings.farmPreferences": "Farm Preferences",
    "settings.switchUser": "Switch User",
    "settings.helpSupport": "Help & Support",
    "settings.appearanceCopy": "Choose your dashboard style and theme. Use the global dark/light toggle in the sidebar for quick switching.",
    "settings.theme": "Theme",
    "settings.previewTheme": "Preview theme",
    "settings.saveAppearance": "Save appearance",
    "settings.languageCopy": "Select your preferred interface language.",
    "settings.preferredLanguage": "Preferred language",
    "settings.saveLanguage": "Save language",
  },
  ne: {
    "nav.home": "गृहपृष्ठ",
    "nav.dashboard": "ड्यासबोर्ड",
    "nav.sensorData": "सेन्सर डाटा",
    "nav.cropReport": "बाली रिपोर्ट",
    "nav.alerts": "सूचना",
    "nav.landMap": "जग्गा नक्सा",
    "nav.cropMonitoring": "बाली निगरानी",
    "nav.cropLibrary": "बाली पुस्तकालय",
    "nav.diseaseGuide": "रोग मार्गदर्शन",
    "nav.expertConsult": "विशेषज्ञ परामर्श",
    "nav.farmerGroups": "किसान समूह",
    "nav.marketplace": "बजार हेर्नुहोस्",
    "nav.expertGroups": "विशेषज्ञ समूह",
    "nav.marketStatus": "बजार स्थिति",
    "nav.weather": "मौसम",
    "nav.reports": "रिपोर्टहरू",
    "nav.settings": "सेटिङहरू",
    "nav.logout": "लगआउट",
    "nav.login": "लगइन",
    "nav.register": "दर्ता",
    "nav.admin": "प्रशासन",
    "theme.auto": "स्वचालित (सिस्टम)",
    "theme.light": "उज्यालो",
    "theme.dark": "अँध्यारो",
    "theme.green": "हरियो फार्म थिम",
    "theme.darkMode": "अँध्यारो मोड",
    "theme.lightMode": "उज्यालो मोड",
    "settings.title": "सेटिङहरू",
    "settings.subtitle": "तपाईंको स्मार्ट कृषि ड्यासबोर्ड र फार्म प्राथमिकताहरू एउटै ठाउँबाट मिलाउनुहोस्।",
    "settings.appearance": "रूप",
    "settings.account": "खाता",
    "settings.language": "भाषा",
    "settings.notifications": "सूचनाहरू",
    "settings.security": "सुरक्षा",
    "settings.farmPreferences": "फार्म प्राथमिकता",
    "settings.switchUser": "प्रयोगकर्ता बदल्नुहोस्",
    "settings.helpSupport": "सहयोग",
    "settings.appearanceCopy": "ड्यासबोर्ड शैली र थिम छान्नुहोस्। छिटो परिवर्तनका लागि साइडबारको डार्क/लाइट टगल प्रयोग गर्नुहोस्।",
    "settings.theme": "थिम",
    "settings.previewTheme": "थिम हेर्नुहोस्",
    "settings.saveAppearance": "रूप सेभ गर्नुहोस्",
    "settings.languageCopy": "आफ्नो मनपर्ने इन्टरफेस भाषा छान्नुहोस्।",
    "settings.preferredLanguage": "मनपर्ने भाषा",
    "settings.saveLanguage": "भाषा सेभ गर्नुहोस्",
  },
};

function applyTheme(theme) {
  const resolvedTheme =
    theme === "auto"
      ? window.matchMedia("(prefers-color-scheme: dark)").matches
        ? "dark"
        : "light"
      : theme;

  body.classList.toggle("dark-mode", resolvedTheme === "dark");
  body.classList.toggle("green-mode", resolvedTheme === "green");

  if (theme === "auto") {
    localStorage.removeItem("smartAgTheme");
  } else {
    localStorage.setItem("smartAgTheme", theme);
  }

  themeToggleButtons.forEach((button) => {
    const language = getLanguage();
    button.textContent =
      resolvedTheme === "dark"
        ? translate("theme.lightMode", language)
        : translate("theme.darkMode", language);
  });
}

function translate(key, language = getLanguage()) {
  return translations[language]?.[key] || translations.en[key] || key;
}

function getLanguage() {
  return localStorage.getItem("smartAgLanguage") || document.documentElement.lang || "en";
}

function applyLanguage(language) {
  const activeLanguage = translations[language] ? language : "en";
  localStorage.setItem("smartAgLanguage", activeLanguage);
  document.documentElement.lang = activeLanguage;

  document.querySelectorAll("[data-i18n]").forEach((element) => {
    element.textContent = translate(element.dataset.i18n, activeLanguage);
  });

  themeToggleButtons.forEach((button) => {
    button.textContent = body.classList.contains("dark-mode")
      ? translate("theme.lightMode", activeLanguage)
      : translate("theme.darkMode", activeLanguage);
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
    applyLanguage(getLanguage());
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

window.SmartAgUI = {
  applyLanguage,
  applyTheme,
};
