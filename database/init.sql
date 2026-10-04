-- Fluent: схема и начальные данные (PostgreSQL 16)
-- Выполняется автоматически при первом запуске контейнера postgres.

CREATE TABLE roles (
    id   BIGSERIAL PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);

INSERT INTO roles (name) VALUES ('user'), ('admin');

CREATE TABLE users (
    id         BIGSERIAL PRIMARY KEY,
    role_id    BIGINT       NOT NULL,
    name       VARCHAR(255) NOT NULL,
    email      VARCHAR(255) NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    is_blocked BOOLEAN      NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_users_role
        FOREIGN KEY (role_id) REFERENCES roles (id) ON DELETE RESTRICT
);

-- e-mail уникален без учёта регистра
CREATE UNIQUE INDEX users_email_lower_idx ON users (LOWER(email));

-- Справочники каталога
CREATE TABLE languages (
    id   BIGSERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE levels (
    id         BIGSERIAL PRIMARY KEY,
    name       VARCHAR(50) NOT NULL UNIQUE,
    sort_order INT         NOT NULL DEFAULT 0
);

CREATE TABLE formats (
    id   BIGSERIAL PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE courses (
    id          BIGSERIAL PRIMARY KEY,
    language_id BIGINT         NOT NULL,
    level_id    BIGINT         NOT NULL,
    format_id   BIGINT         NOT NULL,
    name        VARCHAR(255)   NOT NULL,
    description TEXT           NOT NULL,
    price       NUMERIC(10, 2) NOT NULL CHECK (price >= 0),
    image       VARCHAR(500),
    created_at  TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_courses_language
        FOREIGN KEY (language_id) REFERENCES languages (id) ON DELETE RESTRICT,
    CONSTRAINT fk_courses_level
        FOREIGN KEY (level_id) REFERENCES levels (id) ON DELETE RESTRICT,
    CONSTRAINT fk_courses_format
        FOREIGN KEY (format_id) REFERENCES formats (id) ON DELETE RESTRICT
);

CREATE INDEX courses_language_idx ON courses (language_id);
CREATE INDEX courses_level_idx    ON courses (level_id);
CREATE INDEX courses_format_idx   ON courses (format_id);

CREATE TABLE application_statuses (
    id         BIGSERIAL PRIMARY KEY,
    name       VARCHAR(50) NOT NULL UNIQUE,
    sort_order INT         NOT NULL DEFAULT 0
);

CREATE TABLE applications (
    id         BIGSERIAL PRIMARY KEY,
    course_id  BIGINT       NOT NULL,
    user_id    BIGINT,
    status_id  BIGINT       NOT NULL,
    name       VARCHAR(255) NOT NULL,
    phone      VARCHAR(50)  NOT NULL,
    email      VARCHAR(255) NOT NULL,
    comment    TEXT,
    created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_applications_course
        FOREIGN KEY (course_id) REFERENCES courses (id) ON DELETE RESTRICT,
    CONSTRAINT fk_applications_user
        FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL,
    CONSTRAINT fk_applications_status
        FOREIGN KEY (status_id) REFERENCES application_statuses (id) ON DELETE RESTRICT
);

CREATE INDEX applications_user_idx    ON applications (user_id);
CREATE INDEX applications_status_idx  ON applications (status_id);
CREATE INDEX applications_created_idx ON applications (created_at);

-- ============ Начальные данные ============

INSERT INTO application_statuses (name, sort_order) VALUES
    ('Новая', 1),
    ('В обработке', 2),
    ('Подтверждена', 3),
    ('Отклонена', 4),
    ('Завершена', 5);

INSERT INTO languages (name) VALUES
    ('Английский'), ('Немецкий'), ('Испанский'), ('Французский');

INSERT INTO levels (name, sort_order) VALUES
    ('Начальный', 1), ('Средний', 2), ('Продвинутый', 3);

INSERT INTO formats (name) VALUES ('Онлайн'), ('Очно');

-- Администратор по умолчанию: admin@fluent.local / Admin123!
-- ОБЯЗАТЕЛЬНО смените пароль перед публикацией на сервере.
INSERT INTO users (role_id, name, email, password)
VALUES (
    (SELECT id FROM roles WHERE name = 'admin'),
    'Администратор',
    'admin@fluent.local',
    '$2y$10$PQ99E7dkjYMo9lJ08nfKrO.KnfGg2lWd/rmS/iEY1nExudTEQGoLW'
);

INSERT INTO courses (language_id, level_id, format_id, name, description, price, created_at) VALUES
(
    (SELECT id FROM languages WHERE name = 'Английский'),
    (SELECT id FROM levels WHERE name = 'Начальный'),
    (SELECT id FROM formats WHERE name = 'Онлайн'),
    'Английский язык для начинающих',
    'Курс для тех, кто начинает изучать английский язык с нуля. Алфавит, базовая грамматика, первые диалоги и лексика для повседневных ситуаций. Занятия с преподавателем в мини-группе.',
    15000.00, NOW() - INTERVAL '7 days'
),
(
    (SELECT id FROM languages WHERE name = 'Английский'),
    (SELECT id FROM levels WHERE name = 'Средний'),
    (SELECT id FROM formats WHERE name = 'Онлайн'),
    'Разговорный английский',
    'Практический курс для развития разговорной речи и понимания английского на слух. Много speaking-практики, разбор живых диалогов и работа над произношением.',
    18000.00, NOW() - INTERVAL '6 days'
),
(
    (SELECT id FROM languages WHERE name = 'Английский'),
    (SELECT id FROM levels WHERE name = 'Начальный'),
    (SELECT id FROM formats WHERE name = 'Очно'),
    'Английский для путешествий',
    'Полезные фразы и навыки общения для поездок: аэропорт, отель, ресторан, транспорт. Отрабатываем реальные ситуации в формате ролевых игр.',
    12000.00, NOW() - INTERVAL '5 days'
),
(
    (SELECT id FROM languages WHERE name = 'Немецкий'),
    (SELECT id FROM levels WHERE name = 'Начальный'),
    (SELECT id FROM formats WHERE name = 'Онлайн'),
    'Немецкий язык',
    'Базовый курс немецкого языка для начинающих: произношение, артикли, порядок слов в предложении и самые нужные разговорные темы.',
    16000.00, NOW() - INTERVAL '4 days'
),
(
    (SELECT id FROM languages WHERE name = 'Испанский'),
    (SELECT id FROM levels WHERE name = 'Начальный'),
    (SELECT id FROM formats WHERE name = 'Очно'),
    'Испанский язык',
    'Введение в испанский язык с упором на практическое общение. Учимся знакомиться, заказывать еду, ориентироваться в городе и поддерживать простую беседу.',
    17000.00, NOW() - INTERVAL '3 days'
),
(
    (SELECT id FROM languages WHERE name = 'Английский'),
    (SELECT id FROM levels WHERE name = 'Продвинутый'),
    (SELECT id FROM formats WHERE name = 'Онлайн'),
    'Бизнес-английский',
    'Переговоры, презентации, деловая переписка и телефонные звонки на английском. Курс для тех, кто работает или планирует работать в международной среде.',
    24000.00, NOW() - INTERVAL '2 days'
),
(
    (SELECT id FROM languages WHERE name = 'Английский'),
    (SELECT id FROM levels WHERE name = 'Продвинутый'),
    (SELECT id FROM formats WHERE name = 'Онлайн'),
    'Подготовка к IELTS',
    'Системная подготовка ко всем частям экзамена: Listening, Reading, Writing и Speaking. Пробные тесты и индивидуальная обратная связь по письменным работам.',
    28000.00, NOW() - INTERVAL '1 day'
),
(
    (SELECT id FROM languages WHERE name = 'Французский'),
    (SELECT id FROM levels WHERE name = 'Начальный'),
    (SELECT id FROM formats WHERE name = 'Онлайн'),
    'Французский с нуля',
    'Знакомство с французским языком: произношение, базовая грамматика и лексика для общения. Лёгкий старт без стресса и зубрёжки.',
    16500.00, NOW()
);
