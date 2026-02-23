<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management System</title>
    <link rel="stylesheet" href="style/style.css">
</head>

<body class="landing-page">
    <div class="container">
        <h1>Task Management System</h1>
        <p>Kelola tugas Anda dengan mudah dan efisien</p>
        <div class="buttons">
            <a href="views/auth/login_view.php" class="btn btn-primary">Login</a>
            <a href="views/auth/register_view.php" class="btn btn-secondary">Register</a>
        </div>
    </div>
</body>

</html>