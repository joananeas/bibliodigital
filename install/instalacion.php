<?php
    # © Joan Aneas
    $peticion = $_POST['peticion'] ?? null;
    $db_server = $_POST['host'] ?? null;
    $db_user = $_POST['user'] ?? null;  
    $db_name = $_POST['db'] ?? null;
    $db_pass = $_POST['passwd'] ?? ""; # Si la contraseña está vacía
    # se conectará sin contraseña (mala práctica, pero para pruebas está bien).
    
    function installation($db_server, $db_user, $db_name, $db_pass){

        /*
        * Encriptación de las credenciales de la base de datos.
        * Se utiliza la función openssl_encrypt para encriptar el usuario, el nombre de la base de datos y el host.
        * La contraseña se encripta con password_hash.
        */

        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $db_server = openssl_encrypt($db_server, 'aes-256-cbc', 'bibliodigital', 0, $iv);
        $db_user = openssl_encrypt($db_user, 'aes-256-cbc', 'bibliodigital', 0, $iv);
        $db_name = openssl_encrypt($db_name, 'aes-256-cbc', 'bibliodigital', 0, $iv);
        $db_pass = openssl_encrypt($db_pass, 'aes-256-cbc', 'bibliodigital', 0, $iv);

        if (file_exists("../mantenimiento/db.php")) {
            return json_encode(["status" => "error", "message" => "El archivo db.php ya existe."]);
        }

        else {
            $archivo_db = fopen("../mantenimiento/db.php", "w");
            fwrite($archivo_db, "<?php\n");
            fwrite($archivo_db, "# © Joan Aneas\n"); # (Comentario) Copyright
            fwrite($archivo_db, "# Este archivo fue generado automáticamente por el instalador de Bibliodigital.\n"); # (Comentario) Aviso.
            fwrite($archivo_db, "# NO MODIFICAR SI SE DESCONOCE EL FUNCIONAMIENTO.\n"); # (Comentario) Aviso.
            fwrite($archivo_db, "define('DB_HOST', '".$db_server."');\n"); # (Constante) HOST, cifrado openssl.
            fwrite($archivo_db, "define('DB_USER', '".$db_user."');\n"); # (Constante) USER, cifrado openssl.
            fwrite($archivo_db, "define('DB_PASSWORD', '".$db_pass."');\n"); # (Constante) PASSWORD, cifrado openssl.
            fwrite($archivo_db, "define('DB_NAME', '".$db_name."');\n"); # (Constante) NAME, cifrado openssl.
            # Es una buena práctica no cerrar con '? >'
            # los archivos php que no contienen código html 
            # fuente: https://www.php.net/basic-syntax.instruction-separation
            $key = 'bibliodigital';
            $host = openssl_decrypt($db_server, 'aes-256-cbc', $key, 0, $iv);
            $user = openssl_decrypt($db_user, 'aes-256-cbc', $key, 0, $iv);
            $password = openssl_decrypt($db_pass, 'aes-256-cbc', $key, 0, $iv);
            $db = openssl_decrypt($db_name, 'aes-256-cbc', $key, 0, $iv);

            fwrite($archivo_db, "# host: ".$host."\n");
            fwrite($archivo_db, "# user: ".$user."\n");
            fwrite($archivo_db, "# password: ".$password."\n");
            fwrite($archivo_db, "# db: ".$db."\n");
            
            fclose($archivo_db);

        }
    }

    switch ($peticion){
        case 'comprobarConn':
            if ($db_server == null || $db_user == null || $db_name == null) {
                echo json_encode(["status" => "error", "message" => "Faltan datos."]);
                return;
            }

            $conn = new mysqli($db_server, $db_user, $db_pass, $db_name);
            if ($conn->connect_error) {
                echo json_encode(["status" => "error", "message" => "Error de conexión: " . $conn->connect_error]);
            } else {
                echo json_encode(["status" => "ok", "message" => "Conexión establecida con éxito."]);
            }
            break;


        case 'crearArchivoDb':
            $db_server = $_POST['host'] ?? null;
            $db_user = $_POST['user'] ?? null;  
            $db_name = $_POST['db'] ?? null;
            $db_pass = $_POST['passwd'] ?? "";

            $msg = installation($db_server, $db_user, $db_name, $db_pass);
            echo $msg;
            break;
        default:
            echo json_encode(["status" => "error", "message" => "Petición no reconocida: $peticion"]);
            break;

    }
