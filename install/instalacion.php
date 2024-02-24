<?php
    # © Joan Aneas
    $peticion = $_POST['peticion'] ?? null;
    $db_server = $_POST['host'] ?? null;
    $db_user = $_POST['user'] ?? null;  
    $db_name = $_POST['db'] ?? null;
    $db_pass = $_POST['passwd'] ?? ""; # Si la contraseña está vacía
    # se conectará sin contraseña (mala práctica, pero para pruebas está bien).
    
    function crearTablasDB($db_server, $db_user, $db_name, $db_pass){
        $conn = new mysqli($db_server, $db_user, $db_pass, $db_name);
        $sql = file_get_contents("../min-bibliodigital.sql");
        if ($conn->multi_query($sql)) {
            // Ciclo a través de cada resultado para asegurarse de que se ejecuten todas las consultas
            while ($conn->more_results() && $conn->next_result()) {
                // Liberar cada conjunto de resultados para liberar memoria
                $result = $conn->use_result();
                if ($result instanceof mysqli_result) {
                    $result->free();
                }
            }
        
            if ($conn->errno) {
                return json_encode(["status" => "error", "message" => "Error al crear las tablas: " . $conn->error]);
            } else {
                return json_encode(["status" => "ok", "message" => "Tablas creadas con éxito."]);
            }
        } else {
            return json_encode(["status" => "error", "message" => "Error al crear las tablas: " . $conn->error]);
        }
    }

    function crearFicheroDB($db_server, $db_user, $db_name, $db_pass, $randKey, $iv){
        sleep(1);
        $archivo_db = fopen("../mantenimiento/db.php", "w");
        fwrite($archivo_db, "<?php\n");
        fwrite($archivo_db, "# © Joan Aneas\n"); # (Comentario) Copyright
        fwrite($archivo_db, "# Este archivo fue generado automáticamente por el instalador de Bibliodigital.\n"); # (Comentario) Aviso.
        fwrite($archivo_db, "# NO MODIFICAR SI SE DESCONOCE EL FUNCIONAMIENTO.\n"); # (Comentario) Aviso.
        fwrite($archivo_db, "\n# IV, Key, cifrado openssl. \n"); 
        fwrite($archivo_db, "define('DB_SERVER_IV', '".base64_encode($iv)."');\n"); # (Constante) IV, cifrado openssl.
        fwrite($archivo_db, "define('DB_SERVER_KEY', '".base64_encode($randKey)."');\n"); # (Constante) KEY, cifrado openssl.
        fwrite($archivo_db, "# Constantes de la BBDD, si se modifican manualmente, luego no se podrán leer.\n");
        fwrite($archivo_db, "# Luego se usa el decrypt, por lo que poner texto plano es mala idea 😅\n");
        fwrite($archivo_db, "define('DB_HOST', '".$db_server."');\n"); # (Constante) HOST, cifrado openssl.
        fwrite($archivo_db, "define('DB_USER', '".$db_user."');\n"); # (Constante) USER, cifrado openssl.
        fwrite($archivo_db, "define('DB_PASSWORD', '".$db_pass."');\n"); # (Constante) PASSWORD, cifrado openssl.
        fwrite($archivo_db, "define('DB_NAME', '".$db_name."');\n"); # (Constante) NAME, cifrado openssl.
        fwrite($archivo_db, "\n# Sígueme en github ^^ (joananeas).\n");
        fwrite($archivo_db, "# Si tienes alguna duda, puedes abrir un issue en el repositorio.\n");
        fwrite($archivo_db, "\n\n/*\n");
        fwrite($archivo_db, "*[-----------------------------------------------------------------------------------------------------------]\n");
        fwrite($archivo_db, "*| IMPORTANTE: Si el archivo no se crea automáticamente, no se podrá acceder a la web. ----------------------|\n");
        fwrite($archivo_db, "*| Si te encuentras en Linux: chown www-data:www-data <raiz_proyecto>/mantenimiento/* -----------------------|\n");
        fwrite($archivo_db, "*| %%%%%%%%%%%%%%%%%%%%%%%%%% chmod 755 <raiz_proyecto>/mantenimiento/* -------------------------------------|\n");
        fwrite($archivo_db, "*| Si te encuentras en Windows: Nidea jajajaj. Es broma, con xampp no deberías tener problemas, -------------|\n");
        fwrite($archivo_db, "*| te recomiendo WLS o una máquina virtual con ubuntu server. -----------------------------------------------|\n");
        fwrite($archivo_db, "*| pero si usas WAMP, deberás dar permisos a la carpeta mantenimiento. --------------------------------------|\n");
        fwrite($archivo_db, "*L-----------------------------------------------------------------------------------------------------------]\n");
        fwrite($archivo_db, "*/\n");
        # Es una buena práctica no cerrar con '? >'
        # los archivos php que no contienen código html 
        # fuente: https://www.php.net/basic-syntax.instruction-separation
        
        # DEBUG (borrar en producción)
        /*$key = 'bibliodigital';
        $host = openssl_decrypt($db_server, 'aes-256-cbc', $key, 0, $iv);
        $user = openssl_decrypt($db_user, 'aes-256-cbc', $key, 0, $iv);
        $password = openssl_decrypt($db_pass, 'aes-256-cbc', $key, 0, $iv);
        $db = openssl_decrypt($db_name, 'aes-256-cbc', $key, 0, $iv);

        fwrite($archivo_db, "# host: ".$host."\n");
        fwrite($archivo_db, "# user: ".$user."\n");
        fwrite($archivo_db, "# password: ".$password."\n");
        fwrite($archivo_db, "# db: ".$db."\n"); */
        
        fclose($archivo_db);            

        if (!file_exists("../mantenimiento/db.php")) {
            return json_encode(["status" => "error", "message" => "Error al crear el archivo db.php."]);
        }

        return json_encode(["status" => "ok", "message" => "Archivo db.php creado con éxito."]);
    }

    function installation($db_server, $db_user, $db_name, $db_pass){
        /*
        * Encriptación de las credenciales de la base de datos.
        * Se utiliza la función openssl_encrypt para encriptar el usuario, el nombre de la base de datos y el host.
        * La contraseña se encripta con password_hash.
        */

        $randKey = openssl_random_pseudo_bytes(32);
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $db_server = openssl_encrypt($db_server, 'aes-256-cbc', $randKey, 0, $iv);
        $db_user = openssl_encrypt($db_user, 'aes-256-cbc', $randKey, 0, $iv);
        $db_name = openssl_encrypt($db_name, 'aes-256-cbc', $randKey, 0, $iv);
        $db_pass = openssl_encrypt($db_pass, 'aes-256-cbc', $randKey, 0, $iv);

        file_exists("../mantenimiento/db.php") ? unlink("../mantenimiento/db.php") : null;
        //return json_encode(["status" => "error", "message" => "El archivo db.php ya existe."]);
        
        $msg = crearFicheroDB($db_server, $db_user, $db_name, $db_pass, $randKey, $iv);
        return $msg;

        }

    switch ($peticion){
        
        case 'comprobarConn':
            if ($db_server == null || $db_user == null || $db_name == null) {
                echo json_encode(["status" => "error", "message" => "Faltan datos."]);
            }

            $conn = new mysqli($db_server, $db_user, $db_pass, $db_name);
            if ($conn->connect_error) {
                echo json_encode(["status" => "error", "message" => "Error de conexión: " . $conn->connect_error]);
            } else {
                echo json_encode(["status" => "ok", "message" => "Conexión establecida con éxito."]);
            }
            break;

        case 'comprobarArchivoDb':
            if (file_exists("../mantenimiento/db.php")) {
                echo json_encode(["status" => "error", "message" => "El archivo db.php ya existe."]);
            } else {
                echo json_encode(["status" => "ok", "message" => "El archivo db.php no existe."]);
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

        case 'crearTablasDb':
            $db_server = $_POST['host'] ?? null;
            $db_user = $_POST['user'] ?? null;  
            $db_name = $_POST['db'] ?? null;
            $db_pass = $_POST['passwd'] ?? "";
            echo crearTablasDB($db_server, $db_user, $db_name, $db_pass);
            break;

        default:
            echo json_encode(["status" => "error", "message" => "Petición no reconocida: $peticion"]);
            break;
    }
