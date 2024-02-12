
// document.getElementById("inputCercaLlibres").addEventListener("focusout", function() {
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
                    desplegable.appendChild(tituloLibro);

                    tituloLibro.addEventListener("click", function() {
                        console.log("Llibre: ", libro.nom);
                        window.location.href = `./libro.php?libro=${libro.nom}`;
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