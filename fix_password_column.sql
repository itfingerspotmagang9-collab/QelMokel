-- Perbaiki ukuran kolom password di tabel users
USE task_management;

ALTER TABLE users MODIFY password VARCHAR(255) NOT NULL;
