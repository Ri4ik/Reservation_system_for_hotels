document.addEventListener('DOMContentLoaded', function () {
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
                    body: new URLSearchParams({ review_id: reviewId })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            // alert('✅ Recenzia bola odstránená.');
                            const el = document.getElementById('review-' + reviewId);
                            if (el) el.remove();
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
});
