<header>
    <a id="menuToggler" href="#" class="menu-hamburguesa"><img id="menuTogglerImg" src="./media/icons/menuHamburguesa.png" width="35px" height="35px" alt="icono menú hamburguesa (desplegable)"></a>
    <span class="titulo"><h1 id="titulo"></h1></span>
    <a class="menu-links" href="libro.php">Cercar llibres</a>
    <a class="menu-links" href="#">Pjt Carlos</a>
    <button id="logoutBoton" class="logout">Logout</button>
</header>

<script>
    document.getElementById("logoutBoton").addEventListener("click", () => {
        console.log("[LOGOUT] Cerrando sesión...");
        let formData = new FormData();
        formData.append('pttn', 'logout');
        fetch("./mantenimiento/api.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.response === "ok") {
                console.log("[LOGOUT] Sesión cerrada.");
                window.location.href = "./login.php";
            } else {
                console.log("[LOGOUT] Error al cerrar sesión.");
            }
        })
        .catch(error => {
            console.log("[ERROR (API_Request)] ", error);
        });
    });
</script>

<script>
    let menuImg = document.getElementById("menuTogglerImg");
    let menuToggler = document.getElementById("menuToggler");
    let menuActivo = false;
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

