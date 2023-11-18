const formInstalacionNormal = document.getElementById("formInstalacionNormal");
const formInstalacionLoading = document.getElementById("formInstalacionLoading");

document.addEventListener("DOMContentLoaded", function() {
    formInstalacionNormal.style.display = "none";
    formInstalacionLoading.style.display = "block";
    const sleep = (ms) => new Promise(resolve => setTimeout(resolve, ms));

    const esperar = async () => {
        await sleep(3000);
        console.log('Fin');
    }
});

const enviarFormulario = () => {
    // Obtén los datos del formulario
    const formData = jQuery("#formInstalacionNormal").serialize();

    // Realiza una petición Ajax con shorthand method de jQuery
    jQuery.post("db-conn.php", formData)
        .done(function(response) {
            const result = JSON.parse(response);
            if (result.status === "success") {
                success();
            } else {
                error();
            }
        })
        .fail(function(error) {
            console.error("Error en la petición Ajax", error);
        });
}


const success = () => {
    let botonSubmit = document.getElementById("submit");
    botonSubmit.style.backgroundColor = "green";
    botonSubmit.value = "Correcto";
    formInstalacionNormal.style.display = "none";
}

const error = () => {
    let botonSubmit = document.getElementById("submit");
    botonSubmit.style.backgroundColor = "red";
    botonSubmit.value = "Error";
}