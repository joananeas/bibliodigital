

const getAllUsers = async () => {
    console.log('Obteniendo usuarios...');
    let formData = new FormData();
    formData.append('pttn', 'getReservesFromUser');

    const response = await fetch('../mantenimiento/api.php', {
        method: 'POST',
        body: formData
    });
    const data = await response.json();

    let table = document.getElementById('reservasTable');
    console.log(data);
    if (Array.isArray(data)) {
        data.forEach(reserva => {
            let tr = document.createElement('tr');

            let tdReserva = document.createElement('td');
            tdReserva.textContent = reserva.reserva;
            tdReserva.classList.add('table-not-shown');
            //tdReserva.classList.add('table-row');
            tr.appendChild(tdReserva);

            let tdCorreo = document.createElement('td');
            tdCorreo.textContent = reserva.llibre;
            //tdCorreo.classList.add('table-row');
            tr.appendChild(tdCorreo);

            let tdDataInici = document.createElement('td');
            tdDataInici.textContent = reserva.data_inici;
            tdDataInici.classList.add('table-not-shown-min');
            //tdDataInici.classList.add('table-row');
            tr.appendChild(tdDataInici);

            let tdDataFi = document.createElement('td');
            tdDataFi.textContent = reserva.data_fi;
            tdDataFi.classList.add('table-not-shown');
            //tdDataFi.classList.add('table-row');
            tr.appendChild(tdDataFi);

            let tdEstat = document.createElement('td');
            tdEstat.textContent = reserva.estat;
            //tdEstat.classList.add('table-row');
            tr.appendChild(tdEstat);

            let tdPrestec = document.createElement('td');
            //tdPrestec.classList.add('table-row');

            let btnPedirPrestamo = document.createElement('button');
            btnPedirPrestamo.textContent = 'Demana préstec';
            btnPedirPrestamo.classList.add('btn');
            btnPedirPrestamo.classList.add('botonUniversal_alt');
            btnPedirPrestamo.addEventListener('click', async () => {
                let formData = new FormData();
                formData.append('pttn', 'prestarExemplar');
                formData.append('id_reserva', reserva.reserva); // ID de la reserva
                const response = await fetch('../mantenimiento/api.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                console.log(data);
                if (data.response === 'OK') {
                    //alert('Préstec realitzat correctament');
                    //location.reload();
                    tdEstat.textContent = 'Demanat';
                } else {
                    alert('Error: ' + data.message);
                }
            });

            tdPrestec.appendChild(btnPedirPrestamo);
            tr.appendChild(tdPrestec);

            tr.classList.add('table-row-bottom');
            table.appendChild(tr);
        });
    }
}

getAllUsers();