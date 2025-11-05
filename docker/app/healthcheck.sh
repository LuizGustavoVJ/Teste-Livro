#!/bin/sh

# Healthcheck para verificar se o PHP-FPM está rodando e respondendo
# Verifica se a porta 9000 está respondendo (usando netcat)

# Verifica se a porta 9000 está respondendo (usando netcat)
if command -v nc > /dev/null 2>&1; then
    if ! nc -z 0.0.0.0 9000 > /dev/null 2>&1; then
        exit 1
    fi
else
    # Fallback: verifica se o processo PID 1 é php-fpm (via /proc)
    if [ ! -f /proc/1/cmdline ] || ! grep -q "php-fpm" /proc/1/cmdline 2>/dev/null; then
        exit 1
    fi
fi

exit 0

