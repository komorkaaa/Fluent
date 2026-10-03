<section class="py-5">
    <div class="container">

        <div class="mb-4">
            <h1 class="display-6 fw-bold">
                Пользователи
            </h1>

            <p class="text-body-secondary">
                Просмотр зарегистрированных пользователей и управление их ролями.
            </p>
        </div>

        <div class="mb-4">
            <a href="/admin" class="btn btn-outline-secondary">
                ← Панель администратора
            </a>
        </div>

        <?php if ($users === []): ?>

            <div class="alert alert-info">
                Пользователей пока нет.
            </div>

        <?php else: ?>

            <div class="d-flex flex-column gap-3">

                <?php foreach ($users as $user): ?>

                    <?php
                    $isCurrentUser =
                        (int) $user['id'] === (int) ($currentUser['id'] ?? 0);

                    $isBlocked = (bool) $user['is_blocked'];
                    ?>

                    <div class="card">

                        <div class="card-body">

                            <div class="row align-items-center g-4">

                                <div class="col-md-1">
                                    <div class="small text-body-secondary">
                                        ID
                                    </div>

                                    <div class="fw-semibold">
                                        <?= (int) $user['id'] ?>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="small text-body-secondary">
                                        Имя
                                    </div>

                                    <div>
                                        <?= htmlspecialchars($user['name']) ?>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="small text-body-secondary">
                                        Email
                                    </div>

                                    <div class="text-break">
                                        <?= htmlspecialchars($user['email']) ?>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="small text-body-secondary">
                                        Регистрация
                                    </div>

                                    <div>
                                        <?= htmlspecialchars($user['created_at']) ?>
                                    </div>
                                </div>

                                <div class="col-md-3">

                                    <div class="small text-body-secondary mb-1">
                                        Роль
                                    </div>

                                    <?php if ($isCurrentUser): ?>

                                        <span class="badge text-bg-primary">
                                            <?= htmlspecialchars($user['role']) ?>
                                        </span>

                                        <div class="small text-body-secondary mt-1">
                                            Ваша учётная запись
                                        </div>

                                    <?php else: ?>

                                        <form
                                                method="POST"
                                                action="/admin/users/<?= (int) $user['id'] ?>/role"
                                        >
                                            <div class="d-flex gap-2">

                                                <select
                                                        name="role"
                                                        class="form-select form-select-sm"
                                                >
                                                    <?php foreach ($roles as $role): ?>

                                                        <option
                                                                value="<?= htmlspecialchars($role) ?>"
                                                            <?= $user['role'] === $role ? 'selected' : '' ?>
                                                        >
                                                            <?= htmlspecialchars($role) ?>
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

                                    <?php endif; ?>

                                </div>

                                <div class="col-12">

                                    <hr class="my-0">

                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 pt-3">

                                        <div>
                                            <span class="small text-body-secondary me-2">
                                                Статус:
                                            </span>

                                            <?php if ($isBlocked): ?>

                                                <span class="badge text-bg-danger">
                                                    Заблокирован
                                                </span>

                                            <?php else: ?>

                                                <span class="badge text-bg-success">
                                                    Активен
                                                </span>

                                            <?php endif; ?>
                                        </div>

                                        <?php if (!$isCurrentUser): ?>

                                            <div class="d-flex flex-wrap gap-2">

                                                <?php if ($isBlocked): ?>

                                                    <form
                                                            method="POST"
                                                            action="/admin/users/<?= (int) $user['id'] ?>/unblock"
                                                    >
                                                        <button
                                                                type="submit"
                                                                class="btn btn-sm btn-success"
                                                        >
                                                            Разблокировать
                                                        </button>
                                                    </form>

                                                <?php else: ?>

                                                    <form
                                                            method="POST"
                                                            action="/admin/users/<?= (int) $user['id'] ?>/block"
                                                    >
                                                        <button
                                                                type="submit"
                                                                class="btn btn-sm btn-warning"
                                                        >
                                                            Заблокировать
                                                        </button>
                                                    </form>

                                                <?php endif; ?>

                                                <form
                                                        method="POST"
                                                        action="/admin/users/<?= (int) $user['id'] ?>/delete"
                                                        onsubmit="return confirm('Удалить этого пользователя?');"
                                                >
                                                    <button
                                                            type="submit"
                                                            class="btn btn-sm btn-outline-danger"
                                                    >
                                                        Удалить
                                                    </button>
                                                </form>

                                            </div>

                                        <?php else: ?>

                                            <div class="small text-body-secondary">
                                                Управление собственной учётной записью недоступно.
                                            </div>

                                        <?php endif; ?>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </div>
</section>