DROP DATABASE IF EXISTS klaxon;

CREATE DATABASE klaxon
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE klaxon;

CREATE TABLE users (
    id_user INT UNSIGNED AUTO_INCREMENT,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    role ENUM('USER', 'ADMIN') NOT NULL DEFAULT 'USER',

    PRIMARY KEY (id_user),
    UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

CREATE TABLE agencies (
    id_agency INT UNSIGNED AUTO_INCREMENT,
    city VARCHAR(150) NOT NULL,

    PRIMARY KEY (id_agency),
    UNIQUE KEY uq_agencies_city (city)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

CREATE TABLE trips (
    id_trip INT UNSIGNED AUTO_INCREMENT,
    departure_at DATETIME NOT NULL,
    arrival_at DATETIME NOT NULL,
    total_seats TINYINT UNSIGNED NOT NULL,
    available_seats TINYINT UNSIGNED NOT NULL,
    author_id INT UNSIGNED NOT NULL,
    departure_agency_id INT UNSIGNED NOT NULL,
    arrival_agency_id INT UNSIGNED NOT NULL,

    PRIMARY KEY (id_trip),

    CONSTRAINT fk_trips_author
        FOREIGN KEY (author_id)
        REFERENCES users (id_user),

    CONSTRAINT fk_trips_departure_agency
        FOREIGN KEY (departure_agency_id)
        REFERENCES agencies (id_agency),

    CONSTRAINT fk_trips_arrival_agency
        FOREIGN KEY (arrival_agency_id)
        REFERENCES agencies (id_agency),

    CONSTRAINT chk_trips_different_agencies
        CHECK (departure_agency_id <> arrival_agency_id),

    CONSTRAINT chk_trips_dates
        CHECK (arrival_at > departure_at),

    CONSTRAINT chk_trips_total_seats
        CHECK (total_seats > 0),

    CONSTRAINT chk_trips_available_seats
        CHECK (available_seats <= total_seats)
);