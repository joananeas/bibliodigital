getColores().then(data => {
    document.getElementById("colorPrincipal").value = data.colorPrincipal;
    document.getElementById("colorSecundario").value = data.colorSecundario;
    document.getElementById("colorTerciario").value = data.colorTerciario;
}).catch(error => {
    console.error('Error al obtener colores:', error);
});

document.getElementById('formColores').addEventListener('submit', function (e) {
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

document.getElementById('formBanner').addEventListener('submit', function (e) {
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
    formData.append('pttn', 'cercaLlibresAll');
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
        // document.getElementById('cataleg').value = detalles.ID_CATALEG || '';
        // document.getElementById('biblioteca').value = detalles.ID_BIBLIOTECA || '';
        document.getElementById('titol').value = detalles.TITOL || '';
        document.getElementById('isbn').value = detalles.ISBN || '';
        document.getElementById('cdu').value = detalles.CDU || '';
        document.getElementById('format').value = detalles.FORMAT || '';
        document.getElementById('autor').value = detalles.AUTOR || '';
        document.getElementById('editorial').value = detalles.EDITORIAL || '';
        document.getElementById('lloc').value = detalles.LLOC || '';
        document.getElementById('colleccio').value = detalles.COLLECCIO || '';
        document.getElementById('pais').value = detalles.PAIS || '';

        // Es un integer (año solamente)
        document.getElementById('dataLlibre').value = detalles.DATA || ''; // Asumiendo que la fecha viene en formato datetime
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
        if (input.id === 'exemplars') {
            input.value = 1;
        }
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
    if (data.response === "OK") {
        let response = data.llibres;
        let desplegable = document.getElementById("buscadorLlibres");
        desplegable.innerHTML = "";
        const inputs = document.querySelectorAll('#crearLlibre input, #crearLlibre textarea');
        const botonModificar = document.getElementById('modificarLlibreSubmit');

        if (document.getElementById('crearLlibreSubmit').style.display === 'none') {
            inputs.forEach(input => {
                input.addEventListener('input', function () {
                    botonModificar.style.display = 'block';
                });
            });
        }

        for (let i = 0; i < response.length; i++) {
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

            boton.addEventListener("click", async function () {
                const libroId = libro.id;
                await llenarDetallesLibro(libroId);
            });

            if (i === response.length - 1) {
                tituloLibro.style.borderBottom = "none";
            }
        }

    }
}


document.getElementById('modificarLlibreSubmit').addEventListener('click', function (event) {
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

document.getElementById('crearLlibreSubmit').addEventListener('click', function (event) {
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

document.getElementById("campoBuscarLibroIndividual").addEventListener('input', function () {
    if (this.value.trim() === "" || this.value.trim() === null || this.value.trim() === undefined) {
        document.getElementById("buscadorLlibres").style.display = "none";
        return;
    }

    document.getElementById('buscadorLlibres').style.display = "block";
    document.getElementById('vistaLibro').style.display = "none";
    buscarLibroIndividual();
});

const loadStatsUsers = () => {
    fetch('../mantenimiento/api.php?pttn=getUserStats', {
        method: 'GET'
    })
        .then(response => response.json())
        .then(data => {
            stats = data.stats;
            if (data.response === 'OK') {
                document.getElementById('usuariosActivos').textContent = stats.actius;
                document.getElementById('usuariosInactivos').textContent = stats.inactius;
                document.getElementById('usuariosBaneados').textContent = stats.expulsats;
                document.getElementById('usuariosEliminados').textContent = stats.expulsat_temp;
                document.getElementById('usuariosTotales').textContent = stats.total;
            } else {
                alert('Error al obtener las estadísticas de usuarios: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al procesar la solicitud');
        });
}

const loadBookStats = () => {
    fetch('../mantenimiento/api.php?pttn=getBookStats', {
        method: 'GET'
    })
        .then(response => response.json())
        .then(data => {
            stats = data.stats;
            if (data.response === 'OK') {
                document.getElementById('librosTotal').textContent = stats.total;
                document.getElementById('librosExemplars').textContent = stats.totalExemplars;
            } else {
                alert('Error al obtener las estadísticas de libros: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al procesar la solicitud');
        });
}


const loadPrestecs = () => {
    const formData = new FormData();
    formData.append('pttn', 'viewAllPrestecs');

    fetch('../mantenimiento/api.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('prestecsList');
            tbody.innerHTML = '';

            if (data.response === 'OK' && data.message.length > 0) {
                data.message.forEach(prestec => {
                    const row = tbody.insertRow();
                    row.insertCell(0).textContent = prestec.id_prestec;
                    row.insertCell(1).textContent = prestec.llibre;
                    row.insertCell(2).textContent = prestec.usuari;
                    row.insertCell(3).textContent = prestec.data_inici;
                    row.insertCell(4).textContent = prestec.data_devolucio;
                    row.insertCell(5).textContent = prestec.data_real_tornada;
                    row.insertCell(6).textContent = prestec.estat;
                    row.insertCell(7).textContent = prestec.comentaris;
                    const accionsCell = row.insertCell(8);

                    const select = document.createElement('select');
                    const optionDefault = document.createElement('option');
                    optionDefault.textContent = 'Accions';
                    select.appendChild(optionDefault);

                    if (prestec.estat === 1 || prestec.estat === '1') {
                        const option1 = document.createElement('option');
                        option1.textContent = 'Autoritzar';
                        select.appendChild(option1);
                        const option2 = document.createElement('option');
                        option2.textContent = 'Denegar';
                        select.appendChild(option2);
                    } else {
                        const option3 = document.createElement('option');
                        option3.textContent = 'Marcar com a retornat';
                        select.appendChild(option3);
                    }

                    const option4 = document.createElement('option');
                    option4.textContent = 'Eliminar';
                    select.appendChild(option4);

                    select.addEventListener('change', async () => {
                        const formData = new FormData();
                        formData.append('id_prestec', prestec.id_prestec);

                        switch (select.value) {
                            case 'Autoritzar':
                                formData.append('pttn', 'autoritzarPrestec');
                                break;
                            case 'Denegar':
                                formData.append('pttn', 'denegarPrestec');
                                break;
                            case 'Marcar com a retornat':
                                formData.append('pttn', 'marcarRetornat');
                                break;
                            case 'Eliminar':
                                formData.append('pttn', 'eliminarPrestec');
                                break;
                            default:
                                return;
                        }

                        fetch('../mantenimiento/api.php', {
                            method: 'POST',
                            body: formData
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.response === 'OK') {
                                    loadPrestecs();
                                } else {
                                    alert('Error: ' + data.message);
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Error al procesar la solicitud');
                            });
                    });

                    accionsCell.appendChild(select);
                });
            } else {
                const row = tbody.insertRow();
                row.innerHTML = '<td colspan="9">No hi han préstecs</td>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al procesar la solicitud');
        });
};

const loadReserves = () => {
    const formData = new FormData();
    formData.append('pttn', 'viewAllReserves');

    fetch('../mantenimiento/api.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            const table = document.getElementById('reservesList');
            if (data.response === 'OK' && data.message.length > 0) {
                data.message.forEach(reserva => {
                    const row = table.insertRow();
                    row.insertCell(0).textContent = reserva.reserva;
                    row.insertCell(1).textContent = reserva.llibre;
                    row.insertCell(2).textContent = reserva.usuari;
                    row.insertCell(3).textContent = reserva.data_inici;
                    row.insertCell(4).textContent = reserva.data_fi;
                    row.insertCell(5).textContent = reserva.estat;
                    row.insertCell(6).textContent = (reserva.prolongada === '1') ? 'Sí' : 'No';
                    row.insertCell(7).textContent = reserva.motiu_prolongacio;

                    const accionsCell = row.insertCell(8);
                    const editButton = document.createElement('button');
                    editButton.textContent = 'Editar';
                    const deleteButton = document.createElement('button');
                    deleteButton.textContent = 'Eliminar';
                    accionsCell.appendChild(editButton);
                    accionsCell.appendChild(deleteButton);
                });
            } else {
                const row = table.insertRow();
                row.innerHTML = '<td colspan="9">No hi han reserves</td>';
                //alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al procesar la solicitud');
        });
}

const getAutor = async (idLlibre) => {
    try {
        const response = await fetch('../mantenimiento/api.php?pttn=getAutor&id=' + idLlibre, {
            method: 'GET'
        });
        const data = await response.json();
        if (data.response === 'OK') {
            if (data.autor === null) {
                return 'Autor no trobat';
            }
            return data.autor;
        } else {
            throw new Error('Error al obtener el autor del libro: ' + data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al procesar la solicitud');
    }
}

const getCDU = async (idLlibre) => {
    try {
        const response = await fetch('../mantenimiento/api.php?pttn=getCDU&id=' + idLlibre, {
            method: 'GET'
        });
        const data = await response.json();
        if (data.response === 'OK') {
            if (data.cdu === null) {
                return 'CDU no trobat';
            }
            return data.cdu;
        } else {
            throw new Error('Error al obtener la CDU del libro: ' + data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al procesar la solicitud');
    }
}

async function generarQR() {
    const input = document.getElementById('qr').value;
    let start = 0;
    let end = 0;

    if (!input.includes(',') && input !== '0') {
        alert('Rang invàlid!');
        return;
    } else if (input === '0') {
        start = 1;
        const response = await fetch('../mantenimiento/api.php?pttn=getBookStats');
        const data = await response.json();
        end = data.stats.total;
    } else {
        const range = input.split(',');
        start = parseInt(range[0], 10);
        end = parseInt(range[1], 10);
    }

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    let x = 10;
    let y = 10;
    let pageHeight = doc.internal.pageSize.height;

    for (let i = start; i <= end; i++) {
        const qrElement = document.createElement('div');
        qrElement.classList.add('qr-container');
        document.getElementById('qrcodes').appendChild(qrElement);

        let txtQR = window.location.origin + '/libro.php?libro=' + i;
        new QRCode(qrElement, {
            text: txtQR,
            width: 64,
            height: 64,
            correctLevel: QRCode.CorrectLevel.H
        });
        await new Promise(resolve => setTimeout(resolve, 100));
        const qrImage = qrElement.querySelector('img').src;
        doc.addImage(qrImage, 'PNG', x, y, 40, 40);
        doc.setFontSize(10);

        let autor = await getAutor(i);
        let cdu = await getCDU(i);

        if (autor.length >= 40) {
            autor = autor.substring(0, 37);
            autor = autor + '...';
        }

        doc.text(`Autor: ${autor}`, x, y + 45, { maxWidth: 40 }, { align: 'center' });
        doc.text(`CDU: ${cdu}`, x, y + 58, { maxWidth: 40 }, { align: 'center' });

        x += 50;
        if (x > 160) {
            x = 10;
            y += 60;
        }
        if (y + 60 > pageHeight) {
            doc.addPage();
            y = 10;
        }
    }
    doc.save('qrcodes.pdf');
}


async function crearChat() {
    const form = document.getElementById('formCreateChat');

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const formData = new FormData(form);
        formData.append('pttn', 'crearChat');
        formData.append('nom_xat', document.getElementById('nom_xat').value);

        try {
            const response = await fetch('../mantenimiento/api.php', {
                method: 'POST',
                body: formData,
            });

            const result = await response.json();

            if (result.response === 'OK') {
                alert('Xat creat amb èxit');
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Hi ha hagut un error en crear el xat.');
        }
    });
};


async function getChats() {
    const formData = new FormData();
    formData.append('pttn', 'getChats');

    try {
        const response = await fetch('../mantenimiento/api.php', {
            method: 'POST',
            body: formData
        });

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const data = await response.json();
        if (data.response === 'OK') {
            if (data.message.length === 0) return;
            const chats = data.message;
            const chatList = document.getElementById('chatList');
            chatList.innerHTML = `
                <tr>
                    <th>ID</th>
                    <th>Nom del xat</th>
                    <th>Total Usuaris</th>
                    <th>Imatge Xat</th>
                    <th>Rol mínim</th>
                    <th>Accions</th>
                </tr>
            `;

            chats.forEach(chat => {
                const row = document.createElement('tr');

                const idCell = document.createElement('td');
                idCell.textContent = chat.id_xat;
                row.appendChild(idCell);

                const nameCell = document.createElement('td');
                nameCell.textContent = chat.nom_xat;
                row.appendChild(nameCell);

                const usersCell = document.createElement('td');
                usersCell.textContent = chat.total_usuaris || 0; // Assuming total_usuaris is a field in the response
                row.appendChild(usersCell);

                const imgCell = document.createElement('td');
                const img = document.createElement('img');
                img.src = "../media/sistema/xats/" + chat.img_xat;
                img.alt = chat.nom_xat;
                img.width = 40;
                img.height = 40;

                imgCell.appendChild(img);
                row.appendChild(imgCell);

                const roleCell = document.createElement('td');
                roleCell.textContent = chat.min_rol;
                row.appendChild(roleCell);

                const actionsCell = document.createElement('td');
                const editImgBtn = document.createElement('button');
                editImgBtn.textContent = 'Editar Imatge';
                editImgBtn.addEventListener('click', () => {
                    const additionalParams = {
                        id_xat: chat.id_xat
                    };

                    setUpPopUp('uploadImgXat', 'imatgeXat', 'Canvia la imatge del xat', additionalParams);
                });

                const viewButton = document.createElement('button');
                viewButton.textContent = 'Veure';
                viewButton.addEventListener('click', () => {
                    window.location.href = `../xats.php?id=${chat.id_xat}`;
                });

                actionsCell.appendChild(editImgBtn);
                actionsCell.appendChild(viewButton);
                row.appendChild(actionsCell);

                chatList.appendChild(row);
            });
        } else {
            const chatList = document.getElementById('chatList');
            chatList.innerHTML = `
                <tr>
                    <td colspan="4">No hi han xats</td>
                </tr>
            `;
            //alert('Error al obtener los chats: ' + data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al procesar la solicitud (getChats)');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    getChats();
});



showPanel('admin-config-panel'); // Mostrar el panel de configuración por defecto al cargar la página.
getColores(); // Suponiendo que estas funciones necesitan ser llamadas al cargar.
getBanner();
getAllUsers();
loadStatsUsers();
loadBookStats();
loadPrestecs();
loadReserves();
getChats();