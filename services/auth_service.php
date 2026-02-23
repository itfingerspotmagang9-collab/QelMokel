<?php

function loginUser($login, $password, $conn)
{
    if (empty($login) || empty($password)) {
        return [
            'success' => false,
            'message' => 'Email/Username dan password wajib diisi'
        ];
    }

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ? OR username = ?");
    $stmt->bind_param("ss", $login);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        return [
            'success' => false,
            'message' => 'Email tidak ditemukan'
        ];
    }

    if (!password_verify($password, $user['password'])) {
        return [
            'success' => false,
            'message' => 'Password salah'
        ];
    }

    return [
        'success' => true,
        'user' => $user
    ];
}

function registerUser($username, $email, $password, $confirm)
{
    global $conn;

    if (empty($username) || empty($email) || empty($password)) {
        return ['success' => false, 'message' => 'Semua field wajib diisi'];
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'message' => 'Format email tidak valid'];
    }

    if ($password !== $confirm) {
        return ['success' => false, 'message' => 'Konfirmasi password tidak sesuai!'];
    }

    // Cek apakah email sudah terdaftar
    $checkEmail = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $checkEmail->store_result();

    if ($checkEmail->num_rows > 0) {
        return ['success' => false, 'message' => 'Email sudah terdaftar'];
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashedPassword);

    if ($stmt->execute()) {
        return ['success' => true];
    } else {
        return ['success' => false, 'message' => 'Gagal menyimpan data'];
    }
}