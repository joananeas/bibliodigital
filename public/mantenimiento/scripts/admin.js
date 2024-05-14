getColores().then(data => {
    document.getElementById("colorPrincipal").value = data.colorPrincipal;
    document.getElementById("colorSecundario").value = data.colorSecundario;
    document.getElementById("colorTerciario").value = data.colorTerciario;
}).catch(error => {
    console.error('Error al obtener colores:', error);
});

document.getElementById('formColores').addEventListener('submit', function(e) {
    e.preventDefault();

    let formData = new FormData();
    formData.append('colorPrincipal', document.getElementById("colorPrincipal").value);
    formData.append('colorSecundario', document.getElementById("colorSecundario").value);
    formData.append('colorTerciario', document.getElementById("colorTerciario").value);
    formData.append('pttn', 'setColores'); 

    fetch('../mantenimiento/api.php', {
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
        getColores();
        console.log(data);
    })
    .catch(error => {
        console.error('Error al hacer la petición:', error);
    });
});

getBanner().then(data => {
    console.log(data);
    if (data.bannerState === "1") {
        document.getElementById("toggleSwitch").checked = true;
        console.log(true);
    } else {
        document.getElementById("toggleSwitch").checked = false;
        console.log(false);
    }
    document.getElementById("banner").value = data.bannerText;
}).catch(error => {
    console.error('Error al obtener colores:', error);
});

const panels = document.querySelectorAll(".admin-panel");
const navLinks = document.querySelectorAll(".nav-link");

const showPanel = (panelId) => {
    panels.forEach(panel => panel.style.display = "none");
    navLinks.forEach(link => {
        link.classList.remove("active");
        link.style.color = "var(--color-terciario)"; // Asegura que el color del texto sea el predeterminado.
    });
    const activeLink = document.querySelector(`.nav-link[data-target='${panelId}']`);
    activeLink.classList.add("active");
    activeLink.style.color = 'white'; // Asegura que el color del texto del enlace activo sea blanco.
    document.getElementById(panelId).style.display = "block";
}

navLinks.forEach(link => {
    link.addEventListener('click', (e) => {
        e.preventDefault();
        const panelId = link.getAttribute("data-target");
        showPanel(panelId);
    });
});

document.getElementById('formBanner').addEventListener('submit', function(e) {
    e.preventDefault();
    let c = (document.getElementById("toggleSwitch").checked == true ? "1" : "0");
    let formData = new FormData();
    console.log("checked:", c);
    formData.append('bannerState', c);
    formData.append('bannerText', document.getElementById("banner").value);
    formData.append('pttn', 'setBanner');

    fetch('../mantenimiento/api.php', {
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
        console.log(data);
        getBanner();
    })
    .catch(error => {
        console.error('Error al hacer la petición:', error);
    });
});

const getAllUsers = async () => {
    console.log('Obteniendo usuarios...');
    let formData = new FormData();
    formData.append('pttn', 'getAllUsers');

    const response = await fetch('../mantenimiento/api.php', {
        method: 'POST',
        body: formData
    });
    const data = await response.json();
    
    let table = document.getElementById('userList');

    if (Array.isArray(data)) {
        data.forEach(user => {
            let tr = document.createElement('tr');

            // Crear y asignar los elementos para 'Nombre', que será el correo
            let tdNombre = document.createElement('td');
            tdNombre.textContent = user.email;
            tdNombre.classList.add('user-row');
            tr.appendChild(tdNombre);

            // Crear y asignar los elementos para 'Correo', que será el rol
            let tdCorreo = document.createElement('td');
            tdCorreo.textContent = user.rol;
            tdCorreo.classList.add('user-row');
            tr.appendChild(tdCorreo);

            // Crear y asignar los elementos vacíos para 'Estado'
            let tdEstado = document.createElement('td');
            tdEstado.classList.add('user-row');
            tr.appendChild(tdEstado);

            // Crear y asignar los elementos vacíos para 'Acciones'
            let tdAcciones = document.createElement('td');
            tr.appendChild(tdAcciones);

            tr.classList.add('user-row-bottom');
            // Añadir la fila completa a la tabla
            table.appendChild(tr);
        });
    }
}

const formCreateUser = () => {
    let menuActivo = false;
    let form = document.getElementById('formCreateUser');
    if (menuActivo) {
        menuImg.style.transform = "rotate(0deg)";
        form.style.display = "none";
        document.querySelector("main").style.display = "block";
        document.querySelector("main").style.opacity = "1";
        menuActivo = false;
    } else {
        menuImg.style.transform = "rotate(90deg)";
        form.style.display = "flex";
        document.querySelector("main").style.opacity = "0.2";
        form.style.opacity = "1";
        menuActivo = true;
    }
    
    document.getElementById('close').addEventListener('click', () => {
        document.getElementById('formCreateUser').style.display = 'none';
        document.querySelector("main").style.display = "block";
        document.querySelector("main").style.opacity = "1";
    });
};

document.getElementById("submitFormCreateUser").addEventListener('click', () => {
    const form = document.getElementById('formUser');

    form.addEventListener('submit', async (event) => {
        event.preventDefault(); // Previene el envío normal del formulario

        // Crear el objeto FormData y agregar los campos del formulario
        let formData = new FormData(form);
        formData.append('email', document.getElementById('email').value);
        formData.append('password', document.getElementById('passwd').value);
        formData.append('rol', document.getElementById('rol').value);
        formData.append('pttn', 'createUser'); // Añadir el parámetro extra

        try {
            // Realizar la petición POST asincrónica
            const response = await fetch('../mantenimiento/api.php', { // Reemplaza 'tu_endpoint_de_api' con la URL correcta
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                throw new Error('Algo salió mal en la solicitud');
            }

            const result = await response.json(); // Asumiendo que la respuesta es JSON

            // Procesar la respuesta aquí
            console.log('Usuario creado:', result);
            alert('Usuario creado con éxito!');

        } catch (error) {
            console.error('Error al crear usuario:', error);
            alert('Error al crear el usuario');
        }
    });
});

const llenarDetallesLibro = async (libroId) => {
    const formData = new FormData();
    formData.append('llibre', libroId);
    formData.append('pttn', 'cercaLlibresAll'); // Asegúrate de que el backend pueda manejar esta petición

    const response = await fetch('../mantenimiento/api.php', {
        method: 'POST',
        body: formData
    });

    if (!response.ok) {
        alert("Error al obtener detalles del libro");
        return;
    }

    let l = document.getElementById('vistaLibro');
    let b = document.getElementById('buscadorLlibres');

    const data = await response.json();
    if (data.response === "OK") {
        const detalles = data.detallesLibro;
        l.style.display = 'block';
        b.style.display = 'none';
        document.getElementById('identificador').value = detalles.NUMERO || '';
        document.getElementById('exemplars').value = detalles.num_exemplars || '';
        document.getElementById('cataleg').value = detalles.ID_CATÀLEG || '';
        document.getElementById('biblioteca').value = detalles.ID_BIBLIOTECA || '';
        document.getElementById('titol').value = detalles.TITOL || '';
        document.getElementById('isbn').value = detalles.ISBN || '';
        document.getElementById('cdu').value = detalles.CDU || '';
        document.getElementById('format').value = detalles.FORMAT || '';
        document.getElementById('autor').value = detalles.AUTOR || '';
        document.getElementById('editorial').value = detalles.EDITORIAL || '';
        document.getElementById('lloc').value = detalles.LLOC || '';
        document.getElementById('colleccio').value = detalles.COLLECCIO || '';
        document.getElementById('pais').value = detalles.PAIS || '';
        document.getElementById('data').value = detalles.DATA ? detalles.DATA.split(' ')[0] : ''; // Asumiendo que la fecha viene en formato datetime
        document.getElementById('llengua').value = detalles.LLENGUA || '';
        document.getElementById('materia').value = detalles.MATERIA || '';
        document.getElementById('descriptor').value = detalles.DESCRIPTOR || '';
        document.getElementById('nivell').value = detalles.NIVELL || '';
        document.getElementById('resum').value = detalles.RESUM || '';
        document.getElementById('url').value = detalles.URL || '';
        document.getElementById('adreca').value = detalles.ADRECA || '';
        document.getElementById('dimensio').value = detalles.DIMENSIO || '';
        document.getElementById('volum').value = detalles.VOLUM || '';
        document.getElementById('pagines').value = detalles.PAGINES || 0;
        document.getElementById('proc').value = detalles.PROC || '';
        document.getElementById('carc').value = detalles.CARC || '';
        document.getElementById('camp_lliure').value = detalles.CAMP_LLIURE || '';
        document.getElementById('npres').value = detalles.NPRES || 0;
        document.getElementById('rec').value = detalles.REC || '';
        document.getElementById('estat').value = detalles.ESTAT || '';
    } else {
        alert("No se encontraron detalles para el libro seleccionado");
    }
};


const formCreateBook = () => {
    let s = document.getElementById('crearLlibreSubmit');
    let m = document.getElementById('modificarLlibreSubmit');
    s.style.display = 'block';
    m.style.display = 'none';

    let id = document.getElementById('identificador');

    let formData = new FormData();
    formData.append('pttn', 'getLastLlibre');

    fetch('../mantenimiento/api.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.response === 'OK') {
            id.value = data.last;
        } else {
            alert('Error al trobar [last] llibre: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al procesar la solicitud');
    });

    document.getElementById('vistaLibro').style.display = 'block';
    document.getElementById('buscadorLlibres').style.display = 'none';
    document.getElementById('campoBuscarLibroIndividual').value = '';

    document.querySelectorAll('#vistaLibro input, #vistaLibro textarea').forEach(input => {
        input.value = ''; // Vaciar todos los campos
    });
};

const buscarLibroIndividual = async () => {
    let formData = new FormData();
    formData.append('llibre', document.getElementById('campoBuscarLibroIndividual').value);
    formData.append('pttn', 'cercaLlibresLite');

    const response = await fetch('../mantenimiento/api.php', {
        method: 'POST',
        body: formData
    });
    const data = await response.json();
    
    tabla = document.getElementById('tablaLibros');
    if(data.response === "OK") {
        let response = data.llibres;
        let desplegable = document.getElementById("buscadorLlibres");
        desplegable.innerHTML = "";
        const inputs = document.querySelectorAll('#crearLlibre input, #crearLlibre textarea');
        const botonModificar = document.getElementById('modificarLlibreSubmit');

        if (document.getElementById('crearLlibreSubmit').style.display === 'none') {
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    botonModificar.style.display = 'block';
                });
            });
        }

        for(let i = 0; i < response.length; i++) {
            let libro = response[i];

            let estadoLibro = document.createElement("span");
            let tituloLibro = document.createElement("li");

            estadoLibro.className = "estadoLlibro";
            if (libro.estadoActual === "Disponible") estadoLibro.style.color = "green", estadoLibro.innerHTML = "Disponible";
            else estadoLibro.style.color = "red", estadoLibro.innerHTML = "No disponible";

            tituloLibro.className = "llibre";
            tituloLibro.appendChild(estadoLibro);
            tituloLibro.appendChild(document.createTextNode(libro.nom));

            let boton = document.createElement("button");
            boton.innerHTML = "Veure";
            boton.className = "botonUniversal";
            boton.style.margin = "0px";
            boton.style.marginLeft = "25px";
            tituloLibro.appendChild(boton);

            desplegable.appendChild(tituloLibro);

            boton.addEventListener("click", async function() {
                const libroId = libro.id; 
                await llenarDetallesLibro(libroId);
            });

            if (i === response.length - 1) {
                tituloLibro.style.borderBottom = "none";
            }
        }
    
    }
}


document.getElementById('modificarLlibreSubmit').addEventListener('click', function(event) {
    event.preventDefault(); 

    const formData = new FormData(document.getElementById('crearLlibre'));
    formData.append('id', document.getElementById('identificador').value);
    formData.append('pttn', 'modificarLlibre');

    fetch('../mantenimiento/api.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.response === 'OK') {
            alert('Libro modificado correctamente!');
        } else {
            alert('Error al modificar el libro: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al procesar la solicitud');
    });
});

// const crearExemplars = async () => {
//     let formData = new FormData();
//     formData.append('id', document.getElementById('identificador').value);
//     formData.append('exemplars', document.getElementById('exemplars').value);
//     formData.append('pttn', 'crearExemplars');

//     const response = await fetch('../mantenimiento/api.php', {
//         method: 'POST',
//         body: formData
//     });
//     const data = await response.json();

//     if (data.response === 'OK') {
//         alert('Exemplares creados correctamente!');
//     } else {
//         alert('Error al crear los exemplares: ' + data.message);
//     }
// }

document.getElementById('crearLlibreSubmit').addEventListener('click', function(event) {
    event.preventDefault(); 

    const formData = new FormData(document.getElementById('crearLlibre'));
    formData.append('pttn', 'crearLlibre');

    fetch('../mantenimiento/api.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.response === 'OK') {
            alert('Libro creado correctamente!');
        } else {
            alert('Error al crear el libro: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al procesar la solicitud');
    });
});

document.getElementById("campoBuscarLibroIndividual").addEventListener('input', function() {
    if (this.value.trim() === "" || this.value.trim() === null || this.value.trim() === undefined) {
        document.getElementById("buscadorLlibres").style.display = "none";
        return;
    }

    document.getElementById('buscadorLlibres').style.display = "block";
    document.getElementById('vistaLibro').style.display = "none";
    buscarLibroIndividual();
});

showPanel('admin-config-panel'); // Mostrar el panel de configuración por defecto al cargar la página.
getColores(); // Suponiendo que estas funciones necesitan ser llamadas al cargar.
getBanner();
getAllUsers();