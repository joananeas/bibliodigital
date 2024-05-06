console.log("[LOAD] main.js");
let urlForFetch = window.location.href;

if (urlForFetch.includes("install") || urlForFetch.includes("admin")) {
    urlForFetch = "../mantenimiento/api.php";
}
else {
    urlForFetch = "./mantenimiento/api.php";
}

const getID = () => {
    fetch(urlForFetch, {
        method: "POST",
        body: new FormData().append('pttn', 'getID')
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
        return data;
    })
    .catch(error => {
        console.error('Error al hacer la petición:', error);
    });
}

function getNotificaciones() {
    let formData = new FormData();
    let number = document.getElementById("notification-number");
    let submenu = document.getElementById("notification-submenu");
    if (submenu === null || number === null) return;

    submenu.innerHTML = '';

    formData.append('pttn', 'getNotifications');
    fetch(urlForFetch, {
        method: "POST",
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Respuesta no válida');
        }
        return response.json();
    })
    .then(data => {
        // Comprobar el estado de la respuesta
        if (data.status === "ok") {
            number.textContent = data.data.length;
            data.data.forEach(notification => {
                let link = document.createElement("a");
                link.textContent = notification.titol;
                link.href = "#"; 
                link.title = notification.missatge;
                submenu.appendChild(link);
            });
        } else {
            console.error("Error al recuperar notificaciones: ", data.message);
        }
    })
    .catch(error => {
        console.error("[ERROR (API_Request)] ", error);
    });
}


getNotificaciones();

const comprobarConexionBBDD = async () => {  
    if (window.location.href.includes("install") || window.location.href.includes("error")) return;
    console.log("[CONEXION_BBDD] Comprobando conexión con la base de datos...");
    let formData = new FormData();
    formData.append('pttn', 'checkDB');
    try {
        const response = await fetch(urlForFetch, {
            method: "POST",
            body: formData
        });
        if (!response.ok) {
            throw new Error('Respuesta no válida');
        }
        const data = await response.json();
        console.log("[CONEXION_BBDD] ", data);
        if (data.response !== "ok") {
            window.location.href = "./error.php?error=0001&msg=" + data;
        }
    } catch (error) {
        console.log("[ERROR (API_Request)] ", error);
        window.location.href = "./error.php?error=0001";
    }
}

const menuMobile = () => {
    let menu = document.getElementById("footerMobile");

    const linksNormal = [
        { id: "footer-m-home", href: "./", src: "./media/icons/home.png", id_img: "footer-img-home"},
        { id: "footer-m-community", href: "./comunitat.php", src: "./media/icons/heart.png", id_img: "footer-img-community"},
        { id: "footer-m-qr", href: "./qr.php", src: "./media/icons/qr-code-white.png", id_img: "footer-img-qr"},
        { id: "footer-m-markers", href: "./marcadors.php", src: "./media/icons/markers.png", id_img: "footer-img-markers"},
        { id: "footer-m-profile", href: "./cuenta.php", src: "./media/icons/user.png", id_img: "footer-img-user"}
    ];

    const linksAdmin = [
        { id: "footer-m-home", href: "../", src: "../media/icons/home.png", id_img: "footer-img-home"},
        { id: "footer-m-community", href: "../comunitat.php", src: "../media/icons/heart.png", id_img: "footer-img-community"},
        { id: "footer-m-qr", href: "../qr.php", src: "../media/icons/qr-code-white.png", id_img: "footer-img-qr"},
        { id: "footer-m-markers", href: "../marcadors.php", src: "../media/icons/markers.png", id_img: "footer-img-markers"},
        { id: "footer-m-profile", href: "../cuenta.php", src: "../media/icons/user.png", id_img: "footer-img-user"},
    ];

    if (window.location.href.includes("admin")) {
        console.log("fotos admin");
        linksAdmin.forEach(link => {
            let a = document.getElementById(link.id);
            let img = document.getElementById(link.id_img);
            a.href = link.href;
            img.src = link.src;
        });
    }
    else {
        console.log("fotos normal");
        linksNormal.forEach(link => {
            let a = document.getElementById(link.id);
            let img = document.getElementById(link.id_img);
            a.href = link.href;
            img.src = link.src;
        });
    }
}

const menuHeader = () => {
    let menu = document.getElementById("menu-nav");
    // TODO: Finish this
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
        if (document.getElementById("info-dinamica") === null) return data;
        const header = document.getElementById("h");
        let banner = document.getElementById("info-dinamica");
        let content = banner.querySelector(".news-content");
    
        if (data.bannerState === "1") {
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
        let linkTituloH1 = document.getElementById("title-nav");
        const versionElement = document.getElementById("version");
        const escuelaFooter = document.getElementById("escuela-footer");

        tituloFavicon.textContent = data.titolWeb;
        let favicon = document.getElementById("favicon");
        if (window.location.href.includes("admin")) {
            favicon.href = "../media/sistema/favicon/" + data.favicon;
        }
        else {
            favicon.href = "./media/sistema/favicon/" + data.favicon;
        }
        
        escuelaFooter.textContent = data.nomBiblioteca + " 📚";
        // Solo lo imprime si existe (en login no).
        tituloH1 !== null ? (tituloH1.textContent = data.h1Web) : null;
        versionElement.textContent = data.version;
        let path = data.rootPath;

        menuMobile();
    })
    .catch(error => {
        console.log("[ERROR (API_Request)] ", error);
    });
}

const getRol = () => {
    if (window.location.href.includes("login") || window.location.href.includes("install")) return;
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

        let menu = document.getElementById("menu-nav");
    
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
        if (url.includes("admin")) cargarEstilos(estilosAdmin);
        else cargarEstilos(estilosIndex);

        if (url.includes("admin")) cargarScripts(scriptsAdmin);
        else cargarScripts(scriptsIndex);
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
        break
    case url.includes("libro"):
        cargarEstilos(estilosLibro);
        cargarScripts(scriptsLibro);
        console.log("libro");
        break;

    case url.includes("admin"):
    case url.includes("admin/index"): 
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
document.addEventListener("DOMContentLoaded", () => { menuMobile(); });
setInterval(loadGlobals, 30000); // Cada 30 segundos se comprueban los globales
setInterval(getBanner, 10000); // Cada 10 segundos se comprueba el banner
setInterval(getNotificaciones, 10000); // Cada 10 segundos se comprueban las notis