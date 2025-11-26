    const usernameDisplay = document.getElementById('username-display');
    const editIcon = document.getElementById('edit-icon');
    const editContainer = document.getElementById('username-edit-container');
    const usernameInput = document.getElementById('username-input');
    const saveButton = document.getElementById('save-button');
    const cancelButton = document.getElementById('cancel-button');

    let initialUsernameValue = usernameInput.value; 


    editIcon.addEventListener('click', () => {
        usernameDisplay.style.display = 'none';
        editContainer.style.display = 'flex';
        
        usernameInput.value = usernameDisplay.textContent.trim().replace('@', '').replace('✏️', '').trim();
        
        usernameInput.focus();
    });

    const switchToDisplayMode = () => {
        editContainer.style.display = 'none';
        usernameDisplay.style.display = 'block'; 
        usernameDisplay.appendChild(editIcon);
    };

    saveButton.addEventListener('click', () => {
        const nuevoNombre = usernameInput.value.trim();
        
        if (nuevoNombre) {
            const nombreFinal = nuevoNombre.startsWith('@') ? nuevoNombre : `@${nuevoNombre}`;

            usernameDisplay.textContent = nombreFinal;
            
            switchToDisplayMode();
            console.log(`[DEV] Nombre guardado. Se debe enviar al servidor: ${nombreFinal}`);
            
            
        } else {
            alert('El nombre de usuario no puede estar vacío.');
            usernameInput.focus();
        }
    });

    cancelButton.addEventListener('click', () => {
        usernameInput.value = initialUsernameValue;

        switchToDisplayMode();
    });
    
    usernameInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            saveButton.click();
        }
    });
