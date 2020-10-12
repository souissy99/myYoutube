FROM debian:buster

RUN apt-get update -y && apt-get install -y php php-cli php-mbstring php-curl php-dev php-gd php-pear php-imap php-json php-mysql php-xml php-xsl php-zip git unzip
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN composer global require laravel/installer
COPY ./ /app
WORKDIR /app
RUN composer install
RUN php artisan key:generate
RUN php artisan jwt:secret
RUN chmod -R 777 public/video
RUN sed -i "s/upload_max_filesize = .*/upload_max_filesize = 2G/" /etc/php/7.3/cli/php.ini
RUN sed -i "s/post_max_size = .*/post_max_size = 3G/" /etc/php/7.3/cli/php.ini
