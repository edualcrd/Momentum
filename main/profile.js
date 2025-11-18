// Obtener todos los elementos del DOM necesarios
const usernameDisplay = document.getElementById('usernameDisplay');
const editUsernameInput = document.getElementById('editUsernameInput');
const editIcon = document.getElementById('editIcon');

const usernameText = document.querySelector('.username-text'); 
const usernameField = document.getElementById('usernameField');

const bioText = document.getElementById('bioText');
const bioField = document.getElementById('bioField');
const saveIcon = document.getElementById('saveIcon');

// --- 1. Función para activar el modo de edición ---
function enableEditMode() {
    // OCULTAR elementos de visualización
    usernameDisplay.style.display = 'none';
    bioText.style.display = 'none';
    
    // MOSTRAR elementos de edición
    editUsernameInput.style.display = 'flex';
    bioField.style.display = 'block';
    saveIcon.style.display = 'block';
    
    // Cargar valores actuales en los campos
    usernameField.value = usernameText.textContent.trim();
    bioField.value = bioText.textContent.trim();
    
    // Enfocar el primer campo
    usernameField.focus();
}

// --- 2. Función para guardar y salir del modo de edición ---
function saveProfile() {
    let newUsername = usernameField.value.trim();
    const newBio = bioField.value.trim();
    
    // Validación básica
    if (newUsername === "") {
        alert("El nombre de usuario no puede estar vacío.");
        usernameField.focus();
        return;
    }

    // Asegurar que el nombre de usuario comience con @
    if (!newUsername.startsWith('@')) {
        newUsername = '@' + newUsername;
    }

    // Actualizar el texto de visualización
    usernameText.textContent = newUsername;
    bioText.textContent = newBio;
    
    // Volver al modo de visualización: OCULTAR elementos de edición
    editUsernameInput.style.display = 'none';
    bioField.style.display = 'none';
    saveIcon.style.display = 'none';
    
    // MOSTRAR elementos de visualización
    usernameDisplay.style.display = 'flex';
    bioText.style.display = 'block';
    
    // Código para enviar los datos al servidor...
    console.log(`Perfil guardado: Usuario: ${newUsername}, Biografía: ${newBio}`);
}

// --- 3. Asignar Event Listeners ---

editIcon.addEventListener('click', enableEditMode);
saveIcon.addEventListener('click', saveProfile);
usernameField.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') { 
        saveProfile();
    }
});