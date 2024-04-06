console.log("[LOAD] main.js");
let urlForFetch = window.location.href;

if (urlForFetch.includes("install") || urlForFetch.includes("admin")) {
    urlForFetch = "../mantenimiento/api.php";
}
else {
    urlForFetch = "./mantenimiento/api.php";
}

const comprobarConexionBBDD = () => {  
    if (window.location.href.includes("install") || window.location.href.includes("error")) return;
    console.log("[CONEXION_BBDD] Comprobando conexión con la base de datos...");
    let formData = new FormData();
    formData.append('pttn', 'checkDB');
    fetch(urlForFetch, {
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

const getColores = () => {
    let formData = new FormData();
    formData.append('pttn', 'getColores'); 

    return fetch(urlForFetch, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Respuesta de la red no fue ok');
        }
        return response.json();
    })
    .then(data => {
        document.documentElement.style.setProperty('--color-principal', data.colorPrincipal);
        document.documentElement.style.setProperty('--color-secundario', data.colorSecundario);
        document.documentElement.style.setProperty('--color-terciario', data.colorTerciario);
        return data; // Devuelve los datos para su uso posterior
    });
}

const getBanner = () => {
    let formData = new FormData();
    formData.append('pttn', 'getBanner');
    return fetch(urlForFetch, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Respuesta de la red no fue ok');
        }
        return response.json();
    })
    .then(data => {
        const header = document.getElementById("h");
        let banner = document.getElementById("info-dinamica");
        let content = banner.querySelector(".news-content");
    
        if (data.bannerState === true) {
            banner.innerHTML = `<div class="news-content"><span>${data.bannerText}</span></div>`;
            banner.style.display = "block";
            header.style.paddingTop = "30px";
            
            // Reiniciar la animación
            content.classList.remove("news-content");
            void banner.offsetWidth; // Truco para ayudar al navegador a reconocer el cambio
            content.classList.add("news-content");
        }
        else {
            banner.style.display = "none";
            header.style.marginTop = "0px";
        }
        return data; // Devuelve los datos para su uso posterior
    });
}

const loadGlobals = () => {
    let formData = new FormData();
    formData.append('pttn', 'getGlobals');

    fetch(urlForFetch, {
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
        
        // En caso de que sea admin, se añade un punto al inicio para que funcione correctamente (../)
        if (window.location.href.includes("admin")) favicon.href = "." + data.favicon;
        else favicon.href = data.favicon;
        escuelaFooter.textContent = data.nomBiblioteca + " 📚";
        // Solo lo imprime si existe (en login no).
        tituloH1 !== null ? (tituloH1.textContent = data.h1Web) : null;
        versionElement.textContent = data.version;
        let path = data.rootPath;
    })
    .catch(error => {
        console.log("[ERROR (API_Request)] ", error);
    });
}

const getRol = () => {
    let formData = new FormData();
    formData.append('pttn', 'getRol');
    fetch(urlForFetch, {
        method: "POST",
        body: formData
    }).then(response => response.json())
    .then(data => {
        // Agrega primero el texto
        let r = document.getElementById("info-usuari");
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
        return data.username;
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
const estilosInstall = ["componentes.css", "instalacion.css"];
const estilosAdmin = ["componentes.css", "paginas/admin.css"];

const scriptsIndex = ["home.js"];
const scriptsLibro = ["libro.js"];
const scriptsLogin = ["login.js"];
const scriptsReservas = ["reservas.js"];
const scriptsInstall = ["install.js"];
const scriptsError = ["error.js"];
const scriptsAdmin = ["admin.js"];

const url = window.location.href;

const cargarScripts = (scripts) => {
    for (let i = 0; i < scripts.length; i++) {
        let script = document.createElement("script");
        if (url.includes("admin")) script.src = "../mantenimiento/scripts/" + scripts[i];
        else script.src = "./mantenimiento/scripts/" + scripts[i];
        document.body.appendChild(script);
    }
}

const cargarEstilos = (estilos) => {
    for (let i = 0; i < estilos.length; i++) {
        let linkEstilos = document.createElement("link");
        linkEstilos.rel = "stylesheet";
        linkEstilos.type = "text/css";
        if (url.includes("admin")) linkEstilos.href = "../estilos/" + estilos[i];
        else linkEstilos.href = "./estilos/" + estilos[i];
        document.head.appendChild(linkEstilos);
    }
}



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
    case url.includes("admin"):
        cargarEstilos(estilosAdmin);
        cargarScripts(scriptsAdmin);
        console.log("admin");
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
document.addEventListener("DOMContentLoaded", () => { getColores(); });
document.addEventListener("DOMContentLoaded", () => { getBanner(); });
document.addEventListener("DOMContentLoaded", () => { loadGlobals(); });
document.addEventListener("DOMContentLoaded", () => { getRol(); });