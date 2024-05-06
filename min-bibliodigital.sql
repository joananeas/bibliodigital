SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET GLOBAL time_zone = '+01:00';
SET GLOBAL event_scheduler = ON;
SET GLOBAL TRANSACTION ISOLATION LEVEL READ COMMITTED;
SET SESSION TRANSACTION ISOLATION LEVEL READ COMMITTED;

START TRANSACTION;
SET foreign_key_checks = 0;
DROP TABLE IF EXISTS `dib_prestecs`, `dib_reserves`, `dib_expulsions`, `dib_exemplars`, `dib_cataleg`, `dib_config`, `dib_usuaris`, `dib_notificacions`, `dib_logs_errores`, `dib_estrelles`, `dib_valoracions`;
SET foreign_key_checks = 1;
COMMIT;

-- Creación de tablas
START TRANSACTION;
CREATE TABLE IF NOT EXISTS dib_usuaris (
  usuari INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255) NOT NULL UNIQUE,
  passwd VARCHAR(255) NOT NULL,
  rol ENUM('guest', 'user', 'bibliotecari', 'admin') NOT NULL,
  estat ENUM('actiu', 'inactiu', 'expulsat', 'expulsat-temp') NOT NULL,
  data_registre DATE,
  experiencia INT DEFAULT 0,
  nivell INT DEFAULT 1,
  INDEX (email)
);

CREATE TABLE dib_cataleg (
  ID_CATÀLEG INT,
  ID_BIBLIOTECA INT,
  NUMERO INT PRIMARY KEY,
  ISBN VARCHAR(20),
  CDU VARCHAR(10),
  FORMAT VARCHAR(50),
  TITOL VARCHAR(255),
  AUTOR VARCHAR(100),
  EDITORIAL VARCHAR(100),
  LLOC VARCHAR(100),
  COL·LECCIÓ VARCHAR(100),
  PAÍS VARCHAR(50),
  DATA FLOAT (7),
  LLENGUA VARCHAR(50),
  MATERIA VARCHAR(100),
  DESCRIPTOR VARCHAR(100),
  NIVELL VARCHAR(50),
  RESUM TEXT,
  URL TEXT,
  ADREÇA VARCHAR(255),
  DIMENSIÓ VARCHAR(50),
  VOLÚM VARCHAR(50),
  PÀGINES INT,
  PROC VARCHAR(50),
  CARC VARCHAR(50),
  CAMP_LLIURE VARCHAR(100),
  NPRES INT,
  REC VARCHAR(50),
  ESTAT VARCHAR(50)
);

CREATE TABLE dib_exemplars (
  IDENTIFICADOR INT,
  NUMERO_EXEMPLAR INT,
  SIGNATURA_EXEMPLAR VARCHAR(255),
  SITUACIO VARCHAR(50),
  ESTAT VARCHAR(50)
);

CREATE TABLE IF NOT EXISTS dib_reserves (
  reserva INT AUTO_INCREMENT PRIMARY KEY,
  exemplar_id INT NOT NULL,
  usuari_id INT NOT NULL,
  data_inici DATE,
  data_fi DATE DEFAULT DATE_ADD(CURRENT_DATE, INTERVAL 7 DAY),
  estat ENUM('finalitzada', 'en-curs', 'pendent') NOT NULL,
  prolongada BOOLEAN,
  motiu_prolongacio TEXT NULL,
  FOREIGN KEY (usuari_id) REFERENCES dib_usuaris(usuari)
);

CREATE TABLE IF NOT EXISTS dib_prestecs (
  id_prestec INT AUTO_INCREMENT PRIMARY KEY,
  exemplar_id INT NOT NULL,
  usuari_id INT NOT NULL,
  data_inici DATE NOT NULL,
  data_devolucio DATE NOT NULL,
  data_real_tornada DATE,
  estat ENUM('prestat', 'retornat', 'retardat') NOT NULL,
  comentaris TEXT,
  FOREIGN KEY (usuari_id) REFERENCES dib_usuaris(usuari)
);

CREATE TABLE IF NOT EXISTS dib_estrelles (
  id_estrella INT AUTO_INCREMENT PRIMARY KEY,
  usuari_id INT NOT NULL,
  puntuacio INT NOT NULL,
  exemplar_id INT NOT NULL,
  data_puntuacio DATE NULL,
  FOREIGN KEY (usuari_id) REFERENCES dib_usuaris(usuari)
);

CREATE TABLE IF NOT EXISTS dib_valoracions (
  id_comentari INT AUTO_INCREMENT PRIMARY KEY,
  usuari_id INT NOT NULL,
  exemplar_id INT NOT NULL,
  data_comentari DATE NULL,
  comentari TEXT NOT NULL,
  FOREIGN KEY (usuari_id) REFERENCES dib_usuaris(usuari)
);

CREATE TABLE IF NOT EXISTS dib_expulsions (
  id_expulsion INT AUTO_INCREMENT PRIMARY KEY,
  usuari_id INT NOT NULL,
  data_expulsion DATE NOT NULL,
  motiu TEXT NOT NULL,
  data_reincorporacio DATE,
  estat ENUM('expulsat', 'expulsat-temp', 'reincorporat') NOT NULL,
  FOREIGN KEY (usuari_id) REFERENCES dib_usuaris(usuari)
);

CREATE TABLE IF NOT EXISTS dib_config (
  CONFIG_ID INT AUTO_INCREMENT PRIMARY KEY,
  NOM_BIBLIOTECA VARCHAR(255),
  TITOL_WEB VARCHAR(255),
  H1_WEB VARCHAR(255),
  FAVICON VARCHAR(255),
  COLOR_PRINCIPAL CHAR(7),
  COLOR_SECUNDARIO CHAR(7),
  COLOR_TERCIARIO CHAR(7),
  BANNER_STATE BOOLEAN,
  BANNER_TEXT TEXT
);

CREATE TABLE IF NOT EXISTS dib_notificacions (
  id_notificacio INT AUTO_INCREMENT PRIMARY KEY,
  usuari_id INT NOT NULL,
  titol VARCHAR(255) NOT NULL,
  missatge TEXT NOT NULL,
  data_creada TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  data_llegida TIMESTAMP NULL,
  estat ENUM('pendent', 'llegida', 'descartada') DEFAULT 'pendent',
  tipus VARCHAR(50),
  FOREIGN KEY (usuari_id) REFERENCES dib_usuaris(usuari)
);

CREATE TABLE IF NOT EXISTS dib_logs_errores (
  id_error INT AUTO_INCREMENT PRIMARY KEY,
  fecha_error TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  descripcion_error TEXT,
  contexto_error VARCHAR(255)
);

COMMIT;

-- Creación de índices
START TRANSACTION;

CREATE INDEX idx_notificacions_usuari ON dib_notificacions(usuari_id);
CREATE INDEX idx_notificacions_estat ON dib_notificacions(estat);
CREATE INDEX idx_notificacions_data ON dib_notificacions(data_creada);

COMMIT;


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

CREATE TRIGGER trg_after_insert_prestec AFTER INSERT ON dib_prestecs FOR EACH ROW
BEGIN
    INSERT INTO dib_notificacions (usuari_id, titol, missatge, tipus)
    VALUES (NEW.usuari_id, 'Préstec Confirmado', CONCAT('Tu préstec con ID ', NEW.id_prestec, ' ha sido registrado con éxito.'), 'Préstec');
END;

CREATE EVENT IF NOT EXISTS eliminar_reservas_expirades ON SCHEDULE EVERY 1 DAY STARTS CURRENT_TIMESTAMP DO
    DELETE FROM dib_reserves WHERE DATE_ADD(fecha_columna, INTERVAL 30 DAY) < CURRENT_DATE;

CREATE EVENT IF NOT EXISTS eliminar_notificacions_expirades ON SCHEDULE EVERY 1 DAY STARTS CURRENT_TIMESTAMP DO
    DELETE FROM dib_notificacions WHERE DATE_ADD(fecha_columna, INTERVAL 30 DAY) < CURRENT_DATE;
COMMIT;