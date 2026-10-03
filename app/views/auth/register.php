<section class="py-5">
    <div class="container">

        <div class="row justify-content-center">

            <div class="col-md-7 col-lg-5">

                <div class="mb-4">
                    <h1 class="h2 fw-bold">
                        Регистрация
                    </h1>

                    <p class="text-body-secondary">
                        Создайте аккаунт Fluent.
                    </p>
                </div>

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

                <form method="POST" action="/register">

                                <input
                                        type="hidden"
                                        name="csrf_token"
                                        value="<?= htmlspecialchars(\App\Core\Auth::csrfToken()) ?>"
                                >

                    <div class="mb-3">
                        <label for="name" class="form-label">
                            Имя
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="name"
                            name="name"
                            autocomplete="name"
                            value="<?= htmlspecialchars($old['name'] ?? '') ?>"
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
                            autocomplete="email"
                            value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">
                            Пароль
                        </label>

                        <input
                            type="password"
                            class="form-control"
                            id="password"
                            name="password"
                            autocomplete="new-password"
                            required
                        >
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">
                            Подтверждение пароля
                        </label>

                        <input
                            type="password"
                            class="form-control"
                            id="password_confirmation"
                            name="password_confirmation"
                            autocomplete="new-password"
                            required
                        >
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Зарегистрироваться
                    </button>

                </form>

                <p class="text-center mt-4">
                    Уже есть аккаунт?
                    <a href="/login">Войти</a>
                </p>

            </div>

        </div>

    </div>
</section>