FROM php:7.3-cli
RUN apt-get update -y && apt-get install -y openssl zip unzip git
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY . /usr/src/weatherapp
WORKDIR /usr/src/weatherapp
RUN [ "composer", "install" ]
