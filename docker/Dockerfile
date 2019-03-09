FROM ubuntu:18.04
LABEL maintainer="Ankit Pokhrel <hello@ankit.pl>"

ENV LANG C.UTF-8
ENV DEBIAN_FRONTEND noninteractive

RUN ln -sf /usr/share/zoneinfo/Asia/Kathmandu /etc/localtime
RUN rm -rf /var/lib/apt/lists/* && apt-get clean

RUN apt-get update --fix-missing && apt-get -y upgrade
RUN apt-get install -y software-properties-common build-essential
RUN add-apt-repository -y ppa:ondrej/php
RUN apt-get update --fix-missing

# Install php, nginx and other dependencies
RUN apt-get -y install rsyslog \
                       rsyslog-gnutls \
                       supervisor \
                       nginx \
                       curl \
                       wget \
                       git \
                       pkg-config \
                       cmake \
                       qt5-default \
                       vim \
                       python3-pip \
                       php7.1-fpm \
                       php7.1-cli \
                       php7.1-dev \
                       php7.1-gd \
                       php7.1-imap \
                       php7.1-intl \
                       php7.1-json \
                       php7.1-mcrypt \
                       php7.1-mbstring \
                       php7.1-ldap \
                       php7.1-zip \
                       php7.1-xml \
                       php-xdebug \
                       php7.1-mysql \
                       php7.1-soap \
                       php7.1-curl && \
                       apt-get clean && \
                       rm -rf /var/lib/apt/lists/*

# Copy helper scripts.
COPY ./bin/* /usr/bin/app/
RUN chmod +x /usr/bin/app/*

# Install OpenCV4.
RUN /usr/bin/app/opencv.sh

# Install PHP OpenCV.
RUN /usr/bin/app/php-opencv.sh

# Install composer.
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && chmod +x /usr/local/bin/composer

# Add configs.
ADD ./configs/www.conf /etc/php/7.1/fpm/pool.d/www.conf
ADD ./configs/php.ini /etc/php/7.1/fpm/conf.d/99-custom.ini
ADD ./configs/php.ini /etc/php/7.1/cli/conf.d/99-custom.ini
ADD ./configs/nginx.conf /etc/nginx/nginx.conf
ADD ./configs/supervisord.conf /etc/supervisord.conf

RUN mkdir /var/run/php/

WORKDIR /var/www

EXPOSE 80
