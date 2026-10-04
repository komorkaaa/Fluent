<?php
/** @var array $account */
/** @var array $applications */
/** @var array $roles */
/** @var array|null $currentUser */

$isSelf = (int) $account['id'] === (int) ($currentUser['id'] ?? 0);
$isBlocked = (bool) $account['is_blocked'];
?>

<section class="page-head pb-4">
    <div class="container">
        <a href="/admin/users" class="breadcrumb-link"><?= icon('arrow-left') ?> Все пользователи</a>

        <div class="d-flex flex-wrap align-items-center gap-3">
            <div class="avatar" aria-hidden="true"><?= e(mb_strtoupper(mb_substr($account['name'], 0, 1))) ?></div>
            <div>
                <h1 class="mb-1"><?= e($account['name']) ?></h1>
                <p><?= e($account['email']) ?></p>
            </div>
        </div>
    </div>
</section>

<section class="section pt-4">
    <div class="container">
        <div class="row g-4 g-xl-5">

            <div class="col-lg-4">
                <div class="account-card mb-4">
                    <h2 class="h5 mb-3">Информация</h2>

                    <dl class="mb-0 d-grid gap-3">
                        <div class="detail-grid">
                            <div>
                                <dt>Регистрация</dt>
                                <dd><?= e(format_date($account['created_at'])) ?></dd>
                            </div>

                            <div>
                                <dt>Статус</dt>
                                <dd>
                                    <?= $isBlocked
                                        ? '<span class="status-badge status-rejected">Заблокирован</span>'
                                        : '<span class="status-badge status-confirmed">Активен</span>' ?>
                                </dd>
                            </div>

                            <div>
                                <dt>Заявок</dt>
                                <dd><?= count($applications) ?></dd>
                            </div>
                        </div>
                    </dl>
                </div>

                <?php if ($isSelf): ?>

                    <div class="alert alert-info">
                        Это ваша учётная запись: менять роль, блокировать и удалять её нельзя.
                    </div>

                <?php else: ?>

                    <div class="account-card mb-4">
                        <h2 class="h5 mb-3">Роль</h2>

                        <form method="POST" action="/admin/users/<?= (int) $account['id'] ?>/role">
                            <?= csrf_field() ?>

                            <label for="role" class="visually-hidden">Роль</label>
                            <select id="role" name="role" class="form-select mb-3">
                                <?php foreach ($roles as $value => $label): ?>
                                    <option value="<?= e($value) ?>" <?= $account['role'] === $value ? 'selected' : '' ?>>
                                        <?= e($label) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <button type="submit" class="btn btn-primary w-100">Сохранить роль</button>
                        </form>
                    </div>

                    <div class="d-grid gap-2">
                        <form
                            method="POST"
                            action="/admin/users/<?= (int) $account['id'] ?>/<?= $isBlocked ? 'unblock' : 'block' ?>"
                        >
                            <?= csrf_field() ?>
                            <input type="hidden" name="back" value="show">
                            <button type="submit" class="btn btn-outline-secondary w-100">
                                <?= $isBlocked ? 'Разблокировать' : 'Заблокировать' ?>
                            </button>
                        </form>

                        <form
                            method="POST"
                            action="/admin/users/<?= (int) $account['id'] ?>/delete"
                            data-confirm="Удалить пользователя «<?= e($account['name']) ?>»? Его заявки сохранятся как гостевые."
                        >
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-outline-secondary text-danger w-100">
                                <?= icon('trash') ?> Удалить пользователя
                            </button>
                        </form>
                    </div>

                <?php endif; ?>
            </div>

            <div class="col-lg-8">
                <h2 class="h4 mb-3">Заявки пользователя</h2>

                <?php if ($applications === []): ?>

                    <div class="empty-state">
                        <p class="text-body-secondary mb-0">Этот пользователь пока не подавал заявок.</p>
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
                                            <a href="/admin/applications/<?= (int) $application['id'] ?>" class="btn btn-outline-primary btn-sm">
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

        </div>
    </div>
</section>
