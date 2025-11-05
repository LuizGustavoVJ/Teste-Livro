-- Configurações iniciais do MySQL para o projeto Teste-Livro
SET GLOBAL sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_DATE,NO_ZERO_IN_DATE,ERROR_FOR_DIVISION_BY_ZERO';

-- Criar usuário adicional para a aplicação (opcional)
CREATE USER IF NOT EXISTS 'laravel'@'%' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON teste_livro.* TO 'laravel'@'%';
FLUSH PRIVILEGES;

-- Configurações de performance
SET GLOBAL innodb_buffer_pool_size = 128M;
SET GLOBAL max_connections = 100;

