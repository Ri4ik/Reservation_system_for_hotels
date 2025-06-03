document.addEventListener('DOMContentLoaded', () => {

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

        if (IS_ADMIN) {
            const user = document.getElementById('search-user').value;
            formData.append('user', user);
        }

        fetch('?c=reservation&a=search', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                const tbody = document.getElementById('reservation-table-body');
                tbody.innerHTML = '';

                if (data.reservations.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="7">Žiadne rezervácie neboli nájdené.</td></tr>';
                    return;
                }

                data.reservations.forEach(res => {
                    const tr = document.createElement('tr');
                    tr.id = 'reservation-' + res.id;

                    // Добавляем CSS-класс в зависимости от статуса
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

    // 🔥 делегирование событий
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
                            searchReservations();
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
                            searchReservations();
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

    // фильтры поиска
    document.getElementById('search-room').addEventListener('input', searchReservations);
    document.getElementById('search-date-from').addEventListener('input', searchReservations);
    document.getElementById('search-date-to').addEventListener('input', searchReservations);
    document.getElementById('search-status').addEventListener('change', searchReservations);

    if (IS_ADMIN) {
        document.getElementById('search-user').addEventListener('input', searchReservations);
    }

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

    searchReservations();
});
