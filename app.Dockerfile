FROM php:7.4

# Install dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get update
RUN apt-get install -y libzip-dev zip && docker-php-ext-install zip mysqli pdo pdo_mysql

# Add the application
ADD ./src /srv

# Change cwd
WORKDIR /srv

# Setup the app
RUN ["rm", "composer.lock"]
RUN ["composer", "install"]

#EXPOSE 8000
#CMD ["php", "artisan", "serve", "--host=0.0.0.0"]
