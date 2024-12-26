# Dockerfile to run a Laravel app
FROM php:8.3

# Update and install packages
RUN apt-get update -y && apt-get install -y openssl zip unzip git libpq-dev libxml2-dev libzip-dev libfreetype-dev libgd-dev

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP extensions, required for postgres as well
RUN docker-php-ext-install pdo_pgsql exif pcntl bcmath

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . /var/www

# Install composer dependencies
RUN composer install --optimize-autoloader --no-dev --no-interaction

# Copy .env.example to .env
RUN cp .env.example .env

# Generate the application key
RUN php artisan key:generate

# Create a symbolic link
RUN php artisan storage:link

# Expose port 8000 and start php server
EXPOSE 8000

# Start the application
CMD php artisan migrate && php artisan serve --host=0.0.0.0 --port=8000
