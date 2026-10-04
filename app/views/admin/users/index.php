<?php
/** @var array $users */
/** @var array|null $currentUser */
?>

<section class="page-head pb-4">
    <div class="container">
        <h1>Пользователи</h1>
        <p>Зарегистрированные пользователи: блокировка, разблокировка и удаление.</p>
    </div>
</section>

<section class="section pt-4">
    <div class="container">
        <?php partial('admin-nav'); ?>

        <div class="table-wrap">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Пользователь</th>
                        <th scope="col">Роль</th>
                        <th scope="col">Статус</th>
                        <th scope="col">Заявок</th>
                        <th scope="col">Регистрация</th>
                        <th scope="col"><span class="visually-hidden">Действия</span></th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($users as $account): ?>
                        <?php
                        $isSelf = (int) $account['id'] === (int) ($currentUser['id'] ?? 0);
                        $isBlocked = (bool) $account['is_blocked'];
                        ?>
                        <tr>
                            <td class="text-body-secondary"><?= (int) $account['id'] ?></td>

                            <td>
                                <a href="/admin/users/<?= (int) $account['id'] ?>" class="fw-bold text-decoration-none">
                                    <?= e($account['name']) ?>
                                </a>
                                <?php if ($isSelf): ?><span class="chip ms-1">это вы</span><?php endif; ?>
                                <div class="small text-body-secondary text-break"><?= e($account['email']) ?></div>
                            </td>

                            <td>
                                <?= $account['role'] === 'admin'
                                    ? '<span class="chip chip-yellow">Администратор</span>'
                                    : '<span class="chip">Пользователь</span>' ?>
                            </td>

                            <td>
                                <?= $isBlocked
                                    ? '<span class="status-badge status-rejected">Заблокирован</span>'
                                    : '<span class="status-badge status-confirmed">Активен</span>' ?>
                            </td>

                            <td><?= (int) $account['applications_count'] ?></td>
                            <td class="text-nowrap"><?= e(format_date($account['created_at'], false)) ?></td>

                            <td>
                                <div class="d-flex justify-content-end flex-wrap gap-2">
                                    <a
                                        href="/admin/users/<?= (int) $account['id'] ?>"
                                        class="btn btn-outline-primary btn-sm"
                                    >
                                        <?= icon('eye') ?> Открыть
                                    </a>

                                    <?php if (!$isSelf): ?>

                                        <form
                                            method="POST"
                                            action="/admin/users/<?= (int) $account['id'] ?>/<?= $isBlocked ? 'unblock' : 'block' ?>"
                                        >
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-outline-secondary btn-sm">
                                                <?= $isBlocked ? 'Разблокировать' : 'Заблокировать' ?>
                                            </button>
                                        </form>

                                        <form
                                            method="POST"
                                            action="/admin/users/<?= (int) $account['id'] ?>/delete"
                                            data-confirm="Удалить пользователя «<?= e($account['name']) ?>»? Его заявки сохранятся как гостевые."
                                        >
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-outline-secondary btn-sm text-danger" aria-label="Удалить пользователя <?= e($account['name']) ?>">
                                                <?= icon('trash') ?>
                                            </button>
                                        </form>

                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
