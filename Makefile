test:
	@./vendor/bin/phpunit
lint:
	@./vendor/bin/phpcs --standard=PSR12 src/ tests/
install:
	@composer install
test-coverage:
	@XDEBUG_MODE=coverage composer exec --verbose phpunit tests -- --coverage-clover=build/logs/clover.xml