<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Course {
    public static function all(): array {
        $database = Database::connection();

        $statement = $database->query(
            'SELECT *
             FROM courses
             ORDER BY created_at DESC'
        );

        return $statement->fetchAll();
    }

    public static function find(int $id): ?array {
        $database = Database::connection();

        $statement = $database->prepare(
            'SELECT *
         FROM courses
         WHERE id = :id'
        );

        $statement->execute([
            'id' => $id,
        ]);

        $course = $statement->fetch();

        return $course ?: null;
    }
}