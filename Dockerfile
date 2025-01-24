# Usa la imagen oficial de PHP como base
FROM php:8.2-fpm

# Instala las dependencias necesarias
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev zip git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Configura el directorio de trabajo
WORKDIR /var/www

# Copia el archivo composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instala las dependencias de Laravel
COPY . .

RUN composer install

# Expone el puerto 9000
EXPOSE 9000

# Inicia PHP-FPM
CMD ["php-fpm"]

