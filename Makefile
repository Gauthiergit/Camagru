# Variables
DOCKER_COMPOSE = docker-compose
NAME = camagru

all: start

# Lance les containers en arrière-plan (detached mode)
# On utilise --build pour s'assurer que les modifs du Dockerfile sont prises en compte
start:
	@echo "Demarrage de $(NAME)..."
	$(DOCKER_COMPOSE) up -d --build

# Arrête les containers sans les supprimer
stop:
	@echo "Arret des containers..."
	$(DOCKER_COMPOSE) stop

# Arrête et supprime les containers et les réseaux
clean:
	@echo "Suppression des containers et reseaux..."
	$(DOCKER_COMPOSE) down

# Nettoyage profond : supprime aussi les volumes (la base de données sera vidée !)
# Utile si tu veux repartir de zéro sur ton setup.php
fclean: clean
	@echo "Suppression des volumes et nettoyage systeme..."
	$(DOCKER_COMPOSE) down -v
	docker system prune -af

# Relance tout proprement
re: fclean all

# Voir les logs en temps réel
logs:
	$(DOCKER_COMPOSE) logs -f

# Entrer dans le container PHP pour debug
exec:
	docker exec -it camagru_php_app bash

.PHONY: all start stop clean fclean re logs exec