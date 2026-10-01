<section class="py-5">
    <div class="container">

        <div class="alert alert-success">
            <h1 class="h4">
                Заявка успешно отправлена
            </h1>

            <p class="mb-0">
                Мы получили вашу заявку на курс
                «<?= htmlspecialchars($course['name']) ?>».
            </p>
        </div>

        <a href="/courses" class="btn btn-primary">
            Вернуться к курсам
        </a>

    </div>
</section>