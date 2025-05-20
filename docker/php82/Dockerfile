# See https://github.com/docker-library/php/blob/master/7.1/fpm/Dockerfile
FROM php:8.2-fpm


RUN apt-get update \
	&& apt-get install -y \
        apt-utils \
        curl \
        default-mysql-client \
        dnsutils \
        exif \
        git \
        g++ \
        libicu-dev \
        libmemcached-dev \
        mc \
        moreutils \
        unzip \
        vim \
        wget \
		zip \
        zlib1g-dev \
    && rm -rf /var/lib/apt/lists/*



# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

RUN composer global require --dev phpunit/phpunit

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
    && docker-php-ext-install -j$(nproc) iconv \
#    && docker-php-ext-install -j$(nproc) iconv mcrypt \
    && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd

#RUN pecl install mcrypt-1.0.4
#RUN docker-php-ext-enable mcrypt

RUN docker-php-ext-install mysqli \
    && docker-php-ext-enable mysqli

# Type docker-php-ext-install to see available extensions
RUN docker-php-ext-configure intl \
    && docker-php-ext-install pdo pdo_mysql opcache intl

# Install Postgre PDO
RUN apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

#install php extensions
RUN apt-get install -y graphviz
RUN apt install -y libxml2-dev
RUN apt-get install -y libzip-dev
RUN docker-php-ext-install zip xml

#exif extension
RUN apt-get -yqq update
RUN apt-get -yqq install exiftool
RUN docker-php-ext-configure exif
RUN docker-php-ext-install exif
RUN docker-php-ext-enable exif

WORKDIR /var/www


#RUN echo "Europe/Moscow" > /etc/timezone
#RUN dpkg-reconfigure -f noninteractive tzdata
ENV TZ Europe/Moscow

# #Open Office (for converting documents)
# RUN mkdir -p /usr/share/man/man1
# RUN apt-get update -y \
#     && apt-get install -y libreoffice
# RUN apt-get install -y unoconv


# Set the locale
RUN apt-get install -y locales locales-all
ENV LC_ALL en_US.UTF-8
ENV LANG en_US.UTF-8
ENV LANGUAGE en_US.UTF-8

#RUN apt-get install -y language-pack-ru

#RUN locale-gen ru_RU.UTF-8
#ENV LANG ru_RU.UTF-8
#ENV LANGUAGE ru.RU:en
#ENV LC_ALL ru_RU.UTF-8

#ENV DEBIAN_FRONTEND noninteractive.
#RUN apt-get update
#RUN apt-get upgrade -y
#RUN locale-gen ru_RU.UTF-8 && dpkg-reconfigure locales

RUN php -r "echo ini_get('memory_limit').PHP_EOL;"

#Tesseract
# RUN apt-get install tesseract-ocr -y \
#     python3 \
#     #python-setuptools \
#     python3-pip \
#     && apt-get clean \
#     && apt-get autoremove


# нужно доделать - добавление файлов языков распознавания в контейнер во время сборки контейнера
# ADD ./

# ADD . /home/App
# WORKDIR /home/App
# COPY requirements.txt ./
# COPY . .

# RUN pip3 install -r requirements.txt

# VOLUME ["/data"]
# EXPOSE 5000 5000
# CMD ["python3","OCRRun.py"]


# install xdebug
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
#RUN echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
#RUN echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
#RUN echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
#RUN echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
#RUN echo "xdebug.remote_connect_back=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
#RUN echo "xdebug.idekey=\"PHPSTORM\"" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
#RUN echo "xdebug.remote_port=9001" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
#RUN echo "xdebug.remote_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
##RUN echo "xdebug.remote_handler=dbgp" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
#RUN echo "zend_extension=xdebug.so" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
#RUN echo "xdebug.default_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
#RUN echo "xdebug.remote_autostart=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
##RUN echo "xdebug.remote_log=/app/xdebug.log" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

#доунгрейдим composer до v1
# RUN composer self-update --1

##symfony cli
#RUN curl -sS https://get.symfony.com/cli/installer | bash \
#    && export PATH="$HOME/.symfony/bin:$PATH"

## bitbucket token for WeConf
#RUN composer config --global --auth http-basic.weconf-modules.repo.repman.io token 5415d47c46b5e862ec6eb3357ebc9e727abd8f18254124ec4ab6416c44b9742b

#npm
ARG NODE_VERSION=20
#RUN curl -sL https://deb.nodesource.com/setup_16.x  | bash -
#RUN apt-get -y install nodejs
RUN apt-get update
RUN apt-get install -y ca-certificates curl gnupg
RUN mkdir -p /etc/apt/keyrings
RUN curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg
RUN echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_$NODE_VERSION.x nodistro main" | tee /etc/apt/sources.list.d/nodesource.list
RUN apt-get update
RUN apt-get install nodejs -y
#yarn
RUN npm install -g yarn

#sail command
RUN alias sail='bash vendor/bin/sail'

#RUN apt-get update \
#	&& apt-get install -y \
#    openssl  \
#    && rm -rf /var/lib/apt/lists/*
#RUN docker-php-ext-install \
###    common \
###    json \  # in php8 is always available
###    xml \ # installed above
###    zip \ # installed above
###    gd \ # installed above
###    intl \ # installed above
###    curl \ # installed above
###    mbstring \
###    mysql-cilent \ # installed above
#    soap \
#    pcntl \
#    bz2 \
#    calendar \
#    opcache \
#    pdo_mysql \
#    xsl \
#    bcmath

#RUN apt-get update
##RUN apt-get install -y gnupg gosu \\
#RUN apt-get install python3-launchpadlib
#RUN mkdir -p ~/.gnupg \
#    && chmod 600 ~/.gnupg \
#    && echo "disable-ipv6" >> ~/.gnupg/dirmngr.conf \
#    && echo "keyserver hkp://keyserver.ubuntu.com:80" >> ~/.gnupg/dirmngr.conf \
#    && gpg --recv-key 0x14aa40ec0831756756d7f66c4f4ea0aae5267a6c \
#    && gpg --export 0x14aa40ec0831756756d7f66c4f4ea0aae5267a6c > /usr/share/keyrings/ppa_ondrej_php.gpg \
#    && echo "deb [signed-by=/usr/share/keyrings/ppa_ondrej_php.gpg] https://ppa.launchpadcontent.net/ondrej/php/ubuntu jammy main" > /etc/apt/sources.list.d/ppa_ondrej_php.list
#RUN apt-get update


## ondrej ppa, вроде не нужен, можно делать стандатрым docker-php-ext-install
#RUN apt-get install software-properties-common -y
#RUN add-apt-repository ppa:ondrej/php
#RUN apt-get update

##xhprof

RUN curl -sSL https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions -o - | sh -s \
      xhprof
RUN cd /usr/local/lib/php/extensions/no-debug-non-zts-20220829/ \
        && echo | ls -la

#RUN apt-get update \
###    && apt-get install php-pear \
#    && pecl bundle -d /usr/src/php/ext xhprof \
#    && cd /urs/src/php/ext \
#    && echo | ls -la \
#    && phpize \
#    && ./configure && make && make install \

#&& COPY docker-php-ext-get /usr/local/lib/php/extensions/no-debug-non-zts-20220829/

#RUN git clone --branch master --depth 1 https://github.com/longxinH/xhprof.git /usr/src/php/ext/xhprof \
#    && cd /usr/src/php/ext/xhprof \
#    && echo | ls -la \
#    && git submodule update --init \
#    && cd /usr/local/etc/php \
#    && ./configure --with-php-config=/usr/local/etc/php \ # не находит configure
#    && make && sudo make install \

#&& docker-php-ext-configure xhprof \
    # --with-php-config=/usr/local/etc/php
#&& docker-php-ext-install xhprof \
#RUN docker-php-ext-enable xhprof

# install mongodb extension. The xhgui-collector will send xprof data to mongo
RUN pecl install mongodb \
#RUN docker-php-ext-install mongodb \
    && docker-php-ext-enable mongodb

# install the package that will collect data for all applications of this PHP container
#    потом в приложении подключаем так:
#    require '/var/xhgui/vendor/autoload.php';
#    $config = include '/var/xhgui/config/config.php'; или $config = \Xhgui\Profiler\Config::create();
#    $profiler = new \Xhgui\Profiler\Profiler($config);
#    $profiler->start();
#WORKDIR /var/xhgui
#RUN composer require perftools/php-profiler perftools/xhgui-collector alcaeus/mongo-php-adapter:^2.0

# copy the configuration file
#COPY ./php82/xhgui/config/config.php /var/xhgui/config/config.php

RUN adduser myuser --uid 1000

WORKDIR /www
RUN wget http://www.telosys.org/download/telosys-cli/telosys-cli-4.1.1-001.zip \
    && unzip telosys-cli-4.1.1-001.zip \
    && chmod 777 tt \
    && export PATH=$PATH:/path/to/telosys


RUN apt-get update \
	&& apt-get install -y \
#    common \
#    pcntl \
#    bz2 \
    calendar
#    xsl


RUN docker-php-ext-install bcmath
RUN docker-php-ext-install soap
RUN docker-php-ext-install pcntl
#RUN docker-php-ext-install xsl
#RUN docker-php-ext-install bz2

