<?php /** @var array $application */ ?>

<section class="page-head pb-4">
    <div class="container">
        <a href="/profile" class="breadcrumb-link">
            <?= icon('arrow-left') ?> Личный кабинет
        </a>

        <div class="d-flex flex-wrap align-items-center gap-3">
            <h1 class="mb-0">Заявка №<?= (int) $application['id'] ?></h1>
            <?= status_badge($application['status']) ?>
        </div>
    </div>
</section>

<section class="section pt-4">
    <div class="container">
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
                    <dt>Имя</dt>
                    <dd><?= e($application['name']) ?></dd>
                </div>

                <div>
                    <dt>Телефон</dt>
                    <dd><?= e($application['phone']) ?></dd>
                </div>

                <div>
                    <dt>E-mail</dt>
                    <dd><?= e($application['email']) ?></dd>
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
</section>
