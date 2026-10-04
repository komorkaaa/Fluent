<?php

use App\Core\Auth;

$user = Auth::user();
$flashMessages = Auth::pullFlash();

$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

$isActive = static function (string $href) use ($currentPath): bool {
    return $href === '/'
        ? $currentPath === '/'
        : str_starts_with($currentPath, $href);
};

$navItems = [
    ['/', 'Главная'],
    ['/courses', 'Курсы'],
    ['/about', 'О компании'],
    ['/contacts', 'Контакты'],
    ['/profile', 'Личный кабинет'],
];

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Fluent — школа иностранных языков: английский, немецкий, испанский и французский онлайн и очно.">

    <title><?= e($title ?? 'Fluent') ?></title>

    <link rel="icon" href="/favicon.svg" type="image/svg+xml">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@500;600;700;800&display=swap&subset=cyrillic"
        rel="stylesheet"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="/css/style.css">
</head>

<body class="d-flex flex-column min-vh-100">

<?php partial('icons'); ?>

<header class="site-header">
    <nav class="navbar navbar-expand-xl" aria-label="Главное меню">
        <div class="container">
            <a class="brand" href="/" aria-label="Fluent — на главную">
                <img src="/images/logo.svg" class="brand-mark" alt="" width="40" height="40">
                Fluent
            </a>

            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav"
                aria-controls="navbarNav"
                aria-expanded="false"
                aria-label="Переключить навигацию"
            >
                <?= icon('menu') ?>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-xl-auto gap-xl-1">
                    <?php foreach ($navItems as [$href, $label]): ?>
                        <li class="nav-item">
                            <a
                                class="nav-link <?= $isActive($href) ? 'active' : '' ?>"
                                href="<?= $href ?>"
                                <?= $isActive($href) ? 'aria-current="page"' : '' ?>
                            >
                                <?= e($label) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>

                    <?php if (($user['role'] ?? null) === 'admin'): ?>
                        <li class="nav-item">
                            <a
                                class="nav-link <?= $isActive('/admin') ? 'active' : '' ?>"
                                href="/admin"
                            >
                                Админ-панель
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>

                <div class="nav-actions d-flex flex-wrap align-items-center gap-2">
                    <?php if ($user === null): ?>

                        <a class="btn btn-outline-secondary" href="/login">Войти</a>
                        <a class="btn btn-primary" href="/courses">Записаться</a>

                    <?php else: ?>

                        <form method="POST" action="/logout" class="d-inline">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-outline-secondary">
                                <?= icon('logout') ?> Выйти
                            </button>
                        </form>

                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
</header>

<main class="flex-grow-1">
    <?php if ($flashMessages !== []): ?>
        <div class="container pt-4">
            <?php foreach ($flashMessages as $flash): ?>
                <div
                    class="alert alert-<?= $flash['type'] === 'danger' ? 'danger' : 'success' ?> mb-3"
                    role="<?= $flash['type'] === 'danger' ? 'alert' : 'status' ?>"
                >
                    <?= e($flash['message']) ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?= $content ?>
</main>

<footer class="site-footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-5">
                <a href="/" class="brand mb-3">
                    <img src="/images/logo.svg" class="brand-mark" alt="" width="40" height="40">
                    Fluent
                </a>
                <p class="mb-0" style="max-width: 24rem;">
                    Школа иностранных языков. Английский, немецкий, испанский
                    и французский — онлайн и очно, с преподавателем.
                </p>
            </div>

            <div class="col-6 col-lg-3">
                <h2>Разделы</h2>
                <ul>
                    <li><a href="/courses">Каталог курсов</a></li>
                    <li><a href="/about">О компании</a></li>
                    <li><a href="/contacts">Контакты</a></li>
                    <li><a href="/profile">Личный кабинет</a></li>
                </ul>
            </div>

            <div class="col-6 col-lg-4">
                <h2>Связаться с нами</h2>
                <ul>
                    <li><a href="tel:+78310000000">+7 (831) 000-00-00</a></li>
                    <li><a href="mailto:info@fluent.local">info@fluent.local</a></li>
                    <li>г. Нижний Новгород,<br>ул. Большая Покровская, 1</li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom d-flex flex-wrap justify-content-between gap-2">
            <span>© <?= date('Y') ?> Fluent. Все права защищены.</span>
            <a href="/privacy">Политика конфиденциальности</a>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="/js/app.js" defer></script>

</body>
</html>
