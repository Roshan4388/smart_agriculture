function validateSignIn() {
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();
    const errorBox = document.getElementById('signin-error');

    errorBox.textContent = '';

    if (!email) {
        errorBox.textContent = 'Please enter your email.';
        return false;
    }

    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        errorBox.textContent = 'Please enter a valid email address.';
        return false;
    }

    if (!password) {
        errorBox.textContent = 'Please enter your password.';
        return false;
    }

    if (password.length < 6) {
        errorBox.textContent = 'Password must be at least 6 characters long.';
        return false;
    }

    return true;
}
