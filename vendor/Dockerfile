# Using PHP 7.0 with Apache2 as base
FROM php:7.0-apache

# Set the author of the docker image
MAINTAINER Andreas Mueller <webmaster@am-wd.de>

# Copy all files into the wwwroot
COPY src/ /var/www/html/

# Additional contents
RUN mkdir -p /var/www/html/doku
ADD vendor/data/saniflyer2013.pdf /var/www/html/doku/saniflyer2013.pdf
ADD vendor/data/sanilogo2013.jpg /var/www/html/doku/sanilogo2013.jpg

# Add the configs
ADD vendor/config.php /var/www/html/php/config.php
RUN mkdir -p /var/www/html/sql
ADD vendor/#ssd.db /var/www/html/sql/#ssd.db

# Make Database writable
RUN chown -R www-data /var/www/html/sql
RUN chmod 0660 /var/www/html/sql/#ssd.db

# Update image to latest tools to prevent security issues
RUN apt-get -qq update
RUN apt-get -yqq upgrade
RUN apt-get -yqq install libmcrypt-dev
RUN docker-php-ext-install mcrypt

# Declare the port 80 as open (allow the host to bind to this port)
EXPOSE 80
