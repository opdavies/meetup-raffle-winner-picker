.PHONY: *

phpcs:
	vendor/bin/phpcs -v --ignore='bin/.phpunit,tests/bootstrap.php'

test: phpcs
