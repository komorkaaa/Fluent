<section class="py-5">
    <div class="container">

        <div class="mb-4">
            <h1 class="display-6 fw-bold">
                Заявки
            </h1>

            <p class="text-body-secondary">
                Просмотр заявок пользователей, фильтрация и изменение статусов.
            </p>
        </div>

        <div class="mb-4">
            <a href="/admin" class="btn btn-outline-secondary">
                ← Панель администратора
            </a>
        </div>

        <div class="card mb-4">
            <div class="card-body">

                <h2 class="h5 mb-3">
                    Фильтрация
                </h2>

                <form method="GET" action="/admin/applications">

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label for="user_id" class="form-label">
                                Пользователь
                            </label>

                            <select
                                    id="user_id"
                                    name="user_id"
                                    class="form-select"
                            >
                                <option value="">
                                    Все пользователи
                                </option>

                                <?php foreach ($users as $user): ?>

                                    <option
                                            value="<?= (int) $user['id'] ?>"
                                            <?= $filters['user_id'] === (string) $user['id'] ? 'selected' : '' ?>
                                    >
                                        <?= htmlspecialchars($user['name']) ?>
                                        — <?= htmlspecialchars($user['email']) ?>
                                    </option>

                                <?php endforeach; ?>

                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="status" class="form-label">
                                Статус
                            </label>

                            <select
                                    id="status"
                                    name="status"
                                    class="form-select"
                            >
                                <option value="">
                                    Все статусы
                                </option>

                                <?php foreach ($statuses as $status): ?>

                                    <option
                                            value="<?= htmlspecialchars($status) ?>"
                                            <?= $filters['status'] === $status ? 'selected' : '' ?>
                                    >
                                        <?= htmlspecialchars($status) ?>
                                    </option>

                                <?php endforeach; ?>

                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="date_from" class="form-label">
                                Дата от
                            </label>

                            <input
                                    type="date"
                                    id="date_from"
                                    name="date_from"
                                    class="form-control"
                                    value="<?= htmlspecialchars($filters['date_from']) ?>"
                            >
                        </div>

                        <div class="col-md-6">
                            <label for="date_to" class="form-label">
                                Дата до
                            </label>

                            <input
                                    type="date"
                                    id="date_to"
                                    name="date_to"
                                    class="form-control"
                                    value="<?= htmlspecialchars($filters['date_to']) ?>"
                            >
                        </div>

                    </div>

                    <div class="d-flex flex-wrap gap-2 mt-4">

                        <button
                                type="submit"
                                class="btn btn-primary"
                        >
                            Применить фильтры
                        </button>

                        <a
                                href="/admin/applications"
                                class="btn btn-outline-secondary"
                        >
                            Сбросить
                        </a>

                    </div>

                </form>

            </div>
        </div>

        <?php if ($applications === []): ?>

            <div class="alert alert-info">
                По заданным параметрам заявки не найдены.
            </div>

        <?php else: ?>

            <div class="mb-3 text-body-secondary">
                Найдено заявок: <?= count($applications) ?>
            </div>

            <div class="d-flex flex-column gap-3">

                <?php foreach ($applications as $application): ?>

                    <div class="card">
                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-start gap-3 mb-3">

                                <div>
                                    <h2 class="h5 mb-1">
                                        Заявка #<?= (int) $application['id'] ?>
                                    </h2>

                                    <div class="text-body-secondary">
                                        <?= htmlspecialchars($application['created_at']) ?>
                                    </div>
                                </div>

                                <div class="text-end">
                                    <div class="small text-body-secondary mb-1">
                                        Статус
                                    </div>

                                    <div class="d-flex flex-column gap-2">

                                        <form
                                                method="POST"
                                                action="/admin/applications/<?= (int) $application['id'] ?>/status"
                                        >
                                            <div class="d-flex gap-2">
                                                <select
                                                        name="status"
                                                        class="form-select form-select-sm"
                                                >
                                                    <?php foreach ($statuses as $status): ?>

                                                        <option
                                                                value="<?= htmlspecialchars($status) ?>"
                                                                <?= $application['status'] === $status ? 'selected' : '' ?>
                                                        >
                                                            <?= htmlspecialchars($status) ?>
                                                        </option>

                                                    <?php endforeach; ?>
                                                </select>

                                                <button
                                                        type="submit"
                                                        class="btn btn-sm btn-primary"
                                                >
                                                    Сохранить
                                                </button>
                                            </div>
                                        </form>

                                        <form
                                                method="POST"
                                                action="/admin/applications/<?= (int) $application['id'] ?>/delete"
                                                onsubmit="return confirm('Удалить заявку #<?= (int) $application['id'] ?>?');"
                                        >
                                            <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-danger w-100"
                                            >
                                                Удалить заявку
                                            </button>
                                        </form>

                                    </div>
                                </div>

                            </div>

                            <hr>

                            <div class="row g-3">

                                <div class="col-md-6">
                                    <div class="small text-body-secondary">
                                        Курс
                                    </div>

                                    <div class="fw-semibold">
                                        <?= htmlspecialchars($application['course_name']) ?>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="small text-body-secondary">
                                        Пользователь
                                    </div>

                                    <div>
                                        <?php if ($application['user_name'] !== null): ?>

                                            <?= htmlspecialchars($application['user_name']) ?>

                                            <div class="small text-body-secondary">
                                                <?= htmlspecialchars($application['user_email']) ?>
                                            </div>

                                        <?php else: ?>

                                            <span class="text-body-secondary">
                                                Гость
                                            </span>

                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="small text-body-secondary">
                                        Имя в заявке
                                    </div>

                                    <div>
                                        <?= htmlspecialchars($application['name']) ?>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="small text-body-secondary">
                                        Телефон
                                    </div>

                                    <div>
                                        <?= htmlspecialchars($application['phone']) ?>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="small text-body-secondary">
                                        Email в заявке
                                    </div>

                                    <div>
                                        <?= htmlspecialchars($application['email']) ?>
                                    </div>
                                </div>

                            </div>

                            <?php if ($application['comment'] !== null && $application['comment'] !== ''): ?>

                                <div class="mt-4">
                                    <div class="small text-body-secondary mb-1">
                                        Комментарий
                                    </div>

                                    <div
                                            class="p-3 bg-body-tertiary rounded"
                                            style="overflow-wrap: anywhere;"
                                    >
                                        <?= nl2br(htmlspecialchars($application['comment'])) ?>
                                    </div>
                                </div>

                            <?php endif; ?>

                        </div>
                    </div>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </div>
</section>