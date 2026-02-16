<?php
require '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];


    if (empty($name) || empty($email) || empty($password)) {
        echo "Semua field harus diisi!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Email tidak valid!";
    } else {

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);


        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $hashed_password);

        if ($stmt->execute()) {
            header("Location: login.php?success=1");
            exit();
        } else {
            $error = "Error: {$stmt->error}";
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Task Management</title>
    <link rel="stylesheet" href="../style/style.css">
</head>

<body class="auth-page">
    <div class="container">
        <h2>Form Registrasi</h2>
        <?php if (isset($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="name">Nama:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <input type="submit" value="Daftar">
        </form>
        <div class="link">
            Sudah punya akun? <a href="login.php">Login di sini</a>
        </div>
    </div>
</body>

</html>