<?php
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

$items = [
    ['/admin', 'Обзор', 'grid', true],
    ['/admin/courses', 'Курсы', 'book', false],
    ['/admin/applications', 'Заявки', 'list', false],
    ['/admin/users', 'Пользователи', 'users', false],
];
?>
<nav class="admin-tabs" aria-label="Разделы админ-панели">
    <?php foreach ($items as [$href, $label, $iconName, $exact]): ?>
        <?php $active = $exact ? $path === $href : str_starts_with($path, $href); ?>
        <a href="<?= $href ?>" class="<?= $active ? 'active' : '' ?>" <?= $active ? 'aria-current="page"' : '' ?>>
            <?= icon($iconName) ?> <?= e($label) ?>
        </a>
    <?php endforeach; ?>
</nav>
