# подключаем .env файл с переменными 
# (в частности с именем используемого контейнера c php)
include ./.env
export


docker_php=docker exec -it php

up:
	@docker compose up -d --build

down:
	@docker compose down

restart:
	@docker compose down
	@docker compose up -d --build

connect-php:
	@$(docker_php) /bin/bash

# выполнить миграции
.PHONY: migrate
migrate:
	docker exec -it ${PROJECT_NAME}_php bash -c 'php artisan migrate'

# откатить миграции
.PHONY: rollback
rollback:
	docker exec -it ${PROJECT_NAME}_php bash -c 'php artisan migrate:rollback'

# установить новые модули из composer
.PHONY: composer_install
composer_install:
	docker exec -it ${PROJECT_NAME}_php bash -c 'composer install'

# выполнить произвольную команду в контейнере
.PHONY: command
command:
	docker exec -it ${PROJECT_NAME}_php bash -c '$(filter-out $@,$(MAKECMDGOALS))'

# произвольная команда artisan
.PHONY: artisan
artisan:
	docker exec -it ${PROJECT_NAME}_php bash -c 'php artisan $(filter-out $@,$(MAKECMDGOALS))'

# восстановить БД из дампа в файле backup/proway.sql
.PHONY: restore_db
restore_db:
	docker exec -i proway_mysql sh -c 'exec /usr/bin/mysql -uroot -psecurerootpassword proway' < ../backup/proway.sql

#комментарий для теста