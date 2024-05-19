const getLibro = () => {
    document.getElementById('min-qr').addEventListener('click', () => viewPopUp('popupQR', 'closeQR'));
    let urlString = window.location.href;
    let paramString = urlString.split('?')[1];
    let queryString = new URLSearchParams(paramString);
    let libro = queryString.get('libro');
    console.log("Libro is:" + libro);
    return libro;
};

const loadQR = () => {
    let formData = new FormData();
    formData.append('pttn', 'server-ip');
    fetch("./mantenimiento/api.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.response === "ok") {
                console.log(data);
                let qr = new QRCode(document.getElementById('qrcode'), {
                    text: window.location.href,
                    width: 128,
                    height: 128,
                    colorDark: "#000000",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.H
                });
            }
        })
        .catch(error => {
            console.log("[ERROR (API_Request)] ", error);
        });

    fetch("./mantenimiento/api.php?pttn=getFavicon")
        .then(response => response.json())
        .then(data => {
            if (data.response === "OK") {
                console.log(data);
                let imgInQr = document.getElementById('qr-image');
                let url = "./media/sistema/favicon/" + data.favicon
                console.log(url);
                imgInQr.src = url;
            }
        })
        .catch(error => {
            console.log("[ERROR (API_Request)] ", error);
        });
};

let l = getLibro(); // No se por que, no funcionaba una funcion DOMContentLoaded, asi que lo he puesto aqui.

let currentStars = 0; // Variable para almacenar el número de estrellas actuales

const getStars = () => {
    let formData = new FormData();
    formData.append('pttn', 'getStars');
    formData.append('llibre', l);
    return fetch("./mantenimiento/api.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.response === "OK") {
                console.log(data);
                currentStars = Math.round(data.estrelles); // Redondear las estrellas para asegurar un número entero
                updateStarsDisplay();
            } else {
                return 0; // En caso de que no haya datos, devuelve 0 estrellas.
            }
        })
        .catch(error => {
            console.log("[ERROR (API_Request)] ", error);
            return 0;
        });
}

const updateStarsDisplay = () => {
    document.querySelectorAll('.star').forEach((star, index) => {
        if (index < currentStars) {
            star.src = './media/icons/star-yellow.png';
        } else {
            star.src = './media/icons/star-grey.png';
        }
    });
};


if (!l) {
    window.location.href = "./index.php";
} else {
    loadQR();
    let formData = new FormData();
    formData.append('pttn', 'cercaLlibresFull');
    formData.append('llibre', l);
    fetch("./mantenimiento/api.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.response === "OK") {
                console.log(data);
                data.llibres.forEach(libro => {
                    document.getElementById('tituloLibro').innerHTML = libro.nom;

                    getStars();
                    document.getElementById('libroImagen').src = "https://aplicacions.ensenyament.gencat.cat" + libro.url;
                    document.getElementById('categoria').innerHTML = libro.categoria;
                    document.getElementById('nivell').innerHTML = libro.nivell;
                    document.getElementById('resumLibro').innerHTML = libro.resum;
                    document.getElementById('autorLibro').innerHTML = libro.autor;
                });
            }
        })
        .catch(error => {
            console.log("[ERROR (API_Request)] ", error);
        });
}

const toast = document.getElementById('toast');
const toastFrame = document.getElementById('toast-frame');

document.getElementById('share-copy').addEventListener('click', () => {
    navigator.clipboard.writeText(window.location.href).then(() => {
        toast.innerHTML = 'URL ';
        toastFrame.style.display = 'block';
        setTimeout(() => {
            toastFrame.style.display = 'none';
        }, 2000);
    }).catch(err => {
        toast.innerHTML = 'Error al copiar la URL al portapapers.';
    });
});


const reservarLibro = (startDate) => {
    if (!startDate) {
        alert("Selecciona un interval de dates vàlid.");
        return;
    }

    let formData = new FormData();
    formData.append('pttn', 'reservar');
    formData.append('id', l);
    formData.append('fecha', startDate);
    fetch("./mantenimiento/api.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.response === "OK") {
                viewPopUp('popupReserva', 'closeReserva');
                toast.innerHTML = 'Reserva realitzada correctament.';
                toastFrame.style.display = 'block';
                setTimeout(() => {
                    toastFrame.style.display = 'none';
                }, 2000);
            }
            else if (data.response === "ERROR") {
                toast.innerHTML = 'Error al realitzar la reserva.';
                toastFrame.style.display = 'block';
                setTimeout(() => {
                    toastFrame.style.display = 'none';
                }, 2000);
            }
        })
        .catch(error => {
            console.log("[ERROR (API_Request)] ", error);
        });
}



document.getElementById('reservar').addEventListener('click', async function () {
    viewPopUp('popupReserva', 'closeReserva');
    const monthNames = ["Gener", "Febrer", "Març", "Abril", "Maig", "Juny",
        "Juliol", "Agost", "Setembre", "Octubre", "Novembre", "Desembre"];
    let currentMonth = new Date().getMonth();
    let currentYear = new Date().getFullYear();

    const monthYear = document.getElementById('month-year');
    const calendarBody = document.getElementById('calendar-body');
    const prevMonth = document.getElementById('prev-month');
    const nextMonth = document.getElementById('next-month');

    prevMonth.onclick = () => changeMonth(-1);
    nextMonth.onclick = () => changeMonth(1);

    function changeMonth(step) {
        currentMonth += step;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        } else if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        fillCalendar();
    }

    const getDisponibilitat = () => {
        let formData = new FormData();
        formData.append('pttn', 'getDisponibilitatLlibrePerReserves');
        formData.append('id_llibre', l); // Asegúrate de que 'l' esté definido en el contexto adecuado
        return fetch("./mantenimiento/api.php", {
            method: "POST",
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.response === "OK") {
                    return data.message;
                } else {
                    return null;
                }
            })
            .catch(error => {
                console.log("[ERROR (API_Request)] ", error);
                return null;
            });
    }

    const highlightNextDays = (startDay, startMonth, startYear) => {
        const daysToHighlight = 7;
        let day = parseInt(startDay, 10);
        let month = startMonth - 1; // Ajuste del mes (0-11)
        let year = startYear;
        const calendarCells = calendarBody.getElementsByTagName('td');

        for (const calendarCell of calendarCells) {
            if (calendarCell.style.backgroundColor === "var(--color-principal)") continue;
            calendarCell.style.backgroundColor = ""; // Resetear color
        }

        for (let i = 0; i < daysToHighlight; i++) {
            if (day > new Date(year, month + 1, 0).getDate()) {
                day = 1;
                month++;
                if (month > 11) {
                    month = 0;
                    year++;
                }
            }

            for (const element of calendarCells) {
                if (parseInt(element.textContent, 10) === day &&
                    month === currentMonth && year === currentYear) {
                    element.style.backgroundColor = "yellow";
                    break;
                }
            }

            day++;
        }

        document.getElementById('reserva-holder').innerHTML = `Del <strong>${startDay}/${startMonth}/${startYear}</strong> fins a <strong>${day - 1}/${month + 1}/${year}</strong>`;

        document.getElementById('reservar-libro').addEventListener('click', () => {
            let startDate = `${startYear}-${padToTwoDigits(startMonth)}-${padToTwoDigits(startDay)}`;
            reservarLibro(startDate);
        });
    }

    async function fillCalendar() {
        const firstDayOfMonth = new Date(currentYear, currentMonth, 1);
        const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
        const currentDay = new Date().getDate();

        monthYear.textContent = `${monthNames[currentMonth]} ${currentYear}`;
        calendarBody.innerHTML = '';

        const reserves = await getDisponibilitat();
        if (reserves && reserves.length > 0) {
            // Pintar todas las celdas de rojo si hay reservas o prestamos
            let date = 1;
            for (let i = 0; i < 6; i++) {
                const row = document.createElement('tr');
                for (let j = 0; j < 7; j++) {
                    const cell = document.createElement('td');
                    if (!(i === 0 && j < firstDayOfMonth.getDay()) && date <= daysInMonth) {
                        cell.textContent = date++;
                        cell.style.backgroundColor = "red";
                    }
                    row.appendChild(cell);
                }
                calendarBody.appendChild(row);
                if (date > daysInMonth) {
                    break;
                }
            }
        } else {
            // Continuar como de costumbre si no hay reservas ni prestamos
            let date = 1;
            for (let i = 0; i < 6; i++) {
                const row = document.createElement('tr');
                for (let j = 0; j < 7; j++) {
                    if (i === 0 && j < firstDayOfMonth.getDay()) {
                        row.appendChild(document.createElement('td'));
                    } else if (date > daysInMonth) {
                        break;
                    } else {
                        const cell = document.createElement('td');

                        if (date < currentDay && currentMonth === new Date().getMonth() && currentYear === new Date().getFullYear()) {
                            cell.style.backgroundColor = "var(--color-principal)";
                        }

                        cell.textContent = date++;
                        cell.addEventListener('click', () => {
                            if (cell.style.backgroundColor === "var(--color-principal)") {
                                alert("No pots reservar per aquest dia");
                                return;
                            }
                            highlightNextDays(cell.textContent, currentMonth + 1, currentYear);
                        });

                        row.appendChild(cell);
                    }
                }
                calendarBody.appendChild(row);
                if (date > daysInMonth) {
                    break;
                }
            }
        }
    }

    function padToTwoDigits(value) {
        return value.toString().padStart(2, '0');
    }

    function reservarLibro(startDate) {
        let formData = new FormData();
        formData.append('pttn', 'reservar');
        formData.append('id', l); // Asegúrate de que 'l' esté definido en el contexto adecuado
        formData.append('fecha', startDate);

        fetch("./mantenimiento/api.php", {
            method: "POST",
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.response === "OK") {
                    console.log(data);
                    document.getElementById('reserva-holder').innerHTML = "Reserva completada!";
                }
            })
            .catch(error => {
                console.log("[ERROR (API_Request)] ", error);
            });
    }

    fillCalendar();
});

// Cambiar la imagen al hacer hover
document.querySelectorAll('.star').forEach((star, index, stars) => {
    star.addEventListener('mouseover', () => {
        for (let i = 0; i <= index; i++) {
            stars[i].src = './media/icons/star-yellow.png'; // URL de la imagen cuando se pasa el ratón por encima
        }
    });

    star.addEventListener('mouseout', () => {
        updateStarsDisplay(); // Restaurar las estrellas al estado actual
    });

    star.addEventListener('click', () => {
        let formData = new FormData();
        formData.append('pttn', 'puntuar');
        formData.append('id_llibre', l);
        formData.append('puntuacio', index + 1);
        fetch("./mantenimiento/api.php", {
            method: "POST",
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.response === "OK") {
                    console.log(data);
                    currentStars = index + 1; // Actualizar el número de estrellas actuales
                    if (data.message === "updated") {
                        document.getElementById('starsToast').textContent = 'Has actualitzat la puntuació a ' + currentStars + ' estrelles.';
                    }
                    else {
                        document.getElementById('starsToast').textContent = 'Has puntuat amb ' + currentStars + ' estrelles.';
                    }
                    updateStarsDisplay(); // Actualizar las estrellas en el display
                }
            })
            .catch(error => {
                console.log("[ERROR (API_Request)] ", error);
            });
    });
});


const sendComment = () => {
    let comment = document.getElementById('comentario').value;
    if (comment === "") {
        alert("No pots enviar un comentari buit.");
        return;
    }

    let formData = new FormData();
    formData.append('pttn', 'sendComment');
    formData.append('id_llibre', l);
    formData.append('comentari', comment);
    fetch("./mantenimiento/api.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.response === "OK") {
                console.log(data);
                document.getElementById('comentario').value = "";
                document.getElementById('comentarioToast').textContent = 'Comentari enviat correctament.';
            }
        })
        .catch(error => {
            console.log("[ERROR (API_Request)] ", error);
        });
}

const loadComments = () => {
    let formData = new FormData();
    formData.append('pttn', 'getComments');
    formData.append('id_llibre', l);
    fetch("./mantenimiento/api.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.response === "OK") {
                console.log(data);
                let comments = data.message;
                let commentList = document.getElementById('comentarios');
                commentList.innerHTML = "";
                comments.forEach((comment, index) => {
                    let commentElement = document.createElement('div');
                    let commentHeader = document.createElement('div');
                    let commentHeaderAutor = document.createElement('div');
                    let commentHeaderImg = document.createElement('img');
                    let commentHeaderInfo = document.createElement('div');
                    let commentHeaderName = document.createElement('p');
                    let commentHeaderDate = document.createElement('time');
                    let commentBody = document.createElement('div');
                    let commentNumber = document.createElement('div');

                    commentElement.classList.add('comentario');
                    commentHeader.classList.add('comentario-header');
                    commentHeaderAutor.classList.add('comentario-autor');
                    commentHeaderInfo.classList.add('comentario-autor-info');
                    commentHeaderImg.src = './media/icons/user.png';
                    commentHeaderImg.classList.add('profileComment');
                    commentBody.classList.add('comentario-body');
                    commentNumber.classList.add('comentario-number');

                    commentBody.textContent = comment.comentari;
                    commentHeaderName.textContent = comment.usuari;
                    commentHeaderName.classList.add('comentario-autor-nombre');
                    commentHeaderDate.textContent = comment.data_comentari;
                    commentNumber.textContent = "#" + (index + 1);

                    commentHeaderInfo.appendChild(commentHeaderName);
                    commentHeaderInfo.appendChild(commentHeaderDate);

                    commentHeaderAutor.appendChild(commentHeaderImg);
                    commentHeaderAutor.appendChild(commentHeaderInfo);

                    commentHeader.appendChild(commentHeaderAutor);
                    commentHeader.appendChild(commentNumber);

                    commentElement.appendChild(commentHeader);
                    commentElement.appendChild(commentBody);
                    commentList.appendChild(commentElement);
                });
            }
        })
        .catch(error => {
            console.log("[ERROR (API_Request)] ", error);
        });
}

// First time loading page
loadComments();

document.getElementById('enviar-comentario').addEventListener('click', sendComment);
//setInterval(loadComments, 5000); // Actualizar los comentarios cada 5 segundos