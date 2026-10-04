<?php

use App\Core\Auth;

/** Экранирование вывода (защита от XSS). */
function e(mixed $value): string {
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Цена: «15 000 ₽» (копейки показываются, только если они есть). */
function price(mixed $value): string {
    $number = (float) $value;
    $decimals = fmod($number, 1.0) === 0.0 ? 0 : 2;

    return number_format($number, $decimals, ',', "\u{00A0}") . "\u{00A0}₽";
}

/** Дата в российском формате. */
function format_date(?string $value, bool $withTime = true): string {
    if ($value === null || $value === '') {
        return '—';
    }

    $timestamp = strtotime($value);

    if ($timestamp === false) {
        return e($value);
    }

    return date($withTime ? 'd.m.Y, H:i' : 'd.m.Y', $timestamp);
}

function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . e(Auth::csrfToken()) . '">';
}

/** SVG-иконка из спрайта (см. views/partials/icons.php). */
function icon(string $name, string $class = ''): string {
    return '<svg class="icon ' . e($class) . '" aria-hidden="true"><use href="#i-' . e($name) . '"/></svg>';
}

/** Подключение частичного представления. */
function partial(string $name, array $vars = []): void {
    extract($vars);

    require __DIR__ . '/../views/partials/' . $name . '.php';
}

/** Обложка курса: загруженное изображение или SVG-обложка по языку. */
function course_image(array $course): string {
    if (!empty($course['image'])) {
        return (string) $course['image'];
    }

    $language = mb_strtolower((string) ($course['language'] ?? ''));

    $covers = [
        'англ' => 'english',
        'нем' => 'german',
        'испан' => 'spanish',
        'франц' => 'french',
    ];

    foreach ($covers as $needle => $file) {
        if (str_contains($language, $needle)) {
            return '/images/courses/' . $file . '.svg';
        }
    }

    return '/images/courses/default.svg';
}

/** Цветной бейдж статуса заявки. */
function status_badge(string $status): string {
    $classes = [
        'Новая' => 'new',
        'В обработке' => 'progress',
        'Подтверждена' => 'confirmed',
        'Отклонена' => 'rejected',
        'Завершена' => 'done',
    ];

    $class = $classes[$status] ?? 'new';

    return '<span class="status-badge status-' . $class . '">' . e($status) . '</span>';
}

/** Склонение по числу: plural(2, 'курс', 'курса', 'курсов') → «курса». */
function plural(int $number, string $one, string $few, string $many): string {
    $mod100 = $number % 100;
    $mod10 = $number % 10;

    if ($mod100 >= 11 && $mod100 <= 19) {
        return $many;
    }

    return match (true) {
        $mod10 === 1 => $one,
        $mod10 >= 2 && $mod10 <= 4 => $few,
        default => $many,
    };
}
