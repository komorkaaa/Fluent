<?php

namespace App\Core;

use App\Models\User;

class Auth {
    private static bool $refreshed = false;

    public static function start(): void {
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.use_strict_mode', '1');

            $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
                || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https';

            session_set_cookie_params([
                'path' => '/',
                'httponly' => true,
                'secure' => $https,
                'samesite' => 'Lax',
            ]);

            session_start();
        }

        self::refresh();
    }

    /**
     * Сверяет сессию с базой: если пользователя заблокировали или удалили,
     * он выходит сразу, а смена роли применяется без повторного входа.
     */
    private static function refresh(): void {
        if (self::$refreshed || !isset($_SESSION['user'])) {
            return;
        }

        self::$refreshed = true;

        $fresh = User::find((int) $_SESSION['user']['id']);

        if ($fresh === null || (bool) $fresh['is_blocked']) {
            unset($_SESSION['user']);

            self::flash(
                'danger',
                $fresh === null
                    ? 'Учётная запись больше не существует.'
                    : 'Ваша учётная запись заблокирована.'
            );

            return;
        }

        $_SESSION['user'] = self::sessionData($fresh);
    }

    private static function sessionData(array $user): array {
        return [
            'id' => (int) $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role_id' => (int) $user['role_id'],
            'role' => $user['role'],
        ];
    }

    public static function login(array $user): void {
        self::start();

        session_regenerate_id(true);

        $_SESSION['user'] = self::sessionData($user);
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    public static function logout(): void {
        self::start();

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
    }

    public static function check(): bool {
        self::start();

        return isset($_SESSION['user']);
    }

    public static function user(): ?array {
        self::start();

        return $_SESSION['user'] ?? null;
    }

    public static function isAdmin(): bool {
        self::start();

        return isset($_SESSION['user'])
            && $_SESSION['user']['role'] === 'admin';
    }

    /** Одноразовое сообщение для следующей страницы. */
    public static function flash(string $type, string $message): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
    }

    public static function pullFlash(): array {
        self::start();

        $messages = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);

        return $messages;
    }

    public static function csrfToken(): string {
        self::start();

        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    public static function verifyCsrfToken(?string $token): bool {
        self::start();

        if ($token === null || !isset($_SESSION['csrf_token'])) {
            return false;
        }

        return hash_equals($_SESSION['csrf_token'], $token);
    }
}
