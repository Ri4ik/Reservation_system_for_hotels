document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    if (!form) return;

    const dateFromInput = form.querySelector('input[name="date_from"]');
    const dateToInput = form.querySelector('input[name="date_to"]');
    const roomIdInput = form.querySelector('select[name="room_id"], input[name="room_id"]');

    [dateFromInput, dateToInput, roomIdInput].forEach(input => {
        input.addEventListener('change', async () => {
            await checkAvailability();
        });
    });
    async function checkAvailability() {
        let errorBox = form.querySelector('.validate-error');
        if (!errorBox) {
            errorBox = document.createElement('p');
            errorBox.classList.add('validate-error');
            form.prepend(errorBox);
        }

        const dateFrom = dateFromInput.value;
        const dateTo = dateToInput.value;
        const roomId = roomIdInput.value;

        if (!dateFrom || !dateTo || !roomId) {
            errorBox.textContent = '';
            return;
        }
        try {
            const response = await fetch(`?c=reservation&a=checkAvailability&room_id=${roomId}&date_from=${dateFrom}&date_to=${dateTo}`);
            const result = await response.json();

            if (!result.available) {
                errorBox.textContent = 'Na zvolené dátumy už existuje potvrdená rezervácia.';
            } else {
                errorBox.textContent = '';
            }
        } catch (err) {
            console.error(err);
            errorBox.textContent = 'Chyba pri kontrole dostupnosti.';
        }
    }

    form.addEventListener('submit', async function (e) {
        e.preventDefault();  // всегда блокируем стандартную отправку формы!

        let errorMessage = '';

        try {
            const dateFromInput = form.querySelector('input[name="date_from"]');
            const dateToInput = form.querySelector('input[name="date_to"]');
            const roomIdInput = form.querySelector('select[name="room_id"], input[name="room_id"]');

            if (!dateFromInput || !dateToInput || !roomIdInput) {
                errorMessage = 'Chýbajúce polia vo formulári!';
                throw new Error('Niektoré polia neboli nájdené v DOM.');
            }

            const dateFrom = dateFromInput.value;
            const dateTo = dateToInput.value;
            const roomId = roomIdInput.value;
            const today = new Date().toISOString().split('T')[0];

            if (!roomId || !dateFrom || !dateTo) {
                errorMessage = 'Všetky polia musia byť vyplnené!';
            } else if (dateFrom < today) {
                errorMessage = 'Dátum od nemôže byť v minulosti!';
            } else if (dateTo <= dateFrom) {
                errorMessage = 'Dátum do musí byť neskorší ako dátum od!';
            } else {
                const response = await fetch(`?c=room&a=checkRoom&id=${roomId}`);
                const result = await response.json();
                if (!result.exists) {
                    errorMessage = 'Zvolená izba neexistuje!';
                }
                if (!errorMessage) {
                    const availability = await fetch(`?c=reservation&a=checkAvailability&room_id=${roomId}&date_from=${dateFrom}&date_to=${dateTo}`);
                    const availResult = await availability.json();
                    if (!availResult.available) {
                        errorMessage = 'Na zvolené dátumy už existuje potvrdená rezervácia.';
                    }
                }
            }
        } catch (err) {
            console.error('Chyba pri spracovaní validácie:', err);
            if (!errorMessage) {
                errorMessage = 'Nastala neočakávaná chyba pri validácii.';
            }
        }

        // Показываем ошибку или отправляем форму
        let errorBox = form.querySelector('.validate-error');
        if (!errorBox) {
            errorBox = document.createElement('p');
            errorBox.classList.add('validate-error');
            form.prepend(errorBox);
        }

        if (errorMessage !== '') {
            errorBox.textContent = errorMessage;
        } else {
            errorBox.textContent = '';  // очищаем старую ошибку

            // отправляем форму вручную после успешной валидации
            form.submit();
        }
    });
});
