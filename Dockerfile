FROM php:8.2-fpm

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libgd-dev \
    libsodium-dev \
    jpegoptim \
    optipng \
    pngquant \
    gifsicle \
    vim \
    nano \
    supervisor \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip fileinfo sodium

# Instalar Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Instalar PCOV para cobertura de código
RUN pecl install pcov && docker-php-ext-enable pcov

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar diretório de trabalho
WORKDIR /var/www/html

# Copiar arquivos do projeto
COPY . /var/www/html

# Instalar Telescope
RUN composer require laravel/telescope --dev

# Instalar dependências do Composer
RUN composer install --optimize-autoloader

# Instalar netcat para healthcheck
RUN apt-get update && apt-get install -y netcat-openbsd && rm -rf /var/lib/apt/lists/*

# Garantir permissão de execução para o entrypoint e healthcheck do app (evita erro no Windows)
RUN chmod +x /var/www/html/docker/app/entrypoint.sh /var/www/html/docker/app/healthcheck.sh || true

#Instalando o Supervisor para queue:work em segundo plano
COPY ./docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Configurar PHP-FPM para escutar em todas as interfaces (necessário para Nginx se conectar)
# Modificar o www.conf padrão para escutar em 0.0.0.0:9000 ao invés de 127.0.0.1:9000
RUN sed -i 's/listen = 127.0.0.1:9000/listen = 0.0.0.0:9000/' /usr/local/etc/php-fpm.d/www.conf || \
    sed -i 's/listen = .*9000/listen = 0.0.0.0:9000/' /usr/local/etc/php-fpm.d/www.conf || true

# Configurar permissões
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Permissões para Linux
RUN chmod -R ug+rwx storage bootstrap/cache

# Expor porta
EXPOSE 8000


