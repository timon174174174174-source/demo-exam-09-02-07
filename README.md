# Портал «Конференции.РФ»

Информационная система для бронирования помещений под проведение Всероссийских
конференций (аудитория, коворкинг, кинозал). Демонстрационный экзамен 09.02.07,
вариант № 2.

Стек: **PHP 8.4 / Laravel 13 / SQLite**, запуск через **Docker**.
Подробности архитектуры и ER-диаграмма — в [docs/design.md](docs/design.md).

## Быстрый запуск

Нужен установленный Docker.

```bash
docker compose up -d --build
```

Контейнер при старте сам подготовит окружение (`.env`, ключ приложения,
зависимости, базу SQLite, миграции и сидеры) и запустит сервер.

Открыть в браузере: **http://localhost:8888**

## Учётные записи

- **Администратор:** логин `Admin26`, пароль `Demo20`
- **Пользователь:** создаётся через страницу регистрации

## Полезные команды

```bash
# логи приложения
docker compose logs -f

# выполнить artisan-команду
docker compose exec app php artisan <команда>

# пересоздать базу с тестовыми данными
docker compose exec app php artisan migrate:fresh --seed

# остановить
docker compose down
```

## Тестирование и качество кода

```bash
# автотесты (21 тест: регистрация, вход, заявки, админка, гейтинг отзывов)
docker compose exec app php artisan test

# проверка стиля кода (Laravel Pint)
docker compose exec app ./vendor/bin/pint --test
```

> Если порт 8888 занят, измените его в `docker-compose.yml` (строка `ports`).

## Структура

- `app/Models` — модели Eloquent (User, Room, Booking, Review)
- `app/Http/Controllers` — контроллеры (аутентификация, кабинет, заявки, админка)
- `resources/views` — Blade-шаблоны (mobile-first дизайн 390×844)
- `database/migrations` — схема БД
- `database/seeders` — помещения и учётная запись администратора
- `routes/web.php` — маршруты
- `tests/Feature` — автотесты приложения
- `docs/` — проектная документация, ER-диаграмма и задание экзамена
