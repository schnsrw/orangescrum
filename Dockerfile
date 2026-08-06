FROM php:7.4-apache

# CakePHP 2.9.4 (this app's framework) uses each(), create_function(), and
# curly-brace string offsets that were removed in PHP 8.0 - it cannot run
# there. PHP 7.4 is the newest version that still supports all of that while
# also dropping the legacy ext-mysql/ereg/split() calls removed in PHP 7.0.
RUN apt-get update && apt-get install -y --no-install-recommends \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libzip-dev \
        libcurl4-openssl-dev \
        libxml2-dev \
        libonig-dev \
        default-mysql-client \
        unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" pdo_mysql mysqli mbstring curl gd zip \
    && a2enmod rewrite headers deflate expires \
    && sed -ri -e 's!AllowOverride None!AllowOverride All!g' /etc/apache2/apache2.conf \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY . /var/www/html/

# This app self-modifies its own config and drops marker/check files at
# scattered paths (app/Config/setup.php rewrites constants.php and reads it
# back on every request; app/webroot/index.php writes an error.check marker
# at webroot root), so the whole tree needs to be owned by the web server user.
RUN mkdir -p app/tmp/cache/models app/tmp/cache/persistent app/tmp/logs app/tmp/sessions \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 app/tmp app/Config
