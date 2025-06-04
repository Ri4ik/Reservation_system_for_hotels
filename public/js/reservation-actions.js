document.addEventListener('DOMContentLoaded', () => {

    // Funkcia na vykonanie vyhľadávania rezervácií na základe filtrov
    function searchReservations() {
        const room = document.getElementById('search-room').value;
        const dateFrom = document.getElementById('search-date-from').value;
        const dateTo = document.getElementById('search-date-to').value;
        const status = document.getElementById('search-status').value;

        const formData = new FormData();
        formData.append('room', room);
        formData.append('date_from', dateFrom);
        formData.append('date_to', dateTo);
        formData.append('status', status);

        // Ak je užívateľ admin, pridáva sa aj filter na užívateľa
        if (IS_ADMIN) {
            const user = document.getElementById('search-user').value;
            formData.append('user', user);
        }

        // Odosielanie požiadavky na server na vyhľadanie rezervácií
        fetch('?c=reservation&a=search', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                const tbody = document.getElementById('reservation-table-body');
                tbody.innerHTML = '';

                // Ak žiadne výsledky, zobrazí sa info o prázdnom zozname
                if (data.reservations.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="7">Žiadne rezervácie neboli nájdené.</td></tr>';
                    return;
                }

                // Vykreslenie každej rezervácie do tabuľky
                data.reservations.forEach(res => {
                    const tr = document.createElement('tr');
                    tr.id = 'reservation-' + res.id;

                    // Pridanie CSS triedy podľa stavu rezervácie
                    let statusClass = '';
                    if (res.status === 'čaká na schválenie') {
                        statusClass = 'status-pending';
                    } else if (res.status === 'potvrdená') {
                        statusClass = 'status-confirmed';
                    } else if (res.status === 'zrušená') {
                        statusClass = 'status-cancelled';
                    }
                    tr.classList.add(statusClass);

                    let html = '';

                    // Ak je admin, zobrazí sa meno a email zákazníka
                    if (IS_ADMIN) {
                        html += `<td data-label="Meno zákazníka">${res.user_name}</td>`;
                        html += `<td data-label="Email">${res.user_email}</td>`;
                    }

                    html += `
                        <td data-label="Izba">${res.room_name}</td>
                        <td data-label="Od">${res.check_in}</td>
                        <td data-label="Do">${res.check_out}</td>
                        <td data-label="Stav">${res.status}</td>
                        <td data-label="Akcie">
                    `;

                    // Ak je rezervácia v stave čaká na schválenie, zobrazia sa akcie
                    if (res.status === 'čaká na schválenie') {
                        if (IS_ADMIN) {
                            html += `<a href="#" class="confirm-reservation" data-id="${res.id}">✅</a>
                                     <a href="#" class="cancel-reservation" data-id="${res.id}">❌</a>`;
                        }
                        html += `<a href="?c=reservation&a=edit&id=${res.id}">✏️</a>
                                 <a href="?c=reservation&a=delete&id=${res.id}" onclick="return confirm('Naozaj zrušiť rezerváciu?')">🗑️</a>`;
                    }

                    html += '</td>';
                    tr.innerHTML = html;
                    tbody.appendChild(tr);
                });
            });
    }

    // Delegovanie udalostí na potvrdenie alebo zrušenie rezervácie
    document.getElementById('reservation-table-body').addEventListener('click', function (e) {
        if (e.target.classList.contains('confirm-reservation')) {
            e.preventDefault();
            const id = e.target.dataset.id;
            if (confirm('Naozaj chcete potvrdiť rezerváciu?')) {
                const formData = new FormData();
                formData.append('id', id);

                fetch(`?c=reservation&a=confirm`, {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin'
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            searchReservations(); // Po úspešnom potvrdení znova načíta tabuľku
                        } else {
                            alert('Nepodarilo sa potvrdiť rezerváciu. ' +  data.message);
                        }
                    })
                    .catch(err => {
                        console.error('Chyba pri potvrdení:', err);
                    });
            }
        }

        if (e.target.classList.contains('cancel-reservation')) {
            e.preventDefault();
            const id = e.target.dataset.id;

            if (confirm('Naozaj chcete zrušiť rezerváciu?')) {
                const formData = new FormData();
                formData.append('id', id);
                fetch(`?c=reservation&a=cancel`, {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin'
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            searchReservations(); // Po úspešnom zrušení znova načíta tabuľku
                        } else {
                            alert('Nepodarilo sa zrušiť rezerváciu.');
                        }
                    })
                    .catch(err => {
                        console.error('Chyba pri zrušení:', err);
                    });
            }
        }
    });

    // Nastavenie filtrov vyhľadávania na zmenu
    document.getElementById('search-room').addEventListener('input', searchReservations);
    document.getElementById('search-date-from').addEventListener('input', searchReservations);
    document.getElementById('search-date-to').addEventListener('input', searchReservations);
    document.getElementById('search-status').addEventListener('change', searchReservations);

    if (IS_ADMIN) {
        document.getElementById('search-user').addEventListener('input', searchReservations);
    }

    // Resetovanie filtrov na pôvodné hodnoty
    document.getElementById('clear-filters').addEventListener('click', () => {
        document.getElementById('search-room').value = '';
        document.getElementById('search-date-from').value = '';
        document.getElementById('search-date-to').value = '';
        document.getElementById('search-status').value = '';
        if (IS_ADMIN) {
            document.getElementById('search-user').value = '';
        }
        searchReservations();
    });

    // Načítanie rezervácií hneď pri načítaní stránky
    searchReservations();
});
