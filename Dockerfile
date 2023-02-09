FROM brettt89/silverstripe-web:8.1-apache
WORKDIR /var/www/html
COPY --from=composer /usr/bin/composer /usr/local/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN install-php-extensions pgsql
ENV DOCUMENT_ROOT /var/www/html
COPY . $DOCUMENT_ROOT
RUN composer install
RUN composer vendor-expose
RUN mkdir -p public/assets/.protected/boltcard/a870de278b
RUN chown 1000:www-data $DOCUMENT_ROOT -R
RUN mkdir -p .graphql-generated
RUN mkdir -p public/.graphql
RUN chown www-data:www-data .graphql-generated -R
RUN chown www-data:www-data public/.graphql -R
RUN chown www-data:www-data public/assets -R
WORKDIR $DOCUMENT_ROOT