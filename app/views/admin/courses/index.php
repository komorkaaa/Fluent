<section class="py-5">
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="display-6 fw-bold">
                    Курсы
                </h1>

                <p class="text-body-secondary mb-0">
                    Управление каталогом курсов.
                </p>
            </div>

            <a href="/admin/courses/create" class="btn btn-primary">
                Добавить курс
            </a>
        </div>

        <div class="mb-4">
            <a href="/admin" class="btn btn-outline-secondary">
                ← Панель администратора
            </a>
        </div>

        <?php if ($courses === []): ?>

            <div class="alert alert-info">
                Курсов пока нет.
            </div>

        <?php else: ?>

            <div class="table-responsive">
                <table class="table table-bordered align-middle">

                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Язык</th>
                        <th>Уровень</th>
                        <th>Формат</th>
                        <th>Цена</th>
                        <th>Действия</th>
                    </tr>
                    </thead>

                    <tbody>

                    <?php foreach ($courses as $course): ?>

                        <tr>
                            <td>
                                <?= (int) $course['id'] ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($course['name']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($course['language']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($course['level']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($course['format']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($course['price']) ?> ₽
                            </td>

                            <td>
                                <div class="d-flex gap-2">

                                    <a
                                        href="/admin/courses/<?= (int) $course['id'] ?>/edit"
                                        class="btn btn-sm btn-outline-primary"
                                    >
                                        Изменить
                                    </a>

                                    <form
                                        method="POST"
                                        action="/admin/courses/<?= (int) $course['id'] ?>

                                <input
                                        type="hidden"
                                        name="csrf_token"
                                        value="<?= htmlspecialchars(\App\Core\Auth::csrfToken()) ?>"
                                >/delete"
                                        onsubmit="return confirm('Удалить этот курс?');"
                                    >
                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                        >
                                            Удалить
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>
            </div>

        <?php endif; ?>

    </div>
</section>