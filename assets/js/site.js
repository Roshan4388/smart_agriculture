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

    topBtn.addEventListener('click', function () {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
});
