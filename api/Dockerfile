FROM hhvm/hhvm-proxygen:3.15.0

RUN apt-get update -y && apt-get install -y curl php5-pgsql git

# Install composer
RUN mkdir /opt/composer
RUN curl -sS https://getcomposer.org/installer | hhvm --php -- --install-dir=/opt/composer

# Add HHVM configuration
ADD hhvm.prod.ini /etc/hhvm/site.ini

EXPOSE 80

WORKDIR /tmp

COPY composer.json /tmp/
RUN hhvm /opt/composer/composer.phar install
RUN mkdir -p /var/www/api && cp -a vendor /var/www/api/

WORKDIR /var/www/api

# Run the server
CMD /usr/bin/hhvm -m server -c /etc/hhvm/server.ini -c /etc/hhvm/site.ini
