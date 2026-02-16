<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $name = trim($_POST['name']);

    if (empty($name)) {
        $error = "Nama kategori harus diisi!";
    } else {
        $stmt = $conn->prepare("INSERT INTO categories (user_id, name) VALUES (?, ?)");
        $stmt->bind_param("is", $user_id, $name);

        if ($stmt->execute()) {
            header("Location: index.php");
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
    <title>Tambah Kategori - Task Management</title>
    <link rel="stylesheet" href="../style/style.css">
</head>

<body>
    <div class="navbar">
        <h1>Task Management System</h1>
        <nav>
            <a href="../dashboard.php">Dashboard</a>
            <a href="../tasks/index.php">Tugas</a>
            <a href="index.php">Kategori</a>
            <a href="../auth/logout.php">Logout</a>
        </nav>
    </div>

    <div class="container">
        <div class="section">
            <h2>Tambah Kategori Baru</h2>
            <?php if (isset($error)): ?>
                <div class="error"><?= $error ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="name">Nama Kategori:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="buttons">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="index.php" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>