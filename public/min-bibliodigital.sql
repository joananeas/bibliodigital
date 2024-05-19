SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
-- Configurar el fus horari global
SET GLOBAL time_zone = 'Europe/Madrid';

-- Configurar el fus horari per a la sessió actual
SET time_zone = 'Europe/Madrid';

-- Permetre events
SET GLOBAL event_scheduler = ON;

-- Configurar el nivell d'aïllament de les transaccions
SET GLOBAL TRANSACTION ISOLATION LEVEL READ COMMITTED;
SET SESSION TRANSACTION ISOLATION LEVEL READ COMMITTED;

START TRANSACTION;
SET foreign_key_checks = 0;
DROP TABLE IF EXISTS `dib_prestecs`, `dib_reserves`, `dib_expulsions`,
                     `dib_exemplars`, `dib_cataleg`, `dib_config`,
                     `dib_usuaris`, `dib_notificacions`, `dib_logs_errores`,
                     `dib_estrelles`, `dib_comentaris`, `dib_missatges`,
                     `dib_xats`, `dib_usuaris_xats`, `dib_reserves_estats`,
                     `dib_prestecs_estats`, `dib_notificacions_estats`;
SET foreign_key_checks = 1;
COMMIT;

-- Creación de tablas
START TRANSACTION;

CREATE TABLE IF NOT EXISTS dib_usuaris (
  usuari INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255) NOT NULL UNIQUE,
  nickname VARCHAR(255) NULL,
  passwd VARCHAR(255) NOT NULL,
  rol ENUM('guest', 'user', 'moderador', 'bibliotecari', 'admin') NOT NULL,
  estat ENUM('actiu', 'inactiu', 'expulsat', 'expulsat-temp') NOT NULL,
  data_registre DATE DEFAULT CURRENT_DATE,
  pfp VARCHAR(255) DEFAULT 'default.png',
  experiencia INT DEFAULT 0,
  nivell INT DEFAULT 1,
  INDEX (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Taula de xats, no particulars, sinó de grups
CREATE TABLE IF NOT EXISTS dib_xats (
  id_xat INT AUTO_INCREMENT PRIMARY KEY,
  nom_xat VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS dib_usuaris_xats (
  id_xat INT NOT NULL,
  usuari_id INT NOT NULL,
  FOREIGN KEY (id_xat) REFERENCES dib_xats(id_xat),
  FOREIGN KEY (usuari_id) REFERENCES dib_usuaris(usuari)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS dib_missatges (
  id_missatge INT AUTO_INCREMENT PRIMARY KEY,
  xat_id INT NOT NULL,
  usuari_id INT NOT NULL,
  data_enviament TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  missatge TEXT NOT NULL,
  FOREIGN KEY (xat_id) REFERENCES dib_xats(id_xat),
  FOREIGN KEY (usuari_id) REFERENCES dib_usuaris(usuari)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE dib_cataleg (
  ID_CATALEG INT,
  ID_BIBLIOTECA INT,
  NUMERO INT PRIMARY KEY,
  ISBN VARCHAR(20),
  CDU VARCHAR(10),
  FORMAT VARCHAR(50),
  TITOL VARCHAR(255),
  AUTOR VARCHAR(100),
  EDITORIAL VARCHAR(100),
  LLOC VARCHAR(100),
  COLLECCIO VARCHAR(100),
  PAIS VARCHAR(50),
  DATA FLOAT (7),
  LLENGUA VARCHAR(50),
  MATERIA VARCHAR(100),
  DESCRIPTOR VARCHAR(100),
  NIVELL VARCHAR(50),
  RESUM TEXT,
  URL TEXT,
  ADRECA VARCHAR(255),
  DIMENSIO VARCHAR(50),
  VOLUM VARCHAR(50),
  PAGINES INT,
  PROC VARCHAR(50),
  CARC VARCHAR(50),
  CAMP_LLIURE VARCHAR(100),
  NPRES INT,
  REC VARCHAR(50),
  ESTAT VARCHAR(50)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE dib_exemplars (
  IDENTIFICADOR INT,
  NUMERO_EXEMPLAR INT,
  SIGNATURA_EXEMPLAR VARCHAR(255),
  SITUACIO VARCHAR(50),
  ESTAT VARCHAR(50)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Estats reserves:
CREATE TABLE IF NOT EXISTS dib_reserves_estats (
  id_estat INT AUTO_INCREMENT PRIMARY KEY,
  estat VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Estats prestecs:
CREATE TABLE IF NOT EXISTS dib_prestecs_estats (
  id_estat INT AUTO_INCREMENT PRIMARY KEY,
  estat VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Estats notificacions:
CREATE TABLE IF NOT EXISTS dib_notificacions_estats (
  id_estat INT AUTO_INCREMENT PRIMARY KEY,
  estat VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS dib_reserves (
  reserva INT AUTO_INCREMENT PRIMARY KEY,
  exemplar_id INT NOT NULL,
  usuari_id INT NOT NULL,
  data_inici DATE,
  data_fi DATE DEFAULT DATE_ADD(CURRENT_DATE, INTERVAL 7 DAY),
  estat INT DEFAULT 1,
  prolongada BOOLEAN,
  motiu_prolongacio TEXT NULL,
  FOREIGN KEY (usuari_id) REFERENCES dib_usuaris(usuari),
  FOREIGN KEY (estat) REFERENCES dib_reserves_estats(id_estat)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS dib_prestecs (
  id_prestec INT AUTO_INCREMENT PRIMARY KEY,
  exemplar_id INT NOT NULL,
  exemplar_num INT NOT NULL,
  usuari_id INT NOT NULL,
  data_inici DATE NOT NULL,
  data_devolucio DATE NOT NULL,
  data_real_tornada DATE,
  estat INT DEFAULT 1,
  comentaris TEXT,
  FOREIGN KEY (usuari_id) REFERENCES dib_usuaris(usuari),
  FOREIGN KEY (estat) REFERENCES dib_prestecs_estats(id_estat)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS dib_estrelles (
  id_estrella INT AUTO_INCREMENT PRIMARY KEY,
  usuari_id INT NOT NULL,
  puntuacio INT NOT NULL,
  exemplar_id INT NOT NULL,
  data_puntuacio DATE NULL,
  FOREIGN KEY (usuari_id) REFERENCES dib_usuaris(usuari)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS dib_comentaris (
  id_comentari INT AUTO_INCREMENT PRIMARY KEY,
  usuari_id INT NOT NULL,
  exemplar_id INT NOT NULL,
  data_comentari DATE NULL,
  comentari TEXT NOT NULL,
  FOREIGN KEY (usuari_id) REFERENCES dib_usuaris(usuari)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS dib_expulsions (
  id_expulsion INT AUTO_INCREMENT PRIMARY KEY,
  usuari_id INT NOT NULL,
  data_expulsion DATE NOT NULL,
  motiu TEXT NOT NULL,
  data_reincorporacio DATE,
  estat ENUM('expulsat', 'expulsat-temp', 'reincorporat') NOT NULL,
  FOREIGN KEY (usuari_id) REFERENCES dib_usuaris(usuari)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS dib_config (
  CONFIG_ID INT AUTO_INCREMENT PRIMARY KEY,
  NOM_BIBLIOTECA VARCHAR(255) DEFAULT 'Diblio',
  TITOL_WEB VARCHAR(255) DEFAULT 'Diblio',
  H1_WEB VARCHAR(255) DEFAULT 'Diblio',
  FAVICON VARCHAR(255) DEFAULT 'favicon.ico',
  COLOR_PRINCIPAL CHAR(7),
  COLOR_SECUNDARIO CHAR(7),
  COLOR_TERCIARIO CHAR(7),
  BANNER_STATE BOOLEAN,
  BANNER_TEXT TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS dib_notificacions (
  id_notificacio INT AUTO_INCREMENT PRIMARY KEY,
  usuari_id INT NOT NULL,
  titol VARCHAR(255) NOT NULL,
  missatge TEXT NOT NULL,
  data_creada TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  data_llegida TIMESTAMP NULL,
  estat INT DEFAULT 1,
  tipus VARCHAR(50),
  FOREIGN KEY (usuari_id) REFERENCES dib_usuaris(usuari),
  FOREIGN KEY (estat) REFERENCES dib_notificacions_estats(id_estat)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS dib_logs_errores (
  id_error INT AUTO_INCREMENT PRIMARY KEY,
  fecha_error TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  descripcion_error TEXT,
  contexto_error VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMIT;

-- Creación de índices
START TRANSACTION;

CREATE INDEX idx_notificacions_usuari ON dib_notificacions(usuari_id);
CREATE INDEX idx_notificacions_estat ON dib_notificacions(estat);
CREATE INDEX idx_notificacions_data ON dib_notificacions(data_creada);

COMMIT;

-- Insert de estados:
START TRANSACTION;
INSERT INTO dib_reserves_estats (estat) VALUES ('pendent'), ('confirmada'), ('finalitzada'), ('cancel·lada');
INSERT INTO dib_prestecs_estats (estat) VALUES ('pendent'), ('confirmat'), ('finalitzat'), ('cancel·lat');
INSERT INTO dib_notificacions_estats (estat) VALUES ('pendent'), ('enviada'), ('llegida');

-- Triggers y eventos
START TRANSACTION;

CREATE TRIGGER trg_after_insert_reserva AFTER INSERT ON dib_reserves FOR EACH ROW
BEGIN
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION
    BEGIN
        INSERT INTO dib_logs_errores (descripcion_error, contexto_error)
        VALUES ('Error al insertar notificación de reserva', CONCAT('Reserva ID: ', NEW.reserva));
    END;

    INSERT INTO dib_notificacions (usuari_id, titol, missatge, tipus) 
    VALUES (NEW.usuari_id, 'Reserva Confirmada', CONCAT('Tu reserva con ID ', NEW.reserva, ' ha sido registrada con éxito.'), 'Reserva');
END;

CREATE TRIGGER noti_user_after_ask_prestec 
AFTER INSERT ON dib_prestecs 
FOR EACH ROW
BEGIN
    INSERT INTO dib_notificacions (usuari_id, titol, missatge, tipus)
    VALUES (NEW.usuari_id, 'Préstec Pendent', 'Préstec a la espera del/la bibliotecari/a.', 'Préstec (usuari)');
END;

CREATE TRIGGER noti_bibliotecari_after_ask_prestec 
AFTER INSERT ON dib_prestecs 
FOR EACH ROW
BEGIN
    INSERT INTO dib_notificacions (usuari_id, titol, missatge, tipus)
    SELECT usuari, 'Préstec Confirmat', CONCAT('Préstec con ID: ', NEW.id_prestec, ' pendiente de confirmación.'), 'Préstec (bibliotecari)'
    FROM dib_usuaris
    WHERE rol = 'bibliotecari';
END;


CREATE EVENT IF NOT EXISTS eliminar_reservas_expirades ON SCHEDULE EVERY 1 DAY STARTS CURRENT_TIMESTAMP DO
    DELETE FROM dib_reserves WHERE DATE_ADD(fecha_columna, INTERVAL 30 DAY) < CURRENT_DATE;

CREATE EVENT IF NOT EXISTS canviar_estat_reserves_finalitzades ON SCHEDULE EVERY 1 DAY STARTS CURRENT_TIMESTAMP DO
    UPDATE dib_reserves SET estat = 'finalitzada', motiu_prolongacio = CURRENT_DATE WHERE data_fi < CURRENT_DATE;

CREATE EVENT IF NOT EXISTS eliminar_notificacions_expirades ON SCHEDULE EVERY 1 DAY STARTS CURRENT_TIMESTAMP DO
    DELETE FROM dib_notificacions WHERE DATE_ADD(fecha_columna, INTERVAL 30 DAY) < CURRENT_DATE;
  
CREATE EVENT IF NOT EXISTS esborrar_missatges_antics ON SCHEDULE EVERY 1 DAY DO
  DELETE FROM dib_missatges  WHERE data_enviament < NOW() - INTERVAL 30 DAY;

COMMIT;