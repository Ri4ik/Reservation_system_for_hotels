document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    if (!form) return;

    // Načítanie polí formulára
    const dateFromInput = form.querySelector('input[name="date_from"]');
    const dateToInput = form.querySelector('input[name="date_to"]');
    const roomIdInput = form.querySelector('select[name="room_id"], input[name="room_id"]');
    let unavailableDates = [];  // Pole obsadených dátumov

    // Inicializácia kalendárov pomocou Flatpickr
    const dateFromPicker = flatpickr(dateFromInput, {
        dateFormat: "Y-m-d",
        disable: [],  // Zoznam blokovaných dátumov (nastavujeme neskôr)
        disableMobile: true,
        onDayCreate: decorateDays  // Funkcia na zvýraznenie blokovaných dní
    });

    const dateToPicker = flatpickr(dateToInput, {
        dateFormat: "Y-m-d",
        disable: [],
        disableMobile: true,
        onDayCreate: decorateDays
    });

    // Funkcia na načítanie obsadených dátumov pre danú izbu
    async function loadUnavailableDates() {
        const roomId = roomIdInput.value;
        if (!roomId) return;

        try {
            const response = await fetch(`?c=reservation&a=getUnavailableDates&room_id=${roomId}`);
            const result = await response.json();
            unavailableDates = result.unavailable.map(dateStr => dateStr);

            // Nastavenie zakázaných dátumov pre oba kalendáre
            dateFromPicker.set('disable', unavailableDates);
            dateToPicker.set('disable', unavailableDates);

            // Redraw nutný na zobrazenie zvýraznenia
            dateFromPicker.redraw();
            dateToPicker.redraw();
        } catch (err) {
            console.error('Chyba pri načítaní obsadených dátumov:', err);
        }
    }

    // Načítame dátumy pri zmene izby
    roomIdInput.addEventListener('change', loadUnavailableDates);
    loadUnavailableDates();

    // Pomocná funkcia na formátovanie dátumu na Y-m-d
    function formatDateToYMD(date) {
        return date.getFullYear() + "-" +
            String(date.getMonth() + 1).padStart(2, '0') + "-" +
            String(date.getDate()).padStart(2, '0');
    }

    // Funkcia na zvýraznenie blokovaných dní priamo v kalendári
    function decorateDays(_, __, fp, dayElem) {
        const day = dayElem.dateObj;
        const dayStr = formatDateToYMD(day);
        if (unavailableDates.includes(dayStr)) {
            dayElem.classList.add("flatpickr-disabled-highlight");
        }
    }

    // Hlavná validačná funkcia (volá sa pri odoslaní formulára)
    async function validate() {
        const dateFrom = dateFromInput.value;
        const dateTo = dateToInput.value;
        const roomId = roomIdInput.value;
        const today = new Date().toISOString().split('T')[0];

        // Základné overenie vstupov
        if (!roomId || !dateFrom || !dateTo) {
            return 'Všetky polia musia byť správne vyplnené!';
        }
        if (dateFrom < today) {
            return 'Dátum od nemôže byť v minulosti!';
        }
        if (dateTo <= dateFrom) {
            return 'Dátum do musí byť neskorší ako dátum od!';
        }

        // Overenie, či existuje izba (asynchrónne volanie servera)
        const roomCheck = await fetch(`?c=room&a=checkRoom&id=${roomId}`);
        const roomResult = await roomCheck.json();
        if (!roomResult.exists) {
            return 'Zvolená izba neexistuje!';
        }

        // Overenie dostupnosti dátumov na serveri
        const availability = await fetch(`?c=reservation&a=checkAvailability&room_id=${roomId}&date_from=${dateFrom}&date_to=${dateTo}`);
        const availResult = await availability.json();
        if (!availResult.available) {
            return 'Na zvolené dátumy už existuje potvrdená rezervácia.';
        }

        return '';
    }

    // Funkcia na zobrazenie chybového hlásenia
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

    // Vyčistenie chybového hlásenia
    function clearError() {
        const errorBox = form.querySelector('.validate-error');
        if (errorBox) {
            errorBox.textContent = '';
            errorBox.style.display = 'none';
        }
    }

    // Spracovanie samotného submitu formulára
    form.addEventListener('submit', async function (e) {
        e.preventDefault();  // Zablokujeme štandardné odoslanie

        const error = await validate();  // Spustíme asynchrónnu validáciu
        if (error) {
            showError(error);  // Ak je chyba, zobrazíme ju
        } else {
            clearError();
            form.submit();  // Ak všetko OK, reálne odošleme formulár
        }
    });
});
