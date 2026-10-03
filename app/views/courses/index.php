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

        <div class="card mb-5">
            <div class="card-body">

                <h2 class="h5 mb-4">
                    Фильтры и сортировка
                </h2>

                <form method="GET" action="/courses">

                    <div class="row g-3">

                        <div class="col-md-4">
                            <label for="language" class="form-label">
                                Язык
                            </label>

                            <select
                                name="language"
                                id="language"
                                class="form-select"
                            >
                                <option value="">Все языки</option>

                                <?php foreach ($languages as $item): ?>
                                    <option
                                            value="<?= htmlspecialchars($item) ?>"
                                            <?= $language === $item ? 'selected' : '' ?>
                                    >
                                        <?= htmlspecialchars($item) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="level" class="form-label">
                                Уровень
                            </label>

                            <select
                                name="level"
                                id="level"
                                class="form-select"
                            >
                                <option value="">Все уровни</option>

                                <?php foreach ($levels as $item): ?>
                                    <option
                                            value="<?= htmlspecialchars($item) ?>"
                                            <?= $level === $item ? 'selected' : '' ?>
                                    >
                                        <?= htmlspecialchars($item) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="format" class="form-label">
                                Формат
                            </label>

                            <select
                                name="format"
                                id="format"
                                class="form-select"
                            >
                                <option value="">Любой формат</option>

                                <?php foreach ($formats as $item): ?>
                                    <option
                                            value="<?= htmlspecialchars($item) ?>"
                                            <?= $format === $item ? 'selected' : '' ?>
                                    >
                                        <?= htmlspecialchars($item) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-8">
                            <label for="sort" class="form-label">
                                Сортировка
                            </label>

                            <select
                                name="sort"
                                id="sort"
                                class="form-select"
                            >
                                <option
                                    value="date_desc"
                                    <?= $sort === 'date_desc' ? 'selected' : '' ?>
                                >
                                    Сначала новые
                                </option>

                                <option
                                    value="price_asc"
                                    <?= $sort === 'price_asc' ? 'selected' : '' ?>
                                >
                                    Цена: по возрастанию
                                </option>

                                <option
                                    value="price_desc"
                                    <?= $sort === 'price_desc' ? 'selected' : '' ?>
                                >
                                    Цена: по убыванию
                                </option>

                                <option
                                    value="name_asc"
                                    <?= $sort === 'name_asc' ? 'selected' : '' ?>
                                >
                                    По названию
                                </option>
                            </select>
                        </div>

                        <div class="col-md-4 d-flex align-items-end">
                            <button
                                type="submit"
                                class="btn btn-primary w-100"
                            >
                                Применить
                            </button>
                        </div>

                    </div>

                </form>

            </div>
        </div>

        <div class="row g-4">

            <?php foreach ($courses as $course): ?>

                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 course-card">

                        <img
                                src="<?= htmlspecialchars($course['image'] ?: '/images/course-placeholder.svg') ?>"
                                class="card-img-top"
                                alt="<?= htmlspecialchars($course['name']) ?>"
                                style="height: 220px; object-fit: cover;"
                        >

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

        <?php if ($courses === []): ?>
            <div class="alert alert-secondary mt-4">
                По выбранным параметрам курсы не найдены.
            </div>
        <?php endif; ?>

    </div>
</section>