ENV_ARGS = --env-file .env $(if $(wildcard .env.local),--env-file .env.local)
COMPOSE = docker compose $(ENV_ARGS)

# Load env vars for use in Makefile targets
include .env
-include .env.local

.PHONY: up down build rebuild rebuild-nc logs shell hooks init count

count:
	@bash bin/count-loc.sh

up:
	$(COMPOSE) up -d

down:
	$(COMPOSE) down --remove-orphans

build:
	$(COMPOSE) build

rebuild:
	$(COMPOSE) build app
	$(COMPOSE) up -d

rebuild-nc:
	$(COMPOSE) build --no-cache app
	$(COMPOSE) up -d

logs:
	$(COMPOSE) logs -f app

shell:
	$(COMPOSE) exec app zsh

hooks:
	@cp config/hooks/pre-commit .git/hooks/pre-commit
	@chmod +x .git/hooks/pre-commit
	@echo "Git pre-commit hook installed"
	@if [ ! -f .php-cs-fixer.php ] && [ -f .php-cs-fixer.dist.php ]; then \
		cp .php-cs-fixer.dist.php .php-cs-fixer.php; \
		echo "php-cs-fixer config copied: .php-cs-fixer.dist.php -> .php-cs-fixer.php"; \
	fi

init:
	@echo ""
	@echo "==> [1/11] Checking prerequisites..."
	@docker info > /dev/null 2>&1 || { echo "ERROR: Docker is not running"; exit 1; }
	@docker network inspect proxy > /dev/null 2>&1 || { \
		echo "ERROR: Docker network 'proxy' not found. Run: docker network create proxy"; exit 1; }
	@docker ps --filter name=traefik --filter status=running --format "{{.Names}}" | grep -q traefik || { \
		echo "ERROR: Traefik is not running. Start it from ~/www/droxy: docker compose up -d"; exit 1; }

# 	@echo "==> [2/10] Downloading files from $(DUMP_SERVER)..."
# 	@if [ ! -f dump.sql.gz ] || [ ! -f var/data/GeoLite2-City.mmdb ] || [ ! -f var/data/GeoLite2-Country.mmdb ]; then \
# 		ssh -o BatchMode=yes -o ConnectTimeout=5 $(DUMP_USER)@$(DUMP_SERVER) exit 2>/dev/null || { \
# 			echo ""; \
# 			echo "ERROR: Cannot connect to $(DUMP_SERVER) as $(DUMP_USER)."; \
# 			echo ""; \
# 			echo "Install your SSH public key on the production server:"; \
# 			echo "  ssh-copy-id $(DUMP_USER)@$(DUMP_SERVER)"; \
# 			echo ""; \
# 			echo "Your available public keys:"; \
# 			ls ~/.ssh/id_rsa.pub ~/.ssh/id_ed25519.pub 2>/dev/null | while read f; do echo "  $$f:"; cat $$f; echo ""; done; \
# 			[ -z "$$(ls ~/.ssh/id_rsa.pub ~/.ssh/id_ed25519.pub 2>/dev/null)" ] && echo "  (no RSA or ed25519 keys found — generate one with: ssh-keygen -t ed25519)"; \
# 			exit 1; }; \
# 	fi
# 	@if [ ! -f dump.sql.gz ]; then \
# 		scp $(DUMP_USER)@$(DUMP_SERVER):$(DUMP_PATH) dump.sql.gz; \
# 		echo "    dump.sql.gz downloaded"; \
# 	else \
# 		echo "    dump.sql.gz already exists, skipping"; \
# 	fi
# 	@mkdir -p var/data
# 	@if [ ! -f var/data/GeoLite2-City.mmdb ]; then \
# 		scp $(DUMP_USER)@$(DUMP_SERVER):/www/sftest2/var/data/GeoLite2-City.mmdb var/data/GeoLite2-City.mmdb; \
# 		echo "    GeoLite2-City.mmdb downloaded"; \
# 	else \
# 		echo "    GeoLite2-City.mmdb already exists, skipping"; \
# 	fi
# 	@if [ ! -f var/data/GeoLite2-Country.mmdb ]; then \
# 		scp $(DUMP_USER)@$(DUMP_SERVER):/www/sftest2/var/data/GeoLite2-Country.mmdb var/data/GeoLite2-Country.mmdb; \
# 		echo "    GeoLite2-Country.mmdb downloaded"; \
# 	else \
# 		echo "    GeoLite2-Country.mmdb already exists, skipping"; \
# 	fi

	@echo "==> [3/10] Generating SSL certificates..."
	@if [ ! -f config/ssl/localhost.crt ] || \
	   ! openssl x509 -checkend 86400 -noout -in config/ssl/localhost.crt 2>/dev/null; then \
		echo "    Generating new self-signed cert (valid 10 years) for sftest2.my, pma.sftest2.my..."; \
		mkdir -p config/ssl; \
		openssl req -x509 -newkey rsa:2048 -nodes \
			-keyout config/ssl/localhost.key \
			-out config/ssl/localhost.crt \
			-days 3650 \
			-subj "/CN=sftest2.my/O=Local Dev" \
			-addext "subjectAltName=DNS:sftest2.my,DNS:pma.sftest2.my,DNS:localhost" 2>/dev/null; \
		echo "    Done"; \
	else \
		echo "    config/ssl/localhost.crt OK (valid), skipping"; \
	fi

	@echo "==> [4/10] Finding free ports..."
	@if grep -q "DOCKER_HTTPS_PORT" .env.local 2>/dev/null; then \
		echo "    ports already set in .env.local, skipping"; \
	else \
		HTTPS_PORT=44301; \
		while nc -z 127.0.0.1 $$HTTPS_PORT 2>/dev/null; do HTTPS_PORT=$$((HTTPS_PORT+1)); done; \
		HTTP_PORT=8081; \
		while nc -z 127.0.0.1 $$HTTP_PORT 2>/dev/null; do HTTP_PORT=$$((HTTP_PORT+1)); done; \
		echo "    HTTPS port: $$HTTPS_PORT"; \
		echo "    HTTP  port: $$HTTP_PORT"; \
		echo "DOCKER_HTTPS_PORT=$$HTTPS_PORT" >> .env.local.ports; \
		echo "DOCKER_HTTP_PORT=$$HTTP_PORT" >> .env.local.ports; \
	fi

	@echo "==> [5/10] Creating .env.local..."
	@if [ ! -f .env.local ]; then \
		printf 'APP_PROD=false\n' > .env.local; \
		printf 'DATABASE_URL="mysql://sftest2:sftest2@db:3306/sftest2?serverVersion=8.0&charset=utf8mb4"\n' >> .env.local; \
		printf 'WKHTMLTOPDF_PATH=/usr/bin/wkhtmltopdf\n' >> .env.local; \
		printf 'WKHTMLTOIMAGE_PATH=/usr/bin/wkhtmltoimage\n' >> .env.local; \
		cat .env.local.ports >> .env.local; \
		echo "    .env.local created"; \
	else \
		grep -q "DOCKER_HTTPS_PORT" .env.local || cat .env.local.ports >> .env.local; \
		echo "    .env.local already exists, skipping"; \
	fi
	@rm -f .env.local.ports

	@echo "==> [6/10] Copying docker-compose.example.yml → docker-compose.yml..."
	@if [ ! -f docker-compose.yml ]; then \
		cp docker-compose.example.yml docker-compose.yml; \
		echo "    docker-compose.yml created"; \
	else \
		echo "    docker-compose.yml already exists, skipping"; \
	fi

	@echo "==> [7/10] Updating /etc/hosts..."
	@grep -q "sftest2.my" /etc/hosts || { echo "127.0.0.1 sftest2.my pma.sftest2.my" | sudo tee -a /etc/hosts > /dev/null; echo "    added sftest2.my pma.sftest2.my"; }
	@grep -q "pma.sftest2.my" /etc/hosts || { echo "127.0.0.1 pma.sftest2.my" | sudo tee -a /etc/hosts > /dev/null; echo "    added pma.sftest2.my"; }
	@echo "    /etc/hosts OK"

	@echo "==> [8/10] Building and starting containers..."
	@if docker image inspect sftest2-app > /dev/null 2>&1; then \
		echo "    image sftest2-app already exists, skipping build"; \
	else \
		$(COMPOSE) build; \
	fi
	$(COMPOSE) up -d

	@echo "==> [9/10] Installing dependencies and building assets..."
	$(COMPOSE) exec app composer install --no-interaction --no-progress
# 	$(COMPOSE) exec app npm install
# 	$(COMPOSE) exec app npm run build

# 	@echo "==> [10/10] Installing git hooks..."
# 	@$(MAKE) hooks

	@echo "==> [11/11] Clearing Symfony cache..."
	$(COMPOSE) exec app bin/console cache:clear

	@echo ""
	@echo "============================================"
	@echo "  Done!"
	@echo ""
	@echo "  URLs:"
	@echo "    https://sftest2.my        — application"
	@echo "    https://pma.sftest2.my    — phpMyAdmin"
	@echo ""
	@echo "  Database credentials:"
	@echo "    user:  sftest2       pass: sftest2"
	@echo "    root:  root     pass: root"
	@echo ""
	@echo "  /etc/hosts updated automatically."
	@echo "  Browser SSL warning on first visit — add an exception once."
	@echo "============================================"
	@echo ""
