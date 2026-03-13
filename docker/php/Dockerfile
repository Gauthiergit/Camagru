FROM php:8.2-apache

ARG SMTP_USER
ARG SMTP_PASS

# Installation des extensions (Postgres + GD)
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql gd

# --- CONFIGURATION APACHE ---
# On change le dossier racine par défaut d'Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Activation du module rewrite pour le futur routing
RUN a2enmod rewrite

EXPOSE 80

# --- INSTALLATION DE MSMTP ET DES CERTIFICATS ---
RUN apt-get update && apt-get install -y \
    msmtp \
    ca-certificates \
    && rm -rf /var/lib/apt/lists/*

# On configure le chemin des certificats pour msmtp
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

# On s'assure que le fichier appartient à l'utilisateur PHP et a les bonnes permissions
RUN chown www-data:www-data /etc/msmtprc && chmod 600 /etc/msmtprc

# On dit à PHP d'utiliser msmtp
RUN echo "sendmail_path = /usr/bin/msmtp -t" > /usr/local/etc/php/conf.d/mail.ini