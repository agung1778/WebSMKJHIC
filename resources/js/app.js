import './bootstrap'; 

import Alpine from 'alpinejs';


window.Alpine = Alpine;

Alpine.start();

import Splide from '@splidejs/splide';

document.addEventListener('DOMContentLoaded', () => {
    const splideElements = document.querySelectorAll('.splide');
    if (splideElements.length) {
        splideElements.forEach(element => {
            new Splide(element).mount();
        });
    }
});