<?php
/** @var array $result */
/** @var array|null $user */
?>

<section class="section">
    <div class="container">
        <div class="error-page py-4">
            <svg viewBox="0 0 360 240" aria-hidden="true">
                <circle cx="180" cy="120" r="104" fill="#dcf7ee"/>
                <rect x="96" y="70" width="168" height="84" rx="42" fill="#fff"/>
                <path d="M130 148l-14 36 44-26z" fill="#fff"/>
                <circle cx="180" cy="112" r="26" fill="#3dd6a5"/>
                <path d="m168 112 9 9 17-18" stroke="#fff" stroke-width="6" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="62" cy="62" r="14" fill="#ffd84d"/>
                <circle cx="306" cy="176" r="10" fill="#ff7aa8"/>
                <path d="M300 46l5 12 12 5-12 5-5 12-5-12-12-5 12-5z" fill="#2f7bff" opacity=".6"/>
            </svg>

            <h1 class="h2 mb-3">Заявка №<?= (int) $result['id'] ?> отправлена</h1>

            <p class="lead text-body-secondary mx-auto mb-4" style="max-width: 32rem;">
                Мы получили вашу заявку на курс «<?= e($result['course_name']) ?>»
                и скоро свяжемся с вами.
            </p>

            <div class="d-flex flex-wrap justify-content-center gap-3">
                <?php if ($user !== null): ?>
                    <a href="/profile" class="btn btn-primary">Мои заявки</a>
                <?php endif; ?>

                <a href="/courses" class="btn <?= $user !== null ? 'btn-outline-primary' : 'btn-primary' ?>">
                    Вернуться к курсам
                </a>
            </div>
        </div>
    </div>
</section>
