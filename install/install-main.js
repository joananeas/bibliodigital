function mostrarAnimacion() {
    var formulario = $('#mainForm');

    formulario.animate({ borderColor: 'green' }, 1000, function() {
        // La animación se completa después de 1000 milisegundos (1 segundo)
    });
}

// Realizar una solicitud AJAX para obtener la respuesta JSON
function enviarFormulario(event) {
    event.preventDefault(); // Prevenir la acción predeterminada del formulario

    // Realizar una solicitud AJAX para obtener la respuesta JSON
    $.ajax({
        url: 'db-conn.php',
        type: 'POST', // Cambia el método a POST para enviar los datos del formulario
        data: $('#mainForm').serialize(), // Envía los datos del formulario
        dataType: 'json',
        success: function(response) {
            if (response.connection) {
                mostrarAnimacion('green');
                console.log('funciona');
            }

        },
        error: function() {
            console.log('Error en la solicitud AJAX');
        }
    });
}
