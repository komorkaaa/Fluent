<?php

namespace App\Models;

use App\Core\Database;

class Application
{
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

    public static function findByUserId(int $userId): array
    {
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
}