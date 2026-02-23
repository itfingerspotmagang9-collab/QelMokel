<?php
session_start();

require '../config/database.php';
require '../services/auth_service.php';
require '../helpers/helper.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $result = loginUser($email, $password, $conn);

    if ($result['success']) {
        $_SESSION['user_id'] = $result['user']['id'];
        redirect('../views/dashboard_view.php');
    } else {
        echo $result['message'];
    }
}