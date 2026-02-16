<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require '../config/database.php';

$user_id = $_SESSION['user_id'];

// Ambil kategori user
$categories = $conn->query("SELECT * FROM categories WHERE user_id = $user_id ORDER BY name");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category_id = $_POST['category_id'];
    $deadline = $_POST['deadline'];
    $status = $_POST['status'];

    if (empty($title) || empty($category_id) || empty($deadline)) {
        $error = "Judul, kategori, dan deadline harus diisi!";
    } else {
        $stmt = $conn->prepare("INSERT INTO tasks (user_id, category_id, title, description, deadline, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iissss", $user_id, $category_id, $title, $description, $deadline, $status);

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
    <title>Tambah Tugas - Task Management</title>
    <link rel="stylesheet" href="../style/style.css">
</head>
<body>
    <div class="navbar">
        <h1>Task Management System</h1>
        <nav>
            <a href="../dashboard.php">Dashboard</a>
            <a href="index.php">Tugas</a>
            <a href="../categories/index.php">Kategori</a>
            <a href="../auth/logout.php">Logout</a>
        </nav>
    </div>

    <div class="container">
        <div class="section">
            <h2>Tambah Tugas Baru</h2>
            <?php if (isset($error)): ?>
                <div class="error"><?= $error ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="title">Judul Tugas:</label>
                    <input type="text" id="title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="description">Deskripsi:</label>
                    <textarea id="description" name="description"></textarea>
                </div>
                <div class="form-group">
                    <label for="category_id">Kategori:</label>
                    <select id="category_id" name="category_id" required>
                        <option value="">Pilih Kategori</option>
                        <?php while ($cat = $categories->fetch_assoc()): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="deadline">Deadline:</label>
                    <input type="date" id="deadline" name="deadline" required>
                </div>
                <div class="form-group">
                    <label for="status">Status:</label>
                    <select id="status" name="status" required>
                        <option value="todo">To Do</option>
                        <option value="progress">In Progress</option>
                        <option value="done">Done</option>
                    </select>
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
