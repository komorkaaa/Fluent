<?php
/** @var array $applications */
/** @var array $statuses */
/** @var array $users */
/** @var array $filters */

$hasFilters = $filters['user_id'] !== null
    || $filters['status_id'] !== null
    || $filters['date_from'] !== ''
    || $filters['date_to'] !== '';
?>

<section class="page-head pb-4">
    <div class="container">
        <h1>Заявки</h1>
        <p>Все заявки на курсы: фильтры по пользователю, дате и статусу.</p>
    </div>
</section>

<section class="section pt-4">
    <div class="container">
        <?php partial('admin-nav'); ?>

        <form method="GET" action="/admin/applications" class="filter-panel mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-6 col-xl-3">
                    <label for="user_id" class="form-label">Пользователь</label>
                    <select id="user_id" name="user_id" class="form-select">
                        <option value="">Все пользователи</option>
                        <?php foreach ($users as $item): ?>
                            <option
                                value="<?= (int) $item['id'] ?>"
                                <?= (int) $filters['user_id'] === (int) $item['id'] ? 'selected' : '' ?>
                            >
                                <?= e($item['name']) ?> — <?= e($item['email']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6 col-xl-3">
                    <label for="status_id" class="form-label">Статус</label>
                    <select id="status_id" name="status_id" class="form-select">
                        <option value="">Все статусы</option>
                        <?php foreach ($statuses as $item): ?>
                            <option
                                value="<?= (int) $item['id'] ?>"
                                <?= (int) $filters['status_id'] === (int) $item['id'] ? 'selected' : '' ?>
                            >
                                <?= e($item['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-6 col-xl-2">
                    <label for="date_from" class="form-label">Дата от</label>
                    <input type="date" id="date_from" name="date_from" class="form-control" value="<?= e($filters['date_from']) ?>">
                </div>

                <div class="col-6 col-xl-2">
                    <label for="date_to" class="form-label">Дата до</label>
                    <input type="date" id="date_to" name="date_to" class="form-control" value="<?= e($filters['date_to']) ?>">
                </div>

                <div class="col-12 col-xl-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">Применить</button>
                    <?php if ($hasFilters): ?>
                        <a href="/admin/applications" class="btn btn-outline-secondary">Сбросить</a>
                    <?php endif; ?>
                </div>
            </div>
        </form>

        <p class="text-body-secondary fw-semibold mb-3">
            Найдено: <?= count($applications) ?> <?= plural(count($applications), 'заявка', 'заявки', 'заявок') ?>
        </p>

        <?php if ($applications === []): ?>

            <div class="empty-state">
                <h2 class="h4">Заявок не найдено</h2>
                <p class="text-body-secondary mb-0">Измените фильтры или сбросьте их.</p>
            </div>

        <?php else: ?>

            <div class="table-wrap">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                        <tr>
                            <th scope="col">№</th>
                            <th scope="col">Дата</th>
                            <th scope="col">Курс</th>
                            <th scope="col">Заявитель</th>
                            <th scope="col">Телефон</th>
                            <th scope="col">Статус</th>
                            <th scope="col"><span class="visually-hidden">Действия</span></th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php foreach ($applications as $application): ?>
                            <tr>
                                <td class="fw-bold">#<?= (int) $application['id'] ?></td>
                                <td class="text-nowrap"><?= e(format_date($application['created_at'])) ?></td>
                                <td><?= e($application['course_name']) ?></td>
                                <td>
                                    <div class="fw-semibold"><?= e($application['name']) ?></div>
                                    <div class="small text-body-secondary">
                                        <?= $application['user_name'] !== null
                                            ? 'Аккаунт: ' . e($application['user_name'])
                                            : 'Гость' ?>
                                    </div>
                                </td>
                                <td class="text-nowrap"><?= e($application['phone']) ?></td>
                                <td><?= status_badge($application['status']) ?></td>
                                <td class="text-end">
                                    <a
                                        href="/admin/applications/<?= (int) $application['id'] ?>"
                                        class="btn btn-outline-primary btn-sm"
                                    >
                                        Открыть
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
</section>
