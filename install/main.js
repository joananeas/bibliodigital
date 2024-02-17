
let f = document.getElementById("formInstalacionNormal");
let s = document.getElementById("statusInstalacion");

// Instalación
const crearArchivoDB = () => {
    let formData = new FormData();
    formData.append("host", document.getElementById("server").value);
    formData.append("user", document.getElementById("usuari").value);
    formData.append("passwd", document.getElementById("passwd").value);
    formData.append("db", document.getElementById("nom").value);
    formData.append("peticion", "crearArchivoDb");

    s.innerHTML = "Creando archivo de configuración...";

    fetch('instalacion.php', {
        method: 'POST',
        body: formData
    }).then(response => response.json()) // Primero, analiza la respuesta en JSON
    .then(data => { // Luego, maneja los datos
        console.log(data); // Esto debería mostrar {"status":"ok","message":"Connexi\u00f3 a la base de dades correcta"}
        if(data.status == "ok") {
                
        } else {
            s.innerHTML = "Error al crear el archivo de configuración.";
            s.style.backgroundColor = "red";
            s.style.color = "white";
        }
    });
}


const success = () => {
    f.style.border = "2px solid green";
    document.getElementById("formInstalacionNormal").style.display = "none";
    document.getElementById("formInstalacionLoading").style.display = "block";
    crearArchivoDB();
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
