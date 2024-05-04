

const getAllUsers = async () => {
    console.log('Obteniendo usuarios...');
    let formData = new FormData();
    formData.append('pttn', 'getReservesFromUser');

    const response = await fetch('../mantenimiento/api.php', {
        method: 'POST',
        body: formData
    });
    const data = await response.json();
    
    let table = document.getElementById('userList');
    console.log(data);
    // if (Array.isArray(data)) {
    //     data.forEach(user => {
    //         let tr = document.createElement('tr');

    //         // Crear y asignar los elementos para 'Nombre', que será el correo
    //         let tdNombre = document.createElement('td');
    //         tdNombre.textContent = user.email;
    //         tdNombre.classList.add('user-row');
    //         tr.appendChild(tdNombre);

    //         // Crear y asignar los elementos para 'Correo', que será el rol
    //         let tdCorreo = document.createElement('td');
    //         tdCorreo.textContent = user.rol;
    //         tdCorreo.classList.add('user-row');
    //         tr.appendChild(tdCorreo);

    //         // Crear y asignar los elementos vacíos para 'Estado'
    //         let tdEstado = document.createElement('td');
    //         tdEstado.classList.add('user-row');
    //         tr.appendChild(tdEstado);

    //         // Crear y asignar los elementos vacíos para 'Acciones'
    //         let tdAcciones = document.createElement('td');
    //         tr.appendChild(tdAcciones);

    //         tr.classList.add('user-row-bottom');
    //         // Añadir la fila completa a la tabla
    //         table.appendChild(tr);
    //     });
    // }
}

getAllUsers();