<section class="auth-wrap">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-5">

                <div class="text-center mb-4">
                    <h1 class="h2">Создайте аккаунт</h1>
                    <p class="text-body-secondary mb-0">Так заявки и данные всегда будут под рукой.</p>
                </div>

                <?php partial('errors', ['errors' => $errors ?? []]); ?>

                <form method="POST" action="/register" class="form-card" novalidate>
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="name" class="form-label">Имя</label>
                        <input
                            type="text"
                            class="form-control"
                            id="name"
                            name="name"
                            autocomplete="name"
                            maxlength="255"
                            value="<?= e($old['name'] ?? '') ?>"
                            required
                            autofocus
                        >
                    </div>

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
                        >
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Пароль</label>
                        <input
                            type="password"
                            class="form-control"
                            id="password"
                            name="password"
                            autocomplete="new-password"
                            minlength="6"
                            required
                        >
                        <div class="form-text">Не короче 6 символов.</div>
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Повторите пароль</label>
                        <input
                            type="password"
                            class="form-control"
                            id="password_confirmation"
                            name="password_confirmation"
                            autocomplete="new-password"
                            required
                        >
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100">Зарегистрироваться</button>

                    <p class="form-text mt-3 mb-0">
                        Регистрируясь, вы соглашаетесь с
                        <a href="/privacy">политикой обработки персональных данных</a>.
                    </p>
                </form>

                <p class="text-center mt-4 mb-0">
                    Уже есть аккаунт?
                    <a href="/login" class="fw-bold">Войти</a>
                </p>

            </div>
        </div>
    </div>
</section>
