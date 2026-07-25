#!/usr/bin/env bash
set -u

MEMBER_ID=""
EXPECTED_COMMIT=""
OUTPUT_DIRECTORY="storage/app/readiness"
STARTED_AT="$(date -u +"%Y-%m-%dT%H:%M:%SZ")"
FINAL_STATUS="FAIL"
COMMAND_JSON=""
EXIT_CODES=""
TEST_PASSED=0
TEST_FAILED=0
TEST_SKIPPED=0
TEST_ASSERTIONS=0
BRANCH=""
COMMIT=""
DB_CONNECTION_VALUE=""
DB_DATABASE_VALUE=""
REPORT_PATH=""

json_escape() {
  php -r 'echo json_encode(stream_get_contents(STDIN), JSON_UNESCAPED_SLASHES);'
}

append_command() {
  local command="$1"
  local exit_code="$2"
  local status="$3"
  local summary="$4"
  local escaped_command escaped_summary
  escaped_command="$(printf "%s" "$command" | json_escape)"
  escaped_summary="$(printf "%s" "$summary" | head -c 1200 | json_escape)"
  if [ -n "$COMMAND_JSON" ]; then
    COMMAND_JSON="$COMMAND_JSON,"
    EXIT_CODES="$EXIT_CODES,"
  fi
  COMMAND_JSON="$COMMAND_JSON{\"command\":$escaped_command,\"exit_code\":$exit_code,\"status\":\"$status\",\"summary\":$escaped_summary}"
  EXIT_CODES="$EXIT_CODES$exit_code"
}

fail() {
  append_command "verification-script" 1 "FAIL" "$1"
  FINAL_STATUS="FAIL"
  exit 1
}

write_report() {
  local completed_at hostname os versions_php versions_composer versions_node versions_npm
  completed_at="$(date -u +"%Y-%m-%dT%H:%M:%SZ")"
  hostname="$(hostname 2>/dev/null || printf "unknown")"
  os="$(uname -a 2>/dev/null || printf "unknown")"
  versions_php="$(php -v 2>/dev/null | head -n 1 || true)"
  versions_composer="$(composer --version 2>/dev/null | head -n 1 || true)"
  versions_node="$(node -v 2>/dev/null || true)"
  versions_npm="$(npm -v 2>/dev/null || true)"

  if [ -z "${REPORT_PATH:-}" ]; then
    REPORT_PATH="${OUTPUT_DIRECTORY:-storage/app/readiness}/${MEMBER_ID:-UNKNOWN}-unknown-readiness.json"
  fi
  mkdir -p "$(dirname "$REPORT_PATH")"
  cat > "$REPORT_PATH" <<JSON
{
  "member_id": "$(printf "%s" "$MEMBER_ID")",
  "hostname": $(printf "%s" "$hostname" | json_escape),
  "os": $(printf "%s" "$os" | json_escape),
  "branch": $(printf "%s" "$BRANCH" | json_escape),
  "commit": $(printf "%s" "$COMMIT" | json_escape),
  "started_at": "$STARTED_AT",
  "completed_at": "$completed_at",
  "versions": {
    "php": $(printf "%s" "$versions_php" | json_escape),
    "composer": $(printf "%s" "$versions_composer" | json_escape),
    "node": $(printf "%s" "$versions_node" | json_escape),
    "npm": $(printf "%s" "$versions_npm" | json_escape)
  },
  "database_connection": $(printf "%s" "$DB_CONNECTION_VALUE" | json_escape),
  "database_name": $(printf "%s" "$DB_DATABASE_VALUE" | json_escape),
  "commands": [$COMMAND_JSON],
  "exit_codes": [$EXIT_CODES],
  "tests": {
    "passed": $TEST_PASSED,
    "failed": $TEST_FAILED,
    "skipped": $TEST_SKIPPED,
    "assertions": $TEST_ASSERTIONS
  },
  "final_status": "$FINAL_STATUS"
}
JSON
  printf 'Readiness report: %s\n' "$REPORT_PATH"
}

trap write_report EXIT

while [ "$#" -gt 0 ]; do
  case "$1" in
    --member-id) MEMBER_ID="${2:-}"; shift 2 ;;
    --expected-commit) EXPECTED_COMMIT="${2:-}"; shift 2 ;;
    --output-directory) OUTPUT_DIRECTORY="${2:-}"; shift 2 ;;
    *) fail "Unknown argument: $1" ;;
  esac
done

case "$MEMBER_ID" in
  TV1|TV2|TV3|TV4|TV5) ;;
  *) fail "member-id must be one of TV1, TV2, TV3, TV4, TV5." ;;
esac

GIT_ROOT="$(git rev-parse --show-toplevel)" || fail "Git root not found."
cd "$GIT_ROOT" || fail "Cannot enter Git root."
[ -f artisan ] || fail "Laravel root not found at Git root."

BRANCH="$(git branch --show-current)"
COMMIT="$(git rev-parse HEAD)"
if [ -n "$EXPECTED_COMMIT" ] && [ "$COMMIT" != "$EXPECTED_COMMIT" ]; then
  fail "ExpectedCommit mismatch. Expected $EXPECTED_COMMIT, got $COMMIT."
fi

case "$OUTPUT_DIRECTORY" in
  /*) OUTPUT_ROOT="$OUTPUT_DIRECTORY" ;;
  *) OUTPUT_ROOT="$GIT_ROOT/$OUTPUT_DIRECTORY" ;;
esac

STORAGE_ROOT="$GIT_ROOT/storage"
case "$OUTPUT_ROOT" in
  "$STORAGE_ROOT"|"$STORAGE_ROOT"/*) ;;
  *) git check-ignore -q -- "$OUTPUT_ROOT" || fail "Output directory must be under storage or ignored by git." ;;
esac

REPORT_PATH="$OUTPUT_ROOT/${MEMBER_ID}-${COMMIT:0:12}-readiness.json"

if [ -n "$(git status --short)" ]; then
  fail "Working tree must be clean before five-machine verification."
fi

dotenv_value() {
  local name="$1"
  local line value
  line="$(grep -E "^${name}=" .env.testing | tail -n 1 || true)"
  value="${line#*=}"
  value="${value%\"}"
  value="${value#\"}"
  value="${value%\'}"
  value="${value#\'}"
  printf "%s" "$value"
}

[ -f .env.testing ] || fail ".env.testing is required."
DB_CONNECTION_VALUE="$(dotenv_value DB_CONNECTION)"
DB_DATABASE_VALUE="$(dotenv_value DB_DATABASE)"
DOTENV_APP_ENV="$(dotenv_value APP_ENV)"
export APP_ENV=testing

if { [ "$DOTENV_APP_ENV" != "testing" ] && [ "${APP_ENV:-}" != "testing" ]; } || [ "$DB_CONNECTION_VALUE" != "mysql" ] || [[ "$DB_DATABASE_VALUE" != techsecond_test* ]]; then
  fail "Unsafe testing DB."
fi

run_gate() {
  local command="$1"
  local output exit_code status plain
  set +e
  output="$(bash -lc "$command" 2>&1)"
  exit_code=$?
  set -e
  status="PASS"
  [ "$exit_code" -eq 0 ] || status="FAIL"
  append_command "$command" "$exit_code" "$status" "$output"
  if [ "$command" = "php artisan test" ]; then
    plain="$(printf "%s" "$output" | sed -E 's/\x1b\[[0-9;]*m//g')"
    if [[ "$plain" =~ Tests:[[:space:]]+([0-9]+)[[:space:]]+passed(,[[:space:]]+([0-9]+)[[:space:]]+failed)?(,[[:space:]]+([0-9]+)[[:space:]]+skipped)?.*\(([0-9]+)[[:space:]]+assertions\) ]]; then
      TEST_PASSED="${BASH_REMATCH[1]}"
      TEST_FAILED="${BASH_REMATCH[3]:-0}"
      TEST_SKIPPED="${BASH_REMATCH[5]:-0}"
      TEST_ASSERTIONS="${BASH_REMATCH[6]}"
    fi
  fi
  [ "$exit_code" -eq 0 ] || fail "Command failed: $command"
}

run_gate "git diff --check"
run_gate "composer validate --strict"
run_gate "composer check-platform-reqs"
run_gate "composer install --dry-run --no-interaction --prefer-dist"
run_gate "npm ci"
run_gate "php artisan optimize:clear"
run_gate "php artisan route:list --json"
run_gate "php artisan migrate:fresh --seed --env=testing"
run_gate "php artisan migrate:status --env=testing"
run_gate "vendor/bin/pint --test"
run_gate "composer run check:quality"
run_gate "php artisan test"
run_gate "npm run build"
run_gate "git diff --check"

FINAL_STATUS="PASS"
