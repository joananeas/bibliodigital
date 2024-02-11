<header>
    <a id="menuToggler" href="#" class="menu-hamburguesa"><img id="menuTogglerImg" src="./media/icons/menuHamburguesa.png" width="35px" height="35px" alt="icono menú hamburguesa (desplegable)"></a>
    <span class="titulo"><h1 id="titulo"></h1></span>
    <a class="menu-links" href="libro.php">Cercar llibres</a>
    <a class="menu-links" href="#">Pjt Carlos</a>
    <!-- <a class="logout"><img src="./media/icons/menuHamburguesa.png" width="35px" height="35px" alt="icono del usuario, accedo a la cuenta."></a> -->
</header>

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

