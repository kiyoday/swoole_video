FROM centos:8

#version defined
ENV SWOOLE_VERSION 4.4.17
ENV EASYSWOOLE_VERSION 3.x-dev

#install libs
RUN yum install -y curl zip unzip  wget openssl-devel gcc-c++ make autoconf
#install php
RUN yum install -y php-devel php-mysqli php-openssl php-mbstring php-json
# install git
#RUN yum install -y  git
# composer
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/bin/composer
# use aliyun composer
RUN composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/

# swoole ext
RUN wget https://github.com/swoole/swoole-src/archive/v${SWOOLE_VERSION}.tar.gz -O swoole.tar.gz \
    && mkdir -p swoole \
    && tar -xf swoole.tar.gz -C swoole --strip-components=1 \
    && rm swoole.tar.gz \
    && ( \
    cd swoole \
    && phpize \
    && ./configure --enable-openssl \
    && make \
    && make install \
    ) \
    && sed -i "2i extension=swoole.so" /etc/php.ini \
    && rm -r swoole

# xdebug ext
#RUN git clone git://github.com/xdebug/xdebug.git \
#    && mkdir -p xdebug \
#    && ( \
#    cd xdebug \
#    && phpize \
#    && ./configure --enable-xdebug \
#    && make \
#    && make install \
#    ) \
#    && sed -i "2i extension=xdebug.so" /etc/php.ini \
#    && rm -r xdebug

# Dir
WORKDIR /easyswoole
# install easyswoole
RUN cd /easyswoole \
    && composer require easyswoole/easyswoole=${EASYSWOOLE_VERSION} \
    && php vendor/bin/easyswoole install

# install phpredis
RUN wget https://github.com/phpredis/phpredis/archive/3.1.4.tar.gz -O redis.tar.gz \
    && mkdir -p redis \
    && tar -xf redis.tar.gz -C redis --strip-components=1 \
    && rm redis.tar.gz \
    && ( \
    cd redis \
    && phpize  \
    && ./configure --with-php-config=php-config \
    && make \
    && make install \
    ) \
    && sed -i "2i extension=redis.so" /etc/php.ini \
    && rm -r redis


EXPOSE 9501