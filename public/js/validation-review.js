document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        const commentInput = form.querySelector('textarea[name="comment"]');
        const ratingInput = form.querySelector('select[name="rating"]'); // môže byť null

        const comment = commentInput.value.trim();
        let rating = null;

        if (ratingInput) {
            rating = parseInt(ratingInput.value);
        }

        let errorMessage = '';

        if (!comment) {
            errorMessage = 'Obsah recenzie nemôže byť prázdny!';
        } else if (ratingInput && (isNaN(rating) || rating < 1 || rating > 5)) {
            errorMessage = 'Vyberte platné hodnotenie od 1 do 5 hviezdičiek!';
        }

        if (errorMessage !== '') {
            e.preventDefault();

            let errorBox = form.querySelector('.validate-error');
            if (!errorBox) {
                errorBox = document.createElement('p');
                errorBox.classList.add('validate-error');
                form.prepend(errorBox);
            }
            errorBox.textContent = errorMessage;
        }
    });
});
