# Fluent — школа иностранных языков

Веб-приложение на PHP 8.4 (чистый MVC, без фреймворков), PostgreSQL 16, Bootstrap 5.3.
Курсовой проект по МДК.02.02.

## Запуск

```bash
docker compose up -d --build
```

Сайт: <http://localhost:8080>

Администратор по умолчанию: `admin@fluent.local` / `Admin123!`
**Смените пароль перед публикацией на сервере.**

### Обновление существующей базы

`database/init.sql` выполняется только при первом создании тома БД. Если база уже была
со старой схемой — либо пересоздайте её (`docker compose down -v && docker compose up -d --build`),
либо примените миграцию (безопасна при повторном запуске):

```bash
docker compose exec -T postgres psql -U fluent -d fluent < database/migrations/001_normalize_and_admin.sql
```

## Структура

```
app/core/         Router, Auth, Controller, Database, helpers
app/controllers/  контроллеры (публичные и admin*)
app/models/       работа с БД через PDO (подготовленные запросы)
app/views/        представления, partials (иконки SVG, карточка курса и т. д.)
routes/web.php    серверные маршруты
public/           точка входа, css, js, SVG-графика
database/         init.sql и миграции
```

## Безопасность

- пароли — `password_hash` (bcrypt), e-mail уникален без учёта регистра;
- CSRF-токен во всех POST-формах;
- права проверяются на сервере; заблокированный пользователь и смена роли
  применяются сразу, без повторного входа;
- все запросы к БД — подготовленные; весь вывод экранируется (`e()`);
- серверная валидация всех форм, клиентская — только подсказки.
