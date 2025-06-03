document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    if (!form) return;

    const nameInput = form.querySelector('input[name="name"]');
    const capacityInput = form.querySelector('input[name="capacity"]');
    const descriptionInput = form.querySelector('textarea[name="description"]');
    const priceInput = form.querySelector('input[name="price"]');
    const imageInputs = form.querySelectorAll('input[type="file"]');
    const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];

    // Добавляем превью для картинок
    imageInputs.forEach(input => {
        input.addEventListener('change', () => {
            const file = input.files[0];
            if (file) {
                const preview = document.createElement('img');
                preview.style.maxWidth = '150px';
                preview.style.marginTop = '10px';
                preview.src = URL.createObjectURL(file);

                // удаляем старое превью
                const oldPreview = input.nextElementSibling;
                if (oldPreview && oldPreview.tagName === 'IMG') {
                    oldPreview.remove();
                }

                input.insertAdjacentElement('afterend', preview);
            }
        });
    });

    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        let errorMessage = '';

        const name = nameInput.value.trim();
        const capacity = parseInt(capacityInput.value);
        const description = descriptionInput.value.trim();
        const price = parseFloat(priceInput.value);

        // Проверка полей
        if (!name) {
            errorMessage = 'Názov izby je povinný.';
        } else if (isNaN(capacity) || capacity <= 0) {
            errorMessage = 'Kapacita musí byť číslo väčšie ako 0.';
        } else if (description.length > 1000) {
            errorMessage = 'Popis nemôže mať viac ako 1000 znakov.';
        } else if (isNaN(price) || price <= 0) {
            errorMessage = 'Cena musí byť kladné číslo.';
        }

        // Проверка формата картинок
        imageInputs.forEach(input => {
            const file = input.files[0];
            if (file && !allowedTypes.includes(file.type)) {
                errorMessage = 'Povolené sú len obrázky JPEG, PNG alebo WEBP.';
            }
        });

        // Проверка уникальности названия комнаты (AJAX)
        if (!errorMessage && form.dataset.mode !== 'edit') {
            try {
                const response = await fetch(`?c=room&a=checkName&name=${encodeURIComponent(name)}`);
                const data = await response.json();
                if (data.exists) {
                    errorMessage = 'Izba s takýmto názvom už existuje.';
                }
            } catch {
                errorMessage = 'Chyba pri kontrole názvu izby.';
            }
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
        } else {
            form.submit();
        }
    });
});
