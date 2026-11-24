
FROM php:8.3-apache

# Instala extensões necessárias para conexão com MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Habilita módulos do Apache
RUN a2enmod rewrite

# Copia o Composer para o container
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Configura o diretório público da aplicação
COPY apache-vhost.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

EXPOSE 80
