SAIL := ./vendor/bin/sail

up:
	sudo $(SAIL) up -d

down:
	sudo $(SAIL) down

migrate:
	sudo $(SAIL) php artisan migrate

seed:
	sudo $(SAIL) php artisan db:seed

shell:
	sudo $(SAIL) shell

admin-shell:
	sudo $(SAIL) admin-shell

clear-cache:
	sudo $(SAIL) php artisan cache:clear
	sudo $(SAIL) php artisan config:clear
	sudo $(SAIL) php artisan route:clear
	sudo $(SAIL) php artisan view:clear

test:
	sudo $(SAIL) php artisan test

fix-permissions:
	sudo ./vendor/bin/sail root-shell -c 'chown -R sail:sail /var/www/html && chmod -R 755 /var/www/html'



help:
	@echo "Available commands:"
	@echo "  up   	 		- Start Laravel Sail services / start project"
	@echo "  down 	 		- Stop Laravel Sail services / stop project"
	@echo "  migrate 		- Run database migrations"
	@echo "  seed    		- Seed the database"
	@echo "  shell   	 	- Opens an interactive shell in the Laravel Sail container"
	@echo "  root-shell		- Opens an interactive shell in the Laravel Sail container as root"
	@echo "  clear-cache   	- clears cache"
	@echo "  test   		- run tests"

