FROM alpine:3.6

MAINTAINER Jesse Boyer

ENV PHALCON_VERSION=3.2.1

RUN apk update && apk upgrade && \
    apk add \
    php7 \
    php7-ctype \
    php7-curl \
    php7-dom \
    php7-exif \
    php7-fileinfo \
    php7-fpm \
    php7-gd \
    php7-gettext \
    php7-iconv \
    php7-imap \
    php7-intl \
    php7-json \
    php7-mbstring \
    php7-mcrypt \
    php7-mysqlnd \
    php7-opcache \
    php7-openssl \
    php7-pdo \
    php7-pdo_mysql \
    php7-phar \
    php7-posix \
    php7-session \
    php7-simplexml \
    php7-sockets \
    php7-tidy \
    php7-tokenizer \
    php7-xml \
    php7-zip \

    # Common
    curl \

    # Composer
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer \

    && rm -rf /tmp/* \
    && rm -rf /var/cache/apk/* \

    # Install dev dependencies
    && apk --update add --virtual build-dependencies \
        autoconf \
        g++ \
        file \
        re2c \
        make \
        git \
        gnupg \
        net-tools \
        sudo \
        tar \
        xz \

    # Install latest Phalcon
    && curl -sS -o /tmp/phalcon.tar.gz https://codeload.github.com/phalcon/cphalcon/tar.gz/v$PHALCON_VERSION \
    && cd /tmp/ \
    && tar xvzf phalcon.tar.gz \
    && cd cphalcon-$PHALCON_VERSION/build \
    && sh install \
    && echo "extension=phalcon.so" > /etc/php7/conf.d/50-phalcon.ini \

    # Cleanup
    && apk del build-dependencies \
    && rm -rf cphalcon-$PHALCON_VERSION \
        v3.2.1 \
        /var/cache/apk/* \
        /tmp/* \
        /var/tmp/* \

    # Remove unneeded envirnment vars
    && unset PHALCON_VERSION \
    # Remove unused config
    && rm -rf /etc/php/php7-fpm.d

# Copy Configs
COPY dockerconf/php.ini /etc/php7/php.ini
COPY dockerconf/php-fpm.conf /etc/php7/php-fpm.conf


EXPOSE 8080

CMD ['php-fpm7', '-F']
