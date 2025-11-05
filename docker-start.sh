#!/bin/bash

# Script de inicialização do ambiente Docker para Teste-Livro
echo "🚀 Iniciando ambiente Docker do Teste-Livro..."

# Verificar se o Docker está instalado
if ! command -v docker &> /dev/null; then
    echo "❌ Docker não está instalado. Por favor, instale o Docker primeiro."
    exit 1
fi

# Detectar Docker Compose (v2: "docker compose" ou v1: "docker-compose")
if command -v docker &> /dev/null && docker compose version &> /dev/null; then
    DC="docker compose"
elif command -v docker-compose &> /dev/null; then
    DC="docker-compose"
else
    echo "❌ Docker Compose não foi encontrado. Instale o Docker Desktop (que inclui 'docker compose') ou o docker-compose v1."
    exit 1
fi

# Preparar arquivo de ambiente
if [ ! -f .env ]; then
    echo "📋 Preparando arquivo de ambiente (.env)..."
    if [ -f .env.docker ]; then
        cp .env.docker .env
    elif [ -f .env.example ]; then
        cp .env.example .env
    else
        echo "⚠️ Nenhum .env.docker ou .env.example encontrado. Gerando .env básico compatível com Docker..."
        cat > .env << 'EOF'
APP_NAME=Teste-Livro
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8085

LOG_CHANNEL=stack

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=teste_livro
DB_USERNAME=root
DB_PASSWORD=password

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
REDIS_HOST=redis
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=noreply@teste-livro.com
MAIL_FROM_NAME="Sistema-Teste-Livro"
EOF
    fi
fi

# Parar containers existentes (se houver)
echo "🛑 Parando containers existentes..."
$DC down

# Construir e iniciar os containers
echo "🏗️ Construindo e iniciando containers..."
$DC up --build -d

# Aguardar os serviços ficarem prontos
echo "⏳ Aguardando serviços ficarem prontos..."
sleep 30

# Verificar status dos containers
echo "📊 Status dos containers:"
$DC ps

# Exibir informações de acesso
echo ""
echo "✅ Ambiente Docker iniciado com sucesso!"
echo ""
echo "🌐 Acesso aos serviços:"
echo "   • Aplicação Laravel: http://127.0.0.1:8085"
echo "   • Laravel Telescope: http://127.0.0.1:8085/telescope"
echo "   • Interface de E-mails (Mailhog): http://127.0.0.1:8025"
echo "   • PHPMyAdmin: http://127.0.0.1:8080"
echo "   • Redis: 127.0.0.1:6379"
echo "   • MySQL (host local): 127.0.0.1:3307"
echo ""
echo "📧 Configurações de E-mail para testes:"
echo "   • SMTP Host: 127.0.0.1"
echo "   • SMTP Port: 1025"
echo "   • Visualizar e-mails: http://127.0.0.1:8025"
echo ""
echo "🗄️ Configurações do Banco de Dados:"
echo "   • Host: localhost"
echo "   • Port: 3306"
echo "   • Database: teste_livro"
echo "   • Username: root"
echo "   • Password: password"
echo ""
echo "🔧 Comandos úteis:"
echo "   • Parar ambiente: $DC down"
echo "   • Ver logs: $DC logs -f"
echo "   • Reiniciar: $DC restart"
echo "   • Executar comandos Laravel: $DC exec app php artisan [comando]"
echo ""

