# Folioform

Folioform is a Laravel portfolio generator. Create a portfolio, save personal and professional details, choose one of three layouts, and publish a shareable portfolio page.

## Features

- Create, edit, preview, and delete portfolios.
- Simple, Modern, and Creative templates, each rendered from the same saved data.
- Public portfolio pages at `/p/{slug}`.
- Private management links use a random 64-character key; only its SHA-256 hash is stored in the database. Keep the management URL private.
- PostgreSQL configuration for Supabase. Profile photos can use a public Supabase Storage bucket; local storage is used when Storage credentials are not configured.

## Local Setup

Requirements: PHP 8.3+, Composer, Node.js 22+, and PostgreSQL or SQLite for local development.

1. Install dependencies: `composer install` and `npm install` (use `npm.cmd` in Windows PowerShell if script execution is restricted).
2. Copy `.env.example` to `.env`. For local SQLite, set `DB_CONNECTION=sqlite` and `DB_DATABASE=database/database.sqlite`; for PostgreSQL, enter your connection details.
3. Generate an application key: `php artisan key:generate`.
4. Create the local SQLite file if using SQLite: `database/database.sqlite`.
5. Run migrations: `php artisan migrate`, and run `php artisan storage:link` when using local profile-picture storage.
6. Build assets: `npm run build` (or `npm.cmd run build` in Windows PowerShell).
7. Start the app: `php artisan serve`.

Open `http://127.0.0.1:8000`.

## Supabase

In Supabase, create a PostgreSQL project and copy its connection details from **Project Settings → Database**. Set `DB_CONNECTION=pgsql`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, and `DB_SSLMODE=require` in the deployment environment. Use the pooler connection details if the host cannot reach Supabase's direct database endpoint. Run `php artisan migrate --force` after setting those values.

To persist profile pictures on a host with an ephemeral filesystem, create a **public** Storage bucket (default name `portfolio-assets`) and set `SUPABASE_URL`, `SUPABASE_SERVICE_KEY`, and `SUPABASE_STORAGE_BUCKET`. The service-role key must only be configured as a server-side secret; never expose it to the browser.

## Deploy on Render

The included `Dockerfile` and `render.yaml` prepare a web service. Push this repository to a Git provider, create a Render Blueprint from `render.yaml`, and set the requested secrets and database values in the service's environment. Render supplies the public URL after the first successful deploy. Configure `APP_URL` to that URL. The container runs database migrations during startup.

The live system requires a Supabase project and a hosting account; neither database credentials nor a public deployment URL can be created from this workspace alone.