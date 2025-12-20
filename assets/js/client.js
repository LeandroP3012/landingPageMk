document.addEventListener('DOMContentLoaded', () => {
    const clientPage = document.querySelector('.client-page');
    const top = document.querySelector('.reveal-top');
    const bottom = document.querySelector('.reveal-bottom');

    if (!clientPage || !top || !bottom) return;

    // Usar IntersectionObserver para animación al aparecer
    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                clientPage.classList.add('is-visible');

                // Esperar la duración de la animación y remover persianas
                setTimeout(() => {
                    top.classList.add('done');
                    bottom.classList.add('done');
                }, 1000); // 1s = duración de la transición

                obs.unobserve(clientPage);
            }
        });
    }, { threshold: 0.25 });

    observer.observe(clientPage);
});
