# Конференции.РФ — окружение для Laravel 13 (PHP 8.4)
FROM php:8.4-cli

# Системные библиотеки и PHP-расширения, нужные Laravel
RUN apt-get update && apt-get install -y --no-install-recommends \
        git unzip libonig-dev libzip-dev libsqlite3-dev \
    && docker-php-ext-install -j"$(nproc)" mbstring zip bcmath pdo_sqlite \
    && rm -rf /var/lib/apt/lists/*

# Composer из официального образа
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
EXPOSE 8000

# Встроенный сервер Laravel, доступный снаружи контейнера
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
