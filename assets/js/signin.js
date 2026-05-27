function setAuthError(message) {
  const errorBox = document.getElementById("signin-error");
  if (!errorBox) {
    return;
  }
  errorBox.textContent = message;
  errorBox.style.display = message ? "block" : "none";
}

function showSpinner(visible) {
  const spinner = document.querySelector(".spinner");
  const submitButton = document.querySelector(
    '#signin-form button[type="submit"]',
  );
  if (spinner) {
    spinner.classList.toggle("hidden", !visible);
  }
  if (submitButton) {
    submitButton.classList.toggle("loading", visible);
    submitButton.disabled = visible;
  }
}

function validateSignIn() {
  setAuthError("");
  const emailElement = document.getElementById("email");
  const passwordElement = document.getElementById("password");
  const confirmElement = document.getElementById("confirm_password");
  const phoneElement = document.getElementById("phone");
  const otpElement = document.getElementById("otp_code");

  if (otpElement) {
    if (!otpElement.value.trim()) {
      setAuthError("Please enter the one-time code.");
      return false;
    }
    if (otpElement.value.trim().length < 4) {
      setAuthError("The OTP code should be at least 4 digits.");
      return false;
    }
    return true;
  }

  if (emailElement) {
    const email = emailElement.value.trim();
    if (!email) {
      setAuthError("Please enter your email.");
      return false;
    }
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
      setAuthError("Please enter a valid email address.");
      return false;
    }
  }

  if (phoneElement) {
    const phone = phoneElement.value.trim();
    if (!phone) {
      setAuthError("Please enter your phone number.");
      return false;
    }
    const phonePattern = /^\+?[0-9 \-]{8,20}$/;
    if (!phonePattern.test(phone)) {
      setAuthError("Please enter a valid phone number with country code.");
      return false;
    }
  }

  if (passwordElement) {
    const password = passwordElement.value.trim();
    if (!password) {
      setAuthError("Please enter your password.");
      return false;
    }
    if (password.length < 6) {
      setAuthError("Password must be at least 6 characters long.");
      return false;
    }
  }

  if (confirmElement) {
    const confirm = confirmElement.value.trim();
    const password = passwordElement ? passwordElement.value.trim() : "";
    if (!confirm) {
      setAuthError("Please confirm your password.");
      return false;
    }
    if (confirm !== password) {
      setAuthError("Passwords do not match.");
      return false;
    }
  }

  showSpinner(true);
  return true;
}

document.addEventListener("DOMContentLoaded", () => {
  const fields = document.querySelectorAll("#signin-form input");
  fields.forEach((field) => {
    field.addEventListener("focus", () => field.classList.add("focused"));
    field.addEventListener("blur", () => field.classList.remove("focused"));
  });
});
