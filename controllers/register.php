<?php
session_start();

require '../config/database.php';
require '../services/auth_service.php';
require '../helpers/helper.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'] ?? '';
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    $result = registerUser($username, $email, $password,  $confirm);

    if ($result['success']) {
        header("Location: ../views/auth/login_view.php");
        exit;
    } else {
        $error = $result['message'];
    }
}