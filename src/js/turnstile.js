function renderTurnstiles() {

    const turnstileElements = document.querySelectorAll('.cf-turnstile-container');

    turnstileElements.forEach((element) => {

        if (element.dataset.rendered) {
            return;
        }

        turnstile.render(element, {
            sitekey: element.dataset.sitekey,
            theme: 'light',
            size: 'flexible'
        });

        element.dataset.rendered = 'true';
    });
}

window.addEventListener('load', () => {

    if (typeof turnstile !== 'undefined') {
        renderTurnstiles();
    }
});