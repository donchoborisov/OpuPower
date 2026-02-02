import './bootstrap';
import Alpine from 'alpinejs';

// hamburger menu
function toggleMenu() {
    document.getElementById('menu-items')?.classList.toggle('hidden');
    document.getElementById('menu-close')?.classList.toggle('hidden');
    document.getElementById('menu-open')?.classList.toggle('hidden');
}

const menuOpen = document.getElementById('menu-open');
const menuClose = document.getElementById('menu-close');

if (menuOpen && menuClose) {
    menuOpen.addEventListener('click', () => {
        toggleMenu();
    });

    menuClose.addEventListener('click', () => {
        toggleMenu();
    });
}

window.Alpine = Alpine;

Alpine.start();
