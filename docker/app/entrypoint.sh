#!/bin/sh

echo "⏳ Executando entrypoint.sh..."

composer install || { echo "❌ Erro no composer install"; exit 1; }

php artisan key:generate --force || { echo "❌ Erro ao gerar chave"; exit 1; }

php artisan config:cache || { echo "❌ Erro ao fazer cache da config"; exit 1; }

#php artisan route:cache || { echo "❌ Erro ao fazer cache das rotas"; exit 1; }

php artisan view:cache || { echo "❌ Erro ao fazer cache das views"; exit 1; }

php artisan migrate:fresh --seed || { echo "❌ Erro ao rodar migrate:fresh"; exit 1; }

php artisan storage:link || { echo "❌ Erro ao linkar o storage"; exit 1; }

echo "✅ Iniciando PHP-FPM"
exec php-fpm
