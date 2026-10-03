<?php

namespace App\Models;

use App\Core\Database;

class Course {
    public static function all(
        ?string $language = null,
        ?string $level = null,
        ?string $format = null,
        string $sort = 'date_desc'
    ): array {
        $database = Database::connection();

        $conditions = [];
        $parameters = [];

        if ($language !== null && $language !== '') {
            $conditions[] = 'language = :language';
            $parameters['language'] = $language;
        }

        if ($level !== null && $level !== '') {
            $conditions[] = 'level = :level';
            $parameters['level'] = $level;
        }

        if ($format !== null && $format !== '') {
            $conditions[] = 'format = :format';
            $parameters['format'] = $format;
        }

        $where = '';

        if ($conditions !== []) {
            $where = 'WHERE ' . implode(' AND ', $conditions);
        }

        $sortOptions = [
            'price_asc' => 'price ASC',
            'price_desc' => 'price DESC',
            'name_asc' => 'name ASC',
            'date_desc' => 'created_at DESC',
        ];

        $orderBy = $sortOptions[$sort] ?? $sortOptions['date_desc'];

        $sql = "
            SELECT *
            FROM courses
            {$where}
            ORDER BY {$orderBy}
        ";

        $statement = $database->prepare($sql);
        $statement->execute($parameters);

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

    public static function create(
        string $name,
        string $description,
        float $price,
        string $level,
        string $format,
        string $language
    ): int {
        $database = Database::connection();

        $statement = $database->prepare(
            'INSERT INTO courses (
                name,
                description,
                price,
                level,
                format,
                language
            )
            VALUES (
                :name,
                :description,
                :price,
                :level,
                :format,
                :language
            )
            RETURNING id'
        );

        $statement->execute([
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'level' => $level,
            'format' => $format,
            'language' => $language,
        ]);

        return (int) $statement->fetchColumn();
    }

    public static function update(
        int $id,
        string $name,
        string $description,
        float $price,
        string $level,
        string $format,
        string $language
    ): void {
        $database = Database::connection();

        $statement = $database->prepare(
            'UPDATE courses
             SET name = :name,
                 description = :description,
                 price = :price,
                 level = :level,
                 format = :format,
                 language = :language
             WHERE id = :id'
        );

        $statement->execute([
            'id' => $id,
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'level' => $level,
            'format' => $format,
            'language' => $language,
        ]);
    }

    public static function delete(int $id): void {
        $database = Database::connection();

        $statement = $database->prepare(
            'DELETE FROM courses
             WHERE id = :id'
        );

        $statement->execute([
            'id' => $id,
        ]);
    }
}