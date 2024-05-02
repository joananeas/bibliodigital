![Static Badge](https://img.shields.io/badge/diblio-4A68A0?style=flat) ![Static Badge](https://img.shields.io/badge/bibliodigital-4A68A0?style=flat) ![Build Status](https://img.shields.io/badge/build-passing-brightgreen) ![Version](https://img.shields.io/github/v/release/USER/REPO)


# Diblio 📚
Diblio és un Sistema Gestor de Biblioteques amb PHP i JS purs. Utilitza python per algunes eines. Està contenidoritzat per a una millor portabilitat.
És un projecte sense ànim de lucre i aporta millores per a la productivitat del bibliotecari/a.
Crea una comunitat de lectors amb un ambient i activitats saludables digitals 📖.

## Prerrequisits
- Docker 🐳.
- Python >= 3.10 🐍.
- [Opcional] Un servidor mariadb si es volen emmagatzemar les dades fora de docker ⛵.

 
##  Instal·lació
Primer de tot, cal inicialitzar un contenidor de docker amb les imatges corresponents, cal introduïr aquesta comanda al directorio arrel / del repositori.
```python
docker-compose up -d
```
Aquesta imatge conté: **apache2.4**, **php8.0** i **mariadb10.4**.

Si és el primer cop, sereu redireccionats a ```<ruta_arrel>/install```. Aquí haureu de connectar-vos a la base de dades, crear un compte d'administrador i configurar el lloc web.

### Instal·lació en un servidor / localhost
Durant la instal·lació, haureu de connectar-vos a la BBDD.

**A localhost**:  
hostname: db

**A un servidor**:  
hostname: localhost:3306

A un servidor si no es posa localhost, busca "db" a la xarxa pública, per tant, mai es connectarà, per això cal definir el port de mariadb.


## Consideracions
Aquest projecte **no ofereix cap garantia** i **no està permesa la redistribució per a ús comercial**. Més informació a la llicència del projecte.
