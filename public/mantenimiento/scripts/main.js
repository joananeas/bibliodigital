console.log("[LOAD] main.js");
let urlForFetch = window.location.href;

if (urlForFetch.includes("install") || urlForFetch.includes("admin")) {
    urlForFetch = "../mantenimiento/api.php";
}
else {
    urlForFetch = "./mantenimiento/api.php";
}

const getID = async () => {
    let formData = new FormData();
    formData.append('pttn', 'getID');

    try {
        const response = await fetch(urlForFetch, {
            method: "POST",
            body: formData
        });
        const data = await response.json();
        console.log(data);
        return data;
    } catch (error) {
        console.error('Error al hacer la petición:', error);
    }
}


function getNotificaciones() {
    let formData = new FormData();
    let number = document.getElementById("notification-number");
    let numberPFP = document.getElementById("notification-number-pfp");
    //let submenu = document.getElementById("notification-submenu");
    if (number === null) return;

    //submenu.innerHTML = '';

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
                numberPFP.textContent = data.data.length;
                number.textContent = data.data.length;
                notificationsFrame = document.getElementById("notificationsFrame"); // En el popup de notis
                notificationsFrame.innerHTML = '';
                if (data.data.length === 0) {
                    notificationsFrame.innerHTML = 'Sense notificacions.';
                    return;
                }

                data.data.forEach(notification => {
                    //let link = document.createElement("a");
                    let p = document.createElement("p");

                    // link.textContent = notification.titol;
                    // link.href = "#"; 
                    // link.title = notification.missatge;

                    p.textContent = notification.id_notificacio + " - " + notification.missatge;

                    //submenu.appendChild(link);
                    notificationsFrame.appendChild(p);
                });
            } else {
                console.error("Error al recuperar notificaciones: ", data.message);
            }
        })
        .catch(error => {
            console.error("[ERROR (API_Request)] ", error);
        });
}

const clearNotifications = () => {
    // Clear content
    document.getElementById("notificationsFrame").innerHTML = 'Sense notificacions.';

    let formData = new FormData();
    formData.append('pttn', 'clearNotifications');
    fetch(urlForFetch, {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            console.log(data);
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
    const linksNormal = [
        { id: "footer-m-home", href: "./", src: "./media/icons/home.png", id_img: "footer-img-home" },
        { id: "footer-m-community", href: "./comunitat.php", src: "./media/icons/heart.png", id_img: "footer-img-community" },
        { id: "footer-m-qr", href: "./qr.php", src: "./media/icons/qr-code-white.png", id_img: "footer-img-qr" },
        { id: "footer-m-markers", href: "./reservas.php", src: "./media/icons/markers.png", id_img: "footer-img-markers" },
        { id: "footer-m-profile", href: "./cuenta.php", src: "./media/icons/user.png", id_img: "footer-img-user" }
    ];

    const linksAdmin = [
        { id: "footer-m-home", href: "../", src: "../media/icons/home.png", id_img: "footer-img-home" },
        { id: "footer-m-community", href: "../comunitat.php", src: "../media/icons/heart.png", id_img: "footer-img-community" },
        { id: "footer-m-qr", href: "../qr.php", src: "../media/icons/qr-code-white.png", id_img: "footer-img-qr" },
        { id: "footer-m-markers", href: "../reservas.php", src: "../media/icons/markers.png", id_img: "footer-img-markers" },
        { id: "footer-m-profile", href: "../cuenta.php", src: "../media/icons/user.png", id_img: "footer-img-user" },
    ];

    if (window.location.href.includes("admin")) {
        linksAdmin.forEach(link => {
            let a = document.getElementById(link.id);
            let img = document.getElementById(link.id_img);
            a.href = link.href;
            img.src = link.src;
        });
    }
    else {
        linksNormal.forEach(link => {
            let a = document.getElementById(link.id);
            let img = document.getElementById(link.id_img);
            a.href = link.href;
            img.src = link.src;
        });
    }
}

const menuHeader = () => {
    const linksNormal = [
        { id: "title-nav", href: "./index.php" },
        { id: "iniciNav", href: "./index.php" },
        { id: "llibresNav", href: "./llibres.php" },
        { id: "prestecsNav", href: "./prestecs.php" },
        { id: "gestioReservesNav", href: "./reservas.php" },
        { id: "enquestesNav", href: "./perfil.php" },
        { id: "gestioCompteNav", href: "./perfil.php" },
        { id: "adminNav", href: "./admin" },
        { id: "suggNav", href: "./perfil.php" }
    ];

    const linksAdmin = [
        { id: "title-nav", href: "../index.php" },
        { id: "iniciNav", href: "../index.php" },
        { id: "llibresNav", href: "../llibres.php" },
        { id: "prestecsNav", href: "../prestecs.php" },
        { id: "gestioReservesNav", href: "../reservas.php" },
        { id: "enquestesNav", href: "../perfil.php" },
        { id: "gestioCompteNav", href: "../perfil.php" },
        { id: "adminNav", href: "#" },
        { id: "suggNav", href: "../perfil.php" }
    ];

    if (window.location.href.includes("admin")) {
        console.log("admin");
        linksAdmin.forEach(link => {
            let a = document.getElementById(link.id);
            if (a !== null) a.href = link.href;
        });

    }
    else {
        console.log("normal");
        linksNormal.forEach(link => {
            let a = document.getElementById(link.id);
            if (a !== null) a.href = link.href;
        });
    }
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
            let n = document.getElementById("nom-usuari");
            n.textContent = data.username;
            r.textContent = data.rol;

            return data.username;
        }).catch(error => {
            console.log("[ERROR (API_Request)] ", error);
        });
}

const getUserDataCreation = () => {
    let formData = new FormData();
    formData.append('pttn', 'getUserCreationDate');
    fetch(urlForFetch, {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.response === "ok") {
                console.log("[DATE] " + data);
                document.getElementById("data-usuari").textContent = data.api;
                return data.api;
            }
        })
        .catch(error => {
            console.error('Error al hacer la petición:', error);
        });
}

getUserDataCreation();
// Realizo un fetch de mantenimiento para recibir todos los datos

const scriptsIndex = ["home.js"];
const scriptsLibro = ["libro.js"];
const scriptsLogin = ["login.js"];
const scriptsReservas = ["reservas.js"];
const scriptsInstall = ["install.js"];
const scriptsError = ["error.js"];
const scriptsAdmin = ["admin.js"];
const scriptsXats = ["xats.js"];

const cargarScripts = (scripts) => {
    for (let i = 0; i < scripts.length; i++) {
        let script = document.createElement("script");
        if (url.includes("admin")) script.src = "../mantenimiento/scripts/" + scripts[i];
        else script.src = "./mantenimiento/scripts/" + scripts[i];
        document.body.appendChild(script);
    }
}


switch (true) {
    case url.includes("index"):
        if (url.includes("admin")) cargarScripts(scriptsAdmin);
        else cargarScripts(scriptsIndex);
        console.log("indice");
        break;
    case url.includes("cuenta"):
        cargarScripts(scriptsCuenta);
        console.log("cuenta");
        break;
    case url.includes("login"):
        cargarScripts(scriptsLogin);
        console.log("login");
        break;
    case url.includes("reservas"):
        cargarScripts(scriptsReservas);
        console.log("reservas");
        break;

    case url.includes("error"):
        cargarScripts(scriptsError);
        console.log("error");
        break;

    case url.includes("install"):
        cargarScripts(scriptsInstall);
        console.log("install");
        break
    case url.includes("libro"):
        cargarScripts(scriptsLibro);
        console.log("libro");
        break;

    case url.includes("admin"):
    case url.includes("admin/index"):
        cargarScripts(scriptsAdmin);
        console.log("admin");
        break;

    case url.includes("xats"):
        cargarScripts(scriptsXats);
        console.log("xats");
        break;

    default:
        cargarScripts(scriptsIndex);
        break;
    // Agregar más casos según sea necesario
}

// Aplica estilos de carga
document.documentElement.style.display = 'block';
document.documentElement.className += ' loading';

document.addEventListener('DOMContentLoaded', function () {
    document.documentElement.className = document.documentElement.className.replace('loading', '');
});

document.addEventListener("DOMContentLoaded", () => { comprobarConexionBBDD(); });
document.addEventListener("DOMContentLoaded", () => { getColores(); });
document.addEventListener("DOMContentLoaded", () => { getBanner(); });
document.addEventListener("DOMContentLoaded", () => { loadGlobals(); });
document.addEventListener("DOMContentLoaded", () => { getRol(); });
document.addEventListener("DOMContentLoaded", () => { menuMobile(); });
document.addEventListener("DOMContentLoaded", () => { menuHeader(); });

setInterval(loadGlobals, 30000); // Cada 30 segundos se comprueban los globales
setInterval(getBanner, 10000); // Cada 10 segundos se comprueba el banner
setInterval(getNotificaciones, 10000); // Cada 10 segundos se comprueban las notis

if (!url.includes("login") && !url.includes("error")) {
    document.getElementById('notificacionesNavBtn').addEventListener("click", () => { viewPopUp('notificationCenter', 'closeNotis') });
    document.getElementById('esborrarNotificacions').addEventListener("click", () => { viewPopUp('notificationCenter', 'closeNotis') });
    document.getElementById('esborrarNotificacions').addEventListener("click", () => { clearNotifications() });
}