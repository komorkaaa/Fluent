<?php /** @var array $courses */ ?>

<section class="page-head pb-4">
    <div class="container">
        <h1>Курсы</h1>
        <p>Каталог курсов: добавление, редактирование и удаление.</p>
    </div>
</section>

<section class="section pt-4">
    <div class="container">
        <?php partial('admin-nav'); ?>

        <div class="d-flex justify-content-between align-items-center mb-4 gap-3">
            <span class="text-body-secondary fw-semibold">
                Всего: <?= count($courses) ?>
            </span>

            <a href="/admin/courses/create" class="btn btn-primary">
                <?= icon('plus') ?> Добавить курс
            </a>
        </div>

        <?php if ($courses === []): ?>

            <div class="empty-state">
                <h2 class="h4">Курсов пока нет</h2>
                <p class="text-body-secondary mb-4">Добавьте первый курс — он сразу появится в каталоге.</p>
                <a href="/admin/courses/create" class="btn btn-primary">Добавить курс</a>
            </div>

        <?php else: ?>

            <div class="table-wrap">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Курс</th>
                            <th scope="col">Язык</th>
                            <th scope="col">Уровень</th>
                            <th scope="col">Формат</th>
                            <th scope="col" class="text-end">Цена</th>
                            <th scope="col"><span class="visually-hidden">Действия</span></th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php foreach ($courses as $course): ?>
                            <tr>
                                <td class="text-body-secondary"><?= (int) $course['id'] ?></td>

                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img
                                            src="<?= e(course_image($course)) ?>"
                                            class="admin-thumb"
                                            alt=""
                                            width="72"
                                            height="45"
                                            loading="lazy"
                                        >
                                        <a
                                            href="/courses/<?= (int) $course['id'] ?>"
                                            class="fw-bold text-decoration-none"
                                        >
                                            <?= e($course['name']) ?>
                                        </a>
                                    </div>
                                </td>

                                <td><?= e($course['language']) ?></td>
                                <td><?= e($course['level']) ?></td>
                                <td><?= e($course['format']) ?></td>
                                <td class="text-end fw-bold text-nowrap"><?= e(price($course['price'])) ?></td>

                                <td>
                                    <div class="d-flex justify-content-end gap-2">
                                        <a
                                            href="/admin/courses/<?= (int) $course['id'] ?>/edit"
                                            class="btn btn-outline-primary btn-sm"
                                        >
                                            <?= icon('edit') ?> Изменить
                                        </a>

                                        <form
                                            method="POST"
                                            action="/admin/courses/<?= (int) $course['id'] ?>/delete"
                                            data-confirm="Удалить курс «<?= e($course['name']) ?>»?"
                                        >
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-outline-secondary btn-sm text-danger">
                                                <?= icon('trash') ?> Удалить
                                            </button>
                                        </form>
                                    </div>
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
