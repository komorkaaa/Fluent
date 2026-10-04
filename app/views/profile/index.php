<?php
/** @var array $user */
/** @var array $applications */
/** @var array $errors */
/** @var array $old */

$initial = mb_strtoupper(mb_substr($user['name'], 0, 1));
?>

<section class="page-head">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar" aria-hidden="true"><?= e($initial) ?></div>
                <div>
                    <h1 class="mb-1">Личный кабинет</h1>
                    <p><?= e($user['name']) ?> · <?= e($user['email']) ?></p>
                </div>
            </div>

            <form method="POST" action="/logout">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-outline-secondary">
                    <?= icon('logout') ?> Выйти из аккаунта
                </button>
            </form>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="row g-4 g-xl-5">

            <div class="col-lg-4">
                <div class="account-card">
                    <h2 class="h4 mb-4">Мои данные</h2>

                    <?php partial('errors', ['errors' => $errors ?? []]); ?>

                    <form method="POST" action="/profile" novalidate>
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="name" class="form-label">Имя</label>
                            <input
                                type="text"
                                class="form-control"
                                id="name"
                                name="name"
                                maxlength="255"
                                value="<?= e($old['name'] ?? $user['name']) ?>"
                                required
                            >
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label">E-mail</label>
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                maxlength="255"
                                value="<?= e($old['email'] ?? $user['email']) ?>"
                                required
                            >
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Сохранить изменения
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-8">
                <h2 class="h3 mb-4">Мои заявки</h2>

                <?php if ($applications === []): ?>

                    <div class="empty-state">
                        <h3 class="h4">Заявок пока нет</h3>
                        <p class="text-body-secondary mb-4">
                            Выберите курс в каталоге и оставьте первую заявку.
                        </p>
                        <a href="/courses" class="btn btn-primary">Перейти в каталог</a>
                    </div>

                <?php else: ?>

                    <div class="table-wrap">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                <tr>
                                    <th scope="col">№</th>
                                    <th scope="col">Курс</th>
                                    <th scope="col">Дата</th>
                                    <th scope="col">Статус</th>
                                    <th scope="col"><span class="visually-hidden">Действия</span></th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php foreach ($applications as $application): ?>
                                    <tr>
                                        <td class="fw-bold">#<?= (int) $application['id'] ?></td>
                                        <td><?= e($application['course_name']) ?></td>
                                        <td class="text-nowrap"><?= e(format_date($application['created_at'])) ?></td>
                                        <td><?= status_badge($application['status']) ?></td>
                                        <td class="text-end">
                                            <a
                                                href="/profile/applications/<?= (int) $application['id'] ?>"
                                                class="btn btn-outline-primary btn-sm"
                                            >
                                                Подробнее
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                <?php endif; ?>
            </div>

        </div>
    </div>
</section>
