<?php
/** @var array $course */
/** @var array $errors */
/** @var string $action */
/** @var string $submitLabel */
/** @var array $languages */
/** @var array $levels */
/** @var array $formats */
?>
<?php partial('errors', ['errors' => $errors ?? []]); ?>

<form method="POST" action="<?= e($action) ?>" class="form-card" novalidate>
    <?= csrf_field() ?>

    <div class="row g-3">
        <div class="col-12">
            <label for="name" class="form-label">Название</label>
            <input
                type="text"
                class="form-control"
                id="name"
                name="name"
                maxlength="255"
                value="<?= e($course['name'] ?? '') ?>"
                required
            >
        </div>

        <div class="col-12">
            <label for="description" class="form-label">Описание</label>
            <textarea
                class="form-control"
                id="description"
                name="description"
                rows="6"
                maxlength="10000"
                required
            ><?= e($course['description'] ?? '') ?></textarea>
        </div>

        <div class="col-md-4">
            <label for="language_id" class="form-label">Язык</label>
            <select class="form-select" id="language_id" name="language_id">
                <option value="">Выберите язык</option>
                <?php foreach ($languages as $item): ?>
                    <option
                        value="<?= (int) $item['id'] ?>"
                        <?= (string) ($course['language_id'] ?? '') === (string) $item['id'] ? 'selected' : '' ?>
                    >
                        <?= e($item['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-4">
            <label for="level_id" class="form-label">Уровень</label>
            <select class="form-select" id="level_id" name="level_id">
                <option value="">Выберите уровень</option>
                <?php foreach ($levels as $item): ?>
                    <option
                        value="<?= (int) $item['id'] ?>"
                        <?= (string) ($course['level_id'] ?? '') === (string) $item['id'] ? 'selected' : '' ?>
                    >
                        <?= e($item['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-4">
            <label for="format_id" class="form-label">Формат</label>
            <select class="form-select" id="format_id" name="format_id">
                <option value="">Выберите формат</option>
                <?php foreach ($formats as $item): ?>
                    <option
                        value="<?= (int) $item['id'] ?>"
                        <?= (string) ($course['format_id'] ?? '') === (string) $item['id'] ? 'selected' : '' ?>
                    >
                        <?= e($item['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-6">
            <label for="new_language" class="form-label">Нет нужного языка?</label>
            <input
                type="text"
                class="form-control"
                id="new_language"
                name="new_language"
                maxlength="100"
                placeholder="Например, Китайский"
                value="<?= e($course['new_language'] ?? '') ?>"
            >
            <div class="form-text">Если заполнено, язык будет создан и выбран вместо списка.</div>
        </div>

        <div class="col-md-6">
            <label for="price" class="form-label">Цена, ₽</label>
            <input
                type="number"
                class="form-control"
                id="price"
                name="price"
                min="0"
                max="99999999.99"
                step="0.01"
                value="<?= e($course['price'] ?? '') ?>"
                required
            >
        </div>

        <div class="col-12">
            <label for="image" class="form-label">Изображение курса</label>
            <input
                type="text"
                class="form-control"
                id="image"
                name="image"
                maxlength="500"
                placeholder="/images/courses/english.svg"
                value="<?= e($course['image'] ?? '') ?>"
            >
            <div class="form-text">
                Необязательно: путь на сайте (<code>/images/…</code>) или ссылка <code>https://…</code>.
                Если оставить пустым, подставится обложка по языку курса.
            </div>
        </div>
    </div>

    <div class="d-flex flex-wrap gap-2 mt-4">
        <button type="submit" class="btn btn-primary"><?= e($submitLabel) ?></button>
        <a href="/admin/courses" class="btn btn-outline-secondary">Отмена</a>
    </div>
</form>
