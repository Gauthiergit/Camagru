DOCKER_COMPOSE = docker-compose
NAME = camagru

all: start


start:
	@echo "Demarrage de $(NAME)..."
	$(DOCKER_COMPOSE) up -d --build

stop:
	@echo "Arret des containers..."
	$(DOCKER_COMPOSE) stop


clean:
	@echo "Suppression des containers et reseaux..."
	$(DOCKER_COMPOSE) down

fclean: clean
	@echo "Suppression des volumes et nettoyage systeme..."
	$(DOCKER_COMPOSE) down -v
	docker system prune -af

re: fclean all

.PHONY: all start stop clean fclean re