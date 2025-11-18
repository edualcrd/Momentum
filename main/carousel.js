// Obtener los elementos del DOM
const tricksList = document.getElementById('tricksList');
const leftArrow = document.getElementById('leftArrow');
const rightArrow = document.getElementById('rightArrow');

// Variable para definir la cantidad de desplazamiento
// Se puede ajustar según el tamaño de la tarjeta + el espacio entre ellas (300px + 30px)
const scrollStep = 330; 

// --- Funcionalidad del Carrusel ---

/**
 * Función para desplazar la lista de trucos.
 * @param {number} direction - 1 para derecha, -1 para izquierda.
 */
function scrollCarousel(direction) {
    // Calcula la nueva posición de desplazamiento
    const newScrollLeft = tricksList.scrollLeft + (direction * scrollStep);
    
    // Aplica el desplazamiento suave
    tricksList.scroll({
        left: newScrollLeft,
        behavior: 'smooth'
    });
    
    // Después de un pequeño retraso, verifica si debe mostrar/ocultar las flechas
    // Esto es útil para carruseles finitos (aunque con este CSS no se ocultan automáticamente)
    setTimeout(updateArrows, 500);
}


// --- Lógica de los Event Listeners ---

// Asignar la función a las flechas
rightArrow.addEventListener('click', () => {
    scrollCarousel(1); // Mover a la derecha
});

leftArrow.addEventListener('click', () => {
    scrollCarousel(-1); // Mover a la izquierda
});


// --- Lógica para mostrar/ocultar flechas (Opcional, si el carrusel tiene un final) ---

function updateArrows() {
    // Muestra/oculta la flecha izquierda si llega al principio
    if (tricksList.scrollLeft <= 0) {
        leftArrow.style.opacity = 0.4;
        leftArrow.style.pointerEvents = 'none';
    } else {
        leftArrow.style.opacity = 1;
        leftArrow.style.pointerEvents = 'auto';
    }
    
    // Muestra/oculta la flecha derecha si llega al final
    // Añadimos un pequeño margen de error (+5) por si el cálculo no es exacto
    if (tricksList.scrollLeft + tricksList.clientWidth >= tricksList.scrollWidth - 5) {
        rightArrow.style.opacity = 0.4;
        rightArrow.style.pointerEvents = 'none';
    } else {
        rightArrow.style.opacity = 1;
        rightArrow.style.pointerEvents = 'auto';
    }
}

// Inicializa las flechas al cargar la página
window.addEventListener('load', updateArrows); 
tricksList.addEventListener('scroll', updateArrows); // Actualiza al desplazar manualmente (si fuera posible)