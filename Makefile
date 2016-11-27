DB_HOST := 127.0.0.1:8889
DB_NAME := affilicious_theme_test
DB_USER := root
DB_PASSWORD := root
WP_VERSION := latest

prod: clean
	@composer install --no-dev --optimize-autoloader

ready: dev
dev: tests-install
	@composer install

install:
	@composer install

update:
	@composer update

clean:
	@rm -rf assets/.cache
	@rm -rf assets/.sass-cache
	@rm -rf assets/*/*.map

tests-database:
	@bin/install-tests.sh $(DB_NAME) $(DB_USER) $(DB_PASSWORD) $(DB_HOST)

tests-install:
	@composer install
	@bin/install-tests.sh $(DB_NAME) $(DB_USER) $(DB_PASSWORD) $(DB_HOST) $(WP_VERSION) true

test:
	@phpunit

tests-uninstall:
	@composer install --no-dev
