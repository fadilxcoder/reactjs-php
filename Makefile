# Makefile

# Set the ports for the servers
NODE_PORT := 3000
PHP_PORT := 8500

.PHONY: start

start:
#	@cd frontend && npm start --silent &
#	@echo "Node.js server started on port $(NODE_PORT)" &
#	@php -S localhost:$(PHP_PORT) -t one-file-api/ -q &
#	@echo "Starting Node.js server..."