-- ===============================
-- Database: car_rental
-- ===============================
CREATE DATABASE IF NOT EXISTS car_rental
CHARACTER SET utf8
COLLATE utf8_general_ci;

USE car_rental;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- ===============================
-- Table: car_brands
-- ===============================
CREATE TABLE car_brands (
    brand_id INT(3) AUTO_INCREMENT PRIMARY KEY,
    brand_name VARCHAR(50) NOT NULL,
    brand_image VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

INSERT INTO car_brands (brand_name, brand_image) VALUES
('Audi', 'Audi-A4-Avant-1.jpg'),
('BMW', 'bmw-3-series-sedan.jpg'),
('Lexus', '2016-Lexus-RX-350-BM-01.jpg'),
('Mercedes Benz', 'Mercedes-C-Class-Estate-1.jpg'),
('MINI', '2016-MINI-Cooper-S-Clubman-ALL4.jpg'),
('Porsche', 'P14_0596_a4_rgb-1.jpg');

-- ===============================
-- Table: car_types
-- ===============================
CREATE TABLE car_types (
    type_id INT(3) AUTO_INCREMENT PRIMARY KEY,
    type_label VARCHAR(50) NOT NULL,
    type_description VARCHAR(250) NOT NULL
) ENGINE=InnoDB;

INSERT INTO car_types (type_label, type_description) VALUES
('Sedan', 'A sedan has four doors and a traditional trunk.'),
('Coupe', 'A coupe has historically been considered a two-door car with a trunk and a solid roof.'),
('Hatchback', 'Compact car with rear hatch door.');

-- ===============================
-- Table: cars
-- ===============================
CREATE TABLE cars (
    id INT(3) AUTO_INCREMENT PRIMARY KEY,
    car_name VARCHAR(30) NOT NULL,
    brand_id INT(3) NOT NULL,
    type_id INT(3) NOT NULL,
    color VARCHAR(20) NOT NULL,
    model VARCHAR(50) NOT NULL,
    description VARCHAR(100) NOT NULL,

    CONSTRAINT fk_car_brand
        FOREIGN KEY (brand_id) REFERENCES car_brands(brand_id)
        ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT fk_car_type
        FOREIGN KEY (type_id) REFERENCES car_types(type_id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

INSERT INTO cars (car_name, brand_id, type_id, color, model, description) VALUES
('Porsche Boxster', 6, 2, 'Red', '2017', 'Luxury sport coupe'),
('Audi A5', 1, 2, 'Red', '2017', 'Premium coupe'),
('Mercedes CLS', 4, 2, 'Blue', '2019', 'Luxury sedan'),
('Audi A7', 1, 3, 'Blue', '2019', 'Sport hatchback');

-- ===============================
-- Table: clients
-- ===============================
CREATE TABLE clients (
    client_id INT(10) AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(30) NOT NULL,
    client_email VARCHAR(100) NOT NULL,
    client_phone VARCHAR(20) NOT NULL
) ENGINE=InnoDB;

INSERT INTO clients (full_name, client_email, client_phone) VALUES
('John Doe', 'john_doe@gmail.com', '0123456789');

-- ===============================
-- Table: reservations
-- ===============================
CREATE TABLE reservations (
    reservation_id INT(10) AUTO_INCREMENT PRIMARY KEY,
    client_id INT(10) NOT NULL,
    car_id INT(3) NOT NULL,
    pickup_date DATE NOT NULL,
    return_date DATE NOT NULL,
    pickup_location VARCHAR(50) NOT NULL,
    return_location VARCHAR(50) NOT NULL,
    canceled TINYINT(1) DEFAULT 0,
    cancellation_reason VARCHAR(250),

    CONSTRAINT fk_res_client
        FOREIGN KEY (client_id) REFERENCES clients(client_id)
        ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT fk_res_car
        FOREIGN KEY (car_id) REFERENCES cars(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

INSERT INTO reservations
(client_id, car_id, pickup_date, return_date, pickup_location, return_location, canceled, cancellation_reason)
VALUES
(1, 1, '2024-03-02', '2024-03-05', 'Paris', 'Paris', 0, NULL);

-- ===============================
-- Table: users
-- ===============================
CREATE TABLE users (
    user_id INT(5) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(20) NOT NULL,
    user_email VARCHAR(50) NOT NULL,
    full_name VARCHAR(50) NOT NULL,
    password VARCHAR(100) NOT NULL,
    group_id TINYINT(1) DEFAULT 1
) ENGINE=InnoDB;



INSERT INTO users (username, user_email, full_name, password, group_id) VALUES
('admin', 'admin.admin@gmail.com', 'Admin Admin',
 'f7c3bc1d808e04732adf679965ccc34ca7ae3441', 0);

COMMIT;
