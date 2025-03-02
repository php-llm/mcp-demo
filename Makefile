inspector:
	npx -y @modelcontextprotocol/inspector

restart:
	symfony server:stop
	symfony server:start -d
	symfony server:log

ci: codestyle phpstan test

codestyle:
	PHP_CS_FIXER_IGNORE_ENV=1 vendor/bin/php-cs-fixer fix

phpstan:
	vendor/bin/phpstan analyse

test:
	vendor/bin/phpunit
