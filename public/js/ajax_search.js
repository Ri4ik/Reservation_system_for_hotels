document.addEventListener('DOMContentLoaded', function () {
    const authorInput = document.getElementById('search-author');
    const dateInput = document.getElementById('search-date');

    authorInput?.addEventListener('input', searchReviews);
    dateInput?.addEventListener('input', searchReviews);

    function searchReviews() {
        const author = authorInput.value;
        const date = dateInput.value;

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

                // Якщо користувач залогінений — покажи кнопку "Pridať recenziu"
                if (data.reviews[0].is_logged) {
                    const addBtn = document.createElement('a');
                    addBtn.href = '?c=review&a=create';
                    addBtn.textContent = '➕ Pridať recenziu';
                    reviewsList.appendChild(addBtn);
                }

                data.reviews.forEach(review => {
                    const item = document.createElement('div');
                    item.className = 'review-item';
                    item.innerHTML = `
                        <p>${review.comment || 'Bez textu'}</p>
                        <div class="review-author">— ${review.user_name}</div>
                        <div class="review-time">${review.created_at}</div>
                        ${review.is_admin ? `
                            <a href="?c=review&a=edit&id=${review.id}" class="edit-review">Upraviť</a>
                            <a href="#" class="delete-review" data-id="${review.id}">🗑 Zmazať</a>
                        ` : ''}
                    `;
                    reviewsList.appendChild(item);
                });
            })
            .catch(err => console.error('Chyba pri načítaní recenzií:', err));
    }
});
