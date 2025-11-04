# Sistema de Gerenciamento de Livros (Teste-Livro)

![Laravel Logo](https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg)

## Sobre o Projeto

O `Teste-Livro` é um sistema de gerenciamento de livros robusto e moderno, desenvolvido com Laravel. Ele permite o cadastro, edição, visualização e exclusão de livros, autores e assuntos.

## Como Configurar e Rodar o Projeto com Docker

**Esta é a forma mais rápida e fácil de testar o sistema, pois não requer configuração manual do ambiente.**

### Pré-requisitos
- Docker
- Docker Compose

### Passos para Inicialização

1.  **Clonar o Repositório:**
    ```bash
    git clone https://github.com/LuizGustavoVJ/Teste-Livro.git
    cd Teste-Livro
    ```

2.  **Iniciar o Ambiente Docker:**
    ```bash
    docker-compose up --build -d
    ```

3.  **Acessar o Sistema:**
    - **Aplicação:** http://localhost:8085
    - **Interface de E-mails (Mailhog):** http://localhost:8025
    - **PHPMyAdmin:** http://localhost:8080

### Serviços Incluídos no Docker

- **Aplicação Laravel**
- **MySQL 8.0**
- **Redis**
- **Mailhog**
- **PHPMyAdmin**
- **Queue Worker**
- **Nginx**

### Comandos Úteis Docker

```bash
# Parar o ambiente
docker-compose down

# Ver logs em tempo real
docker-compose logs -f

# Reiniciar serviços
docker-compose restart

# Executar comandos Laravel
docker-compose exec app php artisan [comando]

# Acessar o container da aplicação
docker-compose exec app bash
```

## Como Testar o Projeto

### Testando com Docker

Se você está usando o ambiente Docker, os testes podem ser executados dentro do container:

```bash
# Executar todos os testes
docker-compose exec app php artisan test
```

## Licença

O Laravel framework é um software de código aberto licenciado sob a [Licença MIT](https://opensource.org/licenses/MIT).

