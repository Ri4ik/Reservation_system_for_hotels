// Po načítaní DOM pridáme validáciu na formulár
document.getElementById("registerForm").addEventListener("submit", function(event) {
    // Kontajner pre zobrazovanie chýb
    const errorContainer = document.getElementById("error-message");
    errorContainer.innerHTML = '';
    errorContainer.style.display = 'none';

    // Načítanie a orezanie hodnôt z formulára
    let name = document.getElementById("name").value.trim();
    let email = document.getElementById("email").value.trim();
    let phone = document.getElementById("phone").value.trim();
    let password = document.getElementById("password").value.trim();

    let errors = []; // Pole na ukladanie chýb

    // Kontrola mena – nesmie byť prázdne
    if (name === "") errors.push("Meno nemôže byť prázdne.");

    // Validácia emailu pomocou regulárneho výrazu
    const emailPattern = /^[^@]+@[^@]+\.[a-z]{2,}$/;
    if (!emailPattern.test(email)) errors.push("Zadajte platný email.");

    // Validácia telefónneho čísla: musí obsahovať 9 až 15 číslic
    if (!/^\d{9,15}$/.test(phone)) errors.push("Telefónne číslo musí byť 9–15 číslic.");

    // Kontrola hesla – minimálne 6 znakov
    if (password.length < 6) errors.push("Heslo musí obsahovať aspoň 6 znakov.");

    // Ak existujú chyby, zastavíme odosielanie formulára a zobrazíme chyby
    if (errors.length > 0) {
        event.preventDefault(); // Zastavenie odoslania formulára
        errorContainer.innerHTML = errors.map(e => `<p>${e}</p>`).join(''); // Vloženie chybových hlášok do kontajnera
        errorContainer.style.display = 'block';
    }
});
