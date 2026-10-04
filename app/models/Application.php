<?php

namespace App\Models;

use App\Core\Database;

class Application {
    public const DEFAULT_STATUS = 'Новая';

    private const SELECT = '
        SELECT
            applications.id,
            applications.course_id,
            applications.user_id,
            applications.status_id,
            applications.name,
            applications.phone,
            applications.email,
            applications.comment,
            applications.created_at,
            application_statuses.name AS status,
            courses.name AS course_name,
            courses.price AS course_price,
            users.name AS user_name,
            users.email AS user_email
        FROM applications
        INNER JOIN application_statuses
            ON application_statuses.id = applications.status_id
        INNER JOIN courses
            ON courses.id = applications.course_id
        LEFT JOIN users
            ON users.id = applications.user_id
    ';

    public static function statuses(): array {
        return Database::connection()
            ->query('SELECT id, name FROM application_statuses ORDER BY sort_order ASC, id ASC')
            ->fetchAll();
    }

    public static function statusExists(int $id): bool {
        $statement = Database::connection()->prepare(
            'SELECT 1 FROM application_statuses WHERE id = :id'
        );
        $statement->execute(['id' => $id]);

        return (bool) $statement->fetchColumn();
    }

    public static function create(
        int $courseId,
        string $name,
        string $phone,
        string $email,
        ?string $comment,
        ?int $userId = null
    ): int {
        $statement = Database::connection()->prepare(
            'INSERT INTO applications (
                course_id, user_id, status_id, name, phone, email, comment
            ) VALUES (
                :course_id,
                :user_id,
                (SELECT id FROM application_statuses WHERE name = :status),
                :name,
                :phone,
                :email,
                :comment
            ) RETURNING id'
        );

        $statement->execute([
            'course_id' => $courseId,
            'user_id' => $userId,
            'status' => self::DEFAULT_STATUS,
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'comment' => $comment,
        ]);

        return (int) $statement->fetchColumn();
    }

    public static function findByUserId(int $userId): array {
        $statement = Database::connection()->prepare(
            self::SELECT . ' WHERE applications.user_id = :user_id
             ORDER BY applications.created_at DESC, applications.id DESC'
        );
        $statement->execute(['user_id' => $userId]);

        return $statement->fetchAll();
    }

    /** Заявка, принадлежащая конкретному пользователю (для личного кабинета). */
    public static function findForUser(int $id, int $userId): ?array {
        $statement = Database::connection()->prepare(
            self::SELECT . ' WHERE applications.id = :id AND applications.user_id = :user_id'
        );
        $statement->execute(['id' => $id, 'user_id' => $userId]);

        return $statement->fetch() ?: null;
    }

    /** Список для админки. Фильтры: user_id, status_id, date_from, date_to. */
    public static function all(array $filters = []): array {
        $conditions = [];
        $parameters = [];

        if (!empty($filters['user_id'])) {
            $conditions[] = 'applications.user_id = :user_id';
            $parameters['user_id'] = (int) $filters['user_id'];
        }

        if (!empty($filters['status_id'])) {
            $conditions[] = 'applications.status_id = :status_id';
            $parameters['status_id'] = (int) $filters['status_id'];
        }

        if (!empty($filters['date_from'])) {
            $conditions[] = 'applications.created_at >= :date_from';
            $parameters['date_from'] = $filters['date_from'] . ' 00:00:00';
        }

        if (!empty($filters['date_to'])) {
            $conditions[] = 'applications.created_at < :date_to';
            $parameters['date_to'] = date(
                'Y-m-d 00:00:00',
                strtotime($filters['date_to'] . ' +1 day')
            );
        }

        $sql = self::SELECT;

        if ($conditions !== []) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $sql .= ' ORDER BY applications.created_at DESC, applications.id DESC';

        $statement = Database::connection()->prepare($sql);
        $statement->execute($parameters);

        return $statement->fetchAll();
    }

    public static function find(int $id): ?array {
        $statement = Database::connection()->prepare(
            self::SELECT . ' WHERE applications.id = :id'
        );
        $statement->execute(['id' => $id]);

        return $statement->fetch() ?: null;
    }

    public static function count(): int {
        return (int) Database::connection()
            ->query('SELECT COUNT(*) FROM applications')
            ->fetchColumn();
    }

    /** Количество заявок по каждому статусу (для обзора в админке). */
    public static function countByStatus(): array {
        return Database::connection()->query(
            'SELECT application_statuses.id, application_statuses.name, COUNT(applications.id) AS total
             FROM application_statuses
             LEFT JOIN applications ON applications.status_id = application_statuses.id
             GROUP BY application_statuses.id, application_statuses.name, application_statuses.sort_order
             ORDER BY application_statuses.sort_order ASC'
        )->fetchAll();
    }

    public static function updateStatus(int $id, int $statusId): void {
        $statement = Database::connection()->prepare(
            'UPDATE applications SET status_id = :status_id WHERE id = :id'
        );

        $statement->execute(['id' => $id, 'status_id' => $statusId]);
    }

    public static function delete(int $id): void {
        $statement = Database::connection()->prepare(
            'DELETE FROM applications WHERE id = :id'
        );

        $statement->execute(['id' => $id]);
    }
}
