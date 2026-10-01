<section class="py-5">
    <div class="container">

        <div class="mb-4">
            <a href="/courses" class="text-decoration-none">
                ← Вернуться к курсам
            </a>
        </div>

        <div class="row">

            <div class="col-lg-8">

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

            </div>

            <div class="col-lg-4">

                <div class="card shadow-sm">
                    <div class="card-body">

                        <p class="text-body-secondary mb-1">
                            Стоимость курса
                        </p>

                        <p class="display-6 fw-bold">
                            <?= htmlspecialchars($course['price']) ?> ₽
                        </p>

                        <a
                            href="/login"
                            class="btn btn-primary w-100"
                        >
                            Записаться на курс
                        </a>

                    </div>
                </div>

            </div>

        </div>

    </div>
</section>