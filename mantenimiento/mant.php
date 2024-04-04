<?php
    # © Joan Aneas

    /*
    *  El 'config.php' de Bibliodigital.
    *  Este archivo contiene las variables globales que se utilizan en todo el sistema.
    *  Estas constantes se modifican en /admin, y sobreescriben el fichero.
    *  IMPORTANTE: No modificar este archivo si se desconoce su funcionamiento.
    */

    $root = realpath(dirname(__FILE__));
    $rootPath = str_replace('mantenimiento', "", $root); # Hay que bajar un directorio (estamos en mantenimiento/)
    $mediaPath = $rootPath . '/media/';
    $adminPath = $rootPath . '/admin/';
    $stylesPath = $rootPath . '/styles/';
    $jsPath = $rootPath . '/mantenimineto/scripts/';
    $apiPath = $rootPath . '/mantenimineto/';
    $dynaPath = $rootPath . '/dynamo/';
    
    $GLOBALS['paths'] = [
        'root' => $rootPath,
        'media' => $mediaPath,
        'admin' => $adminPath,
        'styles' => $stylesPath,
        'js' => $jsPath,
        'api' => $apiPath,
        'dyna' => $dynaPath
    ];

    # Comprueba si se ha instalado el sistema:
    /*
    * db.php [OK]
    * mant.php [OK]
    * conexión a BBDD [OK] (realmente esta no se comprueba aquí, sino en api.php)
    */
    const INSTALLED = false; # true | false 

    # Normalmente es el nombre institucional de la biblioteca / centro educativo
    const NOM_BIBLIOTECA = 'vedruna vall';

    # <title> En el HTML del header dinámico (título de la pestaña del navegador)
    const TITOL_WEB = 'Biblio Digital'; 
    # <h1> En el HTML del header dinámico (título de la página)
    const H1_WEB = 'Biblio Digital';

    # Favicon de la web (ruta relativa).
    /*
    * Se supone que se va a guardar siempre en media/sistema/.
    * NO se recomienda cambiar la ruta, solo el nombre.
    * Se recomienda que sea un SVG,ICO o PNG.
    */
    const FAVICON = './media/sistema/favicon.svg';
