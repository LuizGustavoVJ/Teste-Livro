# Sistema de Gerenciamento de Livros

## Sobre o Projeto

Este projeto é um sistema de gerenciamento de livros desenvolvido em Laravel, como parte de um teste técnico para a vaga de Especialista PHP. O sistema permite o cadastro, edição, visualização e exclusão de livros, autores e assuntos.

## Requisitos Atendidos

O projeto foi desenvolvido para atender 100% dos requisitos obrigatórios do teste, com foco na qualidade do código, boas práticas de mercado e aderência à documentação oficial do Laravel e PHP.

### Funcionalidades

- **CRUD Completo:** Implementação do CRUD para Livros, Autores e Assuntos.
- **Campo de Valor:** Adição do campo `valor` (R$) para os livros.
- **Relatório:** Geração de um relatório de livros por autor, consumindo uma `VIEW` do banco de dados.
- **Interface Simples e Funcional:** Utilização de Bootstrap para uma interface limpa e responsiva.

### Qualidade e Boas Práticas

- **TDD (Test-Driven Development):** O desenvolvimento foi guiado por testes, com uma suíte completa de testes unitários e de feature.
- **Tratamento de Erros:** Implementação de tratamento de exceções específico para erros de banco de dados e outras situações.
- **Código Limpo e Organizado:** Utilização de Form Requests para validação, Services para lógica de negócio e outras práticas para manter o código limpo e de fácil manutenção.

## Tecnologias Utilizadas

- **Laravel 10.x**
- **PHP 8.1+**
- **SQLite** (para desenvolvimento e testes)
- **Bootstrap 5**
- **PHPUnit**

## Como Configurar e Rodar o Projeto

### Pré-requisitos

- PHP 8.1+
- Composer
- Node.js e NPM/Yarn

### Passos para Instalação

1.  **Clonar o Repositório:**
    ```bash
    git clone https://github.com/LuizGustavoVJ/Teste-Livro.git
    cd Teste-Livro
    ```

2.  **Instalar Dependências:**
    ```bash
    composer install
    npm install
    npm run dev
    ```

3.  **Configurar o Ambiente:**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4.  **Configurar o Banco de Dados (SQLite):**
    Crie o arquivo de banco de dados:
    ```bash
    touch database/database.sqlite
    ```
    No arquivo `.env`, certifique-se de que a conexão de banco de dados esteja configurada para SQLite:
    ```dotenv
    DB_CONNECTION=sqlite
    ```

5.  **Executar as Migrações:**
    ```bash
    php artisan migrate
    ```

6.  **Iniciar o Servidor:**
    ```bash
    php artisan serve
    ```
    O sistema estará acessível em `http://127.0.0.1:8000`.

## Como Testar o Projeto

Para executar a suíte de testes, utilize o comando:

```bash
php artisan test
```
