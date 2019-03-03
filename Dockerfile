FROM debian:stretch-slim

RUN apt-get update && apt-get install -y \
    apache2 \
    php \
 && rm -rf /var/lib/apt/lists/* \
 && a2dissite '*'

# RUN apt-get update && apt-get install -y \
#     curl \
#     php-mbstring \
#     php-intl \
#     php-pear \
#     php-dom \
#     php-xml \
#     php-json \
#     php-pdo \
#     php-gd \
#     php-imagick \
#     php-zip \
#     php-pdo-pgsql \
#     php-pdo-mysql \
#     php-pgsql \
#     php-mysql \
#     php-auth-sasl \
#     php-curl \
#  && rm -rf /var/lib/apt/lists/*

RUN a2enmod \
    # alias \
    # auth_basic \
    # expires \
    # headers \
    rewrite \
    # vhost_alias \
 && a2disconf \
    other-vhosts-access-log

COPY site.conf /etc/apache2/sites-enabled/
COPY *.html *.php /var/www/html/
COPY css /var/www/html/css
COPY font /var/www/html/font
COPY js /var/www/html/js

RUN echo "Listen 8080" > /etc/apache2/ports.conf

EXPOSE 8080
USER 33:33

CMD exec /usr/sbin/apache2ctl -D FOREGROUND
