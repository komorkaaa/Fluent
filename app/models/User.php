<?php

namespace App\Models;

use App\Core\Database;

class User {
    public static function findByEmail(string $email): ?array {
        $database = Database::connection();

        $statement = $database->prepare(
            'SELECT
                users.id,
                users.name,
                users.email,
                users.password,
                users.role_id,
                users.is_blocked,
                roles.name AS role
             FROM users
             INNER JOIN roles ON roles.id = users.role_id
             WHERE users.email = :email'
        );

        $statement->execute([
            'email' => $email,
        ]);

        $user = $statement->fetch();

        return $user ?: null;
    }

    public static function create(
        string $name,
        string $email,
        string $password
    ): int {
        $database = Database::connection();

        $statement = $database->prepare(
            'INSERT INTO users (
                role_id,
                name,
                email,
                password
            )
            VALUES (
                (
                    SELECT id
                    FROM roles
                    WHERE name = :role
                ),
                :name,
                :email,
                :password
            )
            RETURNING id'
        );

        $statement->execute([
            'role' => 'user',
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ]);

        return (int) $statement->fetchColumn();
    }

    public static function update(
        int $id,
        string $name,
        string $email
    ): void {
        $database = Database::connection();

        $statement = $database->prepare(
            'UPDATE users
             SET name = :name,
                 email = :email
             WHERE id = :id'
        );

        $statement->execute([
            'id' => $id,
            'name' => $name,
            'email' => $email,
        ]);
    }

    public static function all(): array {
        $database = Database::connection();

        $statement = $database->query(
            'SELECT
                users.id,
                users.name,
                users.email,
                users.created_at,
                users.is_blocked,
                roles.name AS role
             FROM users
             INNER JOIN roles
                ON roles.id = users.role_id
             ORDER BY users.created_at DESC'
        );

        return $statement->fetchAll();
    }

    public static function updateRole(
        int $id,
        string $role
    ): void {
        $database = Database::connection();

        $statement = $database->prepare(
            'UPDATE users
             SET role_id = (
                 SELECT id
                 FROM roles
                 WHERE name = :role
             )
             WHERE id = :id'
        );

        $statement->execute([
            'id' => $id,
            'role' => $role,
        ]);
    }

    public static function setBlocked(
        int $id,
        bool $blocked
    ): void {
        $database = Database::connection();

        $statement = $database->prepare(
            'UPDATE users
         SET is_blocked = :is_blocked
         WHERE id = :id'
        );

        $statement->bindValue(
            ':is_blocked',
            $blocked,
            \PDO::PARAM_BOOL
        );

        $statement->bindValue(
            ':id',
            $id,
            \PDO::PARAM_INT
        );

        $statement->execute();
    }

    public static function delete(int $id): void {
        $database = Database::connection();

        $statement = $database->prepare(
            'DELETE FROM users
             WHERE id = :id'
        );

        $statement->execute([
            'id' => $id,
        ]);
    }
}