<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require '../config/database.php';

$user_id = $_SESSION['user_id'];
$category_id = $_GET['id'] ?? 0;

// Ambil data kategori
$stmt = $conn->prepare("SELECT * FROM categories WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $category_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: index.php");
    exit();
}

$category = $result->fetch_assoc();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);

    if (empty($name)) {
        $error = "Nama kategori harus diisi!";
    } else {
        $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("sii", $name, $category_id, $user_id);

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
    <title>Edit Kategori - Task Management</title>
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
            <h2>Edit Kategori</h2>
            <?php if (isset($error)): ?>
                <div class="error"><?= $error ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="name">Nama Kategori:</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($category['name']) ?>"
                        required>
                </div>
                <div class="buttons">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="index.php" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>