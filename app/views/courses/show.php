<?php
/** @var array $course */
/** @var array $similarCourses */
?>

<section class="page-head pb-4">
    <div class="container">
        <a href="/courses" class="breadcrumb-link">
            <?= icon('arrow-left') ?> Все курсы
        </a>

        <h1><?= e($course['name']) ?></h1>

        <div class="chips mt-3 mb-0">
            <span class="chip"><?= e($course['language']) ?></span>
            <span class="chip chip-yellow"><?= e($course['level']) ?></span>
            <span class="chip chip-mint"><?= e($course['format']) ?></span>
        </div>
    </div>
</section>

<section class="section pt-4">
    <div class="container">
        <div class="row g-4 g-xl-5">

            <div class="col-lg-8">
                <img
                    src="<?= e(course_image($course)) ?>"
                    class="course-hero-img mb-5"
                    alt="Обложка курса «<?= e($course['name']) ?>»"
                    width="800"
                    height="450"
                >

                <h2 class="h3 mb-3">О курсе</h2>
                <p class="description-text"><?= e($course['description']) ?></p>

                <h2 class="h3 mt-5 mb-3">Характеристики</h2>

                <ul class="spec-list">
                    <li><span class="k">Язык</span> <span class="v"><?= e($course['language']) ?></span></li>
                    <li><span class="k">Уровень</span> <span class="v"><?= e($course['level']) ?></span></li>
                    <li><span class="k">Формат</span> <span class="v"><?= e($course['format']) ?></span></li>
                    <li><span class="k">Стоимость</span> <span class="v"><?= e(price($course['price'])) ?></span></li>
                    <li><span class="k">Добавлен</span> <span class="v"><?= e(format_date($course['created_at'], false)) ?></span></li>
                </ul>
            </div>

            <div class="col-lg-4">
                <div class="buy-card">
                    <div class="text-body-secondary fw-semibold">Стоимость курса</div>
                    <div class="price-tag fs-1 mb-3"><?= e(price($course['price'])) ?></div>

                    <p class="text-body-secondary">
                        Оставьте заявку — мы свяжемся с вами, ответим на вопросы
                        и согласуем дату первого занятия.
                    </p>

                    <a
                        href="/courses/<?= (int) $course['id'] ?>/apply"
                        class="btn btn-primary btn-lg w-100"
                    >
                        Записаться на курс
                    </a>
                </div>
            </div>

        </div>

        <?php if ($similarCourses !== []): ?>
            <div class="mt-5 pt-5">
                <h2 class="mb-4">Похожие курсы</h2>

                <div class="row g-4">
                    <?php foreach ($similarCourses as $similar): ?>
                        <div class="col-md-6 col-lg-4">
                            <?php partial('course-card', ['course' => $similar]); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
