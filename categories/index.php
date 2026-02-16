<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require '../config/database.php';

$user_id = $_SESSION['user_id'];

// Ambil semua kategori user dengan jumlah tugas
$query = "
    SELECT c.*, COUNT(t.id) as task_count 
    FROM categories c 
    LEFT JOIN tasks t ON c.id = t.category_id 
    WHERE c.user_id = $user_id 
    GROUP BY c.id 
    ORDER BY c.created_at DESC
";
$categories = $conn->query($query);

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori - Task Management</title>
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
        <div class="header">
            <h2>Daftar Kategori</h2>
            <a href="create.php" class="btn">+ Tambah Kategori</a>
        </div>

        <div class="section">
            <?php if ($categories->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Nama Kategori</th>
                            <th>Jumlah Tugas</th>
                            <th>Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($cat = $categories->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($cat['name']) ?></td>
                                <td><?= $cat['task_count'] ?> tugas</td>
                                <td><?= date('d M Y', strtotime($cat['created_at'])) ?></td>
                                <td class="actions">
                                    <a href="edit.php?id=<?= $cat['id'] ?>">Edit</a>
                                    <a href="delete.php?id=<?= $cat['id'] ?>" class="delete"
                                        onclick="return confirm('Yakin ingin menghapus kategori ini?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty">Belum ada kategori. Silakan tambah kategori baru.</div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>