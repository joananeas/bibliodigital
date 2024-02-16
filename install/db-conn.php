<?php
    # © Joan Aneas
    $db_server = $_POST['host'] ?? null;
    $db_user = $_POST['user'] ?? null;  
    $db_name = $_POST['db'] ?? null;
    $db_pass = $_POST['passwd'] ?? ""; # Si la contraseña está vacía
    # se conectará sin contraseña (mala práctica, pero para pruebas está bien).
    
    $conn = new mysqli($db_server, $db_user, $db_pass, $db_name);
    
    function installation($db_server, $db_user, $db_name, $db_pass){
        if (!file_exists("./mantenimiento/db.php")) {
            $archivo_db = fopen("db.php", "w");
            fwrite($archivo_db, "<?php\n");
            fwrite($archivo_db, "\ndefine('DB_HOST', '".$db_server."');\n");
            fwrite($archivo_db, "\ndefine('DB_USER', '".$db_user."');\n");
            fwrite($archivo_db, "\ndefine('DB_PASSWORD', '".$db_pass."');\n");
            fwrite($archivo_db, "\ndefine('DB_NAME', '".$db_name."');\n");
            # Es una buena práctica no cerrar con '? >'
            # los archivos php que no contienen código html 
            # fuente: https://www.php.net/basic-syntax.instruction-separation
            fclose($archivo_db);
            return;
        }

        unlink("./mantenimiento/db.php");
    }

    if ($conn->connect_error) {
        echo json_encode(array
        ('status' => 'error',
         'message' => 'Error de connexió a la base de dades: ' . $conn->connect_error,
         'data' => array('host' => $db_server, 'user' => $db_user, 'db' => $db_name, 'passwd' => $db_pass)));
    }
    else {
        echo json_encode(array('status' => 'ok', 'message' => 'Connexió a la base de dades correcta'));
    }

    $conn -> close();
