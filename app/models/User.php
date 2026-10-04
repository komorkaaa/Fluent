<?php

namespace App\Models;

use App\Core\Database;

class User {
    private const SELECT = '
        SELECT
            users.id,
            users.name,
            users.email,
            users.password,
            users.role_id,
            users.is_blocked,
            users.created_at,
            roles.name AS role
        FROM users
        INNER JOIN roles ON roles.id = users.role_id
    ';

    public static function find(int $id): ?array {
        $statement = Database::connection()->prepare(
            self::SELECT . ' WHERE users.id = :id'
        );
        $statement->execute(['id' => $id]);

        return $statement->fetch() ?: null;
    }

    /** Поиск по e-mail без учёта регистра. */
    public static function findByEmail(string $email): ?array {
        $statement = Database::connection()->prepare(
            self::SELECT . ' WHERE LOWER(users.email) = LOWER(:email)'
        );
        $statement->execute(['email' => $email]);

        return $statement->fetch() ?: null;
    }

    public static function create(
        string $name,
        string $email,
        string $password
    ): int {
        $statement = Database::connection()->prepare(
            'INSERT INTO users (role_id, name, email, password)
             VALUES (
                (SELECT id FROM roles WHERE name = :role),
                :name,
                :email,
                :password
             )
             RETURNING id'
        );

        $statement->execute([
            'role' => 'user',
            'name' => $name,
            'email' => mb_strtolower($email),
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ]);

        return (int) $statement->fetchColumn();
    }

    public static function update(int $id, string $name, string $email): void {
        $statement = Database::connection()->prepare(
            'UPDATE users SET name = :name, email = :email WHERE id = :id'
        );

        $statement->execute([
            'id' => $id,
            'name' => $name,
            'email' => mb_strtolower($email),
        ]);
    }

    public static function all(): array {
        return Database::connection()->query(
            'SELECT
                users.id,
                users.name,
                users.email,
                users.created_at,
                users.is_blocked,
                roles.name AS role,
                (SELECT COUNT(*) FROM applications
                 WHERE applications.user_id = users.id) AS applications_count
             FROM users
             INNER JOIN roles ON roles.id = users.role_id
             ORDER BY users.created_at DESC, users.id DESC'
        )->fetchAll();
    }

    public static function count(): int {
        return (int) Database::connection()
            ->query('SELECT COUNT(*) FROM users')
            ->fetchColumn();
    }

    public static function updateRole(int $id, string $role): void {
        $statement = Database::connection()->prepare(
            'UPDATE users
             SET role_id = (SELECT id FROM roles WHERE name = :role)
             WHERE id = :id'
        );

        $statement->execute(['id' => $id, 'role' => $role]);
    }

    public static function setBlocked(int $id, bool $blocked): void {
        $statement = Database::connection()->prepare(
            'UPDATE users SET is_blocked = :is_blocked WHERE id = :id'
        );

        $statement->bindValue(':is_blocked', $blocked, \PDO::PARAM_BOOL);
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }

    public static function delete(int $id): void {
        $statement = Database::connection()->prepare(
            'DELETE FROM users WHERE id = :id'
        );

        $statement->execute(['id' => $id]);
    }
}
