# Gebruik een officiële PHP-FPM-image als basis
FROM php:5.6-fpm

# Installeer de benodigde PHP-extensies
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN docker-php-ext-enable mysqli

# Kopieer de applicatiebestanden naar de container
COPY . /

# Stel de werkmap in voor PHP
WORKDIR /