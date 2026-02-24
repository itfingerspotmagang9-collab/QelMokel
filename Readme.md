STRUKTUR FOLDER

/config
    database.php

/controllers
    register.php
    login.php
    logout.php

/services
    auth_service.php
    task_service.php
    category_service.php

/helpers
    helper.php
    session_helper.php

/views
    auth/
        login_view.php
        register_view.php
    dashboard_view.php

/public
    index.php

📂 /controllers

Ini pintu masuk request.
Tugas:

1. Ambil input
2. Panggil service
3. Redirect / kirim ke view

Controller tidak boleh:
1. Ada SQL
2. Ada validasi kompleks
3. Ada hashing password

📂 /services

Ini jantung sistem.
Berisi:
1. Validasi
2. Aturan bisnis
3. Query database
4. Return hasil terstruktur

📂 /helpers

Fungsi yang reusable dan umum.
Contoh:

redirect()
sanitize()
isLoggedIn()

📂 /views

Murni HTML + sedikit echo.
Tidak ada query.