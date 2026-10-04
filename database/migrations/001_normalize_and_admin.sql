-- Миграция со старой схемы (courses.language/level/format и applications.status как текст)
-- на новую: справочники languages/levels/formats/application_statuses.
-- Безопасно запускать повторно: если миграция уже применена, ничего не произойдёт.
--
--   docker compose exec -T postgres psql -U fluent -d fluent < database/migrations/001_normalize_and_admin.sql

DO $$
BEGIN
    IF EXISTS (
        SELECT 1 FROM information_schema.columns
        WHERE table_name = 'courses' AND column_name = 'language'
    ) THEN

        CREATE TABLE languages (
            id BIGSERIAL PRIMARY KEY,
            name VARCHAR(100) NOT NULL UNIQUE
        );
        CREATE TABLE levels (
            id BIGSERIAL PRIMARY KEY,
            name VARCHAR(50) NOT NULL UNIQUE,
            sort_order INT NOT NULL DEFAULT 0
        );
        CREATE TABLE formats (
            id BIGSERIAL PRIMARY KEY,
            name VARCHAR(50) NOT NULL UNIQUE
        );
        CREATE TABLE application_statuses (
            id BIGSERIAL PRIMARY KEY,
            name VARCHAR(50) NOT NULL UNIQUE,
            sort_order INT NOT NULL DEFAULT 0
        );

        INSERT INTO languages (name) SELECT DISTINCT language FROM courses WHERE language <> '';
        INSERT INTO levels (name, sort_order)
            SELECT DISTINCT level,
                   CASE level WHEN 'Начальный' THEN 1 WHEN 'Средний' THEN 2 WHEN 'Продвинутый' THEN 3 ELSE 9 END
            FROM courses WHERE level <> '';
        INSERT INTO formats (name) SELECT DISTINCT format FROM courses WHERE format <> '';

        INSERT INTO languages (name) VALUES ('Английский'), ('Немецкий'), ('Испанский'), ('Французский')
            ON CONFLICT DO NOTHING;
        INSERT INTO levels (name, sort_order) VALUES ('Начальный', 1), ('Средний', 2), ('Продвинутый', 3)
            ON CONFLICT DO NOTHING;
        INSERT INTO formats (name) VALUES ('Онлайн'), ('Очно') ON CONFLICT DO NOTHING;

        INSERT INTO application_statuses (name, sort_order) VALUES
            ('Новая', 1), ('В обработке', 2), ('Подтверждена', 3), ('Отклонена', 4), ('Завершена', 5);
        -- на случай нестандартных статусов в старых данных
        INSERT INTO application_statuses (name, sort_order)
            SELECT DISTINCT status, 9 FROM applications
            WHERE status NOT IN (SELECT name FROM application_statuses);

        ALTER TABLE courses ADD COLUMN language_id BIGINT;
        ALTER TABLE courses ADD COLUMN level_id BIGINT;
        ALTER TABLE courses ADD COLUMN format_id BIGINT;
        UPDATE courses c SET language_id = l.id FROM languages l WHERE l.name = c.language;
        UPDATE courses c SET level_id    = l.id FROM levels l    WHERE l.name = c.level;
        UPDATE courses c SET format_id   = f.id FROM formats f   WHERE f.name = c.format;

        -- курсы с пустыми значениями получают первое значение справочника
        UPDATE courses SET language_id = (SELECT MIN(id) FROM languages) WHERE language_id IS NULL;
        UPDATE courses SET level_id    = (SELECT MIN(id) FROM levels)    WHERE level_id IS NULL;
        UPDATE courses SET format_id   = (SELECT MIN(id) FROM formats)   WHERE format_id IS NULL;

        ALTER TABLE courses ALTER COLUMN language_id SET NOT NULL;
        ALTER TABLE courses ALTER COLUMN level_id SET NOT NULL;
        ALTER TABLE courses ALTER COLUMN format_id SET NOT NULL;
        ALTER TABLE courses ADD CONSTRAINT fk_courses_language FOREIGN KEY (language_id) REFERENCES languages (id) ON DELETE RESTRICT;
        ALTER TABLE courses ADD CONSTRAINT fk_courses_level    FOREIGN KEY (level_id)    REFERENCES levels (id)    ON DELETE RESTRICT;
        ALTER TABLE courses ADD CONSTRAINT fk_courses_format   FOREIGN KEY (format_id)   REFERENCES formats (id)   ON DELETE RESTRICT;
        ALTER TABLE courses ADD CONSTRAINT courses_price_check CHECK (price >= 0);
        ALTER TABLE courses DROP COLUMN language, DROP COLUMN level, DROP COLUMN format;

        ALTER TABLE applications ADD COLUMN status_id BIGINT;
        UPDATE applications a SET status_id = s.id FROM application_statuses s WHERE s.name = a.status;
        ALTER TABLE applications ALTER COLUMN status_id SET NOT NULL;
        ALTER TABLE applications ADD CONSTRAINT fk_applications_status FOREIGN KEY (status_id) REFERENCES application_statuses (id) ON DELETE RESTRICT;
        ALTER TABLE applications DROP COLUMN status;

        CREATE INDEX courses_language_idx ON courses (language_id);
        CREATE INDEX courses_level_idx    ON courses (level_id);
        CREATE INDEX courses_format_idx   ON courses (format_id);
        CREATE INDEX applications_user_idx    ON applications (user_id);
        CREATE INDEX applications_status_idx  ON applications (status_id);
        CREATE INDEX applications_created_idx ON applications (created_at);
    END IF;
END $$;

-- e-mail уникален без учёта регистра (упадёт, если уже есть дубли по регистру — их нужно слить вручную)
CREATE UNIQUE INDEX IF NOT EXISTS users_email_lower_idx ON users (LOWER(email));

-- Администратор по умолчанию (если администраторов ещё нет): admin@fluent.local / Admin123!
INSERT INTO users (role_id, name, email, password)
SELECT (SELECT id FROM roles WHERE name = 'admin'),
       'Администратор',
       'admin@fluent.local',
       (SELECT password FROM (VALUES ('$2y$10$PQ99E7dkjYMo9lJ08nfKrO.KnfGg2lWd/rmS/iEY1nExudTEQGoLW')) AS t(password))
WHERE NOT EXISTS (
    SELECT 1 FROM users WHERE role_id = (SELECT id FROM roles WHERE name = 'admin')
);
