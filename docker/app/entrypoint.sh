#!/bin/sh

echo "⏳ Executando entrypoint.sh..."

composer install || { echo "❌ Erro no composer install"; exit 1; }

php artisan key:generate --force || { echo "❌ Erro ao gerar chave"; exit 1; }

php artisan config:cache || { echo "❌ Erro ao fazer cache da config"; exit 1; }

php artisan view:cache || { echo "❌ Erro ao fazer cache das views"; exit 1; }

# Garantir diretórios e permissões de escrita (evita Permission denied em storage/logs)
mkdir -p storage/logs storage/framework/sessions storage/framework/views storage/framework/cache bootstrap/cache || true
chmod -R 777 storage bootstrap/cache || true

#php artisan route:cache || { echo "❌ Erro ao fazer cache das rotas"; exit 1; }

php artisan migrate:fresh --seed || { echo "❌ Erro ao rodar migrate:fresh"; exit 1; }

php artisan storage:link || { echo "❌ Erro ao linkar o storage"; exit 1; }

echo "✅ Iniciando PHP-FPM"
# Verifica se o PHP-FPM está configurado corretamente
if ! php-fpm -t; then
    echo "❌ Erro: Configuração do PHP-FPM inválida"
    exit 1
fi

# Inicia PHP-FPM em modo foreground (exec substitui o processo atual)
# Isso garante que o container permaneça rodando enquanto o PHP-FPM está ativo
exec php-fpm -F
