const recargarImgPerfil = () => {
    getPFP().then(data => {
        let pfp = document.getElementById("pfpPerfilSettings");
        let path = "./media/sistema/usuaris/";
        if (window.location.href.includes("admin")) path = "../media/sistema/usuaris/";
        pfp.src = path + data.pfp;
    })
}

const setUpPopUp = () => {
    const popUpTitle = document.getElementById('uploadMediaTitle');
    const popUpSubtitle = document.getElementById('uploadMediaSubtitle');
    const popUpContent = document.getElementById('uploadMediaContent');

    popUpTitle.textContent = 'Canvia la teva imatge de perfil';
    popUpSubtitle.textContent = 'Arronsa una imatge des de el teu ordinador';

    // Limpiar el contenido previo
    popUpContent.innerHTML = '';

    // Crear input file
    const fileInput = document.createElement('input');
    fileInput.type = 'file';
    fileInput.id = 'file';
    fileInput.accept = 'image/jpeg, image/png'; // Aceptar solo JPEG y PNG
    fileInput.style.display = 'none';
    popUpContent.appendChild(fileInput);

    // Crear label para input file
    const fileLabel = document.createElement('label');
    fileLabel.htmlFor = 'file';
    fileLabel.textContent = 'També pots pujar una imatge des de el teu ordinador';
    popUpContent.appendChild(fileLabel);

    // Crear div para previsualización
    const previewDiv = document.createElement('div');
    previewDiv.id = 'preview';
    popUpContent.appendChild(previewDiv);

    // Mostrar popup
    viewPopUp('popupUploadMedia', 'closeUploadMedia');

    // Manejar drag and drop
    popUpContent.addEventListener('dragover', (event) => {
        event.preventDefault();
        popUpContent.classList.add('dragover');
    });

    popUpContent.addEventListener('dragleave', () => {
        popUpContent.classList.remove('dragover');
    });

    popUpContent.addEventListener('drop', (event) => {
        event.preventDefault();
        popUpContent.classList.remove('dragover');
        const files = event.dataTransfer.files;
        handleFiles(files);
    });

    // Manejar selección de archivos
    fileInput.addEventListener('change', (event) => {
        const files = event.target.files;
        handleFiles(files);
    });

    // Manejar archivos
    const handleFiles = (files) => {
        const file = files[0];
        if (file && (file.type === 'image/jpeg' || file.type === 'image/png')) {
            const reader = new FileReader();
            reader.onload = (event) => {
                previewDiv.innerHTML = ''; // Limpiar contenido previo
                const img = document.createElement('img');
                img.src = event.target.result;
                img.alt = 'Preview';
                img.style.maxWidth = '100%';
                previewDiv.appendChild(img);
            };
            reader.readAsDataURL(file);
        } else {
            alert('Només pots pujar arxius d\'imatge en format JPG o PNG!');
        }
    };

    document.getElementById('uploadMediaButton').addEventListener('click', () => {
        const file = document.getElementById('file').files[0];
        if (file) {
            const formData = new FormData();
            formData.append('pttn', 'uploadImgPFP');
            formData.append('imatgePFP', file);
            fetch('./mantenimiento/api.php', {
                method: 'POST',
                body: formData
            }).then((response) => response.json())
                .then((data) => {
                    if (data.response === 'OK') {
                        // Se recarga en el nav.
                        loadGlobals();

                        // Se recarga en el aside (img).
                        recargarImgPerfil();
                    } else {
                        alert('No s\'ha pogut pujar la imatge!');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('No s\'ha pogut pujar la imatge!');
                });
        } else {
            alert('Selecciona una imatge!');
        }
    });
};

const getProfileData = () => {
    let formData = new FormData();
    formData.append('pttn', 'getProfileData');

    fetch('./mantenimiento/api.php', {
        method: 'POST',
        body: formData
    }).then(response => response.json())
        .then(data => {
            if (data.response === 'OK') {
                document.getElementById('username').value = data.data.nickname;
                document.getElementById('email').value = data.data.email;
                document.getElementById('description').value = data.data.descripcio;
            } else {
                alert('Error al cargar los datos del perfil: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('No se pudo cargar los datos del perfil.');
        });
}

// Primera vez que se carga la página
recargarImgPerfil();
getProfileData();

document.getElementById('cambiarImgBtn').addEventListener('click', () => setUpPopUp());
document.getElementById('profile-form').addEventListener('submit', function (event) {
    event.preventDefault(); // Prevenir el envío del formulario

    const formData = new FormData();
    formData.append('pttn', 'updateProfile');
    formData.append('username', document.getElementById('username').value);
    formData.append('email', document.getElementById('email').value);
    formData.append('password', (document.getElementById('password').value) ? document.getElementById('password').value : '');
    formData.append('description', document.getElementById('description').value);

    const toast = document.getElementById('toast');
    fetch('./mantenimiento/api.php', {
        method: 'POST',
        body: formData
    }).then(response => response.json())
        .then(data => {
            if (data.response === 'OK') {
                toast.textContent = 'Perfil actualitzat correctament.';
                setTimeout(() => {
                    toast.textContent = '';
                }, 3000);
                getProfileData(); // Recargar la página para mostrar los cambios
            } else {
                toast.textContent = 'Error al actualizar el perfil: ' + data.message;
                setTimeout(() => {
                    toast.textContent = '';
                }, 3000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('No se pudo actualizar el perfil.');
        });
});