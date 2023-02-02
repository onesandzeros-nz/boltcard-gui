FROM brettt89/silverstripe-web:8.1-apache
WORKDIR /var/www/html
COPY --from=composer /usr/bin/composer /usr/local/bin/composer
RUN install-php-extensions pgsql
ENV DOCUMENT_ROOT /var/www/html
COPY . $DOCUMENT_ROOT
RUN chown www-data:www-data public/assets -R
WORKDIR $DOCUMENT_ROOT

