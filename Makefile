##################
# Variables
##################

DOCKER_COMPOSE = docker-compose -f ./docker-compose.yml --env-file ./.env
DOCKER_COMPOSE_PHP_FPM_EXEC = ${DOCKER_COMPOSE} exec -u www-data php-fpm

##################
# Docker compose
##################

dc_build:
	${DOCKER_COMPOSE} build
dc_start:
	${DOCKER_COMPOSE} start
dc_stop:
	${DOCKER_COMPOSE} stop
dc_up:
	${DOCKER_COMPOSE} up -d --remove-orphans
dc_ps:
	${DOCKER_COMPOSE} ps
dc_logs:
	${DOCKER_COMPOSE} logs -f
dc_down:
	${DOCKER_COMPOSE} down -v --rmi=all --remove-orphans
dc_restart:
	make dc_stop dc_start

##################
# App
##################

app_bash:
	${DOCKER_COMPOSE} exec -u www-data php-fpm bash
php:
	${DOCKER_COMPOSE} exec -u www-data php-fpm bash
test:
	${DOCKER_COMPOSE} exec -u www-data php-fpm php bin/phpunit
jwt:
	${DOCKER_COMPOSE} exec -u www-data php-fpm php bin/console lexik:jwt:generate-keypair
cache:
	docker-compose -f ./docker-compose.yml exec -u www-data php-fpm php bin/console cache:clear
	docker-compose -f ./docker-compose.yml exec -u www-data php-fpm php bin/console cache:clear --env=test

##################
# Database
##################

db_migrate:
	${DOCKER_COMPOSE} exec -u www-data php-fpm php bin/console doctrine:migrations:migrate --no-interaction
db_diff:
	${DOCKER_COMPOSE} exec -u www-data php-fpm php bin/console doctrine:migrations:diff --no-interaction
db_drop:
	docker-compose -f ./docker-compose.yml exec -u www-data php-fpm php bin/console doctrine:schema:drop --force


##################
# Static code analysis
##################

phpstan:
	${DOCKER_COMPOSE_PHP_FPM_EXEC} vendor/bin/phpstan analyse src tests -c phpstan.neon
deptrac:
	${DOCKER_COMPOSE_PHP_FPM_EXEC} vendor/bin/deptrac analyze --config-file=deptrac-layers.yaml
	${DOCKER_COMPOSE_PHP_FPM_EXEC} vendor/bin/deptrac analyze --config-file=deptrac-modules.yaml
cs_fix:
	${DOCKER_COMPOSE_PHP_FPM_EXEC} vendor/bin/php-cs-fixer fix
cs_fix_diff:
	${DOCKER_COMPOSE_PHP_FPM_EXEC} vendor/bin/php-cs-fixer fix --dry-run --diff
