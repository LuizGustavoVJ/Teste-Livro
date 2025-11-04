# Sistema de Gerenciamento de Livros

## Sobre o Projeto

Sistema de cadastro de livros desenvolvido em Laravel, permitindo o gerenciamento completo de livros, autores e assuntos. O projeto inclui funcionalidades de CRUD, relatórios e exportação em PDF.

## Funcionalidades

- **CRUD de Livros:** Cadastro, edição, visualização e exclusão de livros
- **CRUD de Autores:** Gerenciamento completo de autores
- **CRUD de Assuntos:** Gerenciamento completo de assuntos
- **Campo de Valor:** Livros possuem campo de valor em reais (R$)
- **Relatórios:** Relatório de livros agrupados por autor
- **Exportação PDF:** Geração de relatórios em PDF
- **Interface Responsiva:** Utiliza Bootstrap para interface moderna
- **Testes Automatizados:** Suíte completa de testes unitários e de feature

## Tecnologias Utilizadas

- **Laravel 10.x:** Framework PHP
- **MySQL:** Banco de dados
- **Bootstrap 5:** Framework CSS
- **Docker:** Containerização
- **PHPUnit:** Testes automatizados
- **DomPDF:** Geração de PDFs

## Como Executar o Projeto

### Pré-requisitos
- Docker
- Docker Compose

### Passos para Execução

1. **Clone o repositório:**
   ```bash
   git clone https://github.com/LuizGustavoVJ/Teste-Livro.git
   cd Teste-Livro
   ```

2. **Execute o ambiente Docker:**
   ```bash
   docker-compose up --build -d
   ```

3. **Acesse a aplicação:**
   - **Sistema:** http://localhost:8085
   - **PHPMyAdmin:** http://localhost:8080

### Comandos Úteis

```bash
# Parar os containers
docker-compose down

# Ver logs
docker-compose logs -f

# Executar comandos Laravel
docker-compose exec app php artisan [comando]

# Executar testes
docker-compose exec app php artisan test
```

## Estrutura do Banco de Dados

O projeto segue o modelo de dados especificado com as seguintes tabelas:

- **books:** Armazena informações dos livros (título, ano, ISBN, valor)
- **authors:** Cadastro de autores
- **subjects:** Cadastro de assuntos
- **book_author:** Relacionamento N:N entre livros e autores
- **book_subject:** Relacionamento N:N entre livros e assuntos

### VIEW para Relatórios

O sistema inclui uma VIEW `books_by_author_view` que consolida os dados das três tabelas principais para geração de relatórios.

## Testes

O projeto inclui testes automatizados para garantir a qualidade do código:

```bash
# Executar todos os testes
docker-compose exec app php artisan test

# Executar testes específicos
docker-compose exec app php artisan test --filter=BookTest
```

## Autor

Luiz Gustavo Finotello

## Licença

Este projeto é licenciado sob a Licença MIT.
