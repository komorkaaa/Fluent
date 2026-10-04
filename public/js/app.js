// Fluent — небольшие улучшения интерфейса. Вся важная логика и валидация — на сервере.

document.addEventListener('DOMContentLoaded', () => {
    // Подтверждение опасных действий: <form data-confirm="Текст вопроса">
    document.querySelectorAll('form[data-confirm]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (!window.confirm(form.dataset.confirm)) {
                event.preventDefault();
            }
        });
    });

    // Совпадение паролей на форме регистрации (подсказка; проверка есть и на сервере)
    const password = document.getElementById('password');
    const confirmation = document.getElementById('password_confirmation');

    if (password && confirmation) {
        const check = () => {
            confirmation.setCustomValidity(
                confirmation.value !== '' && confirmation.value !== password.value
                    ? 'Пароли не совпадают'
                    : ''
            );
        };

        password.addEventListener('input', check);
        confirmation.addEventListener('input', check);
    }

    // Показать/скрыть пароль: <button data-toggle-password="id_поля">
    document.querySelectorAll('[data-toggle-password]').forEach((button) => {
        button.addEventListener('click', () => {
            const input = document.getElementById(button.dataset.togglePassword);

            if (!input) {
                return;
            }

            const visible = input.type === 'text';
            input.type = visible ? 'password' : 'text';
            button.textContent = visible ? 'Показать' : 'Скрыть';
            button.setAttribute('aria-pressed', String(!visible));
        });
    });
});
