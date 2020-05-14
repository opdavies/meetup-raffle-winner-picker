.PHONY: *

phpcs:
	vendor/bin/phpcs -v --ignore='bin/.phpunit,tests/bootstrap.php'

phpunit:
	bin/phpunit -v --testdox

test: phpunit phpcs
