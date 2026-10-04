<section class="auth-wrap">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-5">

                <div class="text-center mb-4">
                    <h1 class="h2">Вход в Fluent</h1>
                    <p class="text-body-secondary mb-0">Войдите, чтобы видеть свои заявки.</p>
                </div>

                <?php partial('errors', ['errors' => $errors ?? []]); ?>

                <form method="POST" action="/login" class="form-card" novalidate>
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input
                            type="email"
                            class="form-control"
                            id="email"
                            name="email"
                            autocomplete="email"
                            maxlength="255"
                            value="<?= e($old['email'] ?? '') ?>"
                            required
                            autofocus
                        >
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">Пароль</label>
                        <input
                            type="password"
                            class="form-control"
                            id="password"
                            name="password"
                            autocomplete="current-password"
                            required
                        >
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100">Войти</button>
                </form>

                <p class="text-center mt-4 mb-0">
                    Нет аккаунта?
                    <a href="/register" class="fw-bold">Зарегистрироваться</a>
                </p>

            </div>
        </div>
    </div>
</section>
