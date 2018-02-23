# Pull base image
FROM akkapong/php-nginx-phalcon-mongo:latest

RUN mkdir -p /data/clientkey
COPY serverkey /data/serverkey
RUN chmod -R 755 /data
RUN chown -R www-data:www-data /data

#RUN composer install