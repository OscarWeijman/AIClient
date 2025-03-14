.PHONY: test test-unit test-feature test-integration test-all

# Standaard test commando (unit + feature tests)
test:
	./vendor/bin/pest --exclude-group=integration

# Alleen unit tests
test-unit:
	./vendor/bin/pest tests/Unit

# Alleen feature tests
test-feature:
	./vendor/bin/pest tests/Feature

# Alleen integratie tests (vereist .env met API keys)
test-integration:
	./vendor/bin/pest tests/Integration

# Alle tests inclusief integratie tests
test-all:
	./vendor/bin/pest