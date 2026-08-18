USE klaxon;

INSERT INTO agencies (
    id_agency,
    city
) VALUES
    (1, 'Perpignan'),
    (2, 'Montpellier'),
    (3, 'Toulouse'),
    (4, 'Narbonne');

    INSERT INTO users (
    id_user,
    first_name,
    last_name,
    email,
    password_hash,
    phone,
    role
) VALUES
    (
        1,
        'Alice',
        'Durand',
        'alice.durand@entreprise.test',
        '$2y$10$LKjlKfWWzJYr/3M4EcsgYOGzTnH.c29N5f2rRXV0Mf4KOQ/sSiFyu',
        '06 10 20 30 40',
        'ADMIN'
    ),
    (
        2,
        'Hugo',
        'Martin',
        'hugo.martin@entreprise.test',
        '$2y$10$L/e6b/CSjqbbxtidhABeeu7aac.m.FiU4/BtgDBj0bFz9Ld//yQPm',
        '06 50 60 70 80',
        'USER'
    );

    INSERT INTO trips (
    id_trip,
    departure_at,
    arrival_at,
    total_seats,
    available_seats,
    author_id,
    departure_agency_id,
    arrival_agency_id
) VALUES
    (
        1,
        DATE_ADD(CURRENT_DATE, INTERVAL 2 DAY) + INTERVAL 8 HOUR + INTERVAL 30 MINUTE,
        DATE_ADD(CURRENT_DATE, INTERVAL 2 DAY) + INTERVAL 10 HOUR + INTERVAL 15 MINUTE,
        4,
        3,
        2,
        1,
        2
    ),
    (
        2,
        DATE_ADD(CURRENT_DATE, INTERVAL 4 DAY) + INTERVAL 9 HOUR,
        DATE_ADD(CURRENT_DATE, INTERVAL 4 DAY) + INTERVAL 11 HOUR + INTERVAL 30 MINUTE,
        5,
        2,
        2,
        2,
        3
    ),
    (
        3,
        DATE_ADD(CURRENT_DATE, INTERVAL 6 DAY) + INTERVAL 14 HOUR,
        DATE_ADD(CURRENT_DATE, INTERVAL 6 DAY) + INTERVAL 16 HOUR + INTERVAL 30 MINUTE,
        4,
        0,
        1,
        3,
        1
    ),
    (
        4,
        DATE_SUB(CURRENT_DATE, INTERVAL 2 DAY) + INTERVAL 7 HOUR + INTERVAL 45 MINUTE,
        DATE_SUB(CURRENT_DATE, INTERVAL 2 DAY) + INTERVAL 8 HOUR + INTERVAL 45 MINUTE,
        4,
        1,
        2,
        4,
        1
    );
