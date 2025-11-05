# 📊 Relatório Técnico - Modelagem e Uso do Banco de Dados

## 📋 Sumário Executivo

Este documento apresenta a modelagem completa do banco de dados do sistema **Teste-Livro**, um sistema de gerenciamento de livros desenvolvido em Laravel 10.x com MySQL 8.0. O sistema gerencia livros, autores, assuntos (categorias), usuários e arquivos (imagens de capa e perfis), implementando relacionamentos muitos-para-muitos e funcionalidades avançadas como soft deletes e views materializadas.

---

## 🗂️ 1. Visão Geral do Sistema

### 1.1. Propósito do Sistema
O sistema **Teste-Livro** é uma aplicação web para gerenciamento de catálogo de livros, permitindo:
- Cadastro, edição e exclusão de livros, autores e assuntos
- Relacionamento entre livros e múltiplos autores (muitos-para-muitos)
- Relacionamento entre livros e múltiplos assuntos/categorias (muitos-para-muitos)
- Upload e gerenciamento de imagens de capa dos livros
- Sistema de usuários com autenticação
- Geração de relatórios (ex: livros por autor)
- Envio de e-mails automáticos

### 1.2. Tecnologias Utilizadas
- **Banco de Dados:** MySQL 8.0
- **ORM:** Eloquent (Laravel)
- **Cache:** Redis
- **Fila de Jobs:** Redis
- **SGBD:** MySQL com InnoDB

---

## 📐 2. Modelagem Conceitual

### 2.1. Diagrama de Entidades e Relacionamentos (ER)

```
┌─────────────┐         ┌──────────────┐         ┌─────────────┐
│   Authors   │◄────┐   │    Books     │   ┌────►│  Subjects   │
│             │     │   │              │   │     │             │
│ - id        │     │   │ - id         │   │     │ - id        │
│ - name      │     │   │ - title      │   │     │ - description│
│ - timestamps│     │   │ - year       │   │     │ - timestamps│
│ - deleted_at│     │   │ - isbn       │   │     │ - deleted_at│
└─────────────┘     │   │ - price      │   │     └─────────────┘
                    │   │ - cover_path │   │
                    │   │ - timestamps │   │
                    │   │ - deleted_at │   │
                    │   └──────────────┘   │
                    │         │           │
                    │         │           │
                    │         │           │
                    │         │           │
            ┌───────┴─────────┴───────────┴───────┐
            │                                     │
            │   ┌───────────────────┐            │
            │   │   book_author      │            │
            │   │ - id               │            │
            │   │ - book_id (FK)     │            │
            │   │ - author_id (FK)   │            │
            │   │ - timestamps       │            │
            │   └───────────────────┘            │
            │                                     │
            │   ┌───────────────────┐            │
            │   │   book_subject     │            │
            │   │ - id               │            │
            │   │ - book_id (FK)     │            │
            │   │ - subject_id (FK)  │            │
            │   │ - timestamps       │            │
            │   └───────────────────┘            │
            └─────────────────────────────────────┘

┌─────────────┐         ┌──────────────┐
│    Users    │         │   Arquivos   │
│             │         │              │
│ - id        │         │ - id         │
│ - name      │         │ - nome_original│
│ - email     │         │ - caminho    │
│ - password  │         │ - mime_type  │
│ - arquivo_id│───┐     │ - timestamps │
│ - timestamps│   │     └──────────────┘
└─────────────┘   │
                  │
                  └──► (users e books podem ter arquivos)
```

### 2.2. Descrição das Entidades

#### **Authors (Autores)**
- **Descrição:** Representa os autores dos livros
- **Características:**
  - Soft delete implementado
  - Um autor pode ter múltiplos livros
  - Um livro pode ter múltiplos autores

#### **Books (Livros)**
- **Descrição:** Entidade principal do sistema, representa os livros cadastrados
- **Características:**
  - Soft delete implementado
  - Relacionamento muitos-para-muitos com autores
  - Relacionamento muitos-para-muitos com assuntos
  - Suporte a upload de imagem de capa (opcional)
  - Campos monetários (price) com precisão decimal

#### **Subjects (Assuntos/Categorias)**
- **Descrição:** Representa as categorias ou assuntos dos livros
- **Características:**
  - Soft delete implementado
  - Um livro pode ter múltiplos assuntos
  - Um assunto pode estar relacionado a múltiplos livros

#### **Users (Usuários)**
- **Descrição:** Usuários do sistema com autenticação
- **Características:**
  - Autenticação via Laravel Sanctum
  - Relacionamento opcional com arquivo (imagem de perfil)
  - Password hashing automático

#### **Arquivos (Arquivos)**
- **Descrição:** Armazena metadados de arquivos (imagens de capa e perfis)
- **Características:**
  - Suporta múltiplos tipos MIME
  - Usado tanto por livros quanto por usuários
  - Caminho relativo para storage

---

## 🗄️ 3. Modelagem Física - Tabelas

### 3.1. Tabela `authors`

```sql
CREATE TABLE authors (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    
    INDEX idx_deleted_at (deleted_at),
    INDEX idx_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Características:**
- **Tipo:** Tabela principal
- **Engine:** InnoDB (suporta transações e foreign keys)
- **Soft Delete:** Implementado via `deleted_at`
- **Índices:** 
  - `idx_deleted_at` para otimizar queries com soft deletes
  - `idx_name` para buscas por nome

**Relacionamentos:**
- Muitos-para-muitos com `books` via tabela `book_author`

---

### 3.2. Tabela `books`

```sql
CREATE TABLE books (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    publication_year INT NULL,
    isbn VARCHAR(13) NULL,
    price DECIMAL(10, 2) NOT NULL,
    cover_image_path VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    
    INDEX idx_deleted_at (deleted_at),
    INDEX idx_title (title),
    INDEX idx_publication_year (publication_year),
    INDEX idx_isbn (isbn),
    INDEX idx_price (price)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Características:**
- **Tipo:** Tabela principal (entidade central)
- **Engine:** InnoDB
- **Soft Delete:** Implementado via `deleted_at`
- **Campos Especiais:**
  - `price`: DECIMAL(10,2) para valores monetários com precisão
  - `isbn`: VARCHAR(13) para ISBN padrão
  - `cover_image_path`: Caminho relativo para imagem de capa
- **Índices:**
  - `idx_deleted_at` para soft deletes
  - `idx_title` para buscas por título
  - `idx_publication_year` para filtros por ano
  - `idx_isbn` para busca por ISBN
  - `idx_price` para ordenação por preço

**Relacionamentos:**
- Muitos-para-muitos com `authors` via `book_author`
- Muitos-para-muitos com `subjects` via `book_subject`

---

### 3.3. Tabela `subjects`

```sql
CREATE TABLE subjects (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    description VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    
    INDEX idx_deleted_at (deleted_at),
    INDEX idx_description (description)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Características:**
- **Tipo:** Tabela principal
- **Engine:** InnoDB
- **Soft Delete:** Implementado
- **Índices:**
  - `idx_deleted_at` para soft deletes
  - `idx_description` para buscas por descrição

**Relacionamentos:**
- Muitos-para-muitos com `books` via tabela `book_subject`

---

### 3.4. Tabela `book_author` (Tabela de Junção)

```sql
CREATE TABLE book_author (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    book_id BIGINT UNSIGNED NOT NULL,
    author_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
    FOREIGN KEY (author_id) REFERENCES authors(id) ON DELETE CASCADE,
    UNIQUE KEY unique_book_author (book_id, author_id),
    
    INDEX idx_book_id (book_id),
    INDEX idx_author_id (author_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Características:**
- **Tipo:** Tabela de junção (pivot table)
- **Finalidade:** Relacionamento muitos-para-muitos entre `books` e `authors`
- **Constraints:**
  - Foreign keys com `ON DELETE CASCADE` para integridade referencial
  - Constraint UNIQUE para evitar duplicatas
- **Índices:**
  - Índices nas foreign keys para performance
  - Índice composto único para evitar duplicatas

**Justificativa de Design:**
- Permite que um livro tenha múltiplos autores (coautoria)
- Permite que um autor tenha múltiplos livros
- Cascade delete garante que ao excluir um livro ou autor, os relacionamentos são removidos automaticamente

---

### 3.5. Tabela `book_subject` (Tabela de Junção)

```sql
CREATE TABLE book_subject (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    book_id BIGINT UNSIGNED NOT NULL,
    subject_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
    UNIQUE KEY unique_book_subject (book_id, subject_id),
    
    INDEX idx_book_id (book_id),
    INDEX idx_subject_id (subject_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Características:**
- **Tipo:** Tabela de junção (pivot table)
- **Finalidade:** Relacionamento muitos-para-muitos entre `books` e `subjects`
- **Constraints:** Similar à `book_author`

**Justificativa de Design:**
- Permite categorização múltipla de livros (ex: "Ficção", "Aventura", "Romance")
- Um livro pode pertencer a múltiplas categorias simultaneamente

---

### 3.6. Tabela `users`

```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    arquivo_id BIGINT UNSIGNED NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (arquivo_id) REFERENCES arquivos(id) ON DELETE SET NULL,
    
    INDEX idx_email (email),
    INDEX idx_arquivo_id (arquivo_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Características:**
- **Tipo:** Tabela principal
- **Engine:** InnoDB
- **Campos Especiais:**
  - `password`: Hash bcrypt automático via Laravel
  - `email`: UNIQUE para garantir unicidade
  - `arquivo_id`: Relacionamento opcional com arquivo de perfil
- **Segurança:**
  - Password hashing via bcrypt
  - Email verification opcional

**Relacionamentos:**
- Um-para-muitos com `arquivos` (opcional)

---

### 3.7. Tabela `arquivos`

```sql
CREATE TABLE arquivos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome_original VARCHAR(255) NOT NULL,
    caminho VARCHAR(255) NOT NULL,
    mime_type VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    INDEX idx_mime_type (mime_type),
    INDEX idx_caminho (caminho)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Características:**
- **Tipo:** Tabela de metadados de arquivos
- **Finalidade:** Armazenar informações sobre arquivos (imagens de capa e perfis)
- **Campos:**
  - `nome_original`: Nome original do arquivo
  - `caminho`: Caminho relativo no storage
  - `mime_type`: Tipo MIME do arquivo
- **Índices:**
  - Por tipo MIME para filtros
  - Por caminho para busca

**Uso:**
- Imagens de capa de livros (via `books.cover_image_path` ou relacionamento futuro)
- Imagens de perfil de usuários (via `users.arquivo_id`)

---

## 👁️ 4. Views

### 4.1. View `books_by_author_view`

#### **Definição SQL:**

```sql
CREATE VIEW books_by_author_view AS
SELECT 
    a.id AS author_id,
    a.name AS author_name,
    b.id AS book_id,
    b.title AS book_title,
    b.publication_year,
    b.isbn,
    b.price,
    GROUP_CONCAT(s.description) AS subjects
FROM 
    authors a
JOIN 
    book_author ba ON a.id = ba.author_id
JOIN 
    books b ON ba.book_id = b.id
LEFT JOIN 
    book_subject bs ON b.id = bs.book_id
LEFT JOIN 
    subjects s ON bs.subject_id = s.id
WHERE 
    a.deleted_at IS NULL AND
    b.deleted_at IS NULL
GROUP BY 
    a.id, a.name, b.id, b.title, b.publication_year, b.isbn, b.price
ORDER BY 
    a.name, b.title;
```

#### **Finalidade:**
Esta view agrega informações de livros agrupadas por autor, incluindo:
- Dados do autor (id, nome)
- Dados do livro (id, título, ano, ISBN, preço)
- Assuntos concatenados (GROUP_CONCAT) para cada livro

#### **Justificativa de Uso:**
1. **Performance:** Evita múltiplos JOINs repetidos em relatórios
2. **Facilidade:** Consulta simplificada para relatórios "Livros por Autor"
3. **Consistência:** Garante que apenas registros não deletados (soft delete) sejam exibidos
4. **Agregação:** Concatena assuntos em uma única string, facilitando exibição

#### **Exemplo de Uso:**

```sql
-- Buscar todos os livros do autor ID 1
SELECT * FROM books_by_author_view WHERE author_id = 1;

-- Buscar estatísticas por autor
SELECT 
    author_id,
    author_name,
    COUNT(*) AS total_livros,
    SUM(price) AS valor_total
FROM books_by_author_view
GROUP BY author_id, author_name;
```

#### **Uso na Aplicação:**
- Utilizada no `ReportController` para gerar relatórios PDF e HTML
- Método `booksByAuthorFromView()` utiliza esta view diretamente
- Otimiza queries complexas que seriam lentas com múltiplos JOINs

---

## 🔍 5. Índices e Performance

### 5.1. Estratégia de Índices

#### **Índices Primários:**
- Todas as tabelas usam `id` como PRIMARY KEY (AUTO_INCREMENT)

#### **Índices de Foreign Keys:**
- Todas as foreign keys possuem índices para otimizar JOINs
- Exemplo: `book_author.book_id` e `book_author.author_id`

#### **Índices de Busca:**
- `authors.name` - Busca por nome de autor
- `books.title` - Busca por título de livro
- `books.isbn` - Busca por ISBN
- `books.publication_year` - Filtro por ano
- `books.price` - Ordenação por preço
- `subjects.description` - Busca por assunto

#### **Índices de Soft Delete:**
- Todas as tabelas com `deleted_at` possuem índice neste campo
- Otimiza queries que filtram registros não deletados

### 5.2. Otimizações Implementadas

1. **Soft Deletes com Índices:** Queries que filtram `WHERE deleted_at IS NULL` são otimizadas
2. **Eager Loading:** Uso de `with()` no Eloquent para evitar N+1 queries
3. **Cache Redis:** Dados frequentemente acessados são cacheados
4. **View Materializada:** `books_by_author_view` reduz complexidade de queries de relatório

---

## 🔐 6. Integridade e Constraints

### 6.1. Foreign Keys

Todas as foreign keys implementam:
- **ON DELETE CASCADE:** Para tabelas de junção (book_author, book_subject)
- **ON DELETE SET NULL:** Para relacionamentos opcionais (users.arquivo_id)

### 6.2. Constraints de Unicidade

- `users.email` - UNIQUE (garante unicidade de e-mail)
- `book_author(book_id, author_id)` - UNIQUE (evita duplicatas)
- `book_subject(book_id, subject_id)` - UNIQUE (evita duplicatas)

### 6.3. Soft Deletes

- Implementado em: `authors`, `books`, `subjects`
- Permite recuperação de dados excluídos
- Queries padrão do Eloquent filtram automaticamente registros deletados

---

## 📈 7. Estratégias de Dados

### 7.1. Tipos de Dados

- **Monetários:** `DECIMAL(10,2)` para `books.price` - Precisão de centavos
- **Strings:** `VARCHAR(255)` para textos variáveis
- **Timestamps:** `TIMESTAMP NULL` para controle de criação/atualização
- **Booleanos:** Via `TIMESTAMP NULL` para soft deletes

### 7.2. Normalização

- **3ª Forma Normal (3NF):** Atingida
- Tabelas separadas para entidades independentes
- Tabelas de junção para relacionamentos muitos-para-muitos
- Sem redundância de dados

### 7.3. Denormalização Controlada

- **View `books_by_author_view`:** Denormalização controlada para performance
- Agrega dados de múltiplas tabelas para otimizar relatórios
- Mantém integridade através de JOINs baseados em foreign keys

---

## 🚀 8. Performance e Escalabilidade

### 8.1. Queries Otimizadas

1. **Eager Loading:**
   ```php
   Book::with(['authors', 'subjects'])->get();
   ```

2. **Uso de View:**
   ```php
   DB::table('books_by_author_view')->where('author_id', $id)->get();
   ```

3. **Pagination:**
   ```php
   Book::with(['authors', 'subjects'])->paginate(10);
   ```

### 8.2. Cache Strategy

- **Redis:** Cache de queries frequentes
- **Cache de Config:** `php artisan config:cache`
- **Cache de Views:** `php artisan view:cache`

### 8.3. Fila de Jobs

- **Redis Queue:** Jobs assíncronos para e-mails
- Desacopla operações pesadas do request HTTP

---

## 📝 9. Migrations e Versionamento

### 9.1. Estrutura de Migrations

Todas as tabelas são criadas via migrations Laravel:
- `2025_05_12_000000_create_users_table.php`
- `2025_06_07_141748_create_authors_table.php`
- `2025_06_07_141754_create_subjects_table.php`
- `2025_06_07_141758_create_books_table.php`
- `2025_06_07_141803_create_book_author_table.php`
- `2025_06_07_141808_create_book_subject_table.php`
- `2025_06_11_142142_create_books_by_author_view.php`
- `2025_04_07_133834_create_arquivos_table.php`

### 9.2. Versionamento

- Migrations numeradas por data/hora
- Permite rollback via `php artisan migrate:rollback`
- Ambiente de desenvolvimento e produção sincronizados

---

## 🔄 10. Relacionamentos e Eloquent

### 10.1. Relacionamentos Definidos

#### **Book Model:**
```php
public function authors(): BelongsToMany
public function subjects(): BelongsToMany
```

#### **Author Model:**
```php
public function books(): BelongsToMany
```

#### **Subject Model:**
```php
public function books(): BelongsToMany
```

#### **User Model:**
```php
public function arquivo(): BelongsToOne
```

### 10.2. Eager Loading

Uso consistente de `with()` para evitar N+1 queries:
```php
Book::with(['authors', 'subjects'])->get();
```

---

## 📊 11. Estatísticas e Métricas

### 11.1. Volume Esperado

- **Livros:** Centenas a milhares
- **Autores:** Dezenas a centenas
- **Assuntos:** Dezenas
- **Usuários:** Dezenas a centenas

### 11.2. Performance Esperada

- **Queries simples:** < 50ms
- **Queries com JOINs:** < 200ms
- **Relatórios via view:** < 500ms
- **Pagination:** < 100ms

---

## ✅ 12. Conclusão

### 12.1. Pontos Fortes

1. ✅ **Normalização adequada** - 3NF implementada
2. ✅ **Soft Deletes** - Recuperação de dados
3. ✅ **Integridade Referencial** - Foreign keys e constraints
4. ✅ **Performance** - Índices estratégicos e views
5. ✅ **Escalabilidade** - Estrutura preparada para crescimento
6. ✅ **Manutenibilidade** - Migrations versionadas

### 12.2. Melhorias Futuras Sugeridas

1. **Full-Text Search:** Adicionar índices FULLTEXT para busca textual
2. **Partitioning:** Para tabelas muito grandes (futuro)
3. **Materialized Views:** Se necessário para relatórios muito complexos
4. **Audit Log:** Tabela de auditoria para rastreamento de mudanças

---

**Documento gerado em:** 2025-11-05  
**Versão do Sistema:** Laravel 10.48.29  
**Versão do MySQL:** 8.0

