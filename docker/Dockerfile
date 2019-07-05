FROM devilbox/php-fpm:7.4-prod
LABEL maintainer="Ankit Pokhrel <hello@ankit.pl>"

ENV LANG C.UTF-8
ENV DEBIAN_FRONTEND noninteractive

RUN ln -sf /usr/share/zoneinfo/Asia/Kathmandu /etc/localtime
RUN rm -rf /var/lib/apt/lists/* && apt-get clean

RUN apt-get update --fix-missing && apt-get -y upgrade
RUN apt-get install -y software-properties-common build-essential

# Install dependencies.
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
                       unzip \
                       python3-pip && \
                       apt-get clean && \
                       rm -rf /var/lib/apt/lists/*

# Copy helper scripts.
COPY ./bin/* /usr/bin/app/
RUN chmod +x /usr/bin/app/*

# Install OpenCV4.
RUN /usr/bin/app/opencv.sh

# Install tensorflow.
RUN /usr/bin/app/tensorflow.sh

# Install composer.
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && chmod +x /usr/local/bin/composer

# Add configs.
ADD ./configs/www.conf /usr/local/etc/php-fpm.d/www.conf.default
ADD ./configs/php.ini /usr/local/etc/php/conf.d/99-custom.ini
ADD ./configs/nginx.conf /etc/nginx/nginx.conf
ADD ./configs/supervisord.conf /etc/supervisord.conf

RUN mkdir /var/run/php/

WORKDIR /var/www

EXPOSE 80
