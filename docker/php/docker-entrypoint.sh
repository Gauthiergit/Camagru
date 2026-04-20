#!/bin/bash

echo "Vérification de la disponibilité de la base de données..."

until php -r "try { new PDO('pgsql:host=db;dbname=$DB_NAME', '$DB_USER', '$DB_PASSWORD'); exit(0); } catch (Exception \$e) { exit(1); }"; do
  echo "La base de données n'est pas prête... on attend 2 secondes."
  sleep 2
done

echo "Base de données prête ! Lancement du setup..."

php /var/www/html/config/setup.php

echo "Setup terminé. Démarrage d'Apache..."
exec apache2-foreground