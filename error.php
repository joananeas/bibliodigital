
<!-- Header DINAMICO -->
<?php require "dynamo/header-dinamico.php"; ?>
<body>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const errorList = {
                "404": "Pàgina no trobada.",
                "403": "Accés denegat.",
                "500": "Error intern de servidor.",
                "0000": "Error desconegut.",
                "0001": "Conexió a la base de dades fallida."
            };

            const getError = () => {
                let urlString = window.location.href;
                let paramString = urlString.split('?')[1];
                let queryString = new URLSearchParams(paramString);
                let e = queryString.get('error');
                console.log("Error is:" + e);
                return e;
            };

            const assignError = (error) => {
                let errorElement = document.getElementById('error');
                let txtErrorElement = document.getElementById('txt-error');

                Object.keys(errorList).forEach(key => {
                    if (key === error) {
                        txtErrorElement.innerHTML = errorList[key];
                    }    
                })

                if (errorElement) {
                    // La E- es para que se vea E-0001 en vez de 0001
                    errorElement.innerHTML = error ? "E-" + error : "Error desconocido";
                } else {
                    console.log("Elemento de error no encontrado");
                }
            }

            let e = getError();
            assignError(e);
        });
    </script>
    <main>
        <section class="frame">
            <h1>🛠️<span id="error"></span>🛠️</h1>
            <p id="txt-error" style="background-color:#333333; color:white;"></p>
            <p><a style="text-decoration:underline; color: #333333;" href="./index.php">Torna a l'inici...</a></p>
        </section>
    </main>
    <?php require "dynamo/footer-dinamico.php"; ?>
</body>

