# GitHub Manual Checklist

## Foundation PR

- [ ] Foundation branch pushed.
- [ ] PR targets `develop`.
- [ ] Remote CI status check `CI / gate-0` is PASS.
- [ ] Expected checks are not skipped.
- [ ] Reviewer approvals complete.
- [ ] No unresolved conversation remains.
- [ ] Merge commit recorded in `docs/readiness/parallel-base-commit.md`.

## Develop Protection

- [ ] Require pull request before merging.
- [ ] Require minimum one approval.
- [ ] Require code owner review after CODEOWNERS is created.
- [ ] Dismiss stale approvals.
- [ ] Require conversation resolution.
- [ ] Require status check `CI / gate-0`.
- [ ] Require branch to be up to date before merge.
- [ ] Block force push.
- [ ] Block deletion.

## Main Protection

- [ ] Require pull request before merging.
- [ ] Require two approvals.
- [ ] Dismiss stale approvals.
- [ ] Require approval of the most recent push.
- [ ] Require conversation resolution.
- [ ] Require status check `CI / gate-0`.
- [ ] Require branch to be up to date before merge.
- [ ] Block force push.
- [ ] Block deletion.

## CODEOWNERS

- [ ] Real usernames inserted.
- [ ] No placeholder remains.
- [ ] `.github/CODEOWNERS` committed on `develop`.
- [ ] GitHub recognizes owners.
- [ ] Test PR requests the expected reviewers.

## Five Machines

- [ ] All members use the same parallel base commit.
- [ ] TV1 report status is PASS.
- [ ] TV2 report status is PASS.
- [ ] TV3 report status is PASS.
- [ ] TV4 report status is PASS.
- [ ] TV5 report status is PASS.
- [ ] Reports contain no secrets.
- [ ] `docs/readiness/five-machine-verification-results.json` updated.

