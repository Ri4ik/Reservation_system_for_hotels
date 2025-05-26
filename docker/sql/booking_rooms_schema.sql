-- Створення бази
DROP DATABASE IF EXISTS booking_rooms;
CREATE DATABASE booking_rooms CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE booking_rooms;

-- Таблиця користувачів
CREATE TABLE users (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       name VARCHAR(50) NOT NULL,
                       email VARCHAR(100) UNIQUE NOT NULL,
                       phone VARCHAR(20) NOT NULL,
                       password VARCHAR(255) NOT NULL,
                       role ENUM('admin', 'client') DEFAULT 'client'
);

-- Таблиця номерів
CREATE TABLE rooms (
                       id INT AUTO_INCREMENT PRIMARY KEY,
                       type ENUM('Jednolôžková', 'Dvojlôžková', 'Trojlôžková') NOT NULL,
                       capacity INT NOT NULL,
                       description TEXT,
                       image VARCHAR(255)
);

-- Таблиця резервацій
CREATE TABLE reservations (
                              id INT AUTO_INCREMENT PRIMARY KEY,
                              user_id INT NOT NULL,
                              room_id INT NOT NULL,
                              check_in DATE NOT NULL,
                              check_out DATE NOT NULL,
                              status ENUM('pending', 'confirmed', 'canceled') DEFAULT 'pending',
                              FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                              FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE
);

-- Таблиця відгуків
CREATE TABLE reviews (
                         id INT AUTO_INCREMENT PRIMARY KEY,
                         user_id INT NOT NULL,
                         comment TEXT NOT NULL,
                         created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                         FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Демо користувачі
INSERT INTO users (name, email, phone, password, role) VALUES
                                                           ('Admin', 'admin@hotel.com', '+421900000000', '$2y$10$adminpassHASH...', 'admin'),
                                                           ('Alice', 'alice@example.com', '+421900111111', '$2y$10$alicepassHASH...', 'client'),
                                                           ('Bob', 'bob@example.com', '+421900222222', '$2y$10$bobpassHASH...', 'client');

-- Демо номери
INSERT INTO rooms (type, capacity, description, image) VALUES
                                                           ('Jednolôžková', 1, 'Izba pre 1 osobu so sprchou.', 'room1.jpg'),
                                                           ('Dvojlôžková', 2, 'Izba pre 2 osoby s balkónom.', 'room2.jpg'),
                                                           ('Dvojlôžková', 2, 'Dve samostatné postele.', 'room3.jpg'),
                                                           ('Trojlôžková', 3, 'Veľká izba pre 3 osoby.', 'room4.jpg'),
                                                           ('Trojlôžková', 3, 'Rodinná izba s kuchynkou.', 'room5.jpg');

-- Демо резервації
INSERT INTO reservations (user_id, room_id, check_in, check_out, status) VALUES
                                                                             (2, 1, '2025-07-01', '2025-07-03', 'confirmed'),
                                                                             (3, 2, '2025-07-05', '2025-07-07', 'pending');

-- Демо відгуки
INSERT INTO reviews (user_id, comment) VALUES
                                           (2, 'Skvelý hotel, čisté izby a milý personál.'),
                                           (3, 'Dobrý pobyt, ale slabšie raňajky.');
