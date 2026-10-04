<section class="page-head">
    <div class="container">
        <h1>Контакты</h1>
        <p>Приходите в центр, звоните или пишите — поможем выбрать курс.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="row g-5">

            <div class="col-lg-5">
                <div class="d-grid gap-4">
                    <div class="contact-item">
                        <span class="ci-icon"><?= icon('pin') ?></span>
                        <div>
                            <div class="ci-label">Адрес</div>
                            <div class="ci-value">г. Нижний Новгород,<br>ул. Большая Покровская, 1</div>
                        </div>
                    </div>

                    <div class="contact-item">
                        <span class="ci-icon"><?= icon('phone') ?></span>
                        <div>
                            <div class="ci-label">Телефон</div>
                            <div class="ci-value"><a href="tel:+78310000000">+7 (831) 000-00-00</a></div>
                        </div>
                    </div>

                    <div class="contact-item">
                        <span class="ci-icon"><?= icon('mail') ?></span>
                        <div>
                            <div class="ci-label">E-mail</div>
                            <div class="ci-value"><a href="mailto:info@fluent.local">info@fluent.local</a></div>
                        </div>
                    </div>

                    <div class="contact-item">
                        <span class="ci-icon"><?= icon('clock') ?></span>
                        <div>
                            <div class="ci-label">Часы работы</div>
                            <div class="ci-value">Пн–Сб, 9:00–20:00<br>Воскресенье — выходной</div>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-3 mt-5">
                    <a href="/courses" class="btn btn-primary">Записаться на курс</a>
                    <a
                        href="https://www.openstreetmap.org/search?query=Нижний%20Новгород%2C%20Большая%20Покровская%2C%201"
                        class="btn btn-outline-primary"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        Открыть на карте
                    </a>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="map-card">
                    <svg viewBox="0 0 640 440" role="img" aria-label="Схема расположения: Fluent, ул. Большая Покровская, 1, Нижний Новгород">
                        <rect width="640" height="440" fill="#e8f0fb"/>

                        <!-- река и парк -->
                        <path d="M-10 392C130 340 270 410 420 372S600 340 660 352" stroke="#bcd7ff" stroke-width="44" fill="none"/>
                        <rect x="34" y="34" width="150" height="104" rx="22" fill="#cdefd9"/>
                        <circle cx="70" cy="70" r="12" fill="#a6e0bd"/>
                        <circle cx="110" cy="96" r="16" fill="#a6e0bd"/>
                        <circle cx="152" cy="64" r="10" fill="#a6e0bd"/>
                        <rect x="470" y="262" width="130" height="70" rx="20" fill="#cdefd9"/>

                        <!-- кварталы -->
                        <g fill="#f7faff">
                            <rect x="210" y="40" width="80" height="70" rx="10"/>
                            <rect x="350" y="36" width="110" height="64" rx="10"/>
                            <rect x="500" y="40" width="100" height="80" rx="10"/>
                            <rect x="30" y="170" width="100" height="70" rx="10"/>
                            <rect x="160" y="168" width="110" height="64" rx="10"/>
                            <rect x="360" y="170" width="100" height="40" rx="10"/>
                            <rect x="520" y="170" width="80" height="60" rx="10"/>
                            <rect x="40" y="290" width="90" height="56" rx="10"/>
                            <rect x="170" y="290" width="110" height="60" rx="10"/>
                            <rect x="350" y="290" width="90" height="50" rx="10"/>
                        </g>

                        <!-- дороги -->
                        <g fill="none" stroke="#fff" stroke-linecap="round">
                            <path d="M-10 268L650 226" stroke-width="26"/>
                            <path d="M296 -10L334 450" stroke-width="26"/>
                            <path d="M-10 142L650 160" stroke-width="14"/>
                            <path d="M196 -10L180 450" stroke-width="14"/>
                            <path d="M470 -10L500 450" stroke-width="14"/>
                        </g>
                        <path d="M-10 268L650 226" stroke="#f3d37a" stroke-width="2" stroke-dasharray="10 12" fill="none"/>

                        <!-- метка -->
                        <g transform="translate(318 244)">
                            <ellipse cx="0" cy="0" rx="30" ry="10" fill="#2f7bff" opacity=".22"/>
                            <path d="M0 0C0 0-30-34-30-56a30 30 0 1 1 60 0C30-34 0 0 0 0z" fill="#2f7bff"/>
                            <circle cx="0" cy="-56" r="12" fill="#fff"/>
                        </g>

                        <!-- подпись -->
                        <g transform="translate(352 92)">
                            <rect width="240" height="70" rx="18" fill="#fff" stroke="#e4eaf5"/>
                            <text x="20" y="31" font-family="Manrope, Arial, sans-serif" font-weight="800" font-size="20" fill="#14213d">Fluent</text>
                            <text x="20" y="54" font-family="Manrope, Arial, sans-serif" font-weight="600" font-size="14" fill="#6b7a94">ул. Большая Покровская, 1</text>
                        </g>
                    </svg>
                </div>

                <p class="form-text mt-2 mb-0">Схема носит ориентировочный характер.</p>
            </div>

        </div>
    </div>
</section>
