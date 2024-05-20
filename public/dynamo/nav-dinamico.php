<!-- <div id="splash-screen" style="position: fixed; width: 100%; height: 100%; background: white; z-index: 9999; margin: 0; padding: 0;">Cargando...</div> -->

<div id="info-dinamica" class="news-bar">
    <div class="news-content">
        <span>Noticia importante 📰</span>
        <!-- Dinamico!!! -->
    </div>
</div>

<header id="h">
    <a id="menuToggler" href="#" class="menu-hamburguesa"><img id="menuTogglerImg" width="35px" height="35px"
            alt="icono menú hamburguesa (desplegable)"></a>
    <a href="" id="title-nav" class="titulo">
        <h1 id="titulo"></h1>
    </a>
    <div class="logout">
        <!-- <span id="info-usuari"></span>
        <div class="dropdown">
            <button style="margin-top:0;" class="botonUniversal_alt" id="notificacionesNavBtn">🔔&nbsp;(<span
                    id="notification-number"></span>)</button>
            <div class="dropdown-content" id="notification-submenu">
            </div>
        </div>
        <button id="logoutBoton" class="botonUniversal_alt" style="margin-top:0;">Logout</button> -->
        <div class="dropdown">
            <span id="notification-number-pfp" class="badgeCounter_alt"
                style="top: 0; right: 0; position: absolute; margin-right:-8px; margin-top:-3px;"></span>
            <img id="pfpNav" class="profileNav" src="./media/icons/user.jpg" alt="icono usuario" width="40px"
                height="40px">
            <section class="dropdown-content" id="notification-submenu">
                <article class="infoProfileNav">
                    <p><strong>Rol: </strong><span id="info-usuari"></span></p>
                    <p><strong>Correu: </strong><span id="nom-usuari"></span></p>
                    <p><strong>Lector desde: </strong><span id="data-usuari"></span></p>
                </article>
                <a id="perfilNav" href="#">👤 Perfil</a>
                <a id="notificacionesNavBtn" href="#">🔔 Notificacions &nbsp;<span id="notification-number"
                        class="badgeCounter"></span></a>
                <a id="logoutBoton" href="#">🚪 Logout</a>
            </section>
        </div>
    </div>
</header>
<nav id="menu-nav" class="frame" style="margin-left:0; margin-right:0; margin-top:20px; padding:0;">
    <li><a id="iniciNav" href="">🏠 Inici</a></li>
    <li><a id="llibresNav" href="">🔍 Cerca de llibres</a></li>
    <li><a id="prestecsNav" href="">💡 Buffet lectura</a></li>
    <li><a id="gestioReservesNav" href="">📚 Gestió de reserves</a></li>
    <li><a id="enquestesNav" href="">🚩 Enquestes </a></li>
    <li><a id="gestioCompteNav" href="">⚙️ Gestió del compte</a></li>
    <?php
    if ($_COOKIE['rol'] == 'admin') {
        echo '<li><a id="adminNav" href="./admin">👑 Administració</a></li>';
    }
    ?>
    <li style="border: none;"><a id="suggNav" href="">📬 Bústia suggerim.</a>
        <!-- <li style="border: none;"><a id="logoutNav" href="">🚪 Logout</a> -->
</nav>

<!-- Centre notificacions 🔔 -->
<div class="popup" style="display:none;" id="notificationCenter">
    <div class="popup-content">
        <section class="frame">
            <a id="closeNotis" class="close">&times;</a>
            <p class="fuenteH1" style="text-align:center;">⏰ Centre de Notificacions 🛎️</p>
            <div id="notificationsFrame" class="notificationsFrame"></div>
            <button id="esborrarNotificacions" class="botonUniversal">Esborrar totes 🗑️</button>
        </section>
    </div>
</div>

<script>
const viewPopUp = (formulari, close, action = null) => {
    console.log("[POPUP]", formulari);
    let form = document.getElementById(formulari);
    if (menuActivo) {
        form.style.display = "none";
        document.querySelector("main").style.display = "block";
        document.querySelector("main").style.opacity = "1";
        menuActivo = false;
    } else {
        form.style.display = "flex";
        document.querySelector("main").style.opacity = "0.2";
        form.style.opacity = "1";
        menuActivo = true;
    }
    const closePopUp = () => {
        form.style.display = "none";
        document.querySelector("main").style.display = "block";
        document.querySelector("main").style.opacity = "1";
    };
    document.getElementById(close).addEventListener("click", () => closePopUp());
    if (action === "close") {
        console.log("[POPUP] Closed by action.");
        closePopUp();
    }
};
</script>

<script>
let urlLogout = "";
if (window.location.href.includes("admin")) urlLogout = "../mantenimiento/api.php";
else urlLogout = "./mantenimiento/api.php";

const logout = () => {
    console.log("[LOGOUT] Cerrando sesión...");
    let formData = new FormData();
    formData.append('pttn', 'logout');
    fetch(urlLogout, {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.response === "ok") {
                console.log("[LOGOUT] Sesión cerrada.");
                if (window.location.href.includes("admin")) window.location.href = "../login.php";
                else window.location.href = "./login.php";
            } else {
                console.log("[LOGOUT] Error al cerrar sesión.");
            }
        })
        .catch(error => {
            console.log("[ERROR (API_Request)] ", error);
        });
};

document.getElementById("logoutBoton").addEventListener("click", () => logout());
//document.getElementById("logoutNav").addEventListener("click", () => logout());
</script>

<script>
let menuActivo = false;
let menuImg = document.getElementById("menuTogglerImg");
let menuToggler = document.getElementById("menuToggler");
if (window.location.href.includes("admin")) {
    menuImg.src = "../media/icons/menuHamburguesa.png";
} else {
    menuImg.src = "./media/icons/menuHamburguesa.png";
}
menuToggler.addEventListener("click", function() {
    if (menuActivo) {
        menuImg.style.transform = "rotate(0deg)";
        document.querySelector("nav").style.display = "none";
        document.querySelector("main").style.display = "block";
        document.querySelector("main").style.opacity = "1";
        menuActivo = false;
    } else {
        menuImg.style.transform = "rotate(90deg)";
        document.querySelector("nav").style.display = "block";
        document.querySelector("main").style.opacity = "0.2";
        document.querySelector("nav").style.opacity = "1";
        menuActivo = true;
    }
});
</script>