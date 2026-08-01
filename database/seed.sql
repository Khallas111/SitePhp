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
        '2026-08-05 08:30:00',
        '2026-08-05 10:15:00',
        4,
        3,
        2,
        1,
        2
    ),
    (
        2,
        '2026-08-06 09:00:00',
        '2026-08-06 11:30:00',
        5,
        2,
        2,
        2,
        3
    ),
    (
        3,
        '2026-08-07 14:00:00',
        '2026-08-07 16:30:00',
        4,
        0,
        1,
        3,
        1
    ),
    (
        4,
        '2026-08-08 07:45:00',
        '2026-08-08 08:45:00',
        4,
        1,
        2,
        4,
        1
    );