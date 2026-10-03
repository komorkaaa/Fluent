<section class="py-5">
    <div class="container">

        <div class="mb-5">
            <h1 class="display-5 fw-bold">
                Личный кабинет
            </h1>

            <p class="lead text-body-secondary">
                Добро пожаловать, <?= htmlspecialchars($user['name']) ?>.
            </p>
        </div>

        <div class="card mb-5">
            <div class="card-body">

                <h2 class="h5 mb-4">
                    Профиль
                </h2>

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

                <form method="POST" action="/profile">

                    <div class="mb-3">
                        <label for="name" class="form-label">
                            Имя
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="name"
                            name="name"
                            value="<?= htmlspecialchars($user['name']) ?>"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">
                            Email
                        </label>

                        <input
                            type="email"
                            class="form-control"
                            id="email"
                            name="email"
                            value="<?= htmlspecialchars($user['email']) ?>"
                            required
                        >
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Сохранить изменения
                    </button>

                </form>

            </div>
        </div>

        <div>
            <h2 class="h4 mb-4">
                Мои заявки
            </h2>

            <?php if ($applications === []): ?>

                <div class="alert alert-secondary">
                    У вас пока нет заявок.
                </div>

            <?php else: ?>

                <div class="table-responsive">

                    <table class="table table-bordered align-middle">

                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Курс</th>
                            <th>Дата</th>
                            <th>Статус</th>
                        </tr>
                        </thead>

                        <tbody>

                        <?php foreach ($applications as $application): ?>

                            <tr>
                                <td>
                                    #<?= (int) $application['id'] ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $application['course_name']
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $application['created_at']
                                    ) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars(
                                        $application['status']
                                    ) ?>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            <?php endif; ?>

        </div>

    </div>
</section>