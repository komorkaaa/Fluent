<section class="py-5">
    <div class="container">

        <div class="mb-5">
            <h1 class="display-5 fw-bold">
                Запись на курс
            </h1>

            <p class="lead text-body-secondary">
                <?= htmlspecialchars($course['name']) ?>
            </p>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-7">

                <form
                        method="POST"
                        action="/courses/<?= (int) $course['id'] ?>

                                <input
                                        type="hidden"
                                        name="csrf_token"
                                        value="<?= htmlspecialchars(\App\Core\Auth::csrfToken()) ?>"
                                >/apply"
                >

                    <?php if ($user !== null): ?>

                        <div class="mb-3">
                            <label class="form-label">
                                Имя
                            </label>

                            <input
                                    type="text"
                                    class="form-control"
                                    value="<?= htmlspecialchars($user['name']) ?>"
                                    readonly
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Email
                            </label>

                            <input
                                    type="email"
                                    class="form-control"
                                    value="<?= htmlspecialchars($user['email']) ?>"
                                    readonly
                            >
                        </div>

                    <?php else: ?>

                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Имя
                            </label>

                            <input
                                    type="text"
                                    class="form-control"
                                    id="name"
                                    name="name"
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
                                    value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                                    required
                            >
                        </div>

                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="phone" class="form-label">
                            Телефон
                        </label>

                        <input
                                type="tel"
                                class="form-control"
                                id="phone"
                                name="phone"
                                autocomplete="tel"
                                value="<?= htmlspecialchars($old['phone'] ?? '') ?>"
                                required
                        >
                    </div>

                    <div class="mb-4">
                        <label for="comment" class="form-label">
                            Комментарий
                        </label>

                        <textarea
                                class="form-control"
                                id="comment"
                                name="comment"
                                rows="4"
                        ><?= htmlspecialchars($old['comment'] ?? '') ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Отправить заявку
                    </button>

                    <a
                            href="/courses/<?= (int) $course['id'] ?>"
                            class="btn btn-outline-secondary ms-2"
                    >
                        Назад к курсу
                    </a>

                </form>

            </div>
        </div>

    </div>
</section>