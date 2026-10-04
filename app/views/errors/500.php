<?php /** @var string|null $details */ ?>
<section class="container error-page">
    <svg viewBox="0 0 360 240" aria-hidden="true">
        <circle cx="180" cy="120" r="104" fill="#ffe7ea"/>
        <rect x="96" y="70" width="168" height="84" rx="42" fill="#fff"/>
        <path d="M130 148l-14 36 44-26z" fill="#fff"/>
        <path d="M150 104l20 20m0-20-20 20M190 104l20 20m0-20-20 20" stroke="#ff5a6e" stroke-width="6" stroke-linecap="round"/>
        <circle cx="64" cy="70" r="14" fill="#ffd84d"/>
        <circle cx="304" cy="182" r="10" fill="#2f7bff"/>
    </svg>

    <div class="error-code">500</div>
    <h1 class="h2 mt-2 mb-3">Что-то пошло не так</h1>

    <p class="text-body-secondary mx-auto mb-4" style="max-width: 30rem;">
        На сервере произошла ошибка. Мы уже в курсе — попробуйте обновить
        страницу чуть позже.
    </p>

    <a href="/" class="btn btn-primary btn-lg">На главную</a>

    <?php if (!empty($details)): ?>
        <pre class="text-start small mt-5 p-3 bg-body-tertiary rounded overflow-auto"><?= e($details) ?></pre>
    <?php endif; ?>
</section>
