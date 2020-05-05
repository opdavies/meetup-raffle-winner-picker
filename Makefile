.PHONY: *

phpcs:
	vendor/bin/phpcs -v

test: phpcs
