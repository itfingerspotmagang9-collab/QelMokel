<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require '../config/database.php';

$user_id = $_SESSION['user_id'];
$task_id = $_GET['id'] ?? 0;

// Ambil data task
$stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $task_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: index.php");
    exit();
}

$task = $result->fetch_assoc();
$stmt->close();

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
        $stmt = $conn->prepare("UPDATE tasks SET title = ?, description = ?, category_id = ?, deadline = ?, status = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ssissii", $title, $description, $category_id, $deadline, $status, $task_id, $user_id);

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
    <title>Edit Tugas - Task Management</title>
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
            <h2>Edit Tugas</h2>
            <?php if (isset($error)): ?>
                <div class="error"><?= $error ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="title">Judul Tugas:</label>
                    <input type="text" id="title" name="title" value="<?= htmlspecialchars($task['title']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="description">Deskripsi:</label>
                    <textarea id="description" name="description"><?= htmlspecialchars($task['description']) ?></textarea>
                </div>
                <div class="form-group">
                    <label for="category_id">Kategori:</label>
                    <select id="category_id" name="category_id" required>
                        <?php while ($cat = $categories->fetch_assoc()): ?>
                            <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $task['category_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="deadline">Deadline:</label>
                    <input type="date" id="deadline" name="deadline" value="<?= $task['deadline'] ?>" required>
                </div>
                <div class="form-group">
                    <label for="status">Status:</label>
                    <select id="status" name="status" required>
                        <option value="todo" <?= $task['status'] === 'todo' ? 'selected' : '' ?>>To Do</option>
                        <option value="progress" <?= $task['status'] === 'progress' ? 'selected' : '' ?>>In Progress</option>
                        <option value="done" <?= $task['status'] === 'done' ? 'selected' : '' ?>>Done</option>
                    </select>
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
