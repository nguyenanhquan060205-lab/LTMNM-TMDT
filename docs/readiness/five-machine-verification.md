# Five-Machine Verification

## Purpose

This process proves that the TechSecond parallel-work foundation can be reproduced on every developer machine before the five module branches start.

The scripts run the same local gates as CI, rebuild only the approved MySQL testing database, and write a JSON evidence file per member.

## Preconditions

- Checkout the agreed parallel base commit on `develop`.
- Do not run this from `main`.
- Working tree must be clean.
- PHP 8.4.x, Composer 2.x, Node.js, npm, MySQL 8.4, and Git must be available in PATH.
- `composer.lock` and `package-lock.json` must be present.
- `.env.testing` must exist locally and must not be committed.

## Create MySQL Testing Database

Create a dedicated local database and user. Use local credentials appropriate for the machine.

```sql
CREATE DATABASE IF NOT EXISTS techsecond_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'techsecond_app'@'localhost' IDENTIFIED BY 'change-me';
GRANT ALL PRIVILEGES ON techsecond_test.* TO 'techsecond_app'@'localhost';
FLUSH PRIVILEGES;
```

The database name may include a suffix, for example `techsecond_test_tv1`, but it must start with `techsecond_test`.

## Prepare `.env.testing`

Use MySQL only:

```dotenv
APP_ENV=testing
APP_DEBUG=true
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=techsecond_test
DB_USERNAME=techsecond_app
DB_PASSWORD=<local-only-password>
CACHE_STORE=array
SESSION_DRIVER=array
QUEUE_CONNECTION=sync
MAIL_MAILER=array
```

Do not paste `.env.testing` into chat or PR comments. The verification report stores only `DB_CONNECTION` and `DB_DATABASE`.

## Run PowerShell

```powershell
.\scripts\verify-parallel-readiness.ps1 -MemberId TV1 -ExpectedCommit <PARALLEL_BASE_COMMIT>
```

Optional output directory:

```powershell
.\scripts\verify-parallel-readiness.ps1 -MemberId TV1 -ExpectedCommit <PARALLEL_BASE_COMMIT> -OutputDirectory storage/app/readiness
```

## Run Bash

```bash
bash scripts/verify-parallel-readiness.sh --member-id TV1 --expected-commit <PARALLEL_BASE_COMMIT>
```

Optional output directory:

```bash
bash scripts/verify-parallel-readiness.sh --member-id TV1 --expected-commit <PARALLEL_BASE_COMMIT> --output-directory storage/app/readiness
```

## Expected Commit

Use the `develop` SHA recorded in `docs/readiness/parallel-base-commit.md`.

The script stops with a non-zero exit code if the current commit does not match the expected commit.

## Read The Output

The default output path is:

```text
storage/app/readiness/<TVx>-<commit>-readiness.json
```

The JSON includes:

- member id;
- hostname and OS;
- PHP, Composer, Node, and npm versions;
- branch and commit;
- database connection and database name;
- command exit codes;
- PHPUnit pass/fail/skipped/assertion counts when parsed;
- final status.

## Submit Evidence

Each member sends only their JSON report. Do not send `.env.testing`, database passwords, app keys, or local logs containing secrets.

The release coordinator records the report path/status in `docs/readiness/five-machine-verification-results.json`.

## PASS Criteria

- Script exit code is `0`.
- `final_status` is `PASS`.
- Commit matches the approved parallel base commit.
- Database is MySQL and name starts with `techsecond_test`.
- All command gates pass.
- Report contains no secrets.

## FAIL Criteria

- Working tree is dirty.
- Commit does not match.
- DB guard fails.
- Any Composer, migration, Pint, quality, test, or build gate fails.
- Report cannot be parsed as JSON.

## Database Safety Rule

The scripts run `php artisan migrate:fresh --seed --env=testing`, which drops all tables in the configured testing database. They refuse to continue unless:

- command environment is `testing` or `.env.testing` declares `APP_ENV=testing`;
- `DB_CONNECTION=mysql`;
- `DB_DATABASE` starts with `techsecond_test`.

Never bypass this guard.

