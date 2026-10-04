<?php

namespace App\Models;

use App\Core\Database;

/** Справочники каталога: языки, уровни, форматы. */
class Lookup {
    public static function languages(): array {
        return Database::connection()
            ->query('SELECT id, name FROM languages ORDER BY name ASC')
            ->fetchAll();
    }

    public static function levels(): array {
        return Database::connection()
            ->query('SELECT id, name FROM levels ORDER BY sort_order ASC, name ASC')
            ->fetchAll();
    }

    public static function formats(): array {
        return Database::connection()
            ->query('SELECT id, name FROM formats ORDER BY name ASC')
            ->fetchAll();
    }

    /** Языки с количеством курсов (для главной). */
    public static function languageStats(): array {
        return Database::connection()->query(
            'SELECT languages.id, languages.name, COUNT(courses.id) AS courses_count
             FROM languages
             INNER JOIN courses ON courses.language_id = languages.id
             GROUP BY languages.id, languages.name
             ORDER BY courses_count DESC, languages.name ASC'
        )->fetchAll();
    }

    public static function exists(string $table, int $id): bool {
        $allowed = ['languages', 'levels', 'formats'];

        if (!in_array($table, $allowed, true)) {
            throw new \InvalidArgumentException('Unknown lookup table');
        }

        $statement = Database::connection()->prepare(
            "SELECT 1 FROM {$table} WHERE id = :id"
        );
        $statement->execute(['id' => $id]);

        return (bool) $statement->fetchColumn();
    }

    /** Возвращает id языка по названию, создавая запись при необходимости. */
    public static function languageIdByName(string $name): int {
        $database = Database::connection();

        $statement = $database->prepare(
            'SELECT id FROM languages WHERE LOWER(name) = LOWER(:name)'
        );
        $statement->execute(['name' => $name]);
        $id = $statement->fetchColumn();

        if ($id !== false) {
            return (int) $id;
        }

        $statement = $database->prepare(
            'INSERT INTO languages (name) VALUES (:name) RETURNING id'
        );
        $statement->execute(['name' => $name]);

        return (int) $statement->fetchColumn();
    }
}
