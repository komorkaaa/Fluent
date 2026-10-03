<section class="py-5">
    <div class="container">

        <div class="mb-4">
            <h1 class="display-6 fw-bold">
                Заявки
            </h1>

            <p class="text-body-secondary">
                Просмотр заявок пользователей и изменение их статуса.
            </p>
        </div>

        <div class="mb-4">
            <a href="/admin" class="btn btn-outline-secondary">
                ← Панель администратора
            </a>
        </div>

        <?php if ($applications === []): ?>

            <div class="alert alert-info">
                Заявок пока нет.
            </div>

        <?php else: ?>

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
                                        Имя
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
                                        Email
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