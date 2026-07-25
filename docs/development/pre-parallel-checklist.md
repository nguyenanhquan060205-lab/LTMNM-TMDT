# Pre-Parallel Checklist

Gate 0 requires:

- Laravel boots.
- Composer install uses lock file.
- `composer validate --strict` passes.
- `composer check-platform-reqs` passes.
- Migrations run from an empty database.
- Seeders run.
- PHPUnit passes.
- Pint passes.
- Quality scanner passes.
- `npm ci` passes.
- `npm run build` passes.
- GitHub Actions CI exists.
- No P0 blocker remains.
