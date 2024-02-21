
let f = document.getElementById("formInstalacionNormal");
let s = document.getElementById("statusInstalacion");

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
        if(data.status == "ok") {
            success();
        } else {
            error();
        }
    });
}

document.getElementById("submit").addEventListener("click", function(e) {
    e.preventDefault();
    comprobarConn();
});
