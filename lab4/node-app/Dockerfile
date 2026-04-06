FROM nginx:1.27-alpine

COPY index.html.template /usr/share/nginx/html/index.html.template
COPY docker-entrypoint.sh /docker-entrypoint.sh

RUN chmod +x /docker-entrypoint.sh

ENTRYPOINT ["/docker-entrypoint.sh"]
