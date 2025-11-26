//Este archivo se llamaba carousel.js y estaba en la carpeta /main/
const tricksList = document.getElementById('tricksList');
const leftArrow = document.getElementById('leftArrow');
const rightArrow = document.getElementById('rightArrow');

const scrollStep = 330; 



/**
 * Función para desplazar la lista de trucos.@param {number} direction - 1 para derecha, -1 para izquierda.
 */

function scrollCarousel(direction) {
    const newScrollLeft = tricksList.scrollLeft + (direction * scrollStep);

    tricksList.scroll({
        left: newScrollLeft,
        behavior: 'smooth'
    });

    setTimeout(updateArrows, 500);
}


rightArrow.addEventListener('click', () => {
    scrollCarousel(1);
});

leftArrow.addEventListener('click', () => {
    scrollCarousel(-1);
});



function updateArrows() {
    if (tricksList.scrollLeft <= 0) {
        leftArrow.style.opacity = 0.4;
        leftArrow.style.pointerEvents = 'none';
    } else {
        leftArrow.style.opacity = 1;
        leftArrow.style.pointerEvents = 'auto';
    }
    
    if (tricksList.scrollLeft + tricksList.clientWidth >= tricksList.scrollWidth - 5) {
        rightArrow.style.opacity = 0.4;
        rightArrow.style.pointerEvents = 'none';
    } else {
        rightArrow.style.opacity = 1;
        rightArrow.style.pointerEvents = 'auto';
    }
}

window.addEventListener('load', updateArrows); 
tricksList.addEventListener('scroll', updateArrows);