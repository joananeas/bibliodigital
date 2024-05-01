# Usar la imagen base oficial de PHP 8.0 con Apache
FROM php:8.0-apache

# Actualizar los paquetes del sistema y limpiar caché de APT
RUN apt-get update && apt-get upgrade -y \
    && apt-get autoremove -y && apt-get clean

# Instalar dependencias necesarias para las extensiones de PHP
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libwebp-dev \
    libxpm-dev \
    && rm -rf /var/lib/apt/lists/*

# Configurar y instalar la extensión GD con soporte para varios formatos
RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
    --with-webp \
    --with-xpm \
    && docker-php-ext-install gd

# Instalar otras extensiones de PHP
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Habilitar Apache mod_rewrite
RUN a2enmod rewrite

# Copiar el contenido del directorio del host al contenedor
COPY . /var/www/html/

# Configurar los permisos adecuados para el directorio raíz del servidor web
RUN chown -R www-data:www-data /var/www/html

# Establecer el búfer de salida
RUN echo 'output_buffering = On' >> /usr/local/etc/php/conf.d/docker-php-output-buffering.ini

# Exponer el puerto 80 para el servidor web
EXPOSE 80

# Por defecto, Apache se ejecutará en primer plano. Puedes especificar un comando diferente si es necesario.
CMD ["apache2-foreground"]
