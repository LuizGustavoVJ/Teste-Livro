# Sistema de Gerenciamento de Livros

Este é um sistema de gerenciamento de livros desenvolvido em Laravel, que permite o cadastro, a visualização, a edição e a exclusão de livros, autores e assuntos.

## Requisitos

- PHP >= 8.1
- Composer
- Docker
- Docker Compose

## Instalação e Execução

1. **Clone o repositório:**

   ```bash
   git clone https://github.com/LuizGustavoVJ/Teste-Livro.git
   cd Teste-Livro
   ```

2. **Copie o arquivo de ambiente para o Docker:**

   ```bash
   cp .env.docker .env
   ```

3. **Suba os contêineres do Docker:**

   ```bash
   docker-compose up -d --build
   ```

4. **Instale as dependências do Composer:**

   ```bash
   docker-compose exec app composer install
   ```

5. **Gere a chave da aplicação:**

   ```bash
   docker-compose exec app php artisan key:generate
   ```

6. **Execute as migrações do banco de dados:**

   ```bash
   docker-compose exec app php artisan migrate
   ```

7. **Acesse a aplicação:**

   A aplicação estará disponível em [http://localhost:8085](http://localhost:8085).

## Executando os Testes

Para executar a suíte de testes, rode o seguinte comando:

```bash
docker-compose exec app php artisan test
```

