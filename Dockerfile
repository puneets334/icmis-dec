########################################################
# Use official PHP 8.2 with Apache
FROM php:8.2-apache AS app-php

# Expose Apache port
EXPOSE 80

# Set working directory
WORKDIR /var/www/html

# Update and install required packages
RUN apt-get update && apt-get install -y \
    apt-utils cron curl nano poppler-utils qpdf \
    libpq-dev libxml2-dev zlib1g-dev libzip-dev \
    libfreetype6-dev libjpeg62-turbo-dev \
    libonig-dev libapache2-mod-security2 \
    pdftk-java \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install -j$(nproc) zip intl gd mbstring
RUN docker-php-ext-configure pgsql --with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install mysqli pgsql pdo_mysql pdo_pgsql soap

# Configure PHP settings
RUN echo 'memory_limit = -1' > /usr/local/etc/php/conf.d/docker-php-memlimit.ini && \
    echo 'post_max_size = 12M' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini && \
    echo 'upload_max_filesize = 50M' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini

# Configure PHP security settings
RUN echo "shell_exec = On" >> /usr/local/etc/php/conf.d/custom.ini && \
    echo "disable_functions =" >> /usr/local/etc/php/conf.d/custom.ini

# Copy Apache configuration
COPY sysconf/docker/apache2/000-default.conf /etc/apache2/sites-available/
COPY sysconf/docker/apache2/ports.conf /etc/apache2/

# Enable Apache modules
RUN a2enmod rewrite headers proxy

# Copy application source code
COPY . /var/www/html/

# Setup directory permissions
RUN mkdir -p /var/www/html/uploaded_documents /mnt/icmis_doc/uploaded_documents && \
    chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html && \
    chmod -R 775 /var/www/html/writable /var/www/html/public/uploaded_documents /mnt/icmis_doc/uploaded_documents

# Remove any existing Git repo inside container
RUN rm -rf /var/www/html/.git/

# Set Apache user permissions
ENV APACHE_RUN_USER=www-data
ENV APACHE_RUN_GROUP=www-data

# Start Apache in foreground
CMD ["apache2-foreground"]



# Judge Appointment System
#Step 1:
# sudo docker build -t icmis_ci . sudo docker run --name=icmis_ci -dit --restart unless-stopped -p 82:80 -v $(pwd):/var/www/html/  icmis_ci4211:latest

# sudo docker run --name=icmis_ci -dit --restart unless-stopped -p 82:80 -v $(pwd):/var/www/html -v /mnt/icmis_doc/uploaded_documents:/var/www/html/uploaded_documents icmis_ci:latest
#Step 2:
# sudo docker run --name=icmis_ci4211 -dit --restart unless-stopped -p 90:80 -v $(pwd):/var/www/html/  icmis_ci4211:latest
# sudo docker run --name=icmis_ci4211 -dit --restart unless-stopped -p 92:80 -v $(pwd):/var/www/html/  icmis_ci4211:latest
#Step 3:
# sudo docker exec -it icmis_ci4211 /bin/bash
#Step 4:
#root@aedf3a0e087d:/var/www/html# chmod -R 777 ./writable
#root@aedf3a0e087d:/var/www/html# chmod -R 777 ./writable/session/
#root@aedf3a0e087d:/var/www/html# chmod -R 777 ./uploaded_documents
#Step 5:
#root@aedf3a0e087d:/var/www/html# ln -s /mnt/icmis_doc/uploaded_documents ~ ./uploaded_documents
