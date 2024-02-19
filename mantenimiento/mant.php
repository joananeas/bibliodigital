<?php
    # © Joan Aneas

    /*
    *  El 'config.php' de Bibliodigital.
    *  Este archivo contiene las variables globales que se utilizan en todo el sistema.
    *  Estas constantes se modifican en /admin, y sobreescriben el fichero.
    *  IMPORTANTE: No modificar este archivo si se desconoce su funcionamiento.
    */

    # Comprueba si se ha instalado el sistema:
    /*
    * db.php [OK]
    * mant.php [OK]
    * conexión a BBDD [OK] (realmente esta no se comprueba aquí, sino en api.php)
    */
    define('INSTALLED', false); # true | false 

    # Normalmente es el nombre institucional de la biblioteca / centro educativo
    define('NOM_BIBLIOTECA', 'vedruna vall');

    # <title> En el HTML del header dinámico (título de la pestaña del navegador)
    define('TITOL_WEB', 'Biblio Digital'); 
    # <h1> En el HTML del header dinámico (título de la página)
    define('H1_WEB', 'Biblio Digital');

    # Favicon de la web (ruta relativa).
    /*
    * Se supone que se va a guardar siempre en media/sistema/.
    * NO se recomienda cambiar la ruta, solo el nombre.
    * Se recomienda que sea un SVG,ICO o PNG.
    */
    define('FAVICON', './media/sistema/favicon.svg');
    # Versión del core.
    define('VERSION', 'v1.2.8'); # Commit: Instalación 60% (creación de db.php, falta mant.php)
