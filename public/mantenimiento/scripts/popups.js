const setUpPopUp = (pttnFormData, nameFileFetch, title, additionalParams = null, typePopUp) => {
    const popUpTitle = document.getElementById('uploadMediaTitle');
    const popUpSubtitle = document.getElementById('uploadMediaSubtitle');
    const popUpContent = document.getElementById('uploadMediaContent');

    popUpTitle.textContent = title;
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
            formData.append('pttn', pttnFormData);
            formData.append(nameFileFetch, file);

            if (additionalParams) {
                for (const [key, value] of Object.entries(additionalParams)) {
                    formData.append(key, value);
                }
            }

            let urlPopUp = './mantenimiento/api.php';
            if (location.href.includes('admin')) {
                urlPopUp = '../mantenimiento/api.php';
            }

            fetch(urlPopUp, {
                method: 'POST',
                body: formData
            }).then((response) => response.json())
                .then((data) => {
                    if (data.response === 'OK') {
                        if (typePopUp === 'pfp') {
                            // Se recarga en el nav.
                            loadGlobals();

                            // Se recarga en el aside (img).
                            recargarImgPerfil();
                        }
                        else if (typePopUp === 'imgXat') {
                            getChats();
                        }
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