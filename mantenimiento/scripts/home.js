
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

const cBack = document.getElementById("c-anterior");
const cNext = document.getElementById("c-siguiente"); 
const cPhoto = document.getElementById("c-foto");
let numFotos = getFotos();

cBack.addEventListener("click", function() {
    let src = cPhoto.src;
    let partes = src.split('-');
    let srcNumericoConExtension = partes.slice(1).join('-');
    let partesPuntos = srcNumericoConExtension.split('.');
    let srcNumericoSinExtension = partesPuntos.slice(0, -1).join('.');
    srcNumericoSinExtension = parseInt(srcNumericoSinExtension);
    srcNumericoSinExtension--;

    //console.log("nombre", partes,"Ext", partesPuntos[1], "Num: ", srcNumericoSinExtension);
    console.log("SRC: ", partes[0] + "-" + srcNumericoSinExtension + "." + partesPuntos[1]);
    cPhoto.src = partes[0] + "-" + srcNumericoSinExtension + "." + partesPuntos[1];
});

cNext.addEventListener("click", function() {
    const src = cPhoto.src;
    const partes = src.split('-');
    const srcNumericoConExtension = partes.slice(1).join('-');
    const partesPuntos = srcNumericoConExtension.split('.');
    let srcNumericoSinExtension = partesPuntos.slice(0, -1).join('.');
    srcNumericoSinExtension = parseInt(srcNumericoSinExtension);
    srcNumericoSinExtension++;

    //console.log("nombre", partes,"Ext", partesPuntos[1], "Num: ", srcNumericoSinExtension);
    console.log("SRC: ", partes[0] + "-" + srcNumericoSinExtension + "." + partesPuntos[1]);
    cPhoto.src = partes[0] + "-" + srcNumericoSinExtension + "." + partesPuntos[1];
});

const getFotos = () => {
    console.log("test")
    let formData = new FormData();
    formData.append('pttn', 'getFotos');
    fetch('./mantenimiento/api.php', {
        method: "POST",
        body: formData
    }).then(response => response.json())
    .then(data => {
        console.log("[RESPONSE: Cerca] ", data);
        const puntosCarroussel = document.getElementById("puntos-carroussel");
        for(let i = 1; i < data.num_libros; i++){
            let li = document.createElement("li");
            li.id = "c-dot-" + i;
            if (i === 1) li.className = "activo";
            puntosCarroussel.appendChild(li);
        }
        console.log(data) 
    })
    .catch(error => {
        console.log("[ERROR (API_Request)] ", error);
    });
    return data.num_libros;
};

document.addEventListener("DOMContentLoaded", getFotos());

