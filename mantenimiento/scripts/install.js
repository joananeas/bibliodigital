$(document).ready(function () {
    $('#submit').click(function () {
        // Datos que deseas enviar al servidor
        var datos = {
            parametro1: 'valor1',
            parametro2: 'valor2'
        };

        $.ajax({
            url: 'tuscript.php',  // Ruta al script PHP en tu servidor
            method: 'POST',       // Método HTTP (POST es común para enviar datos)
            data: datos,          // Datos que se envían al servidor
            dataType: 'json',     // Tipo de datos esperado en la respuesta

            success: function (respuesta) {
                // Aquí puedes manejar la respuesta del servidor (respuesta)
                console.log(respuesta);
            },
            error: function () {
                alert('Error al procesar la solicitud');
            }
        });
    });
});