# Rezervačný systém hotelových izieb

Tento projekt je webová aplikácia pre správu hotelových rezervácií. Bol vytvorený v rámci predmetu VAII na FRI ŽU v Žiline.

---

## 📦 Použité technológie

- PHP 8.x
- MySQL (MariaDB)
- XAMPP
- Čistý MVC framework (VAIICKO)
- HTML / CSS / JavaScript (vrátane AJAX)
- phpMyAdmin

---

## 🖥️ Inštalácia na lokálnom PC (bez Dockeru, pomocou XAMPP)

### 1️⃣ Naklonovanie projektu

- Skopírujte celý projekt do hlavného adresára XAMPP:

 C:\xampp\htdocs\Rezervacny_System_VAII

---

### 2️⃣ Spustenie XAMPP

- Spustite **XAMPP Control Panel**.
- Najprv spustite **Apache**.
- Potom spustite **MySQL**.

> ❗ Ak MySQL nespustí kvôli obsadenému portu 3306:

- Otvorte CMD ako administrátor.
- Zadajte:

netstat -aon | findstr :3306

- Zobrazí sa PID procesu, ktorý blokuje port. Napríklad:

TCP 0.0.0.0:3306 0.0.0.0:0 LISTENING 1234

- Ukončite proces zadaním:

taskkill /PID 1234 /F

- Spustite MySQL znova.

---

### 3️⃣ Import databázy

- Otvorte phpMyAdmin:  
  http://localhost/phpmyadmin

- Vytvorte novú databázu:

  booking_rooms

- Prejdite do SQL sekcie a vložte tam obsah súboru:

docker/sql/booking_rooms_schema.sql

- Spustite skript.

---

### 4️⃣ Spustenie aplikácie

Po úspešnom importe databázy spustite aplikáciu v prehliadači:

http://localhost/Rezervacny_System_VAII/

---

## 🔐 Preddefinované účty:

- **Admin:**
  - Email: `berezok.2002@gmail.com`
  - Heslo: 111111

- **Klienti:**
  - Možnosť registrácie cez aplikáciu.
  - alebo:
  - Email: `berezok.2002@gmail.com1`
  - Heslo: 111111
---

## 📂 Štruktúra projektu

- `App/Controllers` – Logika kontrolérov
- `App/Models` – Prístup k databáze
- `App/Views` – Šablóny pre frontend
- `public/` – Statické súbory (CSS, JS, obrázky)
- `docker/sql/booking_rooms_schema.sql` – Kompletný SQL dump databázy

---

## ⚠️ Poznámka

- Projekt je optimalizovaný na spúšťanie pod XAMPP.
- Docker konfigurácia nie je nutná pre túto inštaláciu.

---

Vypracoval:  
**Danyil Berezhnyi**
