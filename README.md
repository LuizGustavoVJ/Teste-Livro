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
- **Testes Unitários Abrangentes:** Criação de testes unitários para os modelos (ex: `BookTest.php`), cobrindo cenários de criação, atualização, exclusão, relacionamentos e validações.
- **Testes de Integração Completos:** Desenvolvimento de testes de integração para os controladores (ex: `BookControllerTest.php`, `AuthorControllerTest.php`), garantindo o funcionamento correto das rotas, validações e interações com o banco de dados, incluindo testes de upload de imagem.
- **Desenvolvimento Orientado a Testes (TDD):** Todas as funcionalidades foram guiadas pela metodologia TDD, garantindo a qualidade e a robustez do código desde o início.
- **Cobertura de Testes:** Foco em alta cobertura de testes para todas as funcionalidades críticas do sistema.

### 🚀 CI/CD
- **Pipeline CI/CD Completo:** Configuração de um workflow automatizado no GitHub Actions (`.github/workflows/ci-cd.yml`) que inclui:
  - **Validação Inicial do Código:** Verificação de sintaxe PHP e configuração do ambiente Laravel.
  - **Execução de Testes:** Rodada automática de testes unitários e de integração.
  - **Análise de Qualidade de Código:** Utilização de ferramentas como PHP CS Fixer, PHP CodeSniffer e PHPStan para garantir a conformidade com padrões de código e identificar possíveis problemas.
  - **Testes de Segurança:** Verificação de vulnerabilidades nas dependências e rotas.
  - **Deploy para Staging:** Deploy automático para um ambiente de staging após a aprovação e merge na branch `master`.
- **Notificações Inteligentes:** Configuração de notificações para o aplicativo móvel do GitHub:
  - **Notificação de PR Criado:** Criação automática de issues e comentários no PR para avisar sobre a necessidade de revisão.
  - **Notificação de PR Aprovado:** Fechamento automático das issues de notificação quando o PR é mergeado.
  - **Notificação de Sucesso/Falha do Pipeline:** Alertas em tempo real sobre o status das execuções do CI/CD.

## Tecnologias Utilizadas

- **Laravel 10.x:** Framework PHP para o backend.
- **PHP 8.1+:** Linguagem de programação.
- **MySQL/SQLite:** Banco de dados (SQLite para desenvolvimento/testes).
- **Bootstrap 5:** Framework CSS para o frontend.
- **Redis:** Para cache e filas de mensageria.
- **GitHub Actions:** Para o pipeline de CI/CD.
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
    - **Aplicação:** http://localhost
    - **Interface de E-mails (Mailhog):** http://localhost:8025
    - **PHPMyAdmin:** http://localhost:8080
    - **Redis:** localhost:6379
    - **MySQL:** localhost:3306

#### Serviços Incluídos no Docker

- **Aplicação Laravel** (porta 80)
- **MySQL 8.0** (porta 3306) - Banco de dados principal
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

Se você está usando o ambiente Docker, os testes podem ser executados dentro do container:

```bash
# Executar todos os testes
docker-compose exec app php artisan test

# Executar apenas testes unitários
docker-compose exec app php artisan test --testsuite=Unit

# Executar apenas testes de integração
docker-compose exec app php artisan test --testsuite=Feature

# Executar testes com cobertura de código
docker-compose exec app php artisan test --coverage
```

### 💻 Testando com Instalação Manual

O projeto possui uma suíte de testes abrangente, incluindo testes unitários e de integração. Para executá-los, siga os passos:

1.  **Certifique-se de que o ambiente de teste esteja configurado** (o `.env.testing` ou as configurações de teste no `phpunit.xml` devem apontar para um banco de dados de teste, como SQLite em memória).

2.  **Rodar todos os Testes (Unitários e de Integração):**
    ```bash
    php artisan test
    ```

3.  **Rodar apenas Testes Unitários:**
    ```bash
    php artisan test --testsuite=Unit
    ```

4.  **Rodar apenas Testes de Integração (Feature Tests):**
    ```bash
    php artisan test --testsuite=Feature
    ```

5.  **Rodar Testes com Cobertura de Código (requer Xdebug):**
    ```bash
    php artisan test --coverage
    ```

## Padrões de Desenvolvimento

Durante o desenvolvimento, foram seguidos os seguintes padrões:

- **TDD (Test-Driven Development):** Todas as funcionalidades foram desenvolvidas com testes primeiro.
- **Estrutura Monolítica:** O projeto mantém uma estrutura monolítica, utilizando HTML e Bootstrap para o frontend.
- **Git Flow:** Utilização de um fluxo de trabalho Git baseado em branches de feature, bugfix, hotfix e release.

## Licença

O Laravel framework é um software de código aberto licenciado sob a [Licença MIT](https://opensource.org/licenses/MIT).


