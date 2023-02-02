FROM brettt89/silverstripe-web:8.1-apache
ENV PORT 80
RUN { \
        echo '<VirtualHost *:${PORT}>'; \
        echo '  DocumentRoot ${DOCUMENT_ROOT}'; \
        echo '  LogLevel warn'; \
        echo '  ServerSignature Off'; \
        echo '  <Directory ${DOCUMENT_ROOT}>'; \
        echo '    Options +FollowSymLinks'; \
        echo '    Options -ExecCGI -Includes -Indexes'; \
        echo '    AllowOverride all'; \
        echo; \
        echo '    Require all granted'; \
        echo '  </Directory>'; \
        echo '  <LocationMatch assets/>'; \
        echo '    php_flag engine off'; \
        echo '  </LocationMatch>'; \
        echo; \
        echo '  IncludeOptional sites-available/000-default.local*'; \
        echo '</VirtualHost>'; \
        } > /etc/apache2/sites-available/000-default.conf && \
    echo "ServerName localhost" > /etc/apache2/conf-available/fqdn.conf && \
    a2enmod rewrite expires remoteip headers

WORKDIR /var/www/html
COPY --from=composer /usr/bin/composer /usr/local/bin/composer
RUN install-php-extensions pgsql
ENV DOCUMENT_ROOT /var/www/html
COPY . $DOCUMENT_ROOT
COPY ./my-httpd.conf /usr/local/apache2/conf/httpd.conf
RUN chown www-data:www-data public/assets -R
WORKDIR $DOCUMENT_ROOT

