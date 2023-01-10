INSERT INTO
    `users` (
        `email`,
        `password`,
        `email_verified_at`,
        `remember_token`,
        `admin`,
        `created_at`,
        `updated_at`
    )
VALUES
    (
        'admin@example.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        '2023-01-10 00:00:00',
        NULL,
        1,
        '2023-01-10 00:00:00',
        '2023-01-10 00:00:00'
    ),
    (
        'user@example.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        '2023-01-10 00:00:00',
        NULL,
        0,
        '2023-01-10 00:00:00',
        '2023-01-10 00:00:00'
    );