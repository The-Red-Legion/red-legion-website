# Copilot instructions for Red Legion Website

Purpose: provide concise, repo-specific guidance so an AI coding agent can be productive immediately.

- Big picture
  - This is a PHP 8.1 procedural web application. The webroot is [public_html](public_html).
  - App initialization and shared services are in [app/bootstrap.php](app/bootstrap.php). It returns a `pdo` and `env` array used by entry points.
  - Helpers and business logic are primarily in [app/functions.php](app/functions.php) (applicant flows, RSI validation, guild membership imports).
  - Frontend fragments (headers, footers, snippets) live in [app/partials](app/partials) and are included by files in [public_html](public_html).
  - Dependencies are managed with Composer; autoload lives in [vendor/autoload.php](vendor/autoload.php).

- Key integration points & patterns
  - Database access: global `$pdo` and prepared statements (see many examples in `app/functions.php`). Avoid replacing that global without refactoring all callers.
  - Session usage: sessions are started/secured in [app/bootstrap.php](app/bootstrap.php). Many functions rely on `$_SESSION['user']` and `$_SESSION['access_token']` (see `insertApplicantFromDiscord`).
  - External calls: RSI profile checks use cURL in `fetchRSIProfileHtml()`; token validation is a plain substring check (`rsiProfileContainsToken`).
  - Files under [public_html](public_html) are treated as the public entry points; changing paths requires updating Nginx config or deploy scripts.

- Developer workflows (commands you can run)
  - Local dev with Docker: `docker-compose up --build` (web on port 8080, phpMyAdmin on 8081). See `docker-compose.yml`.
  - Install PHP deps locally: `composer install` from repo root.
  - Quick deploy scripts: `./deploy.sh` for a simple standalone VM setup; production-style runs use Ansible: `ansible/playbook -i ansible/inventory.ini ansible/deploy.yml` (see `ansible/`).
  - Verify app bootstrap: open `http://localhost:8080/` (or your configured host) and `http://localhost:8081/` for phpMyAdmin.

- Project-specific conventions
  - Procedural style: many files use global scope (globals, includes). Prefer small, backwards-compatible changes over sweeping architecture changes.
  - Return shapes: `app/bootstrap.php` returns an array `['pdo'=>..., 'env'=>...]` — entry scripts expect these semantics.
  - DB table naming & behavior: callers assume tables like `Applicants`, `Guild_Memberships` with certain columns (see `app/functions.php`). When editing SQL, keep column names and status values consistent (e.g., `Status` values: 'Unsubmitted', 'Applied', 'Approved', 'Denied').
  - Partial includes: header/footer snippets are plain HTML/PHP includes — modify them carefully to avoid breaking many pages.

- Safety notes and gotchas
  - Environment variables are critical: DB_* and APP_ENV are read in `app/bootstrap.php`. Missing values will throw runtime exceptions in dev or die quietly in prod.
  - No automated test suite present — prefer small, manual verification steps (curl, visiting pages) and add unit tests in a separate PR if needed.
  - Be conservative with global refactors. If you introduce DI, update bootstrapping and a representative set of consumers.

- Helpful files to review when making changes
  - [app/bootstrap.php](app/bootstrap.php) — initialization, session and PDO setup.
  - [app/functions.php](app/functions.php) — core business logic and SQL examples.
  - [public_html/index.php](public_html/index.php) & other public files — how templates/partials are included.
  - [docker-compose.yml](docker-compose.yml), [deploy.sh](deploy.sh), [ansible/deploy.yml](ansible/deploy.yml) — dev and deploy workflows.

If any section is unclear or you'd like examples expanded (SQL schema snippets, common edit patterns, or a suggested small-first refactor), tell me which part to expand.
