# Pre-Parallel Baseline

- Date: 2026-07-22.
- Starting branch: `main`.
- Working branch: `chore/pre-parallel-foundation`.
- Starting commit: `5c9ed90c5899c4c6549dd2743154c59c0b6d0240`.
- Initial working tree: `AGENTS.md`, `README.md`, `HANDOVER_DOCUMENT.md` were untracked from prior documentation work.

Environment detected before foundation work:

| Tool | Result |
| --- | --- |
| PHP | `8.2.12` |
| Composer | Not in PATH |
| MySQL CLI | Not in PATH |
| Node | `v24.15.0` |
| npm | `11.12.1` |

Baseline blockers:

- PHP CLI does not meet PHP 8.4 target.
- Composer missing.
- MySQL CLI missing.
- Old Laravel port did not boot because `vendor/autoload.php` was missing.
- Old port contained route mismatches, Razor remnants and mojibake.
