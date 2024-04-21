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

showPanel('admin-config-panel'); // Mostrar el panel de configuración por defecto al cargar la página.
getColores(); // Suponiendo que estas funciones necesitan ser llamadas al cargar.
getBanner();
getAllUsers();