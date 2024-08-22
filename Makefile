##################
# Docker compose
##################

dc_build:
	docker-compose -f ./docker/docker-compose.yml build

dc_start:
	docker-compose -f ./docker/docker-compose.yml start

dc_stop:
	docker-compose -f ./docker/docker-compose.yml stop

dc_up:
	docker-compose -f ./docker/docker-compose.yml up -d --remove-orphans

dc_ps:
	docker-compose -f ./docker/docker-compose.yml ps

dc_logs:
	docker-compose -f ./docker/docker-compose.yml logs -f

dc_down:
	docker-compose -f ./docker/docker-compose.yml down -v --rmi=all --remove-orphans

app_db_setup:
	docker-compose exec php-fpm php bin/console doctrine:migration:diff && \
    docker-compose exec php-fpm php bin/console doctrine:migration:migrate && \
    docker-compose exec php-fpm php bin/console doctrine:fixtures:load --no-interaction && \
    docker-compose exec php-fpm php bin/console doctrine:database:create --env=test && \
    docker-compose exec php-fpm php bin/console doctrine:migrations:migrate --env=test && \
    docker-compose exec php-fpm php bin/console doctrine:fixtures:load --env=test --no-interaction


##################
# App
##################

app_bash:
	docker-compose -f ./docker/docker-compose.yml exec -u www-data php-fpm bash
