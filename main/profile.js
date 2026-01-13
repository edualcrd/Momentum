document.addEventListener('DOMContentLoaded', () => {
    // =========================================
    // 1. VARIABLES Y ELEMENTOS DEL DOM
    // =========================================
    
    // Contenedores de modos
    const viewMode = document.getElementById('viewMode');
    const editMode = document.getElementById('editMode');
    
    // Botones de acción
    const editIcon = document.getElementById('editIcon');
    const saveBtn = document.getElementById('saveBtn');
    const cancelBtn = document.getElementById('cancelBtn');

    // Inputs del formulario de edición
    const editUsername = document.getElementById('editUsername');
    const editGroup = document.getElementById('editGroup');
    const editBio = document.getElementById('editBio');
    const editPhoto = document.getElementById('editPhoto'); // Input de archivo

    // Elementos de visualización (Texto e Imagen)
    const usernameDisplay = document.getElementById('usernameDisplay');
    const groupDisplay = document.getElementById('groupDisplay');
    const bioDisplay = document.getElementById('bioDisplay');
    const profilePicDisplay = document.getElementById('profilePicDisplay');

    // =========================================
    // 2. LÓGICA DE EDICIÓN DE PERFIL
    // =========================================

    // Abrir modo edición
    if (editIcon) {
        editIcon.addEventListener('click', () => {
            viewMode.style.display = 'none';
            editMode.style.display = 'block';
        });
    }

    // Cancelar edición (Restaurar valores y cerrar)
    if (cancelBtn) {
        cancelBtn.addEventListener('click', () => {
            editMode.style.display = 'none';
            viewMode.style.display = 'block';
            
            // Resetear inputs a los valores originales visuales
            editUsername.value = usernameDisplay.innerText;
            editGroup.value = groupDisplay.innerText;
            editBio.value = bioDisplay.innerText;
            editPhoto.value = ""; // Limpiar selección de archivo
        });
    }

    // Guardar cambios (AJAX con FormData)
    if (saveBtn) {
        saveBtn.addEventListener('click', () => {
            // FEEDBACK VISUAL: Botón cargando
            const originalText = saveBtn.innerText;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
            saveBtn.classList.add('btn-loading'); // Clase definida en CSS para cursor: wait

            // Preparamos los datos
            const formData = new FormData();
            formData.append('username', editUsername.value);
            formData.append('grupo', editGroup.value);
            formData.append('biografia', editBio.value);
            
            // Solo añadir la foto si el usuario seleccionó una
            if (editPhoto.files.length > 0) {
                formData.append('foto', editPhoto.files[0]);
            }

            // Enviar al servidor
            fetch('/Momentum/main/update_profile.php', {
                method: 'POST',
                body: formData // Importante: no usar headers JSON con FormData
            })
            .then(response => response.json())
            .then(data => {
                // RESTAURAR BOTÓN
                saveBtn.innerHTML = originalText;
                saveBtn.classList.remove('btn-loading');

                if (data.success) {
                    // 1. Actualizar textos en la pantalla
                    usernameDisplay.innerText = editUsername.value;
                    groupDisplay.innerText = editGroup.value;
                    bioDisplay.innerText = editBio.value;

                    // 2. Actualizar foto si vino una nueva URL
                    if (data.newPhotoUrl) {
                        profilePicDisplay.src = data.newPhotoUrl;
                    }
                    
                    // 3. Cerrar modo edición
                    editMode.style.display = 'none';
                    viewMode.style.display = 'block';
                    editPhoto.value = ""; 
                    
                    // 4. Mostrar notificación (función global en index.php)
                    if (typeof showToast === 'function') {
                        showToast("Perfil actualizado correctamente", "success");
                    }
                } else {
                    if (typeof showToast === 'function') {
                        showToast('Error: ' + data.message, "error");
                    } else {
                        alert('Error: ' + data.message);
                    }
                }
            })
            .catch(error => {
                // Error de red
                saveBtn.innerHTML = originalText;
                saveBtn.classList.remove('btn-loading');
                console.error('Error:', error);
                if (typeof showToast === 'function') {
                    showToast('Error de conexión con el servidor', "error");
                }
            });
        });
    }

    // =========================================
    // 3. LÓGICA DE ELIMINAR TRUCOS
    // =========================================
    
    const deleteButtons = document.querySelectorAll('.delete-icon');

    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const trickId = this.getAttribute('data-id');
            
            // Confirmación sencilla
            if (!confirm("¿Seguro que quieres eliminar este truco? Esta acción es irreversible.")) {
                return;
            }

            // Seleccionar la tarjeta completa
            const card = document.getElementById('trick-' + trickId);
            
            // Feedback visual inmediato (opacidad)
            if(card) card.style.opacity = '0.5';

            fetch('/Momentum/main/delete_trick.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: trickId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Animación de salida
                    if (card) {
                        card.style.transition = 'transform 0.3s ease, opacity 0.3s ease';
                        card.style.transform = 'scale(0)';
                        
                        setTimeout(() => {
                            card.remove();
                            // Si usas carrusel, aquí podrías llamar a actualizar flechas
                            // if (typeof updateArrows === 'function') updateArrows();
                        }, 300);
                    }
                    
                    if (typeof showToast === 'function') {
                        showToast("Truco eliminado", "success");
                    }
                } else {
                    // Restaurar tarjeta si hubo error
                    if(card) card.style.opacity = '1';
                    
                    if (typeof showToast === 'function') {
                        showToast(data.message || "No se pudo eliminar", "error");
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if(card) card.style.opacity = '1';
                if (typeof showToast === 'function') {
                    showToast("Error de conexión", "error");
                }
            });
        });
    });
});