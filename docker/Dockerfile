# ベースとなるイメージ指定
FROM php:8.0-apache
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get install -y \
    git \
    && docker-php-ext-install pdo_mysql
# public配下のindex.phpから読み込まれるので、ここでapacheのドキュメントルート設定
RUN sed -i 's!/var/www/html!/var/www/app/public!g' /etc/apache2/sites-available/000-default.conf