

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
            tdReserva.classList.add('user-row');
            tr.appendChild(tdReserva);

            let tdCorreo = document.createElement('td');
            tdCorreo.textContent = reserva.exemplar_id;
            tdCorreo.classList.add('user-row');
            tr.appendChild(tdCorreo);

            let tdDataInici = document.createElement('td');
            tdDataInici.textContent = reserva.data_inici;
            tdDataInici.classList.add('user-row');
            tr.appendChild(tdDataInici);

            let tdDataFi = document.createElement('td');
            tdDataFi.textContent = reserva.data_fi;
            tdDataFi.classList.add('user-row');
            tr.appendChild(tdDataFi);

            let tdEstat = document.createElement('td');
            tdEstat.textContent = reserva.estat;
            tdEstat.classList.add('user-row');
            tr.appendChild(tdEstat);
            
            let tdPrestec = document.createElement('td');
            tdPrestec.classList.add('user-row');

            let btnPedirPrestamo = document.createElement('button');
            btnPedirPrestamo.textContent = 'Demana préstec';
            btnPedirPrestamo.classList.add('btn');
            btnPedirPrestamo.classList.add('botonUniversal_alt');
            btnPedirPrestamo.addEventListener('click', async () => {
                let formData = new FormData();
                formData.append('pttn', 'prestarExemplar');
                formData.append('reserva', reserva.reserva);
                const response = await fetch('../mantenimiento/api.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                console.log(data);
                if (data.status == 'ok') {
                    alert('Préstec realitzat correctament');
                    location.reload();
                } else {
                    alert('Error al realitzar el préstec');
                }
            });
            
            tdPrestec.appendChild(btnPedirPrestamo);
            tr.appendChild(tdPrestec);

            tr.classList.add('user-row-bottom');
            table.appendChild(tr);
        });
    }
}

getAllUsers();