
// document.getElementById("buscador").addEventListener("focusout", function() {
//     document.getElementById("buscadorLlibres").style.display = "none";
// });
document.getElementById("inputCercaLlibres").addEventListener("focus", function() {
    document.getElementById("buscadorLlibres").style.display = "block";
});


document.getElementById("inputCercaLlibres").addEventListener("input", function() {
    let formData = new FormData();
    formData.append('pttn', 'cercaLlibresLite');
    formData.append('llibre', this.value);
    if (this.value !== "") {
        fetch("./mantenimiento/api.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            // console.log("[RESPONSE: Cerca] ", data);
            if(data.response === "OK") {
                let response = data.llibres;
                let desplegable = document.getElementById("buscadorLlibres");
                desplegable.innerHTML = "";
                for(let i = 0; i < response.length; i++) {
                    let libro = response[i];

                    let estadoLibro = document.createElement("span");
                    let tituloLibro = document.createElement("li");

                    estadoLibro.className = "estadoLlibro";
                    if (libro.estadoActual == 1) estadoLibro.style.color = "green", estadoLibro.innerHTML = "Disponible";
                    else estadoLibro.style.color = "red", estadoLibro.innerHTML = "No disponible";

                    tituloLibro.className = "llibre";
                    tituloLibro.appendChild(estadoLibro);
                    tituloLibro.appendChild(document.createTextNode(libro.nom));

                    // Agregar botón al final del elemento tituloLibro
                    let a = document.createElement("a");
                    a.href = `./libro.php?libro=${libro.nom}`;
                    a.innerHTML = "Clic aquí per a més informació";
                    a.style.marginLeft = "25px";
                    a.style.color = "grey";
                    tituloLibro.appendChild(a);

                    let boton = document.createElement("button");
                    boton.innerHTML = "Reservar";
                    boton.className = "botonUniversal";
                    boton.style.margin = "0px";
                    boton.style.float = "right";
                    tituloLibro.appendChild(boton);

                    desplegable.appendChild(tituloLibro);
                    boton.addEventListener("click", function() {
                        console.log("Reservar: ", libro.nom);
                        window.location.href = `./reservas.php?libro=${libro.nom}`;
                    });

                    if (i === response.length - 1) {
                        tituloLibro.style.borderBottom = "none";
                    }
                }
            }
        })
            .catch(error => {
                console.log("[ERROR (API_Request)] ", error);
            });
    }
});