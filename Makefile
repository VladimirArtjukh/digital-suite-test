.PHONY: up down install test

up:
	./vendor/bin/sail up -d
	./vendor/bin/sail npm run dev &

down:
	./vendor/bin/sail down

install:
	cp .env.example .env || true
	./vendor/bin/sail up -d
	./vendor/bin/sail composer install
	./vendor/bin/sail artisan key:generate
	./vendor/bin/sail artisan migrate
	./vendor/bin/sail npm install
	./vendor/bin/sail npm run dev

test:
	./vendor/bin/sail test
