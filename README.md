# Sistema de Gerenciamento de Livros (Teste-Livro)

![Laravel Logo](https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg)

## Sobre o Projeto

O `Teste-Livro` é um sistema de gerenciamento de livros robusto e moderno, desenvolvido com Laravel. Este projeto passou por uma refatoração completa, visando aprimorar a experiência do usuário, otimizar o desempenho, garantir a qualidade do código e implementar um pipeline de CI/CD completo. Ele permite o cadastro, edição, visualização e exclusão de livros, autores e assuntos, com funcionalidades avançadas como upload de capa e sistema de e-mails.

## Funcionalidades Implementadas

### 🎨 Frontend
- **Página Inicial (Landing Page):** Uma página de entrada moderna e responsiva, com design atrativo, ilustrações e um botão de acesso ao login, substituindo a tela de login inicial.
- **Views:** Todas as páginas do sistema (listagem, criação, edição e visualização de livros, autores e assuntos) foram criadas para seguir um padrão visual consistente, moderno e responsivo, utilizando Bootstrap 5.
- **Interface de Upload de Imagem:** Implementação de uma interface intuitiva para upload da capa dos livros e eimagem do usuário, com pré-visualização em tempo real.
- **Experiência do Usuário:** Adição de gradientes, hover effects, transições suaves e ícones para uma navegação mais agradável e profissional.
- **Seleção de Autores e Assuntos:** Selects para iniciarem com os dados e permitirem seleção múltipla com busca, melhorando a usabilidade.

### ⚙️ Backend
- **Integração com Redis:** Configuração do Redis para cache de dados e como driver de fila para mensageria, otimizando o desempenho e a escalabilidade do sistema.
- **Sistema de E-mails Automatizado:** Implementação de funcionalidades de envio de e-mails:
  - **E-mail de Boas-Vindas:** Enviado automaticamente para novos usuários cadastrados.
  - **E-mails de Relatórios:** Geração e envio de relatórios (ex: relatório de livros por autor).
- **Upload de Imagem Modularizado:** A lógica de upload e exclusão de imagens de capa foi feita para utilizar a classe `UploadService`, promovendo a modularidade e reutilização de código.
- **APIs Robustas:** Implementação de APIs para gerenciamento de livros, autores e assuntos, seguindo as melhores práticas RESTful.
- **Otimizações de Performance e Segurança:** Garante um sistema mais rápido e seguro.

### 🧪 Qualidade
- **Testes Unitários Abrangentes:** 68 testes unitários cobrindo todos os modelos (Book, Author, Subject, User, Arquivo) e services (UploadService), incluindo cenários de criação, atualização, exclusão, relacionamentos e validações.
- **Testes de Integração Completos:** 60 testes de funcionalidade cobrindo todos os controllers principais (BookWebController, AuthorWebController, SubjectWebController, HomeController, ReportController, EmailController), garantindo o funcionamento correto das rotas, validações e interações com o banco de dados, incluindo testes de upload de imagem.
- **Testes de API:** 21 testes cobrindo todas as APIs REST (BookController, AuthorController, SubjectController), seguindo as melhores práticas RESTful.
- **Desenvolvimento Orientado a Testes (TDD):** Todas as funcionalidades foram guiadas pela metodologia TDD, garantindo a qualidade e a robustez do código desde o início.
- **Cobertura de Testes:** **151 testes com 1.187+ assertions**, alcançando **85-90%+ de cobertura** para todos os componentes principais do sistema.

## Tecnologias Utilizadas

- **Laravel 10.x:** Framework PHP para o backend.
- **PHP 8.1+:** Linguagem de programação.
- **MySQL/SQLite:** Banco de dados (SQLite para desenvolvimento/testes).
- **Bootstrap 5:** Framework CSS para o frontend.
- **Redis:** Para cache e filas de mensageria.
- **Composer:** Gerenciador de dependências PHP.
- **NPM/Yarn:** Gerenciador de pacotes JavaScript.
- **PHPUnit:** Framework de testes para PHP.
- **PHP CS Fixer, PHP CodeSniffer, PHPStan:** Ferramentas de análise de qualidade de código.

## Como Configurar e Rodar o Projeto

### 🐳 Opção 1: Usando Docker

**Esta é a forma mais rápida e fácil de testar o sistema, pois não requer configuração manual do ambiente.**

#### Pré-requisitos
- Docker
- Docker Compose

#### Passos para Inicialização

1.  **Clonar o Repositório:**
    ```bash
    git clone https://github.com/LuizGustavoVJ/Teste-Livro.git
    cd Teste-Livro
    ```

2.  **Iniciar o Ambiente Docker:**
    ```bash
    ./docker-start.sh
    ```
    
    Ou manualmente:
    ```bash
    docker-compose up --build -d
    ```

3.  **Aguardar a Inicialização:**
    O script aguardará automaticamente todos os serviços ficarem prontos (aproximadamente 30-60 segundos).

4.  **Acessar o Sistema:**
    - **Aplicação:** http://127.0.0.1:8085
    - **Interface de E-mails (Mailhog):** http://127.0.0.1:8025
    - **PHPMyAdmin:** http://127.0.0.1:8080
    - **Redis:** localhost:6379
    - **MySQL:** localhost:3307

#### Serviços Incluídos no Docker

- **Aplicação Laravel** (porta 8085) - Acessível via Nginx
- **MySQL 8.0** (porta 3307) - Banco de dados principal
- **Redis** (porta 6379) - Cache e filas
- **Mailhog** (porta 8025) - Captura e visualização de e-mails
- **PHPMyAdmin** (porta 8080) - Interface web para MySQL
- **Queue Worker** - Processamento de filas em background
- **Nginx** - Servidor web

#### Comandos Úteis Docker

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

# Limpar cache
docker-compose exec app php artisan cache:clear
```

### 💻 Opção 2: Instalação Manual (Para Desenvolvimento)

Siga os passos abaixo para configurar e rodar o projeto em seu ambiente local:

1.  **Clonar o Repositório:**
    ```bash
    git clone https://github.com/LuizGustavoVJ/Teste-Livro.git
    cd Teste-Livro
    ```

2.  **Instalar Dependências PHP:**
    ```bash
    composer install
    ```

3.  **Configurar o Arquivo `.env`:**
    Copie o arquivo de exemplo e gere uma nova chave de aplicação:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    Edite o arquivo `.env` para configurar o banco de dados. Para desenvolvimento, você pode usar SQLite:
    ```dotenv
    DB_CONNECTION=sqlite
    DB_DATABASE=/caminho/para/Teste-Livro/database/database.sqlite
    ```
    *Certifique-se de que o caminho para `database.sqlite` esteja correto e que o arquivo exista ou seja criado.* Se preferir MySQL, configure as credenciais apropriadamente.

4.  **Configurar Redis (Opcional, mas recomendado):**
    Certifique-se de ter o Redis instalado e em execução em sua máquina. No `.env`, configure:
    ```dotenv
    REDIS_HOST=127.0.0.1
    REDIS_PASSWORD=null
    REDIS_PORT=6379
    CACHE_DRIVER=redis
    QUEUE_CONNECTION=redis
    ```

5.  **Rodar Migrações e Seeders:**
    Crie o arquivo do banco de dados SQLite (se estiver usando):
    ```bash
    touch database/database.sqlite
    ```
    Execute as migrações para criar as tabelas no banco de dados:
    ```bash
    php artisan migrate
    ```
    Popule o banco de dados com dados de exemplo (usuários, livros, autores, assuntos):
    ```bash
    php artisan db:seed
    ```

6.  **Instalar Dependências Node.js e Compilar Assets:**
    ```bash
    npm install # ou yarn install
    npm run dev # ou yarn dev
    ```

7.  **Iniciar o Servidor de Desenvolvimento:**
    ```bash
    php artisan serve
    ```
    O sistema estará acessível em `http://127.0.0.1:8000` (ou a porta indicada).

8.  **Iniciar link de upload de imagens (para armazenamento no sistema):**
    ```bash
    php artisan storage:link
    ```

9.  **Iniciar o Worker de Fila (para e-mails e outras tarefas assíncronas):**
    ```bash
    php artisan queue:work
    ```

## Como Testar o Projeto

### 🐳 Testando com Docker

O projeto possui **151 testes** cobrindo todas as funcionalidades principais. Se você está usando o ambiente Docker, os testes podem ser executados dentro do container:

#### Pré-requisito: Rebuild do Docker para Cobertura

**⚠️ IMPORTANTE:** Para visualizar a cobertura de código, é necessário fazer rebuild do Docker para instalar o PCOV. Sem isso, você verá o aviso "No code coverage driver available".

```bash
# Rebuild do Docker para instalar PCOV
docker compose build app
docker compose up -d

# Aguardar alguns segundos para os serviços iniciarem
# Depois verificar se o PCOV foi instalado
docker compose exec app php -m | grep pcov
```

Se o comando acima retornar `pcov`, o PCOV está instalado e você pode gerar relatórios de cobertura.

**Troubleshooting - PCOV não instalado:**

Se após o rebuild o PCOV ainda não aparecer, verifique:

1. **Verifique se o Dockerfile foi atualizado corretamente:**
   ```bash
   # O Dockerfile deve conter a linha:
   # RUN pecl install pcov && docker-php-ext-enable pcov
   ```

2. **Força o rebuild completo:**
   ```bash
   docker compose build --no-cache app
   docker compose up -d
   ```

3. **Verifique novamente:**
   ```bash
   docker compose exec app php -m | grep pcov
   ```

4. **Windows PowerShell/Git Bash:** Se o comando `grep` não funcionar, use:
   ```bash
   docker compose exec app php -m | findstr pcov
   # ou
   docker compose exec app php -m | Select-String pcov
   ```

#### Executar Testes

```bash
# Executar todos os testes (151 testes)
docker compose exec app php vendor/bin/phpunit

# Executar apenas testes unitários
docker compose exec app php vendor/bin/phpunit tests/Unit

# Executar apenas testes de funcionalidade
docker compose exec app php vendor/bin/phpunit tests/Feature

# Executar apenas testes de API
docker compose exec app php vendor/bin/phpunit tests/Feature/API

# Executar um teste específico
docker compose exec app php vendor/bin/phpunit tests/Unit/BookTest.php

# Executar com filtro
docker compose exec app php vendor/bin/phpunit --filter test_pode_criar_livro
```

#### Executar Testes com Cobertura

**⚠️ ATENÇÃO:** Antes de executar estes comandos, certifique-se de ter feito o **rebuild do Docker** para instalar o PCOV (veja seção acima).

```bash
# Executar testes com cobertura (formato texto)
docker compose exec app php vendor/bin/phpunit --coverage-text --coverage-filter=app

# Executar testes com cobertura (formato HTML)
docker compose exec app php vendor/bin/phpunit --coverage-html coverage
```

**Nota:** Se você ver o aviso "No code coverage driver available", significa que o PCOV não está instalado. Execute o rebuild do Docker primeiro.

Após gerar o relatório HTML, você pode acessá-lo:
- **Linux/Mac:** Abra o arquivo `coverage/index.html` no navegador
- **Windows:** Copie a pasta `coverage` para fora do container e abra `coverage/index.html` no navegador

Para copiar a pasta coverage do container (Windows):
```bash
# Copiar pasta coverage do container para o host
docker compose cp app:/var/www/html/coverage ./coverage
```

**Troubleshooting - Copiar arquivos do container (Windows):**

Se o comando `docker compose cp` não funcionar, use o nome do container diretamente:

```bash
# O nome do container é: teste-livro-app
docker cp teste-livro-app:/var/www/html/coverage ./coverage
```

**Alternativa:** Se você quiser verificar o nome do container:
```bash
docker compose ps
# Procure pelo nome do container na coluna NAME
```

#### Estrutura de Testes

O projeto possui a seguinte estrutura de testes:

```
tests/
├── Unit/                    # 68 testes unitários
│   ├── BookTest.php         (20 testes)
│   ├── AuthorTest.php       (7 testes)
│   ├── SubjectTest.php      (9 testes)
│   ├── UserTest.php         (11 testes)
│   ├── ArquivoTest.php      (10 testes)
│   └── UploadServiceTest.php (11 testes)
├── Feature/                 # 60 testes de funcionalidade
│   ├── BookWebControllerTest.php      (13 testes)
│   ├── AuthorWebControllerTest.php    (16 testes)
│   ├── SubjectWebControllerTest.php   (11 testes)
│   ├── HomeControllerTest.php         (5 testes)
│   ├── ReportControllerTest.php      (6 testes)
│   ├── EmailControllerTest.php       (9 testes)
│   └── API/                 # 21 testes de API
│       ├── BookControllerTest.php    (7 testes)
│       ├── AuthorControllerTest.php  (7 testes)
│       └── SubjectControllerTest.php (7 testes)
└── TestCase.php
```

**Total: 151 testes com 1.187+ assertions**

#### Resultado Esperado dos Testes

Ao executar todos os testes, você deve ver:

```
✅ Tests: 151
✅ Assertions: 1.187
✅ Errors: 0
✅ Failures: 0
⚠️ Warnings: 1 (apenas sobre code coverage driver - esperado até rebuild)
```

### 📊 Visualizar Relatório de Cobertura

#### Opção 1: Relatório HTML (Recomendado)

**⚠️ IMPORTANTE:** Certifique-se de ter feito o rebuild do Docker antes de executar estes comandos.

1. **Gerar relatório HTML:**
   ```bash
   docker compose exec app php vendor/bin/phpunit --coverage-html coverage
   ```
   
   Se você ver o aviso "No code coverage driver available", execute:
   ```bash
   docker compose build app
   docker compose up -d
   ```
   E tente novamente.

2. **Copiar pasta coverage para o host:**
   
   **Windows (PowerShell/Git Bash):**
   ```bash
   docker compose cp app:/var/www/html/coverage ./coverage
   ```
   
   Se o comando acima não funcionar, use o nome do container diretamente:
   ```bash
   # O nome do container é: teste-livro-app
   docker cp teste-livro-app:/var/www/html/coverage ./coverage
   ```
   
   **Nota:** Se você obtiver um erro "Could not find the file /var/www/html/coverage", significa que o PCOV não está instalado e o relatório não foi gerado. Execute o rebuild do Docker primeiro (veja seção "Pré-requisito: Rebuild do Docker para Cobertura").

3. **Abrir no navegador:**
   - Navegue até a pasta `coverage` no projeto
   - Abra o arquivo `coverage/index.html` no seu navegador
   - Você verá a cobertura detalhada de cada arquivo, classe e método

#### Opção 2: Relatório Texto (Console)

**⚠️ IMPORTANTE:** Certifique-se de ter feito o rebuild do Docker antes de executar este comando.

```bash
docker compose exec app php vendor/bin/phpunit --coverage-text --coverage-filter=app
```

Este comando mostrará no console uma tabela com a cobertura de cada arquivo.

**Nota:** Se você ver o aviso "No code coverage driver available", execute o rebuild do Docker primeiro (veja seção "Pré-requisito: Rebuild do Docker para Cobertura").

### 📈 Cobertura Atual

O sistema possui **cobertura acima de 85-90%** para todos os componentes principais:

- ✅ **Controllers:** 9 de 9 principais testados (100%)
- ✅ **Models:** 5 de 5 testados (100%)
- ✅ **Services:** 1 de 1 testado (100%)
- ✅ **API Controllers:** 3 de 3 testados (100%)

Para mais detalhes sobre a cobertura de testes, consulte o arquivo [`RELATORIO_COBERTURA_TESTES.md`](RELATORIO_COBERTURA_TESTES.md).

### 💻 Testando com Instalação Manual

O projeto possui uma suíte de testes abrangente, incluindo testes unitários e de integração. Para executá-los, siga os passos:

1.  **Certifique-se de que o ambiente de teste esteja configurado** (o `.env.testing` ou as configurações de teste no `phpunit.xml` devem apontar para um banco de dados de teste, como SQLite em memória).

2.  **Instalar PCOV para cobertura (opcional):**
    ```bash
    pecl install pcov
    ```

3.  **Rodar todos os Testes (Unitários e de Integração):**
    ```bash
    php vendor/bin/phpunit
    ```

4.  **Rodar apenas Testes Unitários:**
    ```bash
    php vendor/bin/phpunit tests/Unit
    ```

5.  **Rodar apenas Testes de Integração (Feature Tests):**
    ```bash
    php vendor/bin/phpunit tests/Feature
    ```

6.  **Rodar Testes com Cobertura de Código:**
    ```bash
    php vendor/bin/phpunit --coverage-text --coverage-filter=app
    php vendor/bin/phpunit --coverage-html coverage
    ```

## Padrões de Desenvolvimento

Durante o desenvolvimento, foram seguidos os seguintes padrões:

- **TDD (Test-Driven Development):** Todas as funcionalidades foram desenvolvidas com testes primeiro.
- **Estrutura Monolítica:** O projeto mantém uma estrutura monolítica, utilizando HTML e Bootstrap para o frontend.
- **Git Flow:** Utilização de um fluxo de trabalho Git baseado em branches de feature, bugfix, hotfix e release.

## Licença

O Laravel framework é um software de código aberto licenciado sob a [Licença MIT](https://opensource.org/licenses/MIT).


