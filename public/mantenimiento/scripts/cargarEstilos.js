const estilosIndex = ["componentes.css", "paginas/index.css"];
const estilosPerfil = ["componentes.css", "paginas/perfil.css"];
const estilosLogin = ["componentes.css", "paginas/login.css"];
const estilosReservas = ["componentes.css", "paginas/reservas.css"];
const estilosLibro = ["componentes.css", "paginas/libro.css"];
const estilosError = ["componentes.css", "paginas/error.css"];
const estilosInstall = ["componentes.css", "instalacion.css"];
const estilosAdmin = ["componentes.css", "paginas/admin.css"];
const estilosXats = ["componentes.css", "paginas/xats.css"];

const url = window.location.href;

const cargarEstilos = (estilos) => {
    for (const element of estilos) {
        let linkEstilos = document.createElement("link");
        linkEstilos.rel = "stylesheet";
        linkEstilos.type = "text/css";
        if (url.includes("admin")) linkEstilos.href = "../estilos/" + element;
        else linkEstilos.href = "./estilos/" + element;
        document.head.appendChild(linkEstilos);
    }
}


switch (true) {
    case url.includes("index"):
        if (url.includes("admin")) cargarEstilos(estilosAdmin);
        else cargarEstilos(estilosIndex);

        console.log("indice");
        break;
    case url.includes("perfil"):
        cargarEstilos(estilosPerfil);
        console.log("perfil");
        break;
    case url.includes("login"):
        cargarEstilos(estilosLogin);
        console.log("login");
        break;
    case url.includes("reservas"):
        cargarEstilos(estilosReservas);
        console.log("reservas");
        break;

    case url.includes("error"):
        cargarEstilos(estilosError);
        console.log("error");
        break;

    case url.includes("install"):
        cargarEstilos(estilosInstall);
        console.log("install");
        break
    case url.includes("libro"):
        cargarEstilos(estilosLibro);
        console.log("libro");
        break;

    case url.includes("admin"):
    case url.includes("admin/index"):
        cargarEstilos(estilosAdmin);
        console.log("admin");
        break;

    case url.includes("xats"):
        cargarEstilos(estilosXats);
        console.log("xats");
        break;

    default:
        cargarEstilos(estilosIndex); // normalmente sale / en vez de /index.php
        break;
    // Agregar más casos según sea necesario
}
