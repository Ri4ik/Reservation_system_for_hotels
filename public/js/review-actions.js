document.addEventListener('DOMContentLoaded', function () {

    const authorInput = document.getElementById('search-author');
    const dateInput = document.getElementById('search-date');

    // Globálna funkcia na vyhľadávanie recenzií (bude sa volať pri filtrovaní alebo po vymazaní)
    window.searchReviews = function () {
        const author = authorInput?.value || '';
        const date = dateInput?.value || '';

        const formData = new FormData();
        if (author) formData.append('author', author);
        if (date) formData.append('date', date);

        // Odoslanie požiadavky na server pre vyhľadanie recenzií
        fetch('?c=review&a=search', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                const reviewsList = document.getElementById('reviews-list');
                reviewsList.innerHTML = '';

                // Ak sa nenašli žiadne recenzie
                if (data.reviews.length === 0) {
                    reviewsList.innerHTML = '<p>Žiadne recenzie neboli nájdené.</p>';
                    return;
                }

                // Zobrazenie priemerného hodnotenia, ak existuje blok
                const avgRatingBlock = document.getElementById('avg-rating');
                if (avgRatingBlock) {
                    avgRatingBlock.innerHTML =
                        `<h3>Priemerné hodnotenie: ${data.avg ?? '0'} ⭐ (${data.total} hlasov)</h3>`;
                }

                // Vždy zobrazíme tlačidlo na pridanie recenzie
                const addBtn = document.createElement('a');
                addBtn.className = 'create-review';
                addBtn.textContent = '➕ Pridať recenziu';
                addBtn.href = data.reviews[0]?.is_logged
                    ? '?c=review&a=create'
                    : '?c=auth&a=login';

                reviewsList.appendChild(addBtn);

                // Vykreslenie každej recenzie do zoznamu
                data.reviews.forEach(review => {
                    const item = document.createElement('div');
                    item.className = 'review-item';
                    item.id = 'review-' + review.id;
                    item.innerHTML = `
                        <p>${review.comment || 'Bez textu'}</p>
                        <div class="review-rating" style="color: gold;">${'★'.repeat(review.rating) + '☆'.repeat(5 - review.rating)}</div>
                        <div class="review-author">— ${review.user_name}</div>
                        <div class="review-time">${review.created_at}</div>
                        ${review.is_admin ? `
                            <a href="?c=review&a=edit&id=${review.id}" class="edit-review">✏️Upraviť</a>
                            <a href="#" class="delete-review" data-id="${review.id}">❌Zmazať</a>
                        ` : ''}
                    `;
                    reviewsList.appendChild(item);
                });

                // Po načítaní znova naviažeme event listenery na tlačidlá zmazania
                bindDeleteHandlers();
            })
            .catch(err => console.error('Chyba pri načítaní recenzií:', err));
    };

    // Pri zmene filtra na meno autora spustí nové vyhľadávanie
    authorInput?.addEventListener('input', searchReviews);
    // Pri zmene dátumu spustí nové vyhľadávanie
    dateInput?.addEventListener('input', searchReviews);

    // Funkcia na naviazanie obsluhy mazania po každom novom vykreslení
    function bindDeleteHandlers() {
        document.querySelectorAll('.delete-review').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const reviewId = this.dataset.id;
                if (!reviewId) {
                    alert('❌ Chyba: ID recenzie chýba!');
                    return;
                }

                if (confirm('Naozaj chcete odstrániť túto recenziu?')) {
                    // Odoslanie požiadavky na server na zmazanie
                    fetch('?c=review&a=ajaxDelete', {
                        method: 'POST',
                        body: new URLSearchParams({review_id: reviewId})
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                const el = document.getElementById('review-' + reviewId);
                                if (el) el.remove();
                                searchReviews(); // Po vymazaní aktualizujeme celý zoznam
                            } else {
                                alert('❌ Chyba: ' + (data.message || 'Nepodarilo sa odstrániť.'));
                            }
                        })
                        .catch(err => {
                            console.error('Chyba pri AJAX požiadavke:', err);
                            alert('❌ Chyba pri spracovaní požiadavky.');
                        });
                }
            });
        });
    }

    // Funkcia pre vyčistenie filtrov
    document.getElementById('clear-filters').addEventListener('click', function () {
        document.getElementById('search-author').value = '';
        document.getElementById('search-date').value = '';
        if (typeof searchReviews === 'function') {
            searchReviews(); // Spustíme nové vyhľadávanie s prázdnymi filtrami
        }
    });

    // Inicializácia pri prvom načítaní — naviazanie delete handlerov
    bindDeleteHandlers();
});
