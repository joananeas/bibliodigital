console.log("[LOAD] main.js");

let inputCerca = false;
document.getElementById("inputCercaLlibres").addEventListener("change", function() {
    if (inputCerca === false) {
        document.getElementById("buscadorLlibres").style.display = "block";
        inputCerca = true;
    } else {
        document.getElementById("buscadorLlibres").style.display = "none";
        inputCerca = false;
    }
});

document.getElementById("inputCercaLlibres").addEventListener("input", function() {
    console.log("Input: ", this.value);
    let formData = new FormData();
    formData.append('pttn', 'cercaLlibres');
    formData.append('llibre', this.value);
    if (this.value !== "") {
        fetch("./mantenimiento/api.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log("[RESPONSE: Cerca] ", data);
            if(data.response === "OK") {
                let llibres = data.llibres;
                let llibresDiv = document.getElementById("buscadorLlibres");
                llibresDiv.innerHTML = "";
                for(let i = 0; i < llibres.length; i++) {
                    let llibre = llibres[i];
                    let llibreLi = document.createElement("li");
                    llibreLi.className = "llibre";
                    llibreLi.textContent = llibre.nom; // Cambia 'titol' a 'nom'
                    llibresDiv.appendChild(llibreLi);
                    if (i === llibres.length - 1) {
                        llibreLi.style.borderBottom = "none";
                    }
                }
            }
        })
        .catch(error => {
            console.log("[ERROR (API_Request)] ", error);
        });
    }
});

const loadGlobals = () => {
    let formData = new FormData();
    formData.append('pttn', 'getGlobals');

    fetch("./mantenimiento/api.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log("[RESPONSE] ", data);

        let tituloFavicon = document.getElementById("tituloTab");
        let tituloH1 = document.getElementById("titulo");
        const versionElement = document.getElementById("version");

        tituloFavicon.textContent = data.titolWeb;
        // Solo lo imprime si existe (en login no).
        tituloH1 !== null ? (tituloH1.textContent = data.h1Web) : null;
        versionElement.textContent = data.version;
    })
    .catch(error => {
        console.log("[ERROR (API_Request)] ", error);
    });
}

// Realizo un fetch de mantenimiento para recibir todos los datos

const estilosIndex = ["componentes.css", "paginas/index.css"];
const estilosCuenta = ["componentes.css", "paginas/cuenta.css"];
const estilosLogin = ["componentes.css", "paginas/login.css"];
const estilosError = ["componentes.css", "paginas/error.css"];
const estilosInstall = ["./../estilos/componentes.css", "./../estilos/paginas/instalacion.css"];

const cargarEstilos = (estilos) => {
    for (let i = 0; i < estilos.length; i++) {
        let linkEstilos = document.createElement("link");
        linkEstilos.rel = "stylesheet";
        linkEstilos.type = "text/css";
        linkEstilos.href = "estilos/" + estilos[i];
        document.head.appendChild(linkEstilos);
    }
}

const url = window.location.href;

switch (true) {
    case url.includes("index"):
        cargarEstilos(estilosIndex);
        console.log("indice");
        break;
    case url.includes("cuenta"):
        cargarEstilos(estilosCuenta);
        console.log("cuenta");
        break;
    case url.includes("login"):
        cargarEstilos(estilosLogin); 
        console.log("login");
        break;
    case url.includes("install"):
        cargarEstilos(estilosInstall); 
        console.log("login");
        break;
    default:
        cargarEstilos(estilosIndex); // normalmente sale / en vez de /index.php
        break;
    // Agregar más casos según sea necesario
}

// Aplica estilos de carga
document.documentElement.style.display = 'block';
document.documentElement.className += ' loading';

document.addEventListener('DOMContentLoaded', function() {
    document.documentElement.className = document.documentElement.className.replace('loading', '');
});

document.addEventListener("DOMContentLoaded", () => { loadGlobals(); });