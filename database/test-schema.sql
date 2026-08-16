SET
    FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS trips;

DROP TABLE IF EXISTS users;

DROP TABLE IF EXISTS agencies;

SET
    FOREIGN_KEY_CHECKS = 1;

    CREATE TABLE agencies (
    id_agency INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    city VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE users (
    id_user INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    role VARCHAR(20) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE trips (
    id_trip INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    departure_at DATETIME NOT NULL,
    arrival_at DATETIME NOT NULL,

    total_seats INT UNSIGNED NOT NULL,
    available_seats INT UNSIGNED NOT NULL,

    author_id INT UNSIGNED NOT NULL,
    departure_agency_id INT UNSIGNED NOT NULL,
    arrival_agency_id INT UNSIGNED NOT NULL,

    CONSTRAINT fk_trips_author
        FOREIGN KEY (author_id)
        REFERENCES users(id_user),

    CONSTRAINT fk_trips_departure_agency
        FOREIGN KEY (departure_agency_id)
        REFERENCES agencies(id_agency),

    CONSTRAINT fk_trips_arrival_agency
        FOREIGN KEY (arrival_agency_id)
        REFERENCES agencies(id_agency),

    CONSTRAINT chk_trip_agencies
        CHECK (
            departure_agency_id
            <> arrival_agency_id
        ),

    CONSTRAINT chk_trip_dates
        CHECK (
            arrival_at > departure_at
        ),

    CONSTRAINT chk_total_seats
        CHECK (
            total_seats > 0
        ),

    CONSTRAINT chk_available_seats
        CHECK (
            available_seats <= total_seats
        )
) ENGINE=InnoDB;