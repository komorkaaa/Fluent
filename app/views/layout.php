<?php

use App\Core\Auth;

Auth::start();

$user = Auth::user();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($title ?? 'Fluent') ?></title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="/css/style.css"
    >
</head>

<body>

<header>
    <nav class="navbar navbar-expand-lg bg-body-tertiary border-bottom">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">Fluent</a>

            <button
                    class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarNav"
                    aria-controls="navbarNav"
                    aria-expanded="false"
                    aria-label="Переключить навигацию"
            >
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Главная</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/courses">Курсы</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/contacts">Контакты</a>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    <?php if ($user === null): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/login">Войти</a>
                        </li>

                        <li class="nav-item">
                            <a class="btn btn-primary ms-lg-2" href="/register">
                                Регистрация
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/profile">
                                <?= htmlspecialchars($user['name']) ?>
                            </a>
                        </li>

                        <li class="nav-item">
                            <form method="POST" action="/logout" class="d-inline">
                                <button
                                        type="submit"
                                        class="btn btn-outline-danger ms-lg-2"
                                >
                                    Выйти
                                </button>
                            </form>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>

<main>
    <?= $content ?>
</main>

<footer class="border-top mt-5 py-4">
    <div class="container text-center text-body-secondary">
        © <?= date('Y') ?> Fluent
    </div>
</footer>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js">
</script>

</body>
</html>