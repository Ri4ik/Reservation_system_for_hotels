document.addEventListener('DOMContentLoaded', function () {
    const authorInput = document.getElementById('search-author');
    const dateInput = document.getElementById('search-date');

    // Глобальна функція — доступна для видалення
    window.searchReviews = function () {
        const author = authorInput?.value || '';
        const date = dateInput?.value || '';

        const formData = new FormData();
        if (author) formData.append('author', author);
        if (date) formData.append('date', date);

        fetch('?c=review&a=search', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                const reviewsList = document.getElementById('reviews-list');
                reviewsList.innerHTML = '';

                if (data.reviews.length === 0) {
                    reviewsList.innerHTML = '<p>Žiadne recenzie neboli nájdené.</p>';
                    return;
                }

                const avgRatingBlock = document.getElementById('avg-rating');
                if (avgRatingBlock) {
                    avgRatingBlock.innerHTML =
                        `<h3>Priemerné hodnotenie: ${data.avg ?? '0'} ⭐ (${data.total} hlasov)</h3>`;
                }

                // Завжди показувати кнопку "Pridať recenziu"
                const addBtn = document.createElement('a');
                addBtn.className = 'create-review';
                addBtn.textContent = '➕ Pridať recenziu';
                addBtn.href = data.reviews[0]?.is_logged
                    ? '?c=review&a=create'
                    : '?c=auth&a=login';

                reviewsList.appendChild(addBtn);

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

                // Після оновлення — заново повісити обробники видалення
                bindDeleteHandlers();
            })
            .catch(err => console.error('Chyba pri načítaní recenzií:', err));
    };

    authorInput?.addEventListener('input', searchReviews);
    dateInput?.addEventListener('input', searchReviews);

    // Видалення з AJAX і оновлення списку
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
                    fetch('?c=review&a=ajaxDelete', {
                        method: 'POST',
                        body: new URLSearchParams({review_id: reviewId})
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                const el = document.getElementById('review-' + reviewId);
                                if (el) el.remove();
                                searchReviews(); // одразу оновити рейтинг і список
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

        document.getElementById('clear-filters').addEventListener('click', function () {
        document.getElementById('search-author').value = '';
        document.getElementById('search-date').value = '';
        if (typeof searchReviews === 'function') {
        searchReviews(); // викликає оновлення
    }
    });

    // Ініціалізувати для початкових кнопок
    bindDeleteHandlers();
});
