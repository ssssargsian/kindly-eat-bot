ifneq ($(if $(MAKECMDGOALS),$(words $(MAKECMDGOALS)),1),1)
.SUFFIXES:
TARGET := $(if $(findstring :,$(firstword $(MAKECMDGOALS))),,$(firstword $(MAKECMDGOALS)))
PARAMS := $(if $(findstring :,$(firstword $(MAKECMDGOALS))),$(MAKECMDGOALS),$(wordlist 2,100000,$(MAKECMDGOALS)))
.DEFAULT_GOAL = help
.PHONY: ONLY_ONCE
ONLY_ONCE:
	$(MAKE) $(TARGET) COMMAND_ARGS="$(PARAMS)"
%: ONLY_ONCE
	@:
else

DISABLE_XDEBUG=XDEBUG_MODE=off
DCE_API=docker compose exec -it api

.PHONY: help
help: ## Помощь
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

.PHONY: start
start: ## Запускает окружение
	@docker-compose up -d

.PHONY: stop
stop: ## Останавливает окружение
	@docker-compose down

.PHONY: composer
composer: ## Работа с Composer. Пример: make -- composer req vendor/package
	@$(DCE_API) sh -c "composer $(COMMAND_ARGS)"

.PHONY: c
c: ## Работа с консолью Symfony. Пример: make -- c c:c
	@$(DCE_API) sh -c "php bin/console $(COMMAND_ARGS)"

.PHONY: ecs
ecs: ## Проверка стиля кода и автоматическое исправление по возможности (backend)
	@$(DCE_API) sh -c "vendor/bin/ecs check src --fix"

.PHONY: logs
logs: ## Смотреть реал-тайм логи
	@clear && docker-compose logs --tail=0 --follow | grep finik-api

.PHONY: phpunit
phpunit: ## PHPUnit
	@echo -n > var/log/test.log
	@docker-compose run --rm api sh -c "$(DISABLE_XDEBUG) php vendor/bin/phpunit --no-coverage $(COMMAND_ARGS)"

.PHONY: paratest
paratest: ## ParaTest
	@rm -rf var/cache/test
	@rm -rf var/cache/test
	@echo -n > var/log/test.log
	@docker-compose run --rm api sh -c "$(DISABLE_XDEBUG) php vendor/bin/paratest --processes=4 --runner=WrapperRunner $(COMMAND_ARGS)"

.PHONY: psalm
psalm: ## Статический анализ кода с Psalm
	@docker-compose run --rm api sh -c "./vendor/bin/psalm $(COMMAND_ARGS)"

.PHONY: cc
cc: ## Очистка кеша
	@$(DCE_API) sh -c "$(DISABLE_XDEBUG) php bin/console c:c $(COMMAND_ARGS)"

.PHONY: unused
unused: ## Список неиспользуемых пакетов
	@$(DCE_API) sh -c "php vendor/bin/composer-unused $(COMMAND_ARGS)"

.PHONY: ld
ld: dc start ## Загрузить дамп

.PHONY: dc
dc: ## Удалить контейнеры
	@docker-compose down -v

.PHONY: rmc
rmc: ## Удалить весь кеш rm -rf
	@rm -rf ./var/cache

.PHONY: jwt
jwt:
	@$(DCE_API) sh -c "$(DISABLE_XDEBUG) php bin/console lexik:jwt:generate-token $(COMMAND_ARGS)"

.PHONY: prepare-dump
prepare-dump:
	@docker compose down -v
	for f in docker/postgres/dump/*.gz ; do \
		if [ -f "$$f" ]; then \
			mv "$$f" "$$f".bak; \
		fi \
	done

.PHONY: dump-dev
dump-dev:
	$(MAKE) prepare-dump
	@ssh root@api.krosspark.ru -p 2221 "cd ~/backend && docker compose exec -T postgres pg_dump -U crosspark crosspark --no-owner --no-privileges" | gzip > docker/postgres/dump/crosspark-dev-$(shell date +%Y%m%d%H%M).sql.gz
	@docker compose up -d
endif
