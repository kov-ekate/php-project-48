test:
	@./vendor/bin/phpunit
lint:
	@ ./vendor/bin/phpcs --standard=PSR12 src/ tests/