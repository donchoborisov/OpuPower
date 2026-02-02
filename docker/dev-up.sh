#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
COMPOSE_FILE="$ROOT/docker/docker-compose.yml"

cd "$ROOT"

if [ ! -f .env ]; then
  cp .env.example .env
  echo "Created .env from .env.example"
fi

set_env() {
  local key="$1"
  local value="$2"
  if grep -q "^${key}=" .env; then
    perl -pi -e "s#^${key}=.*#${key}=${value}#" .env
  else
    echo "${key}=${value}" >> .env
  fi
}

set_env APP_URL "http://localhost:8080"
set_env DB_HOST "db"
set_env DB_DATABASE "laravel"
set_env DB_USERNAME "laravel"
set_env DB_PASSWORD "secret"
set_env MAIL_HOST "mailhog"
set_env MAIL_PORT "1025"

ASSETS=false
SKIP_MIGRATE=false
RUN_TESTS_ON_START="${RUN_TESTS_ON_START:-true}"
HALT_ON_TEST_FAIL="${HALT_ON_TEST_FAIL:-false}"
SEED_PAGES_ON_START="${SEED_PAGES_ON_START:-true}"

for arg in "$@"; do
  case "$arg" in
    --assets) ASSETS=true ;;
    --skip-migrate) SKIP_MIGRATE=true ;;
    --help|-h)
      echo "Usage: docker/dev-up.sh [--assets] [--skip-migrate]"
      exit 0
      ;;
  esac
done

docker compose -f "$COMPOSE_FILE" down --remove-orphans
docker compose -f "$COMPOSE_FILE" up -d --build

echo "Waiting for database to be ready..."
READY=false
for i in $(seq 1 30); do
  if docker compose -f "$COMPOSE_FILE" exec -T db mysqladmin ping -h "localhost" --silent; then
    READY=true
    break
  fi
  sleep 1
done

if [ "$READY" != "true" ]; then
  echo "Database not ready after 30s; continuing anyway."
fi

if [ ! -f vendor/autoload.php ]; then
  docker compose -f "$COMPOSE_FILE" exec -T app composer install
fi

APP_KEY_VALUE="$(grep -E '^APP_KEY=' .env | cut -d= -f2- || true)"
if [ -z "$APP_KEY_VALUE" ]; then
  docker compose -f "$COMPOSE_FILE" exec -T app php artisan key:generate
fi

if [ "$SKIP_MIGRATE" != "true" ]; then
  docker compose -f "$COMPOSE_FILE" exec -T app php artisan migrate --seed --force
fi

if [ "$SEED_PAGES_ON_START" = "true" ]; then
  docker compose -f "$COMPOSE_FILE" exec -T app php artisan db:seed --class=PagesTableSeeder --force
fi

# Ignore error if storage link already exists
set +e

docker compose -f "$COMPOSE_FILE" exec -T app php artisan storage:link

set -e

if [ "$RUN_TESTS_ON_START" = "true" ]; then
  echo "Running core tests..."
  if ! docker compose -f "$COMPOSE_FILE" exec -T app php artisan test --group=core; then
    if [ "$HALT_ON_TEST_FAIL" = "true" ]; then
      echo "Tests failed; stopping containers."
      docker compose -f "$COMPOSE_FILE" down
      exit 1
    fi
    echo "Tests failed; continuing because HALT_ON_TEST_FAIL=false"
  fi
fi

if [ "$ASSETS" = "true" ]; then
  docker compose -f "$COMPOSE_FILE" run --rm node npm install
  docker compose -f "$COMPOSE_FILE" run --rm node npm run dev
fi

echo "App should be running at http://localhost:8080"
