console.log("[LOAD] main.js");

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
const estilosLibro = ["componentes.css", "paginas/libro.css"];
const estilosInstall = ["./../estilos/componentes.css", "./../estilos/paginas/instalacion.css"];

const scriptsIndex = ["home.js"];
//const scriptsCuenta = ["cuenta.js"];
const scriptsLogin = ["login.js"];
//const scriptsError = ["error.js"];
const scriptsInstall = ["install.js"];

const cargarScripts = (scripts) => {
    for (let i = 0; i < scripts.length; i++) {
        let script = document.createElement("script");
        script.src = "mantenimiento/scripts/" + scripts[i];
        document.body.appendChild(script);
    }
}

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
        cargarScripts(scriptsIndex);
        console.log("indice");
        break;
    case url.includes("cuenta"):
        cargarEstilos(estilosCuenta);
        cargarScripts(scriptsCuenta);
        console.log("cuenta");
        break;
    case url.includes("login"):
        cargarEstilos(estilosLogin); 
        cargarScripts(scriptsLogin);
        console.log("login");
        break;
    case url.includes("install"):
        cargarEstilos(estilosInstall); 
        cargarScripts(scriptsInstall);
        console.log("install");
        break;
    case url.includes("libro"):
        cargarEstilos(estilosIndex);
        cargarScripts(scriptsIndex);
        console.log("libro");
        break;
    default:
        cargarEstilos(estilosIndex); // normalmente sale / en vez de /index.php
        cargarScripts(scriptsIndex);
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