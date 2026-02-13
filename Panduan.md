# 🎯 MINI PROJECT

## **Sistem Manajemen Tugas (Task Management System)**

### Deskripsi Singkat

Buat aplikasi web sederhana menggunakan **PHP native (tanpa framework)** dan **MySQL** untuk mengelola tugas harian dengan fitur kategori, status, dan filter.

Targetnya bukan sekadar CRUD, tapi memahami:

* Relasi tabel
* Query join
* Validasi input
* Struktur folder backend sederhana
* Konsep session (login sederhana)
* Optimasi query dasar

---

# 1️⃣ Analisis Masalah

Masalah yang ingin diselesaikan:

> Bagaimana membuat sistem yang bisa mengelola tugas berdasarkan kategori, status, dan user?

Berarti sistem minimal harus punya:

* User
* Tugas
* Kategori
* Status

---

# 2️⃣ Spesifikasi Fitur (WAJIB)

## 🔐 1. Authentication

* Register
* Login
* Logout
* Password di-hash (gunakan `password_hash()`)

---

## 📂 2. Manajemen Kategori

* Tambah kategori
* Edit kategori
* Hapus kategori
* Satu user bisa punya banyak kategori

---

## 📝 3. Manajemen Tugas

Setiap tugas memiliki:

* Judul
* Deskripsi
* Deadline
* Status (todo, progress, done)
* Kategori
* User pemilik

Fitur:

* Tambah tugas
* Edit tugas
* Hapus tugas
* Ubah status
* Filter berdasarkan:

  * Status
  * Kategori
  * Deadline terdekat

---

## 🔎 4. Dashboard

Menampilkan:

* Total tugas
* Jumlah tugas selesai
* Jumlah tugas belum selesai
* 5 tugas dengan deadline terdekat

Gunakan query JOIN + GROUP BY.

---

# 3️⃣ Desain Database

## Tabel users

```
id (PK)
name
email (unique)
password
created_at
```

## Tabel categories

```
id (PK)
user_id (FK)
name
created_at
```

## Tabel tasks

```
id (PK)
user_id (FK)
category_id (FK)
title
description
deadline
status (enum: todo, progress, done)
created_at
```

---

# 4️⃣ Struktur Folder (Disiplin Backend)

```
/config
    database.php

/auth
    login.php
    register.php
    logout.php

/categories
    index.php
    create.php
    edit.php
    delete.php

/tasks
    index.php
    create.php
    edit.php
    delete.php
    update_status.php

/dashboard.php
/index.php
```

Kenapa penting?
Karena ini melatih kamu berpikir modular sebelum masuk OOP.

---

# 5️⃣ Tantangan Query (Level Menengah)

### 🔹 1. Tampilkan jumlah tugas per status

```sql
SELECT status, COUNT(*) 
FROM tasks 
WHERE user_id = ?
GROUP BY status;
```

### 🔹 2. Tugas deadline terdekat

```sql
SELECT * 
FROM tasks 
WHERE user_id = ?
AND status != 'done'
ORDER BY deadline ASC
LIMIT 5;
```

### 🔹 3. Filter dinamis (gabungan status + kategori)

Gunakan conditional query builder manual di PHP.

---

# 6️⃣ Aspek yang WAJIB Kamu Latih

### ✅ Validasi Input

* Tidak boleh kosong
* Deadline tidak boleh di masa lalu
* Email harus valid

### ✅ Keamanan

* Gunakan prepared statement (PDO / mysqli prepared)
* Escape output (`htmlspecialchars`)
* Session validation di setiap halaman

### ✅ Error Handling

Jangan tampilkan error SQL mentah ke user.

---

# 7️⃣ Fitur Tambahan (Jika Ingin Naik Level)

* Pagination
* Search tugas
* Upload attachment sederhana
* Soft delete (tambahkan deleted_at)
* AJAX untuk update status tanpa reload

---

# 8️⃣ Kriteria Lulus (Self-Evaluation)

Kamu siap lanjut OOP & Laravel jika:

* Kamu tidak bingung relasi FK
* Bisa membuat query join tanpa copy-paste
* Paham kenapa prepared statement penting
* Bisa memisahkan logic dan tampilan
* Bisa menjelaskan alur request → proses → response

---

# 9️⃣ Insight Strategis

Kenapa ini penting sebelum Laravel?

Karena Laravel:

* Mengabstraksi query
* Mengabstraksi routing
* Mengabstraksi authentication

Kalau kamu belum merasakan “susahnya” manual:

* Kamu tidak akan benar-benar paham apa yang Laravel lakukan.

Framework itu alat.
Yang membedakan engineer dan user framework adalah:

> Apakah dia paham fondasi di bawahnya?

---

Kalau kamu mau, saya bisa:

* Buatkan roadmap pengerjaan 7 hari
* Buatkan versi yang lebih kompleks (multi-user role admin)
* Atau review desain database yang kamu buat nanti

Kamu ingin tingkat kesulitan dinaikkan sedikit atau mulai dari versi ini dulu?
