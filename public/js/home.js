const chevron = document.querySelector('.scroll');

function checkPosition() {
    let windowY = window.scrollY;

    if (windowY == 0) {
        chevron.classList.remove('hidden');
    } else {
        chevron.classList.add('hidden');
    }
    scrollPos = windowY;
}

function scrollDown() {
    const offsetTop = document.body.offsetHeight;

    scroll({
        top: offsetTop,
        behavior: "smooth"
    });
}

window.addEventListener('scroll', checkPosition);
chevron.addEventListener('click', scrollDown);
