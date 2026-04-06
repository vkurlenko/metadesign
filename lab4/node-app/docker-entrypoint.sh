#!/usr/bin/env sh
set -eu

envsubst '${NODE_NAME}' < /usr/share/nginx/html/index.html.template > /usr/share/nginx/html/index.html
exec nginx -g 'daemon off;'
