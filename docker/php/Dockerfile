FROM php:8.2-apache

ARG SMTP_USER
ARG SMTP_PASS

RUN apt-get update && apt-get install -y \
	libpq-dev \
	libpng-dev \
	libjpeg-dev \
	libfreetype6-dev \
	&& docker-php-ext-configure gd --with-freetype --with-jpeg \
	&& docker-php-ext-install pdo pdo_pgsql gd

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN a2enmod rewrite

COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh && \
	sed -i 's/\r$//' /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]

RUN apt-get update && apt-get install -y \
	msmtp \
	ca-certificates \
	&& rm -rf /var/lib/apt/lists/*

RUN echo "defaults" > /etc/msmtprc && \
	echo "tls on" >> /etc/msmtprc && \
	echo "tls_trust_file /etc/ssl/certs/ca-certificates.crt" >> /etc/msmtprc && \
	echo "logfile /var/log/msmtp.log" >> /etc/msmtprc && \
	echo "" >> /etc/msmtprc && \
	echo "account gmail" >> /etc/msmtprc && \
	echo "host smtp.gmail.com" >> /etc/msmtprc && \
	echo "port 587" >> /etc/msmtprc && \
	echo "auth on" >> /etc/msmtprc && \
	echo "user ${SMTP_USER}" >> /etc/msmtprc && \
	echo "password ${SMTP_PASS}" >> /etc/msmtprc && \
	echo "from ${SMTP_USER}" >> /etc/msmtprc && \
	echo "" >> /etc/msmtprc && \
	echo "account default : gmail" >> /etc/msmtprc

RUN chown www-data:www-data /etc/msmtprc && chmod 600 /etc/msmtprc

RUN echo "sendmail_path = /usr/bin/msmtp -t" > /usr/local/etc/php/conf.d/mail.ini