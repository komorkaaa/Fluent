<?php
/** @var array $course */
/** @var array|null $user */
/** @var array $errors */
/** @var array $old */
?>

<section class="page-head pb-4">
    <div class="container">
        <a href="/courses/<?= (int) $course['id'] ?>" class="breadcrumb-link">
            <?= icon('arrow-left') ?> К курсу
        </a>

        <h1>Запись на курс</h1>
        <p>Оставьте контакты — мы свяжемся с вами и согласуем детали.</p>
    </div>
</section>

<section class="section pt-4">
    <div class="container">
        <div class="row g-4 g-xl-5">

            <div class="col-lg-7">
                <?php partial('errors', ['errors' => $errors ?? []]); ?>

                <form
                    method="POST"
                    action="/courses/<?= (int) $course['id'] ?>/apply"
                    class="form-card"
                    novalidate
                >
                    <?= csrf_field() ?>

                    <?php if ($user !== null): ?>

                        <div class="mb-3">
                            <label for="name_ro" class="form-label">Имя</label>
                            <input type="text" id="name_ro" class="form-control" value="<?= e($user['name']) ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="email_ro" class="form-label">E-mail</label>
                            <input type="email" id="email_ro" class="form-control" value="<?= e($user['email']) ?>" readonly>
                            <div class="form-text">
                                Данные берутся из вашего профиля.
                                <a href="/profile">Изменить</a>
                            </div>
                        </div>

                    <?php else: ?>

                        <div class="mb-3">
                            <label for="name" class="form-label">Имя</label>
                            <input
                                type="text"
                                class="form-control"
                                id="name"
                                name="name"
                                autocomplete="name"
                                maxlength="255"
                                value="<?= e($old['name'] ?? '') ?>"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                autocomplete="email"
                                maxlength="255"
                                value="<?= e($old['email'] ?? '') ?>"
                                required
                            >
                        </div>

                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Телефон</label>
                        <input
                            type="tel"
                            class="form-control"
                            id="phone"
                            name="phone"
                            autocomplete="tel"
                            maxlength="50"
                            placeholder="+7 (900) 000-00-00"
                            value="<?= e($old['phone'] ?? '') ?>"
                            required
                        >
                    </div>

                    <div class="mb-4">
                        <label for="comment" class="form-label">Комментарий</label>
                        <textarea
                            class="form-control"
                            id="comment"
                            name="comment"
                            rows="4"
                            maxlength="5000"
                            placeholder="Удобное время для звонка, ваш уровень, вопросы"
                        ><?= e($old['comment'] ?? '') ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg">
                        Отправить заявку
                    </button>

                    <p class="form-text mt-3 mb-0">
                        Отправляя заявку, вы соглашаетесь с
                        <a href="/privacy">политикой обработки персональных данных</a>.
                    </p>
                </form>
            </div>

            <div class="col-lg-5">
                <div class="account-card">
                    <div class="text-body-secondary fw-semibold mb-2">Вы записываетесь на курс</div>

                    <img
                        src="<?= e(course_image($course)) ?>"
                        alt=""
                        class="course-hero-img mb-3"
                        style="border-radius: 18px;"
                        width="800"
                        height="450"
                    >

                    <h2 class="h4"><?= e($course['name']) ?></h2>

                    <div class="chips">
                        <span class="chip"><?= e($course['language']) ?></span>
                        <span class="chip chip-yellow"><?= e($course['level']) ?></span>
                        <span class="chip chip-mint"><?= e($course['format']) ?></span>
                    </div>

                    <div class="price-tag"><?= e(price($course['price'])) ?></div>
                </div>
            </div>

        </div>
    </div>
</section>
