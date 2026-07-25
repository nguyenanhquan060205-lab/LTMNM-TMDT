# Local Setup

Requirements:

- PHP 8.4.x
- Composer 2.x
- MySQL 8.4
- Node.js LTS
- npm

Commands:

```bash
composer install
npm ci
copy .env.example .env
php artisan key:generate
```

Use `cp .env.example .env` on macOS/Linux.

Create local databases:

```text
techsecond
techsecond_test
```

Before destructive commands, verify `DB_DATABASE` is whitelisted.
