document.addEventListener('DOMContentLoaded', function () {
    document.body.classList.add('loaded');

    const nav = document.querySelector('.main-nav');
    const topBtn = document.createElement('button');
    topBtn.className = 'back-to-top';
    topBtn.type = 'button';
    topBtn.title = 'Scroll to top';
    topBtn.innerText = '↑';
    document.body.appendChild(topBtn);

    function updateNav() {
        const y = window.scrollY;
        if (nav) {
            nav.classList.toggle('scrolled', y > 20);
        }
        topBtn.classList.toggle('visible', y > 320);
    }

    updateNav();
    window.addEventListener('scroll', updateNav, { passive: true });

    const themeButtons = document.querySelectorAll('.theme-toggle');
    const currentTheme = localStorage.getItem('farm-theme');
    if (currentTheme === 'dark') {
        document.body.classList.add('dark-mode');
    }

    themeButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            document.body.classList.toggle('dark-mode');
            const activeTheme = document.body.classList.contains('dark-mode') ? 'dark' : 'light';
            localStorage.setItem('farm-theme', activeTheme);
            btn.textContent = activeTheme === 'dark' ? '☀️ Light mode' : '🌙 Dark mode';
        });
        if (document.body.classList.contains('dark-mode')) {
            btn.textContent = '☀️ Light mode';
        }
    });

    topBtn.addEventListener('click', function () {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
});
