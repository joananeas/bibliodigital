const recargarImgPerfil = () => {
    getPFP().then(data => {
        let pfp = document.getElementById("pfpPerfilSettings");
        let path = "./media/sistema/usuaris/";
        if (window.location.href.includes("admin")) path = "../media/sistema/usuaris/";
        pfp.src = path + data.pfp;
    })
}

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

document.getElementById('cambiarImgBtn').addEventListener('click', () => setUpPopUp('uploadImgPFP', 'imatgePFP', 'Canvia la teva imatge de perfil', 'pfp'));
document.getElementById('profile-form').addEventListener('submit', function (event) {
    event.preventDefault(); // Prevenir el envío del formulario

    const formData = new FormData();
    formData.append('pttn', 'updateProfile');
    formData.append('username', document.getElementById('username').value);
    formData.append('email', document.getElementById('email').value);
    formData.append('password', document.getElementById('password').value || '');
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