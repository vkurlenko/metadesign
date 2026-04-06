#!/usr/bin/env bash
set -euo pipefail

health_url="${1:-http://127.0.0.1:8000/health}"
max_attempts=30
sleep_seconds=2

for ((attempt=1; attempt<=max_attempts; attempt++)); do
  response="$(curl -fsS "$health_url" || true)"
  if echo "$response" | grep -q '"status":"ok"'; then
    echo "Healthcheck passed on attempt ${attempt}"
    exit 0
  fi

  echo "Waiting for app health (${attempt}/${max_attempts})"
  sleep "$sleep_seconds"
done

echo "Healthcheck failed: ${health_url}"
exit 1
