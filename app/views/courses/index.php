<section class="py-5">
    <div class="container">

        <div class="mb-5">
            <h1 class="display-5 fw-bold">
                Курсы
            </h1>

            <p class="lead text-body-secondary">
                Выберите подходящий курс иностранного языка.
            </p>
        </div>

        <div class="row g-4">

            <?php foreach ($courses as $course): ?>

                <div class="col-md-6 col-lg-4">
                    <div class="card h-100">

                        <div class="card-body d-flex flex-column">

                            <h2 class="card-title h4">
                                <?= htmlspecialchars($course['name']) ?>
                            </h2>

                            <p class="card-text text-body-secondary">
                                <?= htmlspecialchars($course['description']) ?>
                            </p>

                            <div class="mb-3">
                                <span class="badge text-bg-secondary">
                                    <?= htmlspecialchars($course['language']) ?>
                                </span>

                                <span class="badge text-bg-light">
                                    <?= htmlspecialchars($course['level']) ?>
                                </span>

                                <span class="badge text-bg-light">
                                    <?= htmlspecialchars($course['format']) ?>
                                </span>
                            </div>

                            <div class="mt-auto">

                                <p class="fs-4 fw-bold mb-3">
                                    <?= htmlspecialchars($course['price']) ?> ₽
                                </p>

                                <a
                                    href="/courses/<?= (int) $course['id'] ?>"
                                    class="btn btn-primary"
                                >
                                    Подробнее
                                </a>

                            </div>

                        </div>

                    </div>
                </div>

            <?php endforeach; ?>

        </div>

    </div>
</section>