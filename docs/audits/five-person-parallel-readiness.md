# Five-Person Parallel Readiness Audit

## 1. Verdict

Repository Foundation:
REPOSITORY_FOUNDATION_NOT_READY

Five-Member Operational:
NOT_READY_FOR_5_PERSON_PARALLEL_WORK

## 2. Executive Summary

Current repository state is stronger than the old handover state: active Laravel source is at repository root, legacy ASP.NET and old Laravel port code are isolated under `legacy/`, route modules exist, canonical migrations/models/enums are present, and several foundation checks pass.

The project is still not ready to split into five parallel branches because mandatory gates fail on the current checkout. `composer install` fails during `php artisan package:discover`, `composer audit` reports active `dompdf/dompdf` advisories, `php artisan test` fails one upload contract test, and `npm run build` fails with `EPERM` while preparing `public/build/assets`.

Operational readiness is also not established: five-machine evidence is pending for all members, branch protection could not be verified because `gh` is unavailable, and only `CODEOWNERS.template` exists.

## 3. Baseline
- Branch: `chore/pre-parallel-foundation`
- HEAD: `1e9f5f04e977ef0843a42fffae2ed9e35be30e92`
- Working tree: clean before report creation; `git status --short` had no output after gates
- Previous audited HEAD: `5c9ed90c5899c4c6549dd2743154c59c0b6d0240`
- Drift from previous audit: baseline commit is ancestor of current HEAD; new commits are `424c5db chore: establish pre-parallel Laravel foundation` and `1e9f5f0 chore: finalize parallel readiness foundation`. Drift mainly moves Laravel to repo root, adds contracts/tests/CI/locks, and isolates legacy code.

## 4. Safety Checks
| Check | Result | Evidence |
| --- | --- | --- |
| Active Laravel codebase | PASS | `LTMNM-TMDT` root contains `artisan`, `app`, `routes/modules`, `composer.lock`; legacy code is under `legacy/`. |
| Legacy ignored for active scans | PASS | Scans targeted `app resources routes config database tests lang`; legacy paths excluded. |
| Local destructive DB whitelist | PASS | `.env`: `DB_CONNECTION=mysql`, `DB_DATABASE=techsecond`. |
| Testing destructive DB whitelist | PASS | `phpunit.xml`: `DB_CONNECTION=mysql`, `DB_DATABASE=techsecond_test`; `.env.testing` also points to `techsecond_test`. |
| Secret handling | PASS | Only non-sensitive DB driver/host/port/name were inspected; no password/key/secret values recorded. |
| Unsafe destructive commands | PASS | Did not run `db:wipe`, `DROP DATABASE`, or non-whitelisted destructive commands. |
| Working tree | PASS | `git status --short` clean after command gates. |

## 5. Commands Executed
| Command | Result | Exit code | Notes |
| --- | --- | ---: | --- |
| `git status --short` | PASS | 0 | No output before report creation. |
| `git branch --show-current` | PASS | 0 | `chore/pre-parallel-foundation`. |
| `git rev-parse HEAD` | PASS | 0 | `1e9f5f04e977ef0843a42fffae2ed9e35be30e92`. |
| `git log -1 --oneline` | PASS | 0 | `1e9f5f0 chore: finalize parallel readiness foundation`. |
| `git remote -v` | PASS | 0 | Origin GitHub remote present. |
| `php -v` | PASS | 0 | PHP `8.4.12`. |
| `php --ini` | PASS | 0 | Loaded php.ini under Laragon PHP 8.4.12. |
| `composer --version` | PASS | 0 | Composer `2.9.4`. |
| `mysql --version` | PASS | 0 | MySQL client `8.4.3`. |
| `node -v` | PASS | 0 | `v24.15.0`. |
| `npm -v` | PASS | 0 | `11.12.1`. |
| `composer validate --strict` | PASS | 0 | `composer.json is valid`. |
| `composer install --no-interaction --prefer-dist` | FAIL | 1 | Fails in `@php artisan package:discover --ansi`; cannot write `storage/logs/laravel.log`, reports `bootstrap/cache` not writable/present. |
| `composer check-platform-reqs` | PASS | 0 | Platform requirements satisfied. |
| `composer audit --format=plain` | FAIL | 1 | 6 advisories affecting `dompdf/dompdf v3.1.5`, affected `<3.1.6`. |
| `php artisan optimize:clear` | PASS | 0 | Cleared config/cache/compiled/events/routes/views. |
| `php artisan config:clear` | PASS | 0 | Config cache cleared. |
| `php artisan about` | PASS | 0 | Laravel `13.20.0`, database `mysql`, storage linked. |
| `php artisan route:list` | PASS | 0 | 61 routes listed. |
| `php artisan package:discover --ansi` | FAIL | 1 | Same write/cache exception as Composer install. |
| `php artisan migrate:fresh --seed --env=testing` | PASS | 0 | Ran only after confirming `techsecond_test`. |
| `php artisan migrate:fresh --seed` | PASS | 0 | Ran only after confirming `techsecond`. |
| `php tools/quality/check-forbidden-patterns.php` | PASS | 0 | Forbidden pattern scan passed. |
| `vendor/bin/pint --test` | PASS | 0 | Pint result passed. |
| PHP syntax lint over active PHP | PASS | 0 | No syntax errors in `app`, `config`, `database`, `routes`, `tests`. |
| `php artisan test` | FAIL | 1 | 39 tests total: 38 passed, 1 failed, 234 assertions, 4.08s. |
| `npm ci` | PASS | 0 | Installed 45 packages from lock. |
| `npm run build` | FAIL | 1 | Vite fails with `EPERM` deleting `public/build/assets`. |
| `composer run check:quality` | PASS | 0 | Runs quality scanner only. |
| `composer run verify` | FAIL | 1 | Fails at `@php artisan test` before frontend build. |
| `git diff --check` | PASS | 0 | No whitespace errors. |
| `gh auth status` | NOT_EXECUTED | 1 | `gh` command not found; GitHub settings require manual verification. |

## 6. Gate Results
| Gate | Result | Evidence |
| --- | --- | --- |
| Composer lock install | FAIL | `composer install` fails in package discovery. |
| Composer platform | PASS | `composer check-platform-reqs` all success. |
| Composer audit | FAIL | `dompdf/dompdf v3.1.5` has 6 advisories affecting `<3.1.6`. |
| Laravel boot | PARTIAL | `about`, `route:list`, `optimize:clear` pass; `package:discover` fails. |
| Route module load | PASS | `routes/web.php` imports only `routes/modules/*`; 61 routes listed. |
| Database migrate/seed | PASS | Local `techsecond` and testing `techsecond_test` migrate/seed pass. |
| Quality scanner | PASS | No active forbidden patterns found. |
| Pint | PASS | `vendor/bin/pint --test` pass. |
| PHP lint | PASS | Active PHP syntax lint pass. |
| PHPUnit | FAIL | Upload contract test fails due `MediaService::storeManagedFile()` returning `false`. |
| Frontend install | PASS | `npm ci` pass from `package-lock.json`. |
| Frontend build | FAIL | Vite `EPERM` on `public/build/assets`. |
| CI parity | PARTIAL | CI runs many gates but not `composer audit`; current local gates would fail CI at tests/build. |

## 7. Historical Issue Regression
| Historical issue | Current status | Evidence |
| --- | --- | --- |
| Razor/C# markers in active source | RESOLVED | No active `@using`, `Html.BeginForm`, `@Html.`, `ViewBag`, `.cshtml`; `namespace App...` matches are PHP false positives. |
| Mojibake in active runtime | RESOLVED | Active scan found no mojibake in app/resources/routes/database/lang; old handover doc contains examples only. |
| Route/controller signature mismatch | RESOLVED_FOR_FOUNDATION | Current route modules use closures or valid controllers; `route:list` pass. |
| Missing historical route names | RESOLVED_BY_RECONTRACTING | Old Vietnamese route names replaced by English contract names; views reference existing active route names. |
| GET mutation | RESOLVED_FOR_FOUNDATION | No route module GET route for delete/update/status/confirm/cancel/lock/unlock. |
| Admin middleware | PASS | `routes/modules/admin.php` group uses `auth`, `not_locked`, `admin`. |
| Legacy session auth | RESOLVED | No active `Session::put('user')` or `Session::get('user')`; auth uses Laravel `Auth`. |
| Legacy models/columns | RESOLVED | No active `NguoiDung`, `MaKH`, `MaSP`, `MaHD`; schema uses English names. |
| Plaintext password | RESOLVED | User cast `password => hashed`; seeder uses `Hash::make`; tests cover hashing. |
| Upload storage | REGRESSED | Contract exists but test fails at `MediaService::storeManagedFile()`, returning `false`. |
| Bootstrap CDN/Tailwind/public Content | RESOLVED | Active frontend uses `@vite`; scans found no Bootstrap CDN, Tailwind, `public/Content`, or `public/Scripts` dependency. |

## 8. Contract Audit
| Contract | Status | Code/document evidence | Drift |
| --- | --- | --- | --- |
| ERD | PRESENT_AND_MATCHES_CODE | `docs/database/erd-v2.md`; migration relationships match canonical entities. | `.png` not present, Markdown ERD used. |
| Migration skeleton | PRESENT_AND_MATCHES_CODE | `2026_07_22_000100_create_marketplace_foundation_tables.php` creates canonical marketplace tables. | No blocking drift. |
| Model skeleton | PRESENT_AND_MATCHES_CODE | Canonical models exist with fillable/casts/relationships. | Business implementations still contract-only for many modules. |
| Enum | PRESENT_AND_MATCHES_CODE | PHP enums match `docs/architecture/status-enums.md`; DB defaults use enum values. | No active Vietnamese status comparisons found. |
| Route contract | PRESENT_AND_MATCHES_CODE | `route:list` includes contract routes in `routes/modules/*.php`. | Foundation routes mostly abort 501 for future module work, acceptable for pre-parallel. |
| Service contract | PRESENT_AND_MATCHES_CODE | Interfaces exist for all modules; `AuthService` and `MediaService` concrete bound. | `MediaService` runtime test fails despite matching signature. |
| Blade variable contract | PRESENT_AND_MATCHES_CODE | `docs/architecture/blade-contract.md`; active views are lowercase placeholders/layouts. | Views are skeletal but route-safe. |
| JSON response contract | PRESENT_AND_MATCHES_CODE | Message broadcast payload contract in docs and `MessageSent::broadcastWith()`. | No full AJAX implementation yet; acceptable for foundation. |
| Storage contract | PRESENT_BUT_DRIFTED | `config/uploads.php`, `MediaService`, upload tests. | Test failure shows storage implementation is not reliable in current runtime. |
| Git workflow | PRESENT_BUT_DRIFTED | `git-workflow.md`, PR template, branch guide. | No real `.github/CODEOWNERS`; branch protection not verified. |
| Definition of Done | PRESENT_BUT_DRIFTED | `AGENTS.md`, PR template. | Issue template absent; enforcement depends on manual/GitHub settings. |
| CI | PRESENT_BUT_DRIFTED | `.github/workflows/ci.yml` exists and runs core gates. | Missing `composer audit`; branch protection not verified. |

## 9. Module Readiness
| Member | Module | Status | Dependencies | Blocking issues |
| --- | --- | --- | --- | --- |
| TV1 | Foundation, Auth, Profile | BLOCKED | Owns `User`, auth routes/controllers/requests/middleware; consumed by all modules. | Global gates fail; package discovery failure affects all developers. |
| TV2 | Category, Product, Search, Images | BLOCKED | Depends on `User`, `ProductStatus`, media storage; consumed by TV3/TV4/TV5. | Upload contract test failure blocks image work; global gates fail. |
| TV3 | Cart, Checkout, Orders, Inventory | BLOCKED | Depends on `User`, `Product`, order enums; consumed by TV4/TV5. | Schema/routes/contracts are adequate, but global test/audit/build gates fail before split. |
| TV4 | Review, Complaint, Admin | BLOCKED | Depends on `User`, `OrderItem`, `Product`, complaint enum. | Contracts adequate, admin middleware present, but global gates fail and GitHub review enforcement missing. |
| TV5 | Chat, PDF, Shared UI, CI | BLOCKED | Depends on `User`, `Product`, `Order`; owns frontend/build/CI/PDF dependency. | `composer audit` fails on DomPDF; `npm run build` fails; five-machine evidence missing. |

## 10. File Ownership And Conflict Matrix
| Path | Owner | Other consumers | Conflict level | Required coordination |
| --- | --- | --- | --- | --- |
| `composer.json` | TV1/TV5 | All | HIGH | Dependency changes require platform review and audit pass. |
| `composer.lock` | TV1/TV5 | All | HIGH | Lock changes serialized; required to remediate DomPDF advisory. |
| `package.json` | TV5 | All frontend users | MEDIUM | TV5 owns dependency/build changes. |
| `package-lock.json` | TV5 | All frontend users | MEDIUM | Lock changes serialized with `npm ci` verification. |
| `routes/web.php` | TV1/TV5 | All route modules | LOW | Import-only; add modules by coordination. |
| `bootstrap/app.php` | TV1 | All | MEDIUM | Middleware/routing alias changes need TV5 review. |
| `config/app.php` | TV1 | All | LOW | Coordinate only for app-wide config changes. |
| `.env.example` | TV1 | All | MEDIUM | Keep DB safety defaults and no secrets. |
| `database/seeders/DatabaseSeeder.php` | TV2 | TV1/TV3/TV4 | MEDIUM | Add module seeders via owner-reviewed blocks or delegated seeders. |
| `resources/css/app.css` | TV5 | All UI modules | MEDIUM | Shared UI owner review. |
| `resources/js/app.js` | TV5 | All UI modules | MEDIUM | Shared UI owner review. |
| `resources/views/layouts/app.blade.php` | TV5 | All views | HIGH | Layout changes require TV5 review. |
| `resources/views/layouts/admin.blade.php` | TV5 | TV4 admin | HIGH | TV4 consumes; TV5 owns. |
| `lang/vi/status.php` | TV4 | All status UI | MEDIUM | Enum/status labels require TV4 and affected module review. |
| `.github/workflows/ci.yml` | TV5 | All | HIGH | TV5 owns; TV1 review for foundation gates. |
| `app/Models/User.php` | TV1 | All modules | HIGH | Changes require dependent module review. |
| `app/Models/Product.php` | TV2 | TV3/TV4/TV5 | HIGH | Product schema/relationship changes require TV3/TV4/TV5 review. |
| `app/Models/Order.php`, `app/Models/OrderItem.php` | TV3 | TV4/TV5 | HIGH | Required before review/complaint/invoice changes. |
| `app/Contracts/Services/*` | Respective owner | Dependent modules | HIGH | Interface changes must be reviewed before implementation branches continue. |

## 11. Dependency Graph
- Serial prerequisites: fix package discovery/storage permission behavior, remediate DomPDF advisory in lock, make PHPUnit pass, make frontend build pass, then establish protected baseline commit.
- Parallel-safe work: after gates pass, TV1 auth/profile, TV2 product/category, TV3 cart/order, TV4 review/complaint/admin, and TV5 chat/invoice/UI can start from service/model/route contracts.
- Bottlenecks: TV1 for `User`/auth/middleware; TV2 for `Product`; TV3 for `OrderItem`; TV5 for shared UI, PDF dependency, CI, and frontend build.
- Cyclic dependencies: no hard code-level cycle found in contracts. Business dependencies are directional: `User -> Product -> Order/OrderItem -> Review/Complaint/Invoice`; shared UI is consumed by all.

## 12. Tests And Coverage Of Foundation Contracts
| Test category | Present | Result | Missing protection |
| --- | --- | --- | --- |
| foundation contract tests | YES | MOSTLY PASS | Upload/storage foundation test fails. |
| authentication tests | YES | PASS | Adequate for auth/session/hash/middleware foundation. |
| product tests | YES | PASS | Product business workflows still future work. |
| cart/order tests | YES | PASS | Checkout/inventory transaction tests are contract-only. |
| review/complaint tests | YES | PASS | Full authorization/ownership workflows not implemented yet. |
| chat/PDF tests | YES | PASS | PDF generation implementation not complete; DomPDF advisory blocks dependency gate. |
| integration/smoke tests | YES | FAIL | Overall `php artisan test` fails 1 upload test. |
| safety scanner | YES | PASS | Scanner is useful but not sole evidence. |

## 13. CI And Git Workflow
- CI: `.github/workflows/ci.yml` exists and runs Composer validate/install/platform, env prep, DB guard, migrate/seed, Pint, quality scanner, PHPUnit, `npm ci`, and `npm run build` on PR/push to `main`/`develop`.
- Branch protection: `MANUAL_GITHUB_VERIFICATION_REQUIRED`; `gh` is not installed.
- Required reviews: documented in PR template and branch guide, but GitHub enforcement was not verified.
- Shared-file review: documented in `CODEOWNERS.template`, but real `.github/CODEOWNERS` is absent.
- Manual verification: required for main/develop protection, required status check `CI / gate-0`, direct push blocking, code owner recognition, and review count.

## 14. Team Machine Evidence
| Member | Evidence | Commit | Gate result |
| --- | --- | --- | --- |
| TV1 | `docs/readiness/five-machine-verification-results.json` status `PENDING`, no machine report | N/A | PENDING |
| TV2 | `PENDING`, no machine report | N/A | PENDING |
| TV3 | `PENDING`, no machine report | N/A | PENDING |
| TV4 | `PENDING`, no machine report | N/A | PENDING |
| TV5 | `PENDING`, no machine report | N/A | PENDING |

## 15. Blockers
### P0
- `composer install --no-interaction --prefer-dist` fails in `php artisan package:discover`; package discovery cannot write log/cache in current runtime.
- `composer audit --format=plain` fails: 6 advisories affect locked `dompdf/dompdf v3.1.5`; affected versions `<3.1.6`.
- `php artisan test` fails: `Tests\Feature\Upload\UploadContractTest::media service stores relative paths and deletes managed files`, `MediaService::storeManagedFile()` returns `false`.
- `npm run build` fails: Vite cannot remove `public/build/assets` due `EPERM`.
- Five-machine verification is missing for TV1-TV5, so five-person operational split is not authorized.

### P1
- CI does not run `composer audit`, so the current DomPDF advisory would not be caught by GitHub Actions.
- Real `.github/CODEOWNERS` is missing; only `CODEOWNERS.template` exists with placeholders.
- GitHub branch protection, required checks, required reviews, and direct-push prevention were not verifiable locally.

### P2
- `.env.testing` contains `APP_ENV=local` even though testing commands/phpunit force testing context; this drifts from the documented `.env.testing` contract.
- `.github/ISSUE_TEMPLATE` is absent, so issue acceptance criteria are not standardized.

### P3
- ERD is present as `docs/database/erd-v2.md`; requested `docs/database/erd-v2.png` is not present.

## 16. Required Actions Before Split
- Fix package discovery/log/cache writability so `composer install --no-interaction --prefer-dist` exits 0 from a clean checkout.
- Remediate `dompdf/dompdf` advisories and commit the updated lock via controlled dependency change.
- Fix upload storage behavior so `php artisan test` passes.
- Fix reproducible frontend build so `npm run build` exits 0 without manual cleanup.
- Add `composer audit` to CI or otherwise enforce advisory checks.
- Create real `.github/CODEOWNERS`, configure branch protection/reviews, and record verification.
- Collect passing five-machine verification reports for TV1-TV5 against the agreed baseline commit.

## 17. Recommended Branch Allocation
| Member | Suggested branch | Owned paths | Required reviewers |
| --- | --- | --- | --- |
| TV1 | `feature/TV1-auth-profile` | `app/Models/User.php`, auth/profile controllers/requests/middleware/policy/routes/views/tests | TV4; TV5 for middleware/bootstrap changes |
| TV2 | `feature/TV2-products-categories` | Category/product/image models, product/media/category services, product requests/policy/routes/views/tests | TV3; TV5 for media/upload shared changes |
| TV3 | `feature/TV3-cart-orders` | Cart/order/order-item models, cart/order/seller-order services, order requests/policy/routes/views/tests | TV2; TV4 for `OrderItem` changes |
| TV4 | `feature/TV4-reviews-admin` | Review/complaint/admin models/services/controllers/requests/policies/routes/views/tests, `lang/vi/status.php` | TV1; TV3 for `OrderItem` contract changes |
| TV5 | `feature/TV5-chat-invoice-ui` | Message/PDF/chat/invoice services/events/routes/channels/layouts/components/assets/CI/tests | TV1 and TV3 |

## 18. Safe Merge Order

Do not split yet. After P0 gates pass and five-machine evidence is collected, the documented merge order remains reasonable:

1. TV1 foundation/auth/profile
2. TV2 product/category/media
3. TV3 cart/order/inventory
4. TV4 review/complaint/admin
5. TV5 chat/invoice/shared UI/CI

If DomPDF/build remediation is assigned to TV5 before split, merge that platform fix before any module branch.

## 19. Final Decision

Do not split five branches now. No member should begin parallel module work from `1e9f5f04e977ef0843a42fffae2ed9e35be30e92` as the shared baseline because mandatory gates fail and team-machine evidence is absent.

TV1/TV5 platform remediation must happen first: package discovery, dependency audit, upload storage test, frontend build, CI audit coverage, CODEOWNERS/branch protection, and five-machine verification. Once those pass, the current contract structure is sufficient for all five members to start with clear ownership and dependency boundaries.
