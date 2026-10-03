CREATE TABLE courses (
                         id BIGSERIAL PRIMARY KEY,
                         name VARCHAR(255) NOT NULL,
                         description TEXT NOT NULL,
                         price NUMERIC(10, 2) NOT NULL,
                         level VARCHAR(50) NOT NULL,
                         format VARCHAR(50) NOT NULL,
                         language VARCHAR(100) NOT NULL,
                         created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO courses (
    name,
    description,
    price,
    level,
    format,
    language
) VALUES
      (
          'Английский язык для начинающих',
          'Курс для тех, кто начинает изучать английский язык с нуля.',
          15000.00,
          'Начальный',
          'Онлайн',
          'Английский'
      ),
      (
          'Разговорный английский',
          'Практический курс для развития разговорной речи и понимания английского языка.',
          18000.00,
          'Средний',
          'Онлайн',
          'Английский'
      ),
      (
          'Английский для путешествий',
          'Полезные фразы и навыки общения для поездок и путешествий.',
          12000.00,
          'Начальный',
          'Очно',
          'Английский'
      ),
      (
          'Немецкий язык',
          'Базовый курс немецкого языка для начинающих.',
          16000.00,
          'Начальный',
          'Онлайн',
          'Немецкий'
      ),
      (
          'Испанский язык',
          'Введение в испанский язык с упором на практическое общение.',
          17000.00,
          'Начальный',
          'Очно',
          'Испанский'
      );


CREATE TABLE roles (
                       id BIGSERIAL PRIMARY KEY,
                       name VARCHAR(50) NOT NULL UNIQUE
);

INSERT INTO roles (name) VALUES
                             ('user'),
                             ('admin');


CREATE TABLE users (
                       id BIGSERIAL PRIMARY KEY,

                       role_id BIGINT NOT NULL,

                       name VARCHAR(255) NOT NULL,
                       email VARCHAR(255) NOT NULL UNIQUE,
                       password VARCHAR(255) NOT NULL,
                       is_blocked BOOLEAN NOT NULL DEFAULT FALSE,

                       created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

                       CONSTRAINT fk_users_role
                           FOREIGN KEY (role_id)
                               REFERENCES roles(id)
                               ON DELETE RESTRICT
);


CREATE TABLE applications (
                              id BIGSERIAL PRIMARY KEY,

                              course_id BIGINT NOT NULL,
                              user_id BIGINT,

                              name VARCHAR(255) NOT NULL,
                              phone VARCHAR(50) NOT NULL,
                              email VARCHAR(255) NOT NULL,
                              comment TEXT,

                              status VARCHAR(50) NOT NULL DEFAULT 'Новая',

                              created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

                              CONSTRAINT fk_applications_course
                                  FOREIGN KEY (course_id)
                                      REFERENCES courses(id)
                                      ON DELETE RESTRICT,

                              CONSTRAINT fk_applications_user
                                  FOREIGN KEY (user_id)
                                      REFERENCES users(id)
                                      ON DELETE SET NULL
);