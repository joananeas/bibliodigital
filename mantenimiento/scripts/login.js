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
        /* #00A550 (verde) #C51E3A (rojo) */
        let confirmacion = document.getElementById("loginConfirmacion");
        if (data.response === 'ok') {
            confirmacion.style.transition = "all 0.5s ease-in-out";
            confirmacion.style.backgroundColor = "#00A550";
            confirmacion.innerHTML = "Login Correcte";
            confirmacion.style.display = "block";
            setTimeout(() => {
                window.location.href = './index.php';
            }, 1000);
        }
        else {
            confirmacion.style.transition = "all 0.5s ease-in-out";
            confirmacion.style.display = "block";
            confirmacion.style.backgroundColor = "#C51E3A";
            confirmacion.innerHTML = "Les dades no són correctes";
            setTimeout(() => {
                confirmacion.style.display = "none";
            }, 3000);
        }
    })
    .catch(error => {
        console.error('Error al procesar la solicitud', error);
    });
}

// const genPasswd = () => {
//     let formData = new FormData();
//     formData.append('pttn', 'genPasswd');

//     fetch('./mantenimiento/api.php', {
//         method: 'POST',
//         body: formData
//     })
//     .then(response => response.json())
//     .then(data => {
//         console.log(data);
//     })
//     .catch(error => {
//         console.error('Error al procesar la solicitud', error);
//     });
// }

// genPasswd();

const togglePassword = () => {
    let password = document.getElementById("password");
    let toggle = document.getElementById("showHide");
    if (password.type === "password") {
        password.type = "text";
        toggle.src = "./media/icons/show.png";
    }
    else {
        password.type = "password";
        toggle.src = "./media/icons/hide.png";
    }
}

document.getElementById("login").addEventListener("click", function(e){
    e.preventDefault();
    let email = document.getElementById("email").value;
    let password = document.getElementById("password").value;
    authUsuario(email, password);
});