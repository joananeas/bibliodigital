<?php
    require_once "../mantenimiento/func_api.php";

    const db_file = "../mantenimiento/db.php";
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
    
        file_exists(db_file) ? unlink(db_file) : null;
        //return json_encode(["status" => "error", "message" => "El archivo db.php ya existe."]);
        
        $msg = crearFicheroDB($db_server, $db_user, $db_name, $db_pass, $randKey, $iv);
        return $msg;
    
    }

    function crearFicheroDB($db_server, $db_user, $db_name, $db_pass, $randKey, $iv){
        sleep(1);
        $archivo_db = fopen(db_file, "w");
        if (!$archivo_db) {
            // Si fopen devuelve false, no se pudo abrir el archivo
            return json_encode(["status" => "error", "message" => "No se pudo abrir el archivo para escritura. Verifique los permisos o la ruta del archivo."]);
        }
    
        // Si el archivo se abrió correctamente, procedemos a escribir en él
        $writes = [
            "<?php\n",
            "# © Joan Aneas\n",
            "# Este archivo fue generado automáticamente por el instalador de Bibliodigital.\n",
            "# NO MODIFICAR SI SE DESCONOCE EL FUNCIONAMIENTO.\n",
            "\n# IV, Key, cifrado openssl. \n",
            "define('DB_SERVER_IV', '".base64_encode($iv)."');\n",
            "define('DB_SERVER_KEY', '".base64_encode($randKey)."');\n",
            "# Constantes de la BBDD, si se modifican manualmente, luego no se podrán leer.\n",
            "# Luego se usa el decrypt, por lo que poner texto plano es mala idea 😅\n",
            "define('DB_HOST', '".$db_server."');\n",
            "define('DB_USER', '".$db_user."');\n",
            "define('DB_PASSWORD', '".$db_pass."');\n",
            "define('DB_NAME', '".$db_name."');\n",
            "\n# Sígueme en github ^^ (joananeas).\n",
            "# Si tienes alguna duda, puedes abrir un issue en el repositorio.\n",
            "\n\n/*\n",
            "*[-----------------------------------------------------------------------------------------------------------]\n",
            "*| IMPORTANTE: Si el archivo no se crea automáticamente, no se podrá acceder a la web. ----------------------|\n",
            "*| Si te encuentras en Linux: chown www-data:www-data <raiz_proyecto>/mantenimiento/* -----------------------|\n",
            "*| %%%%%%%%%%%%%%%%%%%%%%%%%% chmod 755 <raiz_proyecto>/mantenimiento/* -------------------------------------|\n",
            "*| Si te encuentras en Windows: Nidea jajajaj. Es broma, con xampp no deberías tener problemas, -------------|\n",
            "*| te recomiendo WLS o una máquina virtual con ubuntu server. -----------------------------------------------|\n",
            "*| pero si usas WAMP, deberás dar permisos a la carpeta mantenimiento. --------------------------------------|\n",
            "*L-----------------------------------------------------------------------------------------------------------]\n",
            "*/\n"
        ];
    
        foreach ($writes as $line) {
            fwrite($archivo_db, $line);
        }
    
        fclose($archivo_db);
    
        if (!file_exists(db_file)) {
            return json_encode(["status" => "error", "message" => "archivo-no-creado"]);
        }
    
        return json_encode(["status" => "ok", "message" => "archivo-creado"]);
    }

    function crearTablasDB(){
        $conn = peticionSQL();
        $sql = file_get_contents("../min-bibliodigital.sql");
        $conn->set_charset("utf8mb4"); # por el archivo

        if ($conn->connect_error) {
            return json_encode(["status" => "error", "message" => "Error de conexión: " . $conn->connect_error]);
        }

        
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
                return json_encode(["status" => "error", "message" => "tablas-no-creadas", "content" => $conn->error]);
            } else {
                return json_encode(["status" => "ok", "message" => "tablas-creadas"]);
            }
        } else {
            return json_encode(["status" => "error", "message" => "tablas-no-creadas", "content" => $conn->error]);
        }
    }

    function crearAdmin($admin, $adminPass){
        $conn = peticionSQL();

        // Verificar la conexión
        if ($conn->connect_error) {
            return json_encode(["status" => "error", "message" => "Error de conexión: " . $conn->connect_error]);
        }
    
        // Hashear la contraseña antes de guardarla en la base de datos
        $hashedPass = password_hash($adminPass, PASSWORD_DEFAULT);

        // Preparar la sentencia SQL para evitar inyecciones SQL
        $stmt = $conn->prepare("INSERT INTO `dib_usuaris` (`usuari`, `email`, `passwd`, `rol`, `estat`, `data_registre`, `experiencia`, `nivell`) 
        VALUES (NULL, ?, ?, 'admin', 'actiu', CURRENT_TIMESTAMP, '0', '0')");
    
        // Comprobar si la sentencia se preparó correctamente
        if (!$stmt) {
            return json_encode(["status" => "error", "message" => "Error al preparar la consulta: " . $conn->error]);
        }
    
        $stmt->bind_param("ss", $admin, $hashedPass);
    
        if ($stmt->execute()) {
            return json_encode(["status" => "ok", "message" => "admin-creado"]);
        } else {
            return json_encode(["status" => "error", "message" => "admin-no-creado: " . $stmt->error]);
        }
    }
    
    function config($nomBiblioteca, $titolWeb, $h1Web, $favicon, $colorPrincipal, $colorSecundario, $colorTerciario) {
        $conn = peticionSQL();
        
        if ($conn->connect_error) {
            return json_encode(["status" => "error", "message" => "Error de conexión: " . $conn->connect_error]);
        }
    
        // Procesar el archivo subido
        if (isset($_FILES['favicon'])) {
            $file = $_FILES['favicon'];
            $filename = $file['name'];
            $tempPath = $file['tmp_name'];
            $fileError = $file['error'];
    
            if ($fileError === 0) {
                $uploadPath = '../media/sistema/favicon/' . basename($filename);
                if (move_uploaded_file($tempPath, $uploadPath)) {
                    $favicon = $filename;  // Aquí se asigna el nuevo nombre de archivo para guardarlo en la base de datos
                } else {
                    return json_encode(["status" => "error", "message" => "Error al mover el archivo"]);
                }
            } else {
                return json_encode(["status" => "error", "message" => "Error en la carga del archivo"]);
            }
        } else {
            return json_encode(["status" => "error", "message" => "Archivo no proporcionado"]);
        }
    
        // Preparar la sentencia SQL
        $stmt = $conn->prepare("INSERT INTO dib_config (NOM_BIBLIOTECA, TITOL_WEB, H1_WEB, FAVICON, COLOR_PRINCIPAL, COLOR_SECUNDARIO, COLOR_TERCIARIO, BANNER_STATE, BANNER_TEXT) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        if (!$stmt) {
            return json_encode(["status" => "error", "message" => "Error al preparar la consulta: " . $conn->error]);
        }
    
        $bannerState = 0;
        $bannerText = "";
    
        $stmt->bind_param("sssssssis", $nomBiblioteca, $titolWeb, $h1Web, $favicon, $colorPrincipal, $colorSecundario, $colorTerciario, $bannerState, $bannerText);
        
        if ($stmt->execute()) {
            return json_encode(["status" => "ok", "message" => "config-ok"]);
        } else {
            return json_encode(["status" => "error", "message" => "error-config"]);
        }
    }
    

    function subirXls($files) {
        $conn = peticionSQL();

        if ($conn->connect_error) {
            return json_encode(["status" => "error", "content" => "Conexión fallida: " . $conn->connect_error]);
        }
    
        if (empty($files['name'])) {
            return json_encode(["status" => "error", "content" => "No hay archivos para subir."]);
        }
    
        $targetDir = '/var/www/html/temporal/';
        if (!is_dir($targetDir) && !mkdir($targetDir, 0777, true)) {
            return json_encode(["status" => "error", "content" => "Failed to create directory"]);
        }
        
        $count = 0;
        foreach ($files['name'] as $key => $filename) {
            $tmp_name = $files['tmp_name'][$key];
            if (!is_uploaded_file($tmp_name)) {
                return json_encode(["status" => "error", "message" => "subir-xls-invalid", "content" => "Archivo no válido: $filename"]);
            }
    
            $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if ($extension !== 'xls') {
                return json_encode(["status" => "error", "message" => "subir-xls-invalid", "content" => "Tipo de archivo no válido: $extension"]);
            }
    
            $nombreArchivoSinExtension = pathinfo($filename, PATHINFO_FILENAME);
            $rutaXls = $targetDir . $nombreArchivoSinExtension . ".xls";

            if (!move_uploaded_file($tmp_name, $rutaXls)) {
                $count++;
            }
        }

        if ($count > 0) {
            return json_encode(["status" => "error", "message" => "subir-xls-error", "content" => "Error al subir los archivos."]);
        }

        else {
            return json_encode(["status" => "ok", "message" => "subir-xls-ok"]);
        }
    }

    function xlsToCsv() {
        // Execute the Python script
        $command = 'python3 /var/www/html/install/converter.py 2>&1'; // Corrected the path
        $output = shell_exec($command);
    
        if (trim($output) !== "ok") {
            return json_encode(["status" => "error", "message" => "xls-to-csv-error", "content" => $output]);
        }

        else{
            return json_encode(["status" => "ok", "message" => "xls-to-csv-ok"]);
        }
    }

    function csvToDB() { 
        $conn = peticionSQL();

        $files['name'] = ['exemplars.csv', 'cataleg.csv']; 

        # Directorio con los .csv
        $targetDir = '/var/www/html/temporal/';

        $count = 0;
        foreach ($files['name'] as $key => $filename) {
            $nombreArchivoSinExtension = pathinfo($filename, PATHINFO_FILENAME);
            $rutaCsv = $targetDir . $nombreArchivoSinExtension . ".csv";
            
            if (!file_exists($rutaCsv)) {
                return json_encode(["status" => "error", "message" => "CSV file not found: $rutaCsv"]);
            }
            
            $tabla = "dib_" . $nombreArchivoSinExtension;
            // Perform the SQL operation
            $conn->options(MYSQLI_OPT_LOCAL_INFILE, true); // Enable LOCAL INFILE
            $sql = "LOAD DATA LOCAL INFILE '" . $rutaCsv . "' INTO TABLE `" . $tabla . "` 
                    FIELDS TERMINATED BY ','
                    ENCLOSED BY '\"'
                    LINES TERMINATED BY '\\n'
                    IGNORE 1 LINES";
            
            if (!$conn->query($sql)) {
                $count++;

            }
        }

        # Esto previene 2 mensajes de error.
        if ($count > 0) {
            return json_encode(["status" => "error", "message" => "csv-to-db-error", "content" => "Error al cargar los datos en la base de datos." . $conn->error]);
        }
    
        return json_encode(["status" => "ok", "message" => "csv-to-db-ok"]);
    }
    
