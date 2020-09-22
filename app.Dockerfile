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
RUN ["composer", "install"]