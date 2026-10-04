<?php
/** @var array $courses */
/** @var array $languages */
/** @var array $levels */
/** @var array $formats */
/** @var array $filters */
/** @var string $sort */
/** @var array $sorts */

$hasFilters = $filters['q'] !== ''
    || $filters['language_id'] !== null
    || $filters['level_id'] !== null
    || $filters['format_id'] !== null
    || $filters['price_min'] !== null
    || $filters['price_max'] !== null;

$total = count($courses);
?>

<section class="page-head">
    <div class="container">
        <h1>Каталог курсов</h1>
        <p>Выберите язык, уровень и формат — остальное мы возьмём на себя.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="row g-4 g-xl-5">

            <div class="col-lg-4 col-xl-3">
                <form method="GET" action="/courses" class="filter-panel">
                    <div class="d-flex align-items-center justify-content-between">
                        <h2 class="mb-lg-4">Подбор курса</h2>

                        <button
                            class="btn btn-outline-primary btn-sm d-lg-none"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#filterFields"
                            aria-expanded="<?= $hasFilters ? 'true' : 'false' ?>"
                            aria-controls="filterFields"
                        >
                            <?= icon('search') ?> Фильтры
                        </button>
                    </div>

                    <div class="collapse d-lg-block mt-3 mt-lg-0 <?= $hasFilters ? 'show' : '' ?>" id="filterFields">

                    <div class="mb-3">
                        <label for="q" class="form-label">Поиск</label>
                        <input
                            type="search"
                            class="form-control"
                            id="q"
                            name="q"
                            maxlength="100"
                            placeholder="Например, IELTS"
                            value="<?= e($filters['q']) ?>"
                        >
                    </div>

                    <div class="mb-3">
                        <label for="language" class="form-label">Язык</label>
                        <select name="language" id="language" class="form-select">
                            <option value="">Все языки</option>
                            <?php foreach ($languages as $item): ?>
                                <option
                                    value="<?= (int) $item['id'] ?>"
                                    <?= (int) $filters['language_id'] === (int) $item['id'] ? 'selected' : '' ?>
                                >
                                    <?= e($item['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="level" class="form-label">Уровень</label>
                        <select name="level" id="level" class="form-select">
                            <option value="">Все уровни</option>
                            <?php foreach ($levels as $item): ?>
                                <option
                                    value="<?= (int) $item['id'] ?>"
                                    <?= (int) $filters['level_id'] === (int) $item['id'] ? 'selected' : '' ?>
                                >
                                    <?= e($item['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="format" class="form-label">Формат</label>
                        <select name="format" id="format" class="form-select">
                            <option value="">Любой формат</option>
                            <?php foreach ($formats as $item): ?>
                                <option
                                    value="<?= (int) $item['id'] ?>"
                                    <?= (int) $filters['format_id'] === (int) $item['id'] ? 'selected' : '' ?>
                                >
                                    <?= e($item['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <fieldset class="mb-3">
                        <legend class="form-label fs-6">Цена, ₽</legend>
                        <div class="row g-2">
                            <div class="col-6">
                                <input
                                    type="number"
                                    class="form-control"
                                    name="price_min"
                                    min="0"
                                    step="100"
                                    placeholder="от"
                                    aria-label="Цена от"
                                    value="<?= $filters['price_min'] !== null ? e($filters['price_min']) : '' ?>"
                                >
                            </div>
                            <div class="col-6">
                                <input
                                    type="number"
                                    class="form-control"
                                    name="price_max"
                                    min="0"
                                    step="100"
                                    placeholder="до"
                                    aria-label="Цена до"
                                    value="<?= $filters['price_max'] !== null ? e($filters['price_max']) : '' ?>"
                                >
                            </div>
                        </div>
                    </fieldset>

                    <div class="mb-4">
                        <label for="sort" class="form-label">Сортировка</label>
                        <select name="sort" id="sort" class="form-select">
                            <?php foreach ($sorts as $key => $label): ?>
                                <option
                                    value="<?= e($key) ?>"
                                    <?= $sort === $key ? 'selected' : '' ?>
                                >
                                    <?= e($label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Показать курсы</button>

                        <?php if ($hasFilters || $sort !== 'date_desc'): ?>
                            <a href="/courses" class="btn btn-outline-secondary">Сбросить</a>
                        <?php endif; ?>
                    </div>
                    </div>
                </form>
            </div>

            <div class="col-lg-8 col-xl-9">
                <p class="text-body-secondary fw-semibold mb-4" aria-live="polite">
                    Найдено: <?= $total ?> <?= plural($total, 'курс', 'курса', 'курсов') ?>
                </p>

                <?php if ($courses === []): ?>

                    <div class="empty-state">
                        <svg viewBox="0 0 140 110" aria-hidden="true">
                            <circle cx="70" cy="55" r="48" fill="#d8e8ff"/>
                            <rect x="34" y="34" width="72" height="42" rx="21" fill="#fff"/>
                            <path d="M52 74l-8 18 22-14z" fill="#fff"/>
                            <circle cx="56" cy="55" r="4" fill="#2f7bff"/>
                            <circle cx="70" cy="55" r="4" fill="#2f7bff"/>
                            <circle cx="84" cy="55" r="4" fill="#2f7bff"/>
                        </svg>

                        <h2 class="h4">Ничего не нашлось</h2>
                        <p class="text-body-secondary mb-4">
                            Попробуйте изменить параметры или сбросить фильтры.
                        </p>
                        <a href="/courses" class="btn btn-primary">Показать все курсы</a>
                    </div>

                <?php else: ?>

                    <div class="row g-4">
                        <?php foreach ($courses as $course): ?>
                            <div class="col-md-6 col-xxl-4">
                                <?php partial('course-card', ['course' => $course]); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                <?php endif; ?>
            </div>

        </div>
    </div>
</section>
