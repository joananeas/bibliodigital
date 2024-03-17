console.log("[LOAD] main.js");

const comprobarConexionBBDD = () => {  
    if (window.location.href.includes("install") || window.location.href.includes("error")) return;
    console.log("[CONEXION_BBDD] Comprobando conexión con la base de datos...");
    let formData = new FormData();
    formData.append('pttn', 'checkDB');
    fetch("./mantenimiento/api.php", {
        method: "POST",
        body: formData
    }).then(response => {
        if (!response.ok) {
            throw new Error('Respuesta no válida');
        }
        return response.json();
    }).then(data => {
        console.log("[CONEXION_BBDD] ", data);
        if (data.response !== "ok") {
            window.location.href = "./error.php?error=0001&msg=" + data;
        }
    }).catch(error => {
        console.log("[ERROR (API_Request)] ", error);
        window.location.href = "./error.php?error=0001&msg=" + error;
    });
}

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
        const escuelaFooter = document.getElementById("escuela-footer");

        tituloFavicon.textContent = data.titolWeb;
        let favicon = document.getElementById("favicon");
        favicon.href = data.favicon;
        escuelaFooter.textContent = data.nomBiblioteca + " 📚";
        // Solo lo imprime si existe (en login no).
        tituloH1 !== null ? (tituloH1.textContent = data.h1Web) : null;
        versionElement.textContent = data.version;
    })
    .catch(error => {
        console.log("[ERROR (API_Request)] ", error);
    });
}

const getRol = () => {
    let formData = new FormData();
    formData.append('pttn', 'getRol');
    fetch("./mantenimiento/api.php", {
        method: "POST",
        body: formData
    }).then(response => response.json())
    .then(data => {
        const r = document.getElementById("info-usuari");
        // Agrega primero el texto
        let rol;
        switch(data.rol){
            case "lector":
                rol = "🕵️ (unknown)"; // anonimo
                break;
            case "user":
                rol = "👨‍🎓 (estudiant)"; // Alumno
                break;
            case "bibliotecari":
                rol = "👨‍🏫 (bibliotecari)"; // Profe / bibliotecario
                break;
            case "admin":
                rol = "👨‍💻 (administrador)"; // Admin
                break;
            default:
                rol = "🕵️ (unknown)"; // anonimo
                break;
        }

        r.textContent = data.username + " - " + rol + " ";

        // Luego crea y agrega el enlace
        // const l = document.createElement("a");
        // l.textContent = "⚙️";
        // l.href = "cuenta.php";  
        // l.className = "info-usuari-conf";
        // r.appendChild(l);
        
    }).catch(error => {
        console.log("[ERROR (API_Request)] ", error);
    });
}

// Realizo un fetch de mantenimiento para recibir todos los datos

const estilosIndex = ["componentes.css", "paginas/index.css"];
const estilosCuenta = ["componentes.css", "paginas/cuenta.css"];
const estilosLogin = ["componentes.css", "paginas/login.css"];
const estilosReservas = ["componentes.css", "paginas/reservas.css"];
const estilosLibro = ["componentes.css", "paginas/libro.css"];
const estilosError = ["componentes.css", "paginas/error.css"];
const estilosInstall = ["./../estilos/componentes.css", "./../estilos/paginas/instalacion.css"];

const scriptsIndex = ["home.js"];
const scriptsLibro = ["libro.js"];
const scriptsLogin = ["login.js"];
const scriptsReservas = ["reservas.js"];
const scriptsInstall = ["install.js"];
const scriptsError = ["error.js"];

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
    case url.includes("reservas"):
        cargarEstilos(estilosReservas);
        cargarScripts(scriptsReservas);
        console.log("reservas");
        break;
    
    case url.includes("error"):
        cargarEstilos(estilosError);
        cargarScripts(scriptsError);
        console.log("error");
        break;    

    case url.includes("install"):
        cargarEstilos(estilosInstall); 
        cargarScripts(scriptsInstall);
        console.log("install");
        break;
    case url.includes("libro"):
        cargarEstilos(estilosLibro);
        cargarScripts(scriptsLibro);
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

document.addEventListener("DOMContentLoaded", () => { comprobarConexionBBDD(); });
document.addEventListener("DOMContentLoaded", () => { loadGlobals(); });
document.addEventListener("DOMContentLoaded", () => { getRol(); });