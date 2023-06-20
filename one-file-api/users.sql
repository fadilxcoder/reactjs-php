-- SQLite
DROP TABLE IF EXISTS `users`;

CREATE TABLE IF NOT EXISTS `users` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `name` VARCHAR(50),
    `email` VARCHAR(60),
    `mobile` BIGINT(10),
    `created_at` TIMESTAMP,
    `updated_at` TIMESTAMP
);