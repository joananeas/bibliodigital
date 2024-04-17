<?php
    # © Joan Aneas
    require '../mantenimiento/vendor/autoload.php';
    use PhpOffice\PhpSpreadsheet\IOFactory;
    use PhpOffice\PhpSpreadsheet\Writer\Csv;
    
    if(isset($_POST['peticion'])) $peticion = $_POST['peticion'];
    else if(isset($_GET['peticion'])) $peticion = $_GET['peticion'];  
    
    $db_server = $_POST['host'] ?? null;
    $db_user = $_POST['user'] ?? null;  
    $db_name = $_POST['db'] ?? null;
    $db_pass = $_POST['passwd'] ?? ""; # Si la contraseña está vacía
    # se conectará sin contraseña (mala práctica, pero para pruebas está bien).

    /*if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {
        // Redirigir al usuario a otra página o mostrar un error
        header('Location: ../error.php?error=404');
        exit;
    }*/

    function crearFicheroMant() {
        sleep(1);
        $archivo_mant = fopen("../mantenimiento/mant.php", "w");
        fwrite($archivo_mant, "<?php\n");
        fwrite($archivo_mant, "    # © Joan Aneas\n\n");
        fwrite($archivo_mant, "    /*\n");
        fwrite($archivo_mant, "    *  El 'config.php' de Bibliodigital.\n");
        fwrite($archivo_mant, "    *  Este archivo contiene las variables globales que se utilizan en todo el sistema.\n");
        fwrite($archivo_mant, "    *  Estas constantes se modifican en /admin, y sobreescriben el fichero.\n");
        fwrite($archivo_mant, "    *  IMPORTANTE: No modificar este archivo si se desconoce su funcionamiento.\n");
        fwrite($archivo_mant, "    */\n\n");
        fwrite($archivo_mant, "    # Comprueba si se ha instalado el sistema:\n");
        fwrite($archivo_mant, "    /*\n");
        fwrite($archivo_mant, "    * db.php [OK]\n");
        fwrite($archivo_mant, "    * mant.php [OK]\n");
        fwrite($archivo_mant, "    * conexión a BBDD [OK] (realmente esta no se comprueba aquí, sino en api.php)\n");
        fwrite($archivo_mant, "    */\n");
        fwrite($archivo_mant, "    define('INSTALLED', false); # true | false\n\n");
        fwrite($archivo_mant, "    define('NOM_BIBLIOTECA', 'vedruna vall');\n");
        fwrite($archivo_mant, "    define('TITOL_WEB', 'Biblio Digital');\n");
        fwrite($archivo_mant, "    define('H1_WEB', 'Biblio Digital');\n");
        fwrite($archivo_mant, "    define('FAVICON', './media/sistema/favicon.svg');\n");
    
        // Cierra el archivo y comprueba si se ha creado correctamente
        fclose($archivo_mant);            
    
        if (!file_exists("../mantenimiento/mant.php")) {
            return json_encode(["status" => "error", "message" => "Error al crear el archivo mant.php."]);
        }
    
        return json_encode(["status" => "ok", "message" => "Archivo mant.php creado con éxito."]);
    }
    
    function convertirXlsACsv($archivoEntrada, $archivoSalida) {
        // Cargar el archivo .xls
        $spreadsheet = IOFactory::load($archivoEntrada);
    
        // Crear un escritor de tipo CSV
        $writer = new Csv($spreadsheet);
    
        // Configurar delimitador a coma
        $writer->setDelimiter(',');  // Establecer el delimitador a coma
        $writer->setEnclosure('"');  // Establecer el tipo de delimitador de texto
        $writer->setLineEnding("\r\n");
        $writer->setSheetIndex(0);   // Escribir la primera hoja del archivo .xls
    
        // Guardar archivo .csv
        $writer->save($archivoSalida);
        return file_exists($archivoSalida) ? $archivoSalida : false;
    }
    
    function moverYConvertirArchivo($archivoEntrada, $nombreArchivo, $targetDir) {
        $pathInfo = pathinfo($nombreArchivo);
        $baseName = $pathInfo['filename']; // Obtener el nombre sin extensión
        $destPath = $targetDir . '/' . $baseName . '.xls'; // Agregar la extensión .xls al archivo de destino
    
        // Intenta mover el archivo al directorio temporal
        if (move_uploaded_file($archivoEntrada, $destPath)) {
            // Define el path de salida para el archivo CSV
            $outputPath = $targetDir . '/' . $baseName . '.csv'; // Agregar la extensión .csv al archivo de salida
            if (convertirXlsACsv($destPath, $outputPath)) {
                return $baseName; // Retorna el nombre base del archivo convertido
            }
        }
        return false; // Retorna false si falla
    }
    
    function subirXlsx($files, $db_server, $db_user, $db_name, $db_pass) {    
        $conn = new mysqli($db_server, $db_user, $db_pass, $db_name);
    
        // Verificar la conexión
        if ($conn->connect_error) {
            die(json_encode(["status" => "error", "message" => "Conexión fallida: " . $conn->connect_error]));
        }
    
        foreach ($files['name'] as $key => $filename) {
            $targetDir = '../tmp'; // Asegúrate de que este directorio existe o crea uno si es necesario
            
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            
            // Mover y convertir el archivo
            $nombreArchivoSinExtension = moverYConvertirArchivo($files['tmp_name'][$key], $filename, $targetDir);
            if (!$nombreArchivoSinExtension) {
                $conn->close();
                return json_encode(["status" => "error", "message" => "Error al mover y convertir el archivo"]);
            }
    
            // Preparar la ruta al archivo CSV
            $rutaCsv = $targetDir . '/' . $nombreArchivoSinExtension . ".csv";
            $tabla = "dib_" . $nombreArchivoSinExtension;
            
            // Preparar la consulta SQL para cargar los datos desde el archivo CSV
            $sql = "LOAD DATA LOCAL INFILE '".$conn->real_escape_string($rutaCsv)."'
                    INTO TABLE ". $tabla ."
                    FIELDS TERMINATED BY ',' 
                    ENCLOSED BY '\"'
                    LINES TERMINATED BY '\n'
                    IGNORE 1 ROWS;";
    
            // Ejecutar la consulta
            if (!$conn->query($sql)) {
                $conn->close();
                return json_encode(["status" => "error", "message" => "Error al subir datos: " . $conn->error]);
            }
        }
    
        $conn->close();
        return json_encode(["status" => "ok", "message" => "Archivos subidos con éxito"]);
    }

    function config($db_server, $db_user, $db_name, $db_pass, $nomBiblioteca, $titolWeb, $h1Web, $favicon, $colorPrincipal, $colorSecundario, $colorTerciario) {
        // Crear una conexión a la base de datos
        $conn = new mysqli($db_server, $db_user, $db_pass, $db_name);
        // Verificar si la conexión fue exitosa
        if ($conn->connect_error) {
            return json_encode(["status" => "error", "message" => "Error de conexión: " . $conn->connect_error]);
        }
    
        // Preparar la sentencia SQL
        $stmt = $conn->prepare("INSERT INTO dib_config (INSTALLED, NOM_BIBLIOTECA, TITOL_WEB, H1_WEB, FAVICON, COLOR_PRINCIPAL, COLOR_SECUNDARIO, COLOR_TERCIARIO, BANNER_STATE, BANNER_TEXT) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
        // Verificar si la sentencia se preparó correctamente
        if (!$stmt) {
            return json_encode(["status" => "error", "message" => "Error al preparar la consulta: " . $conn->error]);
        }

        # Harcodeamos el valor de INSTALLED a 1 para que no se pueda instalar de nuevo.
        # El banner y el texto se dejan vacíos (se configura posteriormente).
        $i = 1;
        $b = 0;
        $bT = "";
        // Vincular los parámetros a la sentencia SQL
        $stmt->bind_param("isssssssis", $i, $nomBiblioteca, $titolWeb, $h1Web, $favicon, $colorPrincipal, $colorSecundario, $colorTerciario, $b, $bT);
    
        // Ejecutar la sentencia
        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            return json_encode(["status" => "ok", "message" => "config-ok"]);
        } else {
            $stmt->close();
            $conn->close();
            return json_encode(["status" => "error", "message" => "error-config"]);
        }
    }

    function crearAdmin($db_server, $db_user, $db_name, $db_pass, $admin, $adminPass){
        $conn = new mysqli($db_server, $db_user, $db_pass, $db_name);
    
        // Verificar la conexión
        if ($conn->connect_error) {
            return json_encode(["status" => "error", "message" => "Error de conexión: " . $conn->connect_error]);
        }
    
        // Hashear la contraseña antes de guardarla en la base de datos
        $hashedPass = password_hash($adminPass, PASSWORD_DEFAULT);

        // Preparar la sentencia SQL para evitar inyecciones SQL
        $stmt = $conn->prepare("INSERT INTO `dib_usuaris` (`email`, `passwd`, `rol`) VALUES (?, ?, 'admin')");
    
        // Comprobar si la sentencia se preparó correctamente
        if (!$stmt) {
            return json_encode(["status" => "error", "message" => "Error al preparar la consulta: " . $conn->error]);
        }
    
        $stmt->bind_param("ss", $admin, $hashedPass);
    
        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            return json_encode(["status" => "ok", "message" => "admin-creado"]);
        } else {
            $stmt->close();
            $conn->close();
            return json_encode(["status" => "error", "message" => "admin-no-creado: " . $stmt->error]);
        }
    }
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
                return json_encode(["status" => "error", "message" => "tablas-no-creadas". $conn->error]);
            } else {
                return json_encode(["status" => "ok", "message" => "tablas-creadas"]);
            }
        } else {
            return json_encode(["status" => "ok", "message" => "tablas-no-creadas"]);
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
            return json_encode(["status" => "error", "message" => "archivo-no-creado"]);
        }

        return json_encode(["status" => "ok", "message" => "archivo-creado"]);
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
                echo json_encode(["status" => "error", "message" => "conex-bad" . $conn->connect_error]);
            } else {
                echo json_encode(["status" => "ok", "message" => "conex-ok"]);
            }

            break;

        case 'comprobarArchivos':
            # Comprueba si existen los archivos necesarios para la instalación.
            if (file_exists("../mantenimiento/mant.php") && file_exists("../mantenimiento/db.php")) {
                echo json_encode(["status" => "ok", "message" => "existen-2"]);
            } else if (file_exists("../mantenimiento/mant.php")) {
                echo json_encode(["status" => "ok", "message" => "existe-mant"]);
            } else if (file_exists("../mantenimiento/db.php")) {
                echo json_encode(["status" => "ok", "message" => "existe-db"]);
            } else {
                echo json_encode(["status" => "error", "message" => "no-existen"]);
            }
            break;

        case 'instalacion-db':
            $db_server = $_POST['host'] ?? null;
            $db_user = $_POST['user'] ?? null;  
            $db_name = $_POST['db'] ?? null;
            $db_pass = $_POST['passwd'] ?? "";

            $msg = installation($db_server, $db_user, $db_name, $db_pass);
            echo $msg;
            break;

        case 'instalacion-tablas':
            $db_server = $_POST['host'] ?? null;
            $db_user = $_POST['user'] ?? null;  
            $db_name = $_POST['db'] ?? null;
            $db_pass = $_POST['passwd'] ?? "";
            echo crearTablasDB($db_server, $db_user, $db_name, $db_pass);
            break;
        
        case 'creacion-admin':
            $admin = $_POST['admin'] ?? null;
            $adminPass = $_POST['adminPass'] ?? null;

            $db_server = $_POST['host'] ?? null;
            $db_user = $_POST['user'] ?? null;  
            $db_name = $_POST['db'] ?? null;
            $db_pass = $_POST['passwd'] ?? "";

            echo crearAdmin($db_server, $db_user, $db_name, $db_pass, $admin, $adminPass);
            break;
        
        case 'config':
            $db_server = $_POST['host'] ?? null;
            $db_user = $_POST['user'] ?? null;  
            $db_name = $_POST['db'] ?? null;
            $db_pass = $_POST['passwd'] ?? "";
            
            $nomBiblioteca = $_POST['nomBiblioteca'] ?? null;
            $titolWeb = $_POST['titolWeb'] ?? null;
            $h1Web = $_POST['h1Web'] ?? null;
            $favicon = $_POST['favicon'] ?? null;
            $colorPrincipal = $_POST['colorPrincipal'] ?? null;
            $colorSecundario = $_POST['colorSecundario'] ?? null;
            $colorTerciario = $_POST['colorTerciario'] ?? null;
        
            // Llamar a la función config con todos los parámetros
            echo config($db_server, $db_user, $db_name, $db_pass, $nomBiblioteca, $titolWeb, $h1Web, $favicon, $colorPrincipal, $colorSecundario, $colorTerciario);
            break;
        
        case 'subir-xlsx':
            $db_server = $_POST['host'] ?? null;
            $db_user = $_POST['user'] ?? null;  
            $db_name = $_POST['db'] ?? null;
            $db_pass = $_POST['passwd'] ?? "";

            echo subirXlsx($_FILES['uploads'], $db_server, $db_user, $db_name, $db_pass);
            break;
        default:
            echo json_encode(["status" => "error", "message" => "Peticion no reconocida: $peticion"]);
            break;
  

    }  
exit;
