FROM phpdockerio/php72-fpm:latest

USER root

COPY ./install_composer.sh /

RUN chmod +x /install_composer.sh && mkdir /home/wwwagent && chown wwwagent:wwwagent /home/wwwagent

RUN /install_composer.sh

WORKDIR /var/www/colvin