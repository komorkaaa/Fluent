<?php
/** @var array $featuredCourses */
/** @var array $languageStats */

$greetings = [
    'Английский' => 'Hello!',
    'Немецкий' => 'Hallo!',
    'Испанский' => '¡Hola!',
    'Французский' => 'Bonjour!',
];

$tileColors = ['tile-blue', 'tile-yellow', 'tile-pink', 'tile-lilac', 'tile-mint'];
?>

<section class="hero">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <h1>Выучите язык с живым преподавателем</h1>

                <p class="hero-lead">
                    Английский, немецкий, испанский и французский. Занятия
                    онлайн и очно, мини-группы и понятная программа
                    для каждого уровня.
                </p>

                <div class="d-flex flex-wrap gap-3 mt-4">
                    <a href="/courses" class="btn btn-primary btn-lg">
                        Выбрать курс
                    </a>

                    <a href="#how" class="btn btn-outline-primary btn-lg">
                        Как это работает
                    </a>
                </div>

                <ul class="hero-points">
                    <li><span class="tick"><?= icon('check') ?></span> Курсы для начинающих и продолжающих</li>
                    <li><span class="tick"><?= icon('check') ?></span> Онлайн из дома или очно в центре</li>
                    <li><span class="tick"><?= icon('check') ?></span> Заявка занимает меньше минуты</li>
                </ul>
            </div>

            <div class="col-lg-6 text-center">
                <?php partial('hero-art'); ?>
            </div>
        </div>
    </div>
</section>

<?php if ($languageStats !== []): ?>
    <section class="section">
        <div class="container">
            <div class="section-head">
                <h2>Какой язык хотите выучить?</h2>
                <p>Выберите направление — покажем все курсы по нему.</p>
            </div>

            <div class="row g-4">
                <?php foreach ($languageStats as $index => $language): ?>
                    <?php $count = (int) $language['courses_count']; ?>
                    <div class="col-sm-6 col-lg-3">
                        <a
                            href="/courses?language=<?= (int) $language['id'] ?>"
                            class="lang-tile <?= $tileColors[$index % count($tileColors)] ?>"
                        >
                            <span class="greeting">
                                <?= e($greetings[$language['name']] ?? 'Hi!') ?>
                            </span>

                            <h3><?= e($language['name']) ?></h3>

                            <span class="count">
                                <?= $count ?> <?= plural($count, 'курс', 'курса', 'курсов') ?>
                            </span>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<section class="section section-soft" id="advantages">
    <div class="container">
        <div class="section-head">
            <h2>Почему учиться во Fluent</h2>
            <p>Всё, чтобы вы занимались регулярно и видели результат.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="feature feature-blue">
                    <div class="feature-icon"><?= icon('users', 'icon-lg') ?></div>
                    <h3>Мини-группы</h3>
                    <p>Преподаватель успевает поработать с каждым и поправить ошибки сразу.</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="feature feature-peach">
                    <div class="feature-icon"><?= icon('chat', 'icon-lg') ?></div>
                    <h3>Много разговорной практики</h3>
                    <p>На каждом занятии вы говорите, а не только слушаете объяснения.</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="feature feature-lilac">
                    <div class="feature-icon"><?= icon('video', 'icon-lg') ?></div>
                    <h3>Онлайн и очно</h3>
                    <p>Выбирайте формат под себя: видеоурок из дома или занятие в центре.</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="feature feature-mint">
                    <div class="feature-icon"><?= icon('book', 'icon-lg') ?></div>
                    <h3>Понятная программа</h3>
                    <p>Три уровня — от первых слов до уверенного общения и подготовки к экзаменам.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if ($featuredCourses !== []): ?>
    <section class="section">
        <div class="container">
            <div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
                <div class="section-head mb-0">
                    <h2>Новые курсы</h2>
                    <p>Свежие программы в каталоге.</p>
                </div>

                <a href="/courses" class="btn btn-outline-primary">
                    Весь каталог <?= icon('arrow-right') ?>
                </a>
            </div>

            <div class="row g-4">
                <?php foreach ($featuredCourses as $course): ?>
                    <div class="col-md-6 col-lg-4">
                        <?php partial('course-card', ['course' => $course]); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<section class="section section-soft" id="how">
    <div class="container">
        <div class="section-head">
            <h2>Как начать заниматься</h2>
            <p>Три шага от выбора курса до первого урока.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="step">
                    <div class="step-number">1</div>
                    <h3>Выберите курс</h3>
                    <p>Отфильтруйте каталог по языку, уровню, формату и цене.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="step">
                    <div class="step-number">2</div>
                    <h3>Оставьте заявку</h3>
                    <p>Укажите телефон и e-mail — это займёт меньше минуты.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="step">
                    <div class="step-number">3</div>
                    <h3>Договоритесь о старте</h3>
                    <p>Мы свяжемся с вами, уточним детали и подтвердим дату первого занятия.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section pb-0">
    <div class="container">
        <div class="cta-band">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <h2>Готовы начать говорить?</h2>
                    <p class="mb-4">
                        Выберите курс в каталоге и оставьте заявку — мы свяжемся
                        с вами и ответим на все вопросы.
                    </p>
                    <a href="/courses" class="btn btn-sun btn-lg">Перейти в каталог</a>
                </div>

                <div class="col-lg-4 d-none d-lg-block">
                    <svg viewBox="0 0 320 200" class="w-100 h-auto" aria-hidden="true">
                        <g class="float-1">
                            <rect x="20" y="20" width="200" height="84" rx="42" fill="#fff"/>
                            <path d="M70 100l-10 30 36-22z" fill="#fff"/>
                            <text x="120" y="73" text-anchor="middle" font-family="Manrope, Arial, sans-serif" font-weight="800" font-size="30" fill="#1b5fd9">Let’s start!</text>
                        </g>
                        <g class="float-2">
                            <rect x="170" y="118" width="130" height="62" rx="31" fill="#ffd84d"/>
                            <path d="M262 176l12 20-34-14z" fill="#ffd84d"/>
                            <text x="235" y="158" text-anchor="middle" font-family="Manrope, Arial, sans-serif" font-weight="800" font-size="26" fill="#14213d">¡Vamos!</text>
                        </g>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section" id="contacts">
    <div class="container">
        <div class="section-head">
            <h2>Контакты</h2>
            <p>Позвоните или напишите — подскажем, какой курс вам подойдёт.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="contact-item">
                    <span class="ci-icon"><?= icon('pin') ?></span>
                    <div>
                        <div class="ci-label">Адрес</div>
                        <div class="ci-value">г. Нижний Новгород,<br>ул. Большая Покровская, 1</div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="contact-item">
                    <span class="ci-icon"><?= icon('phone') ?></span>
                    <div>
                        <div class="ci-label">Телефон</div>
                        <div class="ci-value"><a href="tel:+78310000000">+7 (831) 000-00-00</a></div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="contact-item">
                    <span class="ci-icon"><?= icon('mail') ?></span>
                    <div>
                        <div class="ci-label">E-mail</div>
                        <div class="ci-value"><a href="mailto:info@fluent.local">info@fluent.local</a></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <a href="/contacts" class="btn btn-outline-primary">Все контакты и карта</a>
        </div>
    </div>
</section>
