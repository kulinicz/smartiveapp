FROM php:8.3-cli

# Install dependencies
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libpng-dev \
    libwebp-dev \
    libpq-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libwebp-dev \
    libxml2-dev \
    curl \
    libzip-dev \
    libicu-dev \
    libxslt1-dev \
    && docker-php-ext-install -j$(nproc) mbstring exif pcntl bcmath intl zip xsl opcache
RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg --with-webp
RUN docker-php-ext-install -j$(nproc) gd


# Install Composer
COPY --from=composer:2.1 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy existing application directory contents
COPY . /var/www/html

# Install PHP dependencies
#RUN composer install --no-interaction --optimize-autoloader

# Expose port for debugging (optional)
EXPOSE 9000

# Command to run when the container starts
#CMD ["php", "bin/console", "messenger:consume", "async"]
CMD ["tail", "-f", "/dev/null"]
