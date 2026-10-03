<section class="py-5">
    <div class="container">

        <h1 class="display-6 fw-bold mb-4">
            Редактирование курса
        </h1>

        <?php if (!empty($errors)): ?>

            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li>
                            <?= htmlspecialchars($error) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

        <?php endif; ?>

        <form
            method="POST"
            action="/admin/courses/<?= (int) $course['id'] ?>/edit"
        >

            <div class="mb-3">
                <label for="name" class="form-label">
                    Название
                </label>

                <input
                    type="text"
                    class="form-control"
                    id="name"
                    name="name"
                    value="<?= htmlspecialchars($course['name']) ?>"
                    required
                >
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">
                    Описание
                </label>

                <textarea
                    class="form-control"
                    id="description"
                    name="description"
                    rows="5"
                    required
                ><?= htmlspecialchars($course['description']) ?></textarea>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">
                    Цена
                </label>

                <input
                    type="number"
                    class="form-control"
                    id="price"
                    name="price"
                    min="0"
                    step="0.01"
                    value="<?= htmlspecialchars($course['price']) ?>"
                    required
                >
            </div>

            <div class="mb-3">
                <label for="language" class="form-label">
                    Язык
                </label>

                <input
                    type="text"
                    class="form-control"
                    id="language"
                    name="language"
                    value="<?= htmlspecialchars($course['language']) ?>"
                    required
                >
            </div>

            <div class="mb-3">
                <label for="level" class="form-label">
                    Уровень
                </label>

                <input
                    type="text"
                    class="form-control"
                    id="level"
                    name="level"
                    value="<?= htmlspecialchars($course['level']) ?>"
                    required
                >
            </div>

            <div class="mb-4">
                <label for="format" class="form-label">
                    Формат
                </label>

                <input
                    type="text"
                    class="form-control"
                    id="format"
                    name="format"
                    value="<?= htmlspecialchars($course['format']) ?>"
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary">
                Сохранить изменения
            </button>

            <a href="/admin/courses" class="btn btn-outline-secondary ms-2">
                Отмена
            </a>

        </form>

    </div>
</section>