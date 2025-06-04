-- phpMyAdmin SQL Dump
-- verzia: 5.2.1
-- Export databázy so slovenskými komentármi a reálnymi údajmi na testovanie
-- Host: 127.0.0.1
-- Dátum exportu: 04.06.2025

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Databáza: `booking_rooms`
--

-- --------------------------------------------------------

--
-- Štruktúra tabuľky `reservations`
--

CREATE TABLE `reservations` (
                                `id` int(11) NOT NULL,
                                `user_id` int(11) NOT NULL,
                                `room_id` int(11) NOT NULL,
                                `check_in` date NOT NULL,
                                `check_out` date NOT NULL,
                                `status` enum('čaká na schválenie','potvrdená','zrušená') NOT NULL DEFAULT 'čaká na schválenie'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dáta pre tabuľku `reservations`
--

INSERT INTO `reservations` (`id`, `user_id`, `room_id`, `check_in`, `check_out`, `status`) VALUES
                                                                                               (1, 3, 1, '2025-06-10', '2025-06-12', 'potvrdená'),
                                                                                               (2, 4, 2, '2025-06-15', '2025-06-18', 'potvrdená'),
                                                                                               (3, 2, 3, '2025-06-20', '2025-06-23', 'čaká na schválenie'),
                                                                                               (4, 4, 4, '2025-06-25', '2025-06-28', 'zrušená'),
                                                                                               (5, 3, 13, '2025-06-30', '2025-07-02', 'potvrdená'),
                                                                                               (6, 2, 14, '2025-07-05', '2025-07-10', 'čaká na schválenie'),
                                                                                               (7, 4, 1, '2025-07-15', '2025-07-18', 'potvrdená'),
                                                                                               (8, 3, 2, '2025-07-20', '2025-07-22', 'zrušená'),
                                                                                               (9, 4, 3, '2025-07-25', '2025-07-28', 'potvrdená'),
                                                                                               (10, 3, 4, '2025-08-01', '2025-08-04', 'čaká na schválenie');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky `reviews`
--

CREATE TABLE `reviews` (
                           `id` int(11) NOT NULL,
                           `user_id` int(11) NOT NULL,
                           `comment` text NOT NULL,
                           `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                           `rating` tinyint(3) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dáta pre tabuľku `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `comment`, `created_at`, `rating`) VALUES
                                                                               (1, 3, 'Perfektná izba, čistá a veľmi tichá. Určite sa vrátim.', '2025-06-01 10:10:00', 5),
                                                                               (2, 4, 'Personál bol veľmi ochotný, krásny výhľad na les.', '2025-06-02 12:30:00', 4),
                                                                               (3, 2, 'Všetko bolo v poriadku, len raňajky by mohli byť bohatšie.', '2025-06-03 15:00:00', 4),
                                                                               (4, 4, 'Mali sme menší problém s klimatizáciou, ale rýchlo vyriešené.', '2025-06-04 16:40:00', 3),
                                                                               (5, 2, 'Super lokalita, veľmi pohodlná posteľ.', '2025-06-05 18:20:00', 5),
                                                                               (6, 3, 'Izba bola trochu menšia ako sme čakali.', '2025-06-06 09:15:00', 3),
                                                                               (7, 4, 'Skvelý wellness, čistota na vysokej úrovni.', '2025-06-07 14:50:00', 5),
                                                                               (8, 2, 'Parkovanie by mohlo byť lepšie organizované.', '2025-06-08 11:05:00', 4);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky `rooms`
--

CREATE TABLE `rooms` (
                         `id` int(11) NOT NULL,
                         `name` varchar(100) NOT NULL,
                         `capacity` int(11) NOT NULL,
                         `description` text DEFAULT NULL,
                         `image1` varchar(255) DEFAULT NULL,
                         `image2` varchar(255) DEFAULT NULL,
                         `image3` varchar(255) DEFAULT NULL,
                         `price` decimal(8,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dáta pre tabuľku `rooms`
--
INSERT INTO `rooms` (`id`, `name`, `capacity`, `description`, `image1`, `image2`, `image3`, `price`) VALUES
 (1, 'Jednolôžková', 2, 'Komfortná izba pre jednu osobu.', '683f86f26ff2e.png', '683f86f270016.png', '683f86f2700c3.png', 60.00),
 (2, 'Dvojlôžková s balkónom', 2, 'Priestranná izba s balkónom a výhľadom.', '683f87fadf014.png', '683f883bf078e.png', '683f8702c181e.png', 75.00),
 (3, 'Dvojlôžková samostatné postele', 2, 'Ideálne pre priateľov alebo kolegov.', '683f873f584ac.png', '683f873f585a0.png', '683f873f58650.png', 90.00),
 (4, 'Trojlôžková s výhľadom', 3, 'Veľká izba pre rodiny s krásnym výhľadom.', '683f67f2df8f2.png', '683f67f2df9e8.png', '683f6cd18a5f6.png', 105.00),
 (13, 'Trojlôžková luxusná', 3, 'Priestranná luxusná izba pre 3 osoby s veľkým balkónom a výhľadom na les.', '683f4a64a085e.png', '683f48785fcf0.png', '683f48785fd9a.png', 120.00),
 (14, 'Apartmán DeLuxe', 4, 'Apartmán najvyššej kategórie pre náročných hostí.', '683f44ade3d3a.png', '683f44ade3e0e.png', '683f44ade3eb6.png', 150.00);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky `users`
--

CREATE TABLE `users` (
                         `id` int(11) NOT NULL,
                         `name` varchar(50) NOT NULL,
                         `email` varchar(100) NOT NULL,
                         `phone` varchar(20) NOT NULL,
                         `password` varchar(255) NOT NULL,
                         `role` enum('admin','client') DEFAULT 'client'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dáta pre tabuľku `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `role`) VALUES
                     (1, 'Admin', 'admin@hotel.com', '+421900000000', '$2y$10$WzB7R/1ZHKSl0D7eF49NfuTxoiE8ZMLgFiwvxgHlB/ogBSAaJdxQa', 'admin'),
                     (2, 'Alice', 'alice@example.com', '+421900111111', '$2y$10$alicepassHASH...', 'client'),
                     (3, 'Bob', 'berezok.2002@gmail.com1', '+421900222222', '$2y$10$d3UvGOJaBKkMnBHcJBZkMufFXdQlank2xB.xTsK8aAqX99vm6urT6', 'client'),
                     (4, 'Danyil Berezhnyi', 'berezok.2002@gmail.com', '0970056404', '$2y$10$d3UvGOJaBKkMnBHcJBZkMufFXdQlank2xB.xTsK8aAqX99vm6urT6', 'admin'),
                     (8, 'TORT', 'TORT@gmail.com', '0970056404', '$2y$10$V.JCCjLKlAfyj8tdCL1Qd.7wCLp5i9pRXdUBsZkY2ukhpbzpsNFxG', 'client');
-- --------------------------------------------------------

-- Indexy a kľúče

ALTER TABLE `reservations` ADD PRIMARY KEY (`id`), ADD KEY `user_id` (`user_id`), ADD KEY `room_id` (`room_id`);
ALTER TABLE `reviews` ADD PRIMARY KEY (`id`), ADD KEY `user_id` (`user_id`);
ALTER TABLE `rooms` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name` (`name`);
ALTER TABLE `users` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `reservations` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
ALTER TABLE `reviews` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
ALTER TABLE `rooms` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
ALTER TABLE `users` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

-- Cudzie kľúče

ALTER TABLE `reservations`
    ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

ALTER TABLE `reviews`
    ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

COMMIT;
