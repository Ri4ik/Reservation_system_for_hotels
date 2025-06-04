
<main>
    <h4>Moje rezervácie</h4>

    <div class="search-form-wrapper">
        <form id="search-reservation-form" action="javascript:void(0);">
            <div class="search-fields">
                <?php if ($isAdmin): ?>
                    <div class="search-field">
                        <label for="search-user">Meno zákazníka</label>
                        <input type="text" id="search-user" name="search-user" placeholder="Zákazník">
                    </div>

                    <div class="search-field">
                        <label for="search-room">Izba</label>
                        <input type="text" id="search-room" name="search-room" placeholder="Izba">
                    </div>

                    <div class="search-field">
                        <label for="search-status">Stav</label>
                        <select id="search-status" name="search-status">
                            <option value="">-- Všetky --</option>
                            <option value="čaká na schválenie">Čaká na schválenie</option>
                            <option value="potvrdená">Potvrdená</option>
                            <option value="zrušená">Zrušená</option>
                        </select>
                    </div>

                    <div class="search-field">
                        <label for="search-date-from">Od</label>
                        <input type="date" id="search-date-from" name="search-date-from">
                    </div>

                    <div class="search-field">
                        <label for="search-date-to">Do</label>
                        <input type="date" id="search-date-to" name="search-date-to">
                    </div>

                    <div class="search-field">
                        <label>&nbsp;</label>
                        <button type="button" id="clear-filters">✖ Vymazať filtre</button>
                    </div>
                <?php else: ?>
                    <div class="search-field">
                        <label for="search-room">Izba</label>
                        <input type="text" id="search-room" name="search-room" placeholder="Izba">
                    </div>

                    <div class="search-field">
                        <label for="search-status">Stav</label>
                        <select id="search-status" name="search-status">
                            <option value="">-- Všetky --</option>
                            <option value="čaká na schválenie">Čaká na schválenie</option>
                            <option value="potvrdená">Potvrdená</option>
                            <option value="zrušená">Zrušená</option>
                        </select>
                    </div>

                    <div class="search-field">
                        <label for="search-date-from">Od</label>
                        <input type="date" id="search-date-from" name="search-date-from">
                    </div>

                    <div class="search-field">
                        <label for="search-date-to">Do</label>
                        <input type="date" id="search-date-to" name="search-date-to">
                    </div>

                    <div class="search-field">
                        <label>&nbsp;</label>
                        <button type="button" id="clear-filters">✖ Vymazať filtre</button>
                    </div>
                <?php endif; ?>
            </div>
        </form>
    </div>


    <div class="create-export-div">
        <a class="create-review" href="?c=reservation&a=create">Nová rezervácia</a>
        <?php if ($isAdmin): ?>
        <a class="create-review export-btn" href="?c=reservation&a=exportReservations">📥 Exportovať rezervácie</a>
        <?php endif; ?>
    </div>

    <table>

        <thead>
        <tr>
            <?php if ($isAdmin): ?>
                <th>Meno zákazníka</th>
                <th>Email</th>
            <?php endif; ?>
            <th>Izba</th>
            <th>Od</th>
            <th>Do</th>
            <th>Stav</th>
            <th>Akcie</th>
        </tr>
        </thead>
        <tbody id="reservation-table-body">
        <!-- Bude dynamicky doplnené pomocou AJAX -->
        </tbody>
    </table>
</main>

<script>
    const IS_ADMIN = <?= $isAdmin ? 'true' : 'false' ?>;
</script>
<script src="/Rezervacny_System_VAII/public/js/reservation-actions.js"></script>
