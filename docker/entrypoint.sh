#!/bin/sh
# Авто-инициализация приложения при старте контейнера:
# готовит .env, ключ, зависимости и базу данных, затем запускает веб-сервер.
set -e
cd /app

# 1. Файл окружения
if [ ! -f .env ]; then
  cp .env.example .env
  echo "[entrypoint] .env создан из .env.example"
fi

# 2. Composer-зависимости (на случай чистого клонирования без папки vendor)
if [ ! -d vendor ]; then
  echo "[entrypoint] устанавливаю composer-зависимости..."
  composer install --no-interaction --prefer-dist
fi

# 3. Ключ приложения
if ! grep -q "^APP_KEY=base64:" .env; then
  php artisan key:generate --force
fi

# 4. База данных SQLite (миграции всегда, сидеры — только при первом создании БД)
NEED_SEED=0
if [ ! -f database/database.sqlite ]; then
  touch database/database.sqlite
  NEED_SEED=1
fi
php artisan migrate --force
if [ "$NEED_SEED" = "1" ]; then
  php artisan db:seed --force
fi

# 5. Запуск встроенного веб-сервера Laravel
exec php artisan serve --host=0.0.0.0 --port=8000
