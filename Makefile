inspector:
	npx -y @modelcontextprotocol/inspector

start:
	symfony server:start -d --no-tls

log:
	symfony server:log

stop:
	symfony server:stop

restart: stop start log

ci: codestyle phpstan test

codestyle:
	PHP_CS_FIXER_IGNORE_ENV=1 vendor/bin/php-cs-fixer fix

phpstan:
	vendor/bin/phpstan analyse

test:
	vendor/bin/phpunit
