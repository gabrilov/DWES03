FROM php:8.2.25-apache

# Instalar las extensiones necesarias para PHP y MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable mysqli pdo pdo_mysql

# Limpiar cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
