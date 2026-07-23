#!/usr/bin/env bash
set -euo pipefail

TV1=""
TV2=""
TV3=""
TV4=""
TV5=""

while [ "$#" -gt 0 ]; do
  case "$1" in
    --tv1) TV1="${2:-}"; shift 2 ;;
    --tv2) TV2="${2:-}"; shift 2 ;;
    --tv3) TV3="${2:-}"; shift 2 ;;
    --tv4) TV4="${2:-}"; shift 2 ;;
    --tv5) TV5="${2:-}"; shift 2 ;;
    *) echo "Unknown argument: $1" >&2; exit 1 ;;
  esac
done

normalize_username() {
  local username="${1#@}"
  if [[ -z "$username" || ! "$username" =~ ^[A-Za-z0-9]([A-Za-z0-9-]{0,37}[A-Za-z0-9])?$ ]]; then
    echo "Invalid GitHub username: $1" >&2
    exit 1
  fi
  printf '@%s' "$username"
}

TV1="$(normalize_username "$TV1")"
TV2="$(normalize_username "$TV2")"
TV3="$(normalize_username "$TV3")"
TV4="$(normalize_username "$TV4")"
TV5="$(normalize_username "$TV5")"

GIT_ROOT="$(git rev-parse --show-toplevel)"
cd "$GIT_ROOT"

php -r '
$template = file_get_contents(".github/CODEOWNERS.template");
$placeholders = ["@TV1_USERNAME", "@TV2_USERNAME", "@TV3_USERNAME", "@TV4_USERNAME", "@TV5_USERNAME"];
foreach ($placeholders as $index => $placeholder) {
    $template = str_replace($placeholder, $argv[$index + 1], $template);
}
if (preg_match("/@TV[1-5]_USERNAME/", $template) === 1) {
    fwrite(STDERR, "Refusing to write CODEOWNERS while placeholders remain.\n");
    exit(1);
}
file_put_contents(".github/CODEOWNERS", $template);
echo "Created .github/CODEOWNERS\n";
' "$TV1" "$TV2" "$TV3" "$TV4" "$TV5"
