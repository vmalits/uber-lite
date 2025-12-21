## Uber Lite — local setup (RoadRunner only)

This guide explains how to run the project locally using Docker (PostgreSQL, Redis, Mailpit), build the frontend, and serve the app with RoadRunner.

### Requirements

- PHP 8.4+
- Composer 2.x
- Node.js 18+ and npm/yarn (Vite)
- Docker + Docker Compose (for PostgreSQL/Redis/Mailpit)
- Optional: `make` (targets are provided in the repository `Makefile`)

### 1) First-time setup

```bash
git clone https://github.com/vmalits/uber-lite.git
cd uber-lite

# Start infra (Postgres, Redis, Mailpit)
make up

# Install PHP deps and download RoadRunner binary
make install            # (uses vendor/bin/dload and sets chmod +x rr)

# Prepare env and build assets in one go (install, copy .env, key:generate, migrate, npm install, build)
composer run setup
```

- Mailpit UI: http://localhost:8025 (SMTP: 1025)
- Buggregator UI: http://localhost:8001 (SMTP: 1026, Dumper: 9912, Ray: 10001)
- RoadRunner will listen on http://localhost:8080 (see `.rr.yaml`). Static files are served from `public`.

### 2) Start the application (RoadRunner)

```bash
make rr                 # starts RR with .rr.yaml on :8080
```

- Stop/Restart: Ctrl+C (if foreground) or `make stop`; then run `make rr` again.

### 3) Frontend dev (Vite)

Run the Vite dev server alongside RR during development:

```bash
npm run dev             # typically serves on http://localhost:5173
```

If you’re using a local SPA, point it at the API base `http://localhost:8080`.

### 4) Testing

```bash
make test   # starts the test suite
make coverage  # generates coverage report
```

### 5) API documentation (Scribe)

```bash
make docs
```

- Generated docs location: `public/docs/index.html`.

### Make targets (cheat sheet)

```bash
# Infra
make up           # start postgres, redis, mailpit
make down         # stop & remove containers/volumes
make logs         # all logs
make db-logs      # postgres logs
make redis-logs   # redis logs

# App
make install      # deps + rr binary (chmod +x rr)
make rr           # start RoadRunner (http://localhost:8080)

# Laravel
make migrate      # php artisan migrate --force
make fresh        # migrate:fresh + seed
make seed         # php artisan db:seed --force
make test         # php artisan test
make analyse      # phpstan analyse
make format       # pint
make rector       # rector
make lint         # pint + phpstan
make docs         # scribe:generate -> public/docs
make coverage     # pest coverage
```

### Troubleshooting

- Ports in use: RR (8080), Postgres (5432/5433), Redis (6379), Mailpit (1025/8025), Buggregator (8001/1026/9912/10001). Free or change ports if needed.
- RR binary permissions: `make install` sets `chmod +x rr`; if you downloaded RR manually, run `chmod +x rr`.
- Cache/config issues: `php artisan config:clear && php artisan cache:clear`, remove `storage/framework/cache/*`.
- Permissions on macOS/Linux: `chmod -R 777 storage bootstrap/cache` (local only).
- After composer updates: restart the RR server (`Ctrl+C` then `make rr`).

---

Stack: Laravel 12, PHP 8.4, PostgreSQL, Redis, Vite/Tailwind. Served via RoadRunner.
