# Parallel Base Commit

Status: PENDING_FOUNDATION_PR

| Item | Value |
|---|---|
| Foundation branch | `chore/pre-parallel-foundation` |
| Foundation commit | `PENDING_COMMIT` |
| Develop merge commit | `PENDING_MERGE` |
| Remote CI | `PENDING` |
| Branch protection | `PENDING` |
| CODEOWNERS | `PENDING` |
| Five-machine verification | `PENDING` |
| Parallel work authorized | `NO` |

## Update Process

1. Commit the foundation changes.
2. Push `chore/pre-parallel-foundation`.
3. Open a pull request targeting `develop`.
4. Wait for remote CI status check `CI / gate-0` to pass.
5. Complete review according to the matrix.
6. Merge the PR into `develop`.
7. Record the resulting `develop` commit as the parallel base commit.
8. Run five-machine verification against that exact SHA.
9. Update `docs/readiness/five-machine-verification-results.json`.
10. Change `Parallel work authorized` to `YES` only after all manual checks pass.

## Base Commit Commands

```bash
git checkout develop
git pull --ff-only origin develop
git rev-parse HEAD
```

Every member creates their feature branch from that exact SHA.

Do not branch from `chore/pre-parallel-foundation` after the foundation PR has merged.

