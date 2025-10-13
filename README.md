# Laravel Blog API

A fast, secure, and extensible RESTful Blog API built with Laravel — ready to open-source.
Clean architecture, sensible defaults, and developer ergonomics in mind: authentication, policies, pagination, search, tags, image uploads, and test coverage. Perfect as a starter template or a production-ready microservice.

---

## Table of Contents

- [Laravel Blog API](#laravel-blog-api)
  - [Table of Contents](#table-of-contents)
- [Why this project](#why-this-project)
- [Features](#features)
- [Tech stack](#tech-stack)
- [Architecture \& conventions](#architecture--conventions)
- [Getting started (quick start)](#getting-started-quick-start)
- [Environment variables (.env)](#environment-variables-env)
- [Database \& migrations](#database--migrations)
- [Seeding (demo data)](#seeding-demo-data)
- [Authentication](#authentication)
- [API Endpoints (examples)](#api-endpoints-examples)
  - [Authentication](#authentication-1)
  - [Posts](#posts)
  - [Categories \& Tags](#categories--tags)
  - [Comments](#comments)
  - [Users](#users)
    - [Example curl — create post](#example-curl--create-post)
- [Validation \& Error format](#validation--error-format)
- [Testing](#testing)
- [Local development with Docker](#local-development-with-docker)
- [CI / Workflow suggestions](#ci--workflow-suggestions)
- [Contributing](#contributing)
- [Roadmap](#roadmap)
- [Troubleshooting](#troubleshooting)
- [Tips for maintainers](#tips-for-maintainers)
- [Example `composer.json` scripts](#example-composerjson-scripts)
- [License](#license)
- [Aesthetic touches (badges \& links)](#aesthetic-touches-badges--links)
  - [About Laravel](#about-laravel)
  - [Learning Laravel](#learning-laravel)
  - [Laravel Sponsors](#laravel-sponsors)
    - [Premium Partners](#premium-partners)
  - [Contributing](#contributing-1)
  - [Code of Conduct](#code-of-conduct)
  - [Security Vulnerabilities](#security-vulnerabilities)
  - [License](#license-1)

---

# Why this project

This repo provides a production-minded Laravel backend for blog-driven applications: headless blogs, content platforms, admin dashboards, and learning resources. It’s opinionated but simple — designed for contributors and teams who want a robust starting point for content APIs.

It focuses on:

* Clean REST API design
* Security (policies, request validation)
* Testability
* Extensibility (tags, categories, image uploads, roles)
* Good developer DX (artisan commands, seeders, docs)

---

# Features

* Authentication: Laravel Sanctum (token-based SPA/API)
* CRUD for Posts, Categories, Tags, Comments, Users
* Role-based access (admin, editor, author, reader)
* Soft deletes + restore
* Media handling (image upload + CDN-ready storage abstraction)
* Pagination, sorting, and filtering
* Full-text search (Eloquent + Scout-ready hooks)
* Request validation, API Resources (JSON:API-ish responses)
* Rate limiting, CORS, and basic security headers
* Unit & Feature tests (PHPUnit)
* Docker Compose setup for easy local bootstrapping
* Postman/Insomnia collection example (optional)

---

# Tech stack

* PHP 8.1+ / 8.2+
* Laravel 10+
* MySQL / PostgreSQL (configurable)
* Redis (cache & queues)
* Laravel Sanctum (auth)
* Eloquent ORM
* Laravel Queues (for image processing / notifications)
* PHPUnit for tests
* Docker (optional; Compose file included)

---

# Architecture & conventions

* HTTP controllers are thin — business logic lives in Services / Actions.
* Repositories or Eloquent models are used for data access.
* Policies for authorization.
* API Resources for response shapes.
* Form Requests for validation.
* Feature tests for endpoints; Unit tests for services.
* Semantic versioning for releases.

---

# Getting started (quick start)

Clone the repo:

```bash
git clone https://github.com/crindalwalt/laravel-blog-api.git
cd laravel-blog-api
```

Install dependencies:

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Set your DB variables in `.env`, then:

```bash
php artisan migrate
php artisan db:seed
php artisan storage:link
```

Serve locally:

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

Or use Docker (see Docker section below).

---

# Environment variables (.env)

Add or confirm important keys in your `.env`:

```
APP_NAME="Laravel Blog API"
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=blog_api
DB_USERNAME=root
DB_PASSWORD=

CACHE_DRIVER=file
QUEUE_CONNECTION=database
SESSION_DRIVER=file
SESSION_LIFETIME=120

SANCTUM_STATEFUL_DOMAINS=localhost
FILESYSTEM_DISK=public

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

SCOUT_DRIVER=database
```

Add third-party API keys as needed (S3, Cloudinary, Algolia, etc.).

---

# Database & migrations

Migrations included for:

* users (with role)
* posts (title, slug, body, status, published_at)
* categories
* tags
* post_tag pivot
* comments
* media (optional)
  Run:

```bash
php artisan migrate
```

Soft deletes are used for posts and comments. Use `php artisan model:prune` if you want to prune soft-deleted items on schedule.

---

# Seeding (demo data)

Demo data includes: users (admin/editor/author), categories, tags, posts, comments.

Run:

```bash
php artisan db:seed
```

If you want a larger dataset for UI testing:

```bash
php artisan db:seed --class=LargeDemoSeeder
```

---

# Authentication

This project uses **Laravel Sanctum** for API token and SPA authentication.

* To register:

  * `POST /api/auth/register` with `{ name, email, password }`
* To login & receive token:

  * `POST /api/auth/login` with `{ email, password }` → returns `token`
* Protect routes with `auth:sanctum` middleware.

Example: attach token as header

```
Authorization: Bearer <token>
```

(You can swap Sanctum for Passport or JWT easily — see `config/auth.php` and `AuthServiceProvider`.)

---

# API Endpoints (examples)

Below is a compact list of the main endpoints. Update for your exact routes.

## Authentication

* `POST /api/auth/register` — register
* `POST /api/auth/login` — login
* `POST /api/auth/logout` — logout (auth)

## Posts

* `GET /api/posts` — list posts (pagination, filter, sort)

  * params: `page`, `per_page`, `q` (search), `tag`, `category`, `author`, `status`
* `GET /api/posts/{id}` — show single post (slug-friendly)
* `POST /api/posts` — create post (auth + permission)
* `PUT /api/posts/{id}` — update post
* `DELETE /api/posts/{id}` — soft-delete post
* `POST /api/posts/{id}/restore` — restore soft-deleted post (admin)

## Categories & Tags

* `GET /api/categories`, `POST /api/categories` (admin)
* `GET /api/tags`, `POST /api/tags` (editor/editor+)

## Comments

* `GET /api/posts/{id}/comments`
* `POST /api/posts/{id}/comments` (auth)
* `DELETE /api/comments/{id}` (moderator/admin)

## Users

* `GET /api/users/{id}` — profile (private/public)
* `PUT /api/users/{id}` — update profile (owner or admin)

### Example curl — create post

```bash
curl -X POST http://localhost:8000/api/posts \
  -H "Authorization: Bearer <token>" \
  -H "Content-Type: application/json" \
  -d '{
    "title":"My first post",
    "body":"Hello world",
    "category_id":1,
    "tags":[ "laravel", "api" ],
    "status":"draft"
  }'
```

---

# Validation & Error format

All API errors return a structured JSON response:

```json
{
  "message": "Validation failed",
  "errors": {
    "title": ["The title field is required."]
  }
}
```

Successful responses use standardized resource wrappers (`data`, `meta`, `links` where applicable).

---

# Testing

Run the test suite:

```bash
php artisan test
# or
vendor/bin/phpunit
```

Tips:

* Use factories for consistent data generation.
* Use `RefreshDatabase` trait for feature tests.
* CI should run tests on PRs.

---

# Local development with Docker

A simple `docker-compose.yml` is provided (PHP-FPM, Nginx, MySQL/Postgres, Redis). Example commands:

```bash
# Build & start
docker compose up -d --build

# Run migrations inside app container
docker compose exec app php artisan migrate --seed

# Run tests (in container)
docker compose exec app php artisan test
```

If you prefer native PHP, use Sail or the bundled `Dockerfile` as reference.

---

# CI / Workflow suggestions

* GitHub Actions template:

  * `php-cs-fixer` linting
  * `composer install --prefer-dist`
  * `php artisan test` (with sqlite in-memory)
  * static analysis (Psalm / PHPStan)
* Protect `main` branch; require PR review & passing checks.

---

# Contributing

Thanks for considering contributing! Keep it simple:

1. Fork the repo
2. Create a feature branch: `feature/my-fix`
3. Follow PSR-12 coding standards
4. Add tests for new behavior
5. Open a PR describing what you changed and why

Code style: run `composer cs` (if included) or `php-cs-fixer`.

Please follow the existing pattern: Controllers are thin, service layer for business logic, requests for validation.

---

# Roadmap

* ✅ Basic CRUD for posts, tags, categories
* ✅ Authentication (Sanctum)
* ✅ Seeders & demo data
* ⏳ Image processing & CDN integration
* ⏳ Full-text search integration (Scout + driver)
* ⏳ GraphQL endpoint (optional)
* ⏳ Webhook & RSS feed support
* ⏳ Admin panel (separate repo / frontend)

---

# Troubleshooting

* `storage:link` creates symlink for public disk — run if images 404.
* If migrations fail with charset issues, check `DB_CHARSET` / `DB_COLLATION`.
* Sanctum token issues: confirm `SANCTUM_STATEFUL_DOMAINS` and CORS settings.
* If queue jobs stuck: run `php artisan queue:work` or use Horizon for monitoring.

---

# Tips for maintainers

* Keep tests green; add regression tests for new features.
* Run dependency updates regularly (`composer update --with-all-dependencies`) and test in a sandbox branch.
* Keep migrations additive and reversible where possible.

---

# Example `composer.json` scripts

```json
"scripts": {
  "test": "php artisan test",
  "lint": "php-cs-fixer fix --dry-run --diff",
  "post-root-package-install": [
    "@php -r \"file_exists('.env') || copy('.env.example', '.env');\""
  ]
}
```

---

# License

This project is open-source — choose a license (MIT recommended). Example `LICENSE` file: **MIT License**.

---

# Aesthetic touches (badges & links)

Replace placeholders with real URLs in your repo:

* Build status | Coverage | License | Code style

```md
![build](https://img.shields.io/github/actions/workflow/status/<user>/<repo>/ci.yml)
![license](https://img.shields.io/github/license/<user>/<repo>)
```

---

If you want, I can:

* generate a matching `README` badge list and `CONTRIBUTING.md`,
* produce the Postman collection JSON,
* scaffold the initial routes & controllers as code,
* or convert this README into a GitHub release-ready template with badges and examples.

Which of those should I add now?

</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
