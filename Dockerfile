FROM brettt89/silverstripe-web:8.1-apache
WORKDIR /var/www/html
COPY --from=composer /usr/bin/composer /usr/local/bin/composer
RUN install-php-extensions pgsql
ENV DOCUMENT_ROOT /var/www/html
COPY . $DOCUMENT_ROOT
COPY ./my-httpd.conf /usr/local/apache2/conf/httpd.conf
RUN chown 1000:www-data $DOCUMENT_ROOT
RUN chown 1000:www-data public
RUN chown www-data:www-data public/assets -R
WORKDIR $DOCUMENT_ROOT

