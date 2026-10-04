<?php
/** @var array $application */
/** @var array $statuses */
?>

<section class="page-head pb-4">
    <div class="container">
        <a href="/admin/applications" class="breadcrumb-link"><?= icon('arrow-left') ?> Все заявки</a>

        <div class="d-flex flex-wrap align-items-center gap-3">
            <h1 class="mb-0">Заявка №<?= (int) $application['id'] ?></h1>
            <?= status_badge($application['status']) ?>
        </div>
    </div>
</section>

<section class="section pt-4">
    <div class="container">
        <div class="row g-4">

            <div class="col-lg-8">
                <div class="account-card">
                    <dl class="detail-grid mb-0">
                        <div>
                            <dt>Курс</dt>
                            <dd>
                                <a href="/courses/<?= (int) $application['course_id'] ?>">
                                    <?= e($application['course_name']) ?>
                                </a>
                            </dd>
                        </div>

                        <div>
                            <dt>Стоимость курса</dt>
                            <dd><?= e(price($application['course_price'])) ?></dd>
                        </div>

                        <div>
                            <dt>Дата подачи</dt>
                            <dd><?= e(format_date($application['created_at'])) ?></dd>
                        </div>

                        <div>
                            <dt>Имя в заявке</dt>
                            <dd><?= e($application['name']) ?></dd>
                        </div>

                        <div>
                            <dt>Телефон</dt>
                            <dd><a href="tel:<?= e(preg_replace('/[^0-9+]/', '', $application['phone'])) ?>"><?= e($application['phone']) ?></a></dd>
                        </div>

                        <div>
                            <dt>E-mail в заявке</dt>
                            <dd><a href="mailto:<?= e($application['email']) ?>"><?= e($application['email']) ?></a></dd>
                        </div>

                        <div>
                            <dt>Пользователь</dt>
                            <dd>
                                <?php if ($application['user_id'] !== null): ?>
                                    <a href="/admin/users/<?= (int) $application['user_id'] ?>">
                                        <?= e($application['user_name']) ?>
                                    </a>
                                <?php else: ?>
                                    Гость (без аккаунта)
                                <?php endif; ?>
                            </dd>
                        </div>
                    </dl>

                    <?php if (!empty($application['comment'])): ?>
                        <div class="mt-4">
                            <div class="text-body-secondary fw-bold small mb-2">Комментарий</div>
                            <div class="comment-box"><?= nl2br(e($application['comment'])) ?></div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="account-card mb-4">
                    <h2 class="h5 mb-3">Изменить статус</h2>

                    <form method="POST" action="/admin/applications/<?= (int) $application['id'] ?>/status">
                        <?= csrf_field() ?>

                        <label for="status_id" class="visually-hidden">Статус</label>
                        <select id="status_id" name="status_id" class="form-select mb-3">
                            <?php foreach ($statuses as $item): ?>
                                <option
                                    value="<?= (int) $item['id'] ?>"
                                    <?= (int) $application['status_id'] === (int) $item['id'] ? 'selected' : '' ?>
                                >
                                    <?= e($item['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <button type="submit" class="btn btn-primary w-100">Сохранить статус</button>
                    </form>
                </div>

                <form
                    method="POST"
                    action="/admin/applications/<?= (int) $application['id'] ?>/delete"
                    data-confirm="Удалить заявку №<?= (int) $application['id'] ?>? Это действие нельзя отменить."
                >
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-outline-secondary text-danger w-100">
                        <?= icon('trash') ?> Удалить заявку
                    </button>
                </form>
            </div>

        </div>
    </div>
</section>
