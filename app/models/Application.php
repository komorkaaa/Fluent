<?php

namespace App\Models;

use App\Core\Database;

class Application {
    public static function create(
        int $courseId,
        string $name,
        string $phone,
        string $email,
        ?string $comment
    ): int {
        $database = Database::connection();

        $statement = $database->prepare(
            'INSERT INTO applications (
                course_id,
                name,
                phone,
                email,
                comment
            )
            VALUES (
                :course_id,
                :name,
                :phone,
                :email,
                :comment
            )
            RETURNING id'
        );

        $statement->execute([
            'course_id' => $courseId,
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'comment' => $comment,
        ]);

        return (int) $statement->fetchColumn();
    }
}