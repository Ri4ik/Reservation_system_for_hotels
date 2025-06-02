document.getElementById("registerForm").addEventListener("submit", function(event) {
    const errorContainer = document.getElementById("error-message");
    errorContainer.innerHTML = '';
    errorContainer.style.display = 'none';

    let name = document.getElementById("name").value.trim();
    let email = document.getElementById("email").value.trim();
    let phone = document.getElementById("phone").value.trim();
    let password = document.getElementById("password").value.trim();

    let errors = [];

    if (name === "") errors.push("Meno nemôže byť prázdne.");
    const emailPattern = /^[^@]+@[^@]+\.[a-z]{2,}$/;
    if (!emailPattern.test(email)) errors.push("Zadajte platný email.");
    if (!/^\d{9,15}$/.test(phone)) errors.push("Telefónne číslo musí byť 9–15 číslic.");
    if (password.length < 6) errors.push("Heslo musí obsahovať aspoň 6 znakov.");

    if (errors.length > 0) {
        event.preventDefault();
        errorContainer.innerHTML = errors.map(e => `<p>${e}</p>`).join('');
        errorContainer.style.display = 'block';
    }
});