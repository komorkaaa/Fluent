<?php

namespace App\Models;

use App\Core\Database;

class Course {
    /** Варианты сортировки: ключ => подпись. */
    public const SORTS = [
        'date_desc' => 'Сначала новые',
        'date_asc' => 'Сначала старые',
        'price_asc' => 'Цена: по возрастанию',
        'price_desc' => 'Цена: по убыванию',
        'name_asc' => 'По названию',
    ];

    private const ORDER = [
        'date_desc' => 'courses.created_at DESC, courses.id DESC',
        'date_asc' => 'courses.created_at ASC, courses.id ASC',
        'price_asc' => 'courses.price ASC, courses.id ASC',
        'price_desc' => 'courses.price DESC, courses.id ASC',
        'name_asc' => 'courses.name ASC, courses.id ASC',
    ];

    private const SELECT = '
        SELECT
            courses.id,
            courses.name,
            courses.description,
            courses.price,
            courses.image,
            courses.created_at,
            courses.language_id,
            courses.level_id,
            courses.format_id,
            languages.name AS language,
            levels.name AS level,
            formats.name AS format
        FROM courses
        INNER JOIN languages ON languages.id = courses.language_id
        INNER JOIN levels ON levels.id = courses.level_id
        INNER JOIN formats ON formats.id = courses.format_id
    ';

    /**
     * Каталог. Фильтры: q, language_id, level_id, format_id, price_min, price_max.
     * Фильтрация и сортировка выполняются в SQL и работают совместно.
     */
    public static function all(array $filters = [], string $sort = 'date_desc'): array {
        $conditions = [];
        $parameters = [];

        if (!empty($filters['q'])) {
            $conditions[] = '(courses.name ILIKE :q OR courses.description ILIKE :q)';
            $parameters['q'] = '%' . addcslashes($filters['q'], '%_\\') . '%';
        }

        foreach (['language_id', 'level_id', 'format_id'] as $field) {
            if (!empty($filters[$field])) {
                $conditions[] = "courses.{$field} = :{$field}";
                $parameters[$field] = (int) $filters[$field];
            }
        }

        if (isset($filters['price_min']) && $filters['price_min'] !== null) {
            $conditions[] = 'courses.price >= :price_min';
            $parameters['price_min'] = $filters['price_min'];
        }

        if (isset($filters['price_max']) && $filters['price_max'] !== null) {
            $conditions[] = 'courses.price <= :price_max';
            $parameters['price_max'] = $filters['price_max'];
        }

        $sql = self::SELECT;

        if ($conditions !== []) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $sql .= ' ORDER BY ' . (self::ORDER[$sort] ?? self::ORDER['date_desc']);

        $statement = Database::connection()->prepare($sql);
        $statement->execute($parameters);

        return $statement->fetchAll();
    }

    public static function latest(int $limit = 3): array {
        $statement = Database::connection()->prepare(
            self::SELECT . ' ORDER BY courses.created_at DESC, courses.id DESC LIMIT :limit'
        );
        $statement->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public static function find(int $id): ?array {
        $statement = Database::connection()->prepare(
            self::SELECT . ' WHERE courses.id = :id'
        );
        $statement->execute(['id' => $id]);

        return $statement->fetch() ?: null;
    }

    /** Похожие курсы: сначала тот же язык, затем тот же уровень. */
    public static function similar(int $id, int $languageId, int $levelId, int $limit = 3): array {
        $statement = Database::connection()->prepare(
            self::SELECT . '
             WHERE courses.id <> :id
             ORDER BY (courses.language_id = :language_id) DESC,
                      (courses.level_id = :level_id) DESC,
                      courses.created_at DESC
             LIMIT :limit'
        );

        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->bindValue(':language_id', $languageId, \PDO::PARAM_INT);
        $statement->bindValue(':level_id', $levelId, \PDO::PARAM_INT);
        $statement->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public static function count(): int {
        return (int) Database::connection()
            ->query('SELECT COUNT(*) FROM courses')
            ->fetchColumn();
    }

    public static function create(array $data): int {
        $statement = Database::connection()->prepare(
            'INSERT INTO courses (
                language_id, level_id, format_id, name, description, price, image
            ) VALUES (
                :language_id, :level_id, :format_id, :name, :description, :price, :image
            ) RETURNING id'
        );

        $statement->execute(self::params($data));

        return (int) $statement->fetchColumn();
    }

    public static function update(int $id, array $data): void {
        $statement = Database::connection()->prepare(
            'UPDATE courses
             SET language_id = :language_id,
                 level_id = :level_id,
                 format_id = :format_id,
                 name = :name,
                 description = :description,
                 price = :price,
                 image = :image
             WHERE id = :id'
        );

        $statement->execute(self::params($data) + ['id' => $id]);
    }

    public static function delete(int $id): void {
        $statement = Database::connection()->prepare(
            'DELETE FROM courses WHERE id = :id'
        );

        $statement->execute(['id' => $id]);
    }

    private static function params(array $data): array {
        return [
            'language_id' => $data['language_id'],
            'level_id' => $data['level_id'],
            'format_id' => $data['format_id'],
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => $data['price'],
            'image' => $data['image'] ?? null,
        ];
    }
}
