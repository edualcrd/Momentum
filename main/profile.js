document.addEventListener('DOMContentLoaded', () => {
    const viewMode = document.getElementById('viewMode');
    const editMode = document.getElementById('editMode');
    
    const editIcon = document.getElementById('editIcon');
    const saveBtn = document.getElementById('saveBtn');
    const cancelBtn = document.getElementById('cancelBtn');

    // Inputs
    const editUsername = document.getElementById('editUsername');
    const editGroup = document.getElementById('editGroup');
    const editBio = document.getElementById('editBio');
    const editPhoto = document.getElementById('editPhoto'); // Nuevo input de foto

    // Displays (Visualización)
    const usernameDisplay = document.getElementById('usernameDisplay');
    const groupDisplay = document.getElementById('groupDisplay');
    const bioDisplay = document.getElementById('bioDisplay');
    const profilePicDisplay = document.getElementById('profilePicDisplay'); // Imagen principal

    // 1. Abrir modo edición
    editIcon.addEventListener('click', () => {
        viewMode.style.display = 'none';
        editMode.style.display = 'block';
    });

    // 2. Cancelar
    cancelBtn.addEventListener('click', () => {
        editMode.style.display = 'none';
        viewMode.style.display = 'block';
        // Resetear valores
        editUsername.value = usernameDisplay.innerText;
        editGroup.value = groupDisplay.innerText;
        editBio.value = bioDisplay.innerText;
        editPhoto.value = ""; // Limpiar input de archivo
    });

    // 3. Guardar cambios (Ahora soporta archivos)
    saveBtn.addEventListener('click', () => {
        const formData = new FormData();
        
        // Añadir textos
        formData.append('username', editUsername.value);
        formData.append('grupo', editGroup.value);
        formData.append('biografia', editBio.value);
        
        // Añadir foto SOLO si el usuario seleccionó una nueva
        if (editPhoto.files.length > 0) {
            formData.append('foto', editPhoto.files[0]);
        }

        fetch('/Momentum/main/update_profile.php', {
            method: 'POST',
            body: formData // Enviamos FormData, NO JSON
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Actualizar textos en la vista
                usernameDisplay.innerText = editUsername.value;
                groupDisplay.innerText = editGroup.value;
                bioDisplay.innerText = editBio.value;

                // Si se subió foto nueva y el servidor devolvió la nueva URL
                if (data.newPhotoUrl) {
                    profilePicDisplay.src = data.newPhotoUrl;
                }
                
                // Volver a vista normal
                editMode.style.display = 'none';
                viewMode.style.display = 'block';
                editPhoto.value = ""; // Limpiar input
            } else {
                alert('Error al guardar: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error de conexión.');
        });
    });
});