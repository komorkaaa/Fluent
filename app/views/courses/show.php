<section class="py-5">
    <div class="container">

        <div class="mb-4">
            <a href="/courses" class="text-decoration-none">
                ← Вернуться к курсам
            </a>
        </div>

        <div class="row g-5">

            <div class="col-lg-8">

                <img
                        src="<?= htmlspecialchars($course['image'] ?: '/images/course-placeholder.svg') ?>"
                        class="img-fluid rounded-4 shadow-sm mb-4 w-100"
                        alt="<?= htmlspecialchars($course['name']) ?>"
                        style="max-height: 450px; object-fit: cover;"
                >

                <h1 class="display-5 fw-bold mb-4">
                    <?= htmlspecialchars($course['name']) ?>
                </h1>

                <div class="mb-4">
                    <span class="badge text-bg-primary">
                        <?= htmlspecialchars($course['language']) ?>
                    </span>

                    <span class="badge text-bg-secondary">
                        <?= htmlspecialchars($course['level']) ?>
                    </span>

                    <span class="badge text-bg-light">
                        <?= htmlspecialchars($course['format']) ?>
                    </span>
                </div>

                <h2 class="h4 mb-3">
                    О курсе
                </h2>

                <p class="lead">
                    <?= htmlspecialchars($course['description']) ?>
                </p>

                <h2 class="h4 mb-3 mt-5">
                    Характеристики
                </h2>

                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th scope="row">Язык</th>
                            <td><?= htmlspecialchars($course['language']) ?></td>
                        </tr>

                        <tr>
                            <th scope="row">Уровень</th>
                            <td><?= htmlspecialchars($course['level']) ?></td>
                        </tr>

                        <tr>
                            <th scope="row">Формат</th>
                            <td><?= htmlspecialchars($course['format']) ?></td>
                        </tr>

                        <tr>
                            <th scope="row">Стоимость</th>
                            <td><?= htmlspecialchars($course['price']) ?> ₽</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="col-lg-4">

                <div class="card shadow-sm sticky-lg-top" style="top: 1rem;">
                    <div class="card-body p-4">

                        <p class="text-body-secondary mb-1">
                            Стоимость курса
                        </p>

                        <p class="display-6 fw-bold">
                            <?= htmlspecialchars($course['price']) ?> ₽
                        </p>

                        <p class="text-body-secondary">
                            Оставьте заявку, и мы свяжемся с вами
                            для уточнения деталей обучения.
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

        </div>

        <?php if ($similarCourses !== []): ?>

            <section class="mt-5 pt-5 border-top">

                <h2 class="fw-bold mb-4">
                    Похожие курсы
                </h2>

                <div class="row g-4">

                    <?php foreach ($similarCourses as $similarCourse): ?>

                        <div class="col-md-6 col-lg-4">

                            <div class="card h-100 course-card">

                                <img
                                        src="<?= htmlspecialchars($similarCourse['image'] ?: '/images/course-placeholder.svg') ?>"
                                        class="card-img-top"
                                        alt="<?= htmlspecialchars($similarCourse['name']) ?>"
                                        style="height: 200px; object-fit: cover;"
                                >

                                <div class="card-body d-flex flex-column">

                                    <h3 class="h5">
                                        <?= htmlspecialchars($similarCourse['name']) ?>
                                    </h3>

                                    <p class="text-body-secondary">
                                        <?= htmlspecialchars($similarCourse['description']) ?>
                                    </p>

                                    <div class="mb-3">
                                        <span class="badge text-bg-secondary">
                                            <?= htmlspecialchars($similarCourse['language']) ?>
                                        </span>

                                        <span class="badge text-bg-light">
                                            <?= htmlspecialchars($similarCourse['level']) ?>
                                        </span>
                                    </div>

                                    <div class="mt-auto">

                                        <p class="fw-bold fs-5">
                                            <?= htmlspecialchars($similarCourse['price']) ?> ₽
                                        </p>

                                        <a
                                                href="/courses/<?= (int) $similarCourse['id'] ?>"
                                                class="btn btn-outline-primary"
                                        >
                                            Подробнее
                                        </a>

                                    </div>

                                </div>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>

            </section>

        <?php endif; ?>

    </div>
</section>