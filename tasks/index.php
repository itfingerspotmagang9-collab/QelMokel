<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require '../config/database.php';

$user_id = $_SESSION['user_id'];

// Filter
$filter_status = $_GET['status'] ?? '';
$filter_category = $_GET['category'] ?? '';

// Build query dengan filter dinamis
$where = ["t.user_id = $user_id"];
if ($filter_status) {
    $where[] = "t.status = '" . $conn->real_escape_string($filter_status) . "'";
}
if ($filter_category) {
    $where[] = "t.category_id = " . intval($filter_category);
}

$where_clause = implode(" AND ", $where);

$query = "
    SELECT t.*, c.name as category_name 
    FROM tasks t 
    LEFT JOIN categories c ON t.category_id = c.id 
    WHERE $where_clause 
    ORDER BY t.deadline ASC
";
$tasks = $conn->query($query);

// Ambil kategori untuk filter
$categories = $conn->query("SELECT * FROM categories WHERE user_id = $user_id ORDER BY name");

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tugas - Task Management</title>
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
        <div class="header">
            <h2>Daftar Tugas</h2>
            <a href="create.php" class="btn">+ Tambah Tugas</a>
        </div>

        <form method="GET" class="filters">
            <label>Filter Status:</label>
            <select name="status" onchange="this.form.submit()">
                <option value="">Semua</option>
                <option value="todo" <?= $filter_status === 'todo' ? 'selected' : '' ?>>To Do</option>
                <option value="progress" <?= $filter_status === 'progress' ? 'selected' : '' ?>>In Progress</option>
                <option value="done" <?= $filter_status === 'done' ? 'selected' : '' ?>>Done</option>
            </select>

            <label>Filter Kategori:</label>
            <select name="category" onchange="this.form.submit()">
                <option value="">Semua</option>
                <?php while ($cat = $categories->fetch_assoc()): ?>
                    <option value="<?= $cat['id'] ?>" <?= $filter_category == $cat['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <?php if ($filter_status || $filter_category): ?>
                <a href="index.php" style="color: #dc3545; text-decoration: none;">Reset Filter</a>
            <?php endif; ?>
        </form>

        <div class="section">
            <?php if ($tasks->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Deadline</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($task = $tasks->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($task['title']) ?></strong><br>
                                    <small style="color: #666;"><?= htmlspecialchars(substr($task['description'], 0, 50)) ?>...</small>
                                </td>
                                <td><?= htmlspecialchars($task['category_name'] ?? '-') ?></td>
                                <td><?= date('d M Y', strtotime($task['deadline'])) ?></td>
                                <td>
                                    <span class="badge badge-<?= $task['status'] ?>">
                                        <?= ucfirst($task['status']) ?>
                                    </span>
                                </td>
                                <td class="actions">
                                    <a href="update_status.php?id=<?= $task['id'] ?>&status=<?= $task['status'] ?>">Ubah Status</a>
                                    <a href="edit.php?id=<?= $task['id'] ?>">Edit</a>
                                    <a href="delete.php?id=<?= $task['id'] ?>" class="delete" onclick="return confirm('Yakin ingin menghapus tugas ini?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty">Tidak ada tugas. Silakan tambah tugas baru.</div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
