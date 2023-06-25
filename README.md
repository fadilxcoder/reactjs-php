# Notes

One file API with CRUD / SQLite

* Create database : `touch app.db` 


* BASE URL : `http://localhost/sqlite-api/`


* HTTP response status codes : https://developer.mozilla.org/en-US/docs/Web/HTTP/Status


* Database schema

```sql

DROP TABLE IF EXISTS `users`;

CREATE TABLE IF NOT EXISTS `users` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `name` VARCHAR(50),
    `email` VARCHAR(60),
    `phone` BIGINT(10),
    `token` VARCHAR(100),
    `created_at` TIMESTAMP,
    `updated_at` TIMESTAMP
);
```

* RUN `newman run postman_collection.json`

```bash

One File API

→ users
  GET http://localhost/sqlite-api/ [200 OK, 2kB, 460ms]

→ users/:id
  GET http://localhost/sqlite-api/index/1 [200 OK, 405B, 423ms]

→ users
  POST http://localhost/sqlite-api/ [200 OK, 603B, 455ms]

→ users
  PUT http://localhost/sqlite-api/ [200 OK, 433B, 435ms]

→ users
  DELETE http://localhost/sqlite-api/index/1 [200 OK, 430B, 420ms]

┌─────────────────────────┬────────────────────┬────────────────────┐
│                         │           executed │             failed │
├─────────────────────────┼────────────────────┼────────────────────┤
│              iterations │                  1 │                  0 │
├─────────────────────────┼────────────────────┼────────────────────┤
│                requests │                  5 │                  0 │
├─────────────────────────┼────────────────────┼────────────────────┤
│            test-scripts │                  0 │                  0 │
├─────────────────────────┼────────────────────┼────────────────────┤
│      prerequest-scripts │                  0 │                  0 │
├─────────────────────────┼────────────────────┼────────────────────┤
│              assertions │                  0 │                  0 │
├─────────────────────────┴────────────────────┴────────────────────┤
│ total run duration: 2.6s                                          │
├───────────────────────────────────────────────────────────────────┤
│ total data received: 2.08kB (approx)                              │
├───────────────────────────────────────────────────────────────────┤
│ average response time: 438ms [min: 420ms, max: 460ms, s.d.: 16ms] │
└───────────────────────────────────────────────────────────────────┘
```
