// Script JS to make a burger menu for mobile and tablet mod

const burgerMenu = document.querySelector('.burger-menu');
const nav = document.querySelector('header nav');

burgerMenu.addEventListener('click', () => {
    nav.classList.toggle('open');
});