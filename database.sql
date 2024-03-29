
DROP TABLE IF EXISTS `users`;

CREATE TABLE IF NOT EXISTS `users` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `name` VARCHAR(50),
    `email` VARCHAR(60),
    `phone` BIGINT(10),
    `password` VARCHAR(100),
    `token` VARCHAR(100),
    `created_at` TIMESTAMP,
    `updated_at` TIMESTAMP,
    `deleted_at` TIMESTAMP
);