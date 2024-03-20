let f = document.getElementById("formInstalacionNormal");
let s = document.getElementById("statusInstalacion");
/*


const miniAnimacion = (id) => {
    let e = document.getElementById(id);
    
    setTimeout(() => {
        e.innerHTML = ".";
        setTimeout(() => {
            e.innerHTML = "..";
            setTimeout(() => {
                e.innerHTML = "...";
                // Agregar un último setTimeout con un tiempo de espera adecuado
                setTimeout(() => {
                    e.innerHTML = ".";
                }, 500);
            }, 1000);
        }, 1000);
    }, 1000);
}


// Instalación
const crearArchivoDB = (a) => {
    let s = document.getElementById(a);
    let formData = new FormData();
    formData.append("host", document.getElementById("server").value);
    formData.append("user", document.getElementById("usuari").value);
    formData.append("passwd", document.getElementById("passwd").value);
    formData.append("db", document.getElementById("nom").value);
    formData.append("peticion", "crearArchivoDb");
    setTimeout(() => {
        s.innerHTML = "Creando archivo de configuración...";
    }, 1000);

    fetch('instalacion.php', {
        method: 'POST',
        body: formData
    }).then(response => response.json()) // Primero, analiza la respuesta en JSON
    .then(data => { // Luego, maneja los datos
        console.log(data); // Esto debería mostrar {"status":"ok","message":"Connexi\u00f3 a la base de dades correcta"}
        if(data.status == "ok") {
            s.style.backgroundColor = "green";
            s.style.color = "white";
        } else {
            s.style.backgroundColor = "red";
            s.style.color = "white";
        }
    }).catch(function() {
        s.style.backgroundColor = "red";
        s.style.color = "white";
    });
}

const comprobarArchivoDB = (a) => {
    let s = document.getElementById(a);
    let formData = new FormData();
    formData.append("peticion", "comprobarArchivoDb");

    fetch('instalacion.php', {
        method: 'POST',
        body: formData
    }).then(response => response.json()) // Primero, analiza la respuesta en JSON
    .then(data => { // Luego, maneja los datos
        console.log(data); // Esto debería mostrar {"status":"ok","message":"Connexi\u00f3 a la base de dades correcta"}
        if(data.status == "ok") {
            s.style.backgroundColor = "green";
            s.style.color = "white";
        } else {
            s.style.backgroundColor = "red";
            s.style.color = "white";
        }
    }).catch(function() {
        s.style.backgroundColor = "red";
        s.style.color = "white";
    });
}

const crearTablas = (a) => {
    let s = document.getElementById(a);
    let formData = new FormData();
    formData.append("host", document.getElementById("server").value);
    formData.append("user", document.getElementById("usuari").value);
    formData.append("passwd", document.getElementById("passwd").value);
    formData.append("db", document.getElementById("nom").value);
    formData.append("peticion", "crearTablasDb");

    fetch('instalacion.php', {
        method: 'POST',
        body: formData
    }).then(response => response.json()) // Primero, analiza la respuesta en JSON
    .then(data => { // Luego, maneja los datos
        console.log(data); // Esto debería mostrar {"status":"ok","message":"Connexi\u00f3 a la base de dades correcta"}
        if(data.status == "ok") {
            s.style.backgroundColor = "green";
            s.style.color = "white";
        } else {
            s.style.backgroundColor = "red";
            s.style.color = "white";
        }
    }).catch(function() {
        s.style.backgroundColor = "red";
        s.style.color = "white";
    });
}

const success = () => {
    f.style.border = "2px solid green";
    document.getElementById("formInstalacionNormal").style.display = "none";
    document.getElementById("formInstalacionLoading").style.display = "block";
    miniAnimacion("cargando-1");
    comprobarArchivoDB("check-1");
    miniAnimacion("cargando-2");
    crearArchivoDB("check-2");
    miniAnimacion("cargando-3");
    crearTablas("check-3");
}

const error = () => {
    f.style.border = "2px solid red";
}

console.log("hola");

*/
const instalacion = () => {
    fetch('instalacion.php?peticion=instalacion-db', function(){
        method: 'GET'
    }).then(response => response.json())
    .then(data => {
        let t = document.getElementById("formInstalacionLoading-text");
        if (data.status !== "ok") return; 
        switch(data.message){
            case "archivo-no-creado":
                t.textContent = "Error al crear l'arxiu de configuració.";
                error();
                break;
            case "archivo-creado":
                t.textContent = "Arxiu de connexió a la BBDD creat correctament.";
                break;
        }
    }).catch(function(){
        console.log("Error");
    });
}

const comprobarConn = () => {
    let formData = new FormData();
    formData.append("host", document.getElementById("server").value);
    formData.append("user", document.getElementById("usuari").value);
    formData.append("passwd", document.getElementById("passwd").value);
    formData.append("db", document.getElementById("nom").value);
    formData.append("peticion", "comprobarConn");

    fetch('instalacion.php', {
        method: 'POST',
        body: formData
    }).then(response => response.json()) // Primero, analiza la respuesta en JSON
    .then(data => { // Luego, maneja los datos
        console.log(data); // Esto debería mostrar {"status":"ok","message":"Connexi\u00f3 a la base de dades correcta"}
        if(data.status === "ok" && data.message === "conex-ok") {
            document.getElementById("formInstalacionNormal").style.display = "none";
            document.getElementById("formInstalacionLoading").style.display = "block";
        } else {
            f.style.border = "2px solid red";
            setTimeout(() => {
                f.style.border = "none";
            }, 2500);
        }
    }).catch(function() {
        f.style.border = "2px solid red";
        setTimeout(() => {
            f.style.border = "none";
        }, 2500);
    });
    document.getElementById("formInstalacionLoading-start").addEventListener("click", function(){
        document.getElementById("formInstalacionLoading-start").style.display = "none";
        instalacion();
    });
}

const peticionAntesDeEmpezar = () => {
    fetch('instalacion.php?peticion=comprobarArchivos', function(){
        method: 'GET'
    }).then(response => response.json())
    .then(data => {
        let l = document.getElementById("formAntesDeEmpezar-label");
        if (data.status !== "ok") return; 
        switch(data.message){
            case "no-existen":
                document.getElementById("formAntesDeEmpezar").style.display = "none";
                document.getElementById("formInstalacionNormal").style.display = "block";
                break;
            case "existen-2":
                document.getElementById("formAntesDeEmpezar").style.display = "block";
                document.getElementById("formInstalacionNormal").style.display = "none";
                l.textContent = "Ja existeixen els arxius de configuració. Segur que vols continuar?";
                break;
            case "existe-mant":
                document.getElementById("formAntesDeEmpezar").style.display = "block";
                document.getElementById("formInstalacionNormal").style.display = "none";
                l.textContent = "Ja existeix l'arxiu de configuració (manteniment). Segur que vols continuar?";
                break;
            case "existe-db":
                document.getElementById("formAntesDeEmpezar").style.display = "block";
                document.getElementById("formInstalacionNormal").style.display = "none";
                l.textContent = "Ja existeix l'arxiu de configuració (connexió-bbdd). Segur que vols continuar?";
                break;
        }

    }).catch(function(){
        console.log("Error");
    });

    document.getElementById("formAntesDeEmpezar-tornar").addEventListener("click", function(){
        window.location.href = "../index.php"
    });
    document.getElementById("formAntesDeEmpezar-continuar").addEventListener("click", function(){
        document.getElementById("formAntesDeEmpezar").style.display = "none";
        document.getElementById("formInstalacionNormal").style.display = "block";
    });

    document.getElementById("formInstalacionNormal-submit").addEventListener("click", function(e) {
        e.preventDefault();
        comprobarConn();
    });
}

document.addEventListener("DOMContentLoaded", peticionAntesDeEmpezar());
