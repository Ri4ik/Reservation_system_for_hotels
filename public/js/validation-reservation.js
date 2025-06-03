document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    if (!form) return;

    const dateFromInput = form.querySelector('input[name="date_from"]');
    const dateToInput = form.querySelector('input[name="date_to"]');
    const roomIdInput = form.querySelector('select[name="room_id"], input[name="room_id"]');
    let unavailableDates = [];

    // Flatpickr инициализация
    const dateFromPicker = flatpickr(dateFromInput, {
        dateFormat: "Y-m-d",
        disable: [],
        disableMobile: true,
        onDayCreate: decorateDays
    });

    const dateToPicker = flatpickr(dateToInput, {
        dateFormat: "Y-m-d",
        disable: [],
        disableMobile: true,
        onDayCreate: decorateDays
    });

    async function loadUnavailableDates() {
        const roomId = roomIdInput.value;
        if (!roomId) return;

        try {
            const response = await fetch(`?c=reservation&a=getUnavailableDates&room_id=${roomId}`);
            const result = await response.json();
            unavailableDates = result.unavailable.map(dateStr => dateStr);

            dateFromPicker.set('disable', unavailableDates);
            dateToPicker.set('disable', unavailableDates);

            dateFromPicker.redraw();
            dateToPicker.redraw();
        } catch (err) {
            console.error('Chyba pri načítaní obsadených dátumov:', err);
        }
    }

    roomIdInput.addEventListener('change', loadUnavailableDates);
    loadUnavailableDates();


    function formatDateToYMD(date) {
        return date.getFullYear() + "-" +
            String(date.getMonth() + 1).padStart(2, '0') + "-" +
            String(date.getDate()).padStart(2, '0');
    }
    // Подсветка заблокированных дат (кроме disable)
    function decorateDays(_, __, fp, dayElem) {
        const day = dayElem.dateObj;
        const dayStr = formatDateToYMD(day);  // <-- вместо toISOString()
        if (unavailableDates.includes(dayStr)) {
            dayElem.classList.add("flatpickr-disabled-highlight");
        }
    }


    async function validate() {
        const dateFrom = dateFromInput.value;
        const dateTo = dateToInput.value;
        const roomId = roomIdInput.value;
        const today = new Date().toISOString().split('T')[0];

        if (!roomId || !dateFrom || !dateTo) {
            return 'Všetky polia musia byť správne vyplnené!';
        }
        if (dateFrom < today) {
            return 'Dátum od nemôže byť v minulosti!';
        }
        if (dateTo <= dateFrom) {
            return 'Dátum do musí byť neskorší ako dátum od!';
        }

        const roomCheck = await fetch(`?c=room&a=checkRoom&id=${roomId}`);
        const roomResult = await roomCheck.json();
        if (!roomResult.exists) {
            return 'Zvolená izba neexistuje!';
        }

        const availability = await fetch(`?c=reservation&a=checkAvailability&room_id=${roomId}&date_from=${dateFrom}&date_to=${dateTo}`);
        const availResult = await availability.json();
        if (!availResult.available) {
            return 'Na zvolené dátumy už existuje potvrdená rezervácia.';
        }

        return '';
    }

    function showError(message) {
        let errorBox = form.querySelector('.validate-error');
        if (!errorBox) {
            errorBox = document.createElement('p');
            errorBox.classList.add('validate-error');
            form.prepend(errorBox);
        }
        errorBox.textContent = message;
        errorBox.style.display = message ? 'block' : 'none';
    }

    function clearError() {
        const errorBox = form.querySelector('.validate-error');
        if (errorBox) {
            errorBox.textContent = '';
            errorBox.style.display = 'none';
        }
    }

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        const error = await validate();
        if (error) {
            showError(error);
        } else {
            clearError();
            form.submit();
        }
    });
});
