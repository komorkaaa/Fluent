<?php

namespace App\Models;

use App\Core\Database;

class Application {
    public static function create(
        int $courseId,
        string $name,
        string $phone,
        string $email,
        ?string $comment,
        ?int $userId = null
    ): int {
        $database = Database::connection();

        $statement = $database->prepare(
            'INSERT INTO applications (
                course_id,
                user_id,
                name,
                phone,
                email,
                comment
            )
            VALUES (
                :course_id,
                :user_id,
                :name,
                :phone,
                :email,
                :comment
            )
            RETURNING id'
        );

        $statement->execute([
            'course_id' => $courseId,
            'user_id' => $userId,
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'comment' => $comment,
        ]);

        return (int) $statement->fetchColumn();
    }

    public static function findByUserId(int $userId): array {
        $database = Database::connection();

        $statement = $database->prepare(
            'SELECT
                applications.id,
                applications.name,
                applications.email,
                applications.phone,
                applications.comment,
                applications.status,
                applications.created_at,
                courses.id AS course_id,
                courses.name AS course_name
             FROM applications
             INNER JOIN courses
                ON courses.id = applications.course_id
             WHERE applications.user_id = :user_id
             ORDER BY applications.created_at DESC'
        );

        $statement->execute([
            'user_id' => $userId,
        ]);

        return $statement->fetchAll();
    }

    public static function all(array $filters = []): array {
        $database = Database::connection();

        $conditions = [];
        $parameters = [];

        if (
            isset($filters['user_id'])
            && $filters['user_id'] !== ''
            && ctype_digit((string) $filters['user_id'])
        ) {
            $conditions[] = 'applications.user_id = :user_id';
            $parameters['user_id'] = (int) $filters['user_id'];
        }

        if (
            isset($filters['status'])
            && $filters['status'] !== ''
        ) {
            $conditions[] = 'applications.status = :status';
            $parameters['status'] = $filters['status'];
        }

        if (
            isset($filters['date_from'])
            && $filters['date_from'] !== ''
        ) {
            $conditions[] = 'applications.created_at >= :date_from';
            $parameters['date_from'] = $filters['date_from'] . ' 00:00:00';
        }

        if (
            isset($filters['date_to'])
            && $filters['date_to'] !== ''
        ) {
            $conditions[] = 'applications.created_at < :date_to';
            $parameters['date_to'] = date(
                'Y-m-d 00:00:00',
                strtotime($filters['date_to'] . ' +1 day')
            );
        }

        $sql = '
            SELECT
                applications.id,
                applications.user_id,
                applications.name,
                applications.phone,
                applications.email,
                applications.comment,
                applications.status,
                applications.created_at,
                courses.name AS course_name,
                users.name AS user_name,
                users.email AS user_email
            FROM applications
            INNER JOIN courses
                ON courses.id = applications.course_id
            LEFT JOIN users
                ON users.id = applications.user_id
        ';

        if ($conditions !== []) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $sql .= ' ORDER BY applications.created_at DESC';

        $statement = $database->prepare($sql);
        $statement->execute($parameters);

        return $statement->fetchAll();
    }

    public static function find(int $id): ?array{
        $database = Database::connection();

        $statement = $database->prepare(
            'SELECT
                applications.id,
                applications.course_id,
                applications.user_id,
                applications.name,
                applications.phone,
                applications.email,
                applications.comment,
                applications.status,
                applications.created_at,
                courses.name AS course_name
             FROM applications
             INNER JOIN courses
                ON courses.id = applications.course_id
             WHERE applications.id = :id'
        );

        $statement->execute([
            'id' => $id,
        ]);

        $application = $statement->fetch();

        return $application ?: null;
    }

    public static function updateStatus(
        int $id,
        string $status
    ): void {
        $database = Database::connection();

        $statement = $database->prepare(
            'UPDATE applications
             SET status = :status
             WHERE id = :id'
        );

        $statement->execute([
            'id' => $id,
            'status' => $status,
        ]);
    }

    public static function delete(int $id): void{
        $database = Database::connection();

        $statement = $database->prepare(
            'DELETE FROM applications
             WHERE id = :id'
        );

        $statement->execute([
            'id' => $id,
        ]);
    }
}