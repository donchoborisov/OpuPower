

## OpuPower Github repo


<img width="1314" alt="image" src="https://user-images.githubusercontent.com/48497063/154512952-e810c3d3-4330-49c6-b021-8acc619d9a63.png">


https://opupower.co.uk/

## Local development (Docker)

Docker setup lives in `docker/`. The application stays at repo root.

1) Copy the env file:

```bash
cp .env.example .env
```

2) Update `.env` for containers:

```
APP_URL=http://localhost:8080
DB_HOST=db
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=secret
MAIL_HOST=mailhog
MAIL_PORT=1025
SITE_LOGO=img/365.png
ADMIN_NAME=Admin
ADMIN_EMAIL=admin@admin.com
ADMIN_PASSWORD=password
```

3) Build and start containers:

```bash
docker compose -f docker/docker-compose.yml up -d --build
```

You can also use the helper script:

```bash
./docker/dev-up.sh
```

To build assets too:

```bash
./docker/dev-up.sh --assets
```

Skip migrations if you already ran them:

```bash
./docker/dev-up.sh --skip-migrate
```

Run tests on startup (default: true), and optionally halt on failure:

```bash
RUN_TESTS_ON_START=true HALT_ON_TEST_FAIL=false ./docker/dev-up.sh
```

Run the core test suite manually:

```bash
docker compose -f docker/docker-compose.yml exec app php artisan test --group=core
```

4) Install PHP deps, generate key, migrate & seed:

```bash
docker compose -f docker/docker-compose.yml exec app composer install
docker compose -f docker/docker-compose.yml exec app php artisan key:generate
docker compose -f docker/docker-compose.yml exec app php artisan migrate --seed
docker compose -f docker/docker-compose.yml exec app php artisan storage:link
```

5) Build assets:

```bash
docker compose -f docker/docker-compose.yml run --rm node npm install
docker compose -f docker/docker-compose.yml run --rm node npm run build
```

Open `http://localhost:8080`. Filament admin is at `/admin` with the user from `ADMIN_EMAIL` / `ADMIN_PASSWORD`.

If you hit permissions issues on `storage/` or `bootstrap/cache/`, run:

```bash
docker compose -f docker/docker-compose.yml exec app chmod -R 775 storage bootstrap/cache
```

For hot-reload during frontend work, run Vite in a separate terminal:

```bash
docker compose -f docker/docker-compose.yml run --rm --service-ports node npm run dev
```

Vite will be available on `http://localhost:5173`.
