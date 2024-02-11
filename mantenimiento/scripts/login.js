const authUsuario = (email, password) => {
    let formData = new FormData();
    formData.append('pttn', 'authUsuario');
    formData.append('email', email);
    formData.append('password', password);

    fetch('./mantenimiento/api.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
        if (data.response === 'ok') {
            window.location.href = './index.php';
        }
        else {
            alert("Los datos introducidos no son correctos."); // TODO: Mejorar el mensaje de error.
        }
    })
    .catch(error => {
        console.error('Error al procesar la solicitud', error);
    });
}

const genPasswd = () => {
    let formData = new FormData();
    formData.append('pttn', 'genPasswd');

    fetch('./mantenimiento/api.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
    })
    .catch(error => {
        console.error('Error al procesar la solicitud', error);
    });
}

genPasswd();

document.getElementById("login").addEventListener("click", function(e){
    e.preventDefault();
    let email = document.getElementById("email").value;
    let password = document.getElementById("password").value;
    authUsuario(email, password);
});