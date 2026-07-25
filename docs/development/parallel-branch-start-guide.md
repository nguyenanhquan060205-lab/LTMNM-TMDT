# Parallel Branch Start Guide

## Ownership And Review Matrix

| Member | Branch | Scope | Reviewer |
|---|---|---|---|
| TV1 | `feature/TV1-auth-profile` | Auth, Profile, Platform foundation | TV4 |
| TV2 | `feature/TV2-products-categories` | Category, Product, Search, Images | TV3 |
| TV3 | `feature/TV3-cart-orders` | Cart, Checkout, Orders | TV2 |
| TV4 | `feature/TV4-reviews-admin` | Review, Complaint, Admin | TV1 |
| TV5 | `feature/TV5-chat-invoice-ui` | Chat, Invoice, Shared UI, CI | TV1 and TV3 |

## Start Commands

```bash
git checkout develop
git pull --ff-only origin develop
git rev-parse HEAD
```

Compare the SHA with `PARALLEL_BASE_COMMIT` in `docs/readiness/parallel-base-commit.md`.

Then create the module branch:

```bash
git checkout -b feature/TV1-auth-profile
```

Use the branch name for your member from the table above.

## Rules

- Do not branch from `chore/pre-parallel-foundation` after the foundation PR has merged.
- Do not branch from `main`.
- Do not edit shared contracts without an issue and dependent reviewer.
- Do not edit frozen foundation migrations.
- Schema changes must use forward migrations.
- Route owners edit only their module route file.
- Shared model changes require owner and dependent reviewer.
- Service interface changes require dependent reviewer before implementation work continues.
- Dependency changes are owned by TV5/Platform.
- Rebase or merge latest `develop` before opening a PR according to the team workflow.
- Run local verification before PR.

## Local Verification Before PR

PowerShell:

```powershell
.\scripts\verify-parallel-readiness.ps1 -MemberId TV1 -ExpectedCommit <PARALLEL_BASE_COMMIT>
```

Bash:

```bash
bash scripts/verify-parallel-readiness.sh --member-id TV1 --expected-commit <PARALLEL_BASE_COMMIT>
```

Use your own member id.

