# 📸 Camagru - Projet Web 42

Camagru est une application web complète permettant aux utilisateurs de réaliser et de partager des montages photo. Inspiré par Instagram et Snapchat, ce projet met l'accent sur la manipulation d'images côté serveur (PHP GD), la gestion d'une base de données relationnelle et la sécurité web.

## 🚀 Fonctionnalités

- **Montage Photo** : Capture via webcam ou upload de fichiers, avec superposition de stickers repositionnables.
- **Galerie Publique** : Consultation des photos de tous les utilisateurs avec pagination.
- **Interactions Sociales** : Système de Likes et de Commentaires en temps réel (AJAX).
- **Notifications** : Envoi de mails lors de la réception d'un commentaire (désactivable dans les réglages).
- **Sécurité** : Protection contre les failles XSS, SQLi, CSRF, et hachage sécurisé des mots de passe.
- **Responsive Design** : Interface fluide sur desktop et mobile.

## 🛠️ Stack Technique

- **Backend** : PHP 8 (Vanilla)
- **Frontend** : JavaScript (Vanilla), CSS3, HTML5
- **Base de données** : PostgreSQL
- **Conteneurisation** : Docker & Docker Compose
- **Serveur Web** : Apache

## 📦 Installation & Lancement

### 1. Prérequis
Assurez-vous d'avoir [Docker](https://docs.docker.com/get-docker/) et [Docker Compose](https://docs.docker.com/compose/install/) installés sur votre machine.

### 2. Configuration (Environnement)
Créez un fichier `.env` à la racine du projet et remplissez les informations suivantes :

```env
# Configuration de la base de données
DB_NAME=camagru_db
DB_USER=user_camagru
DB_PASSWORD=votre_mot_de_passe_secret
DB_HOST=db
DB_PORT=5432

# Configuration SMTP (Gmail)
SMTP_USER=votre.email@gmail.com
SMTP_PASS=votre_app_key_generee
```
### 3. Starting  
Déplacez-vous dans le repo cloné et ...
```
make
```
