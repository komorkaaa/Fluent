<?php
/** @var array $stats */
/** @var array $statusStats */
?>

<section class="page-head pb-4">
    <div class="container">
        <h1>Панель администратора</h1>
        <p>Управление курсами, заявками и пользователями Fluent.</p>
    </div>
</section>

<section class="section pt-4">
    <div class="container">
        <?php partial('admin-nav'); ?>

        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <a href="/admin/courses" class="stat-card feature-blue d-block text-decoration-none">
                    <div class="stat-value"><?= (int) $stats['courses'] ?></div>
                    <div class="stat-label">
                        <?= plural((int) $stats['courses'], 'курс', 'курса', 'курсов') ?> в каталоге
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="/admin/applications" class="stat-card feature-peach d-block text-decoration-none">
                    <div class="stat-value"><?= (int) $stats['applications'] ?></div>
                    <div class="stat-label">
                        <?= plural((int) $stats['applications'], 'заявка', 'заявки', 'заявок') ?> всего
                    </div>
                </a>
            </div>

            <div class="col-md-4">
                <a href="/admin/users" class="stat-card feature-mint d-block text-decoration-none">
                    <div class="stat-value"><?= (int) $stats['users'] ?></div>
                    <div class="stat-label">
                        <?= plural((int) $stats['users'], 'пользователь', 'пользователя', 'пользователей') ?>
                    </div>
                </a>
            </div>
        </div>

        <h2 class="h4 mb-3">Заявки по статусам</h2>

        <div class="d-flex flex-wrap gap-3">
            <?php foreach ($statusStats as $row): ?>
                <a
                    href="/admin/applications?status_id=<?= (int) $row['id'] ?>"
                    class="account-card py-3 px-4 text-decoration-none d-flex align-items-center gap-3"
                >
                    <?= status_badge($row['name']) ?>
                    <span class="fs-4 fw-bold text-body"><?= (int) $row['total'] ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
