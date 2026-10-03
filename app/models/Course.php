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

    public static function similar(int $id, string $language, int $limit = 3): array {
        $database = Database::connection();

        $statement = $database->prepare(
            'SELECT *
             FROM courses
             WHERE id != :id
               AND language = :language
             ORDER BY created_at DESC
             LIMIT :limit'
        );

        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->bindValue(':language', $language);
        $statement->bindValue(':limit', $limit, \PDO::PARAM_INT);

        $statement->execute();

        return $statement->fetchAll();
    }

    public static function create(
        string $name,
        string $description,
        float $price,
        string $level,
        string $format,
        string $language,
        ?string $image = null
    ): int {
        $database = Database::connection();

        $statement = $database->prepare(
            'INSERT INTO courses (
                name,
                description,
                price,
                level,
                format,
                language,
                image
            )
            VALUES (
                :name,
                :description,
                :price,
                :level,
                :format,
                :language,
                :image
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
            'image' => $image,
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
        string $language,
        ?string $image = null
    ): void {
        $database = Database::connection();

        $statement = $database->prepare(
            'UPDATE courses
             SET name = :name,
                 description = :description,
                 price = :price,
                 level = :level,
                 format = :format,
                 language = :language,
                 image = :image
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
            'image' => $image,
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

    public static function distinctLanguages(): array {
        $database = Database::connection();

        $statement = $database->query(
            'SELECT DISTINCT language
         FROM courses
         WHERE language <> \'\'
         ORDER BY language ASC'
        );

        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }

    public static function distinctLevels(): array {
        $database = Database::connection();

        $statement = $database->query(
            'SELECT DISTINCT level
         FROM courses
         WHERE level <> \'\'
         ORDER BY level ASC'
        );

        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }

    public static function distinctFormats(): array {
        $database = Database::connection();

        $statement = $database->query(
            'SELECT DISTINCT format
         FROM courses
         WHERE format <> \'\'
         ORDER BY format ASC'
        );

        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }
}