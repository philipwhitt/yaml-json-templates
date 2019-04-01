FROM php:7.1-cli-alpine

COPY src /app/src
COPY vendor /app/vendor

ENTRYPOINT ["/usr/local/bin/php", "/app/src/main.php"]