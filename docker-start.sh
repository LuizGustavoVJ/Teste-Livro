#!/bin/bash

# Script de inicialização do ambiente Docker para Teste-Livro
echo "🚀 Iniciando ambiente Docker do Teste-Livro..."

# Verificar se o Docker está instalado
if ! command -v docker &> /dev/null; then
    echo "❌ Docker não está instalado. Por favor, instale o Docker primeiro."
    exit 1
fi

# Verificar se o Docker Compose está instalado
if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose não está instalado. Por favor, instale o Docker Compose primeiro."
    exit 1
fi

# Copiar arquivo de ambiente para Docker
if [ ! -f .env ]; then
    echo "📋 Copiando arquivo de ambiente..."
    cp .env.docker .env
fi

# Parar containers existentes (se houver)
echo "🛑 Parando containers existentes..."
docker-compose down

# Construir e iniciar os containers
echo "🏗️ Construindo e iniciando containers..."
docker-compose up --build -d

# Aguardar os serviços ficarem prontos
echo "⏳ Aguardando serviços ficarem prontos..."
sleep 30

# Verificar status dos containers
echo "📊 Status dos containers:"
docker-compose ps

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
echo "   • MySQL: 127.0.0.1:3306"
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
echo "   • Parar ambiente: docker-compose down"
echo "   • Ver logs: docker-compose logs -f"
echo "   • Reiniciar: docker-compose restart"
echo "   • Executar comandos Laravel: docker-compose exec app php artisan [comando]"
echo ""

