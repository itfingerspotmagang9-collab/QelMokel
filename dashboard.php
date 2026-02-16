<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

require 'config/database.php';

$user_id = $_SESSION['user_id'];

// Total tugas
$total_tasks = $conn->query("SELECT COUNT(*) as count FROM tasks WHERE user_id = $user_id")->fetch_assoc()['count'];

// Jumlah tugas per status
$status_query = $conn->query("SELECT status, COUNT(*) as count FROM tasks WHERE user_id = $user_id GROUP BY status");
$status_counts = ['todo' => 0, 'progress' => 0, 'done' => 0];
while ($row = $status_query->fetch_assoc()) {
    $status_counts[$row['status']] = $row['count'];
}

// 5 tugas dengan deadline terdekat
$upcoming_tasks = $conn->query("
    SELECT t.*, c.name as category_name 
    FROM tasks t 
    LEFT JOIN categories c ON t.category_id = c.id 
    WHERE t.user_id = $user_id AND t.status != 'done' 
    ORDER BY t.deadline ASC 
    LIMIT 5
");

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Task Management</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
    <div class="navbar">
        <h1>Task Management System</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="tasks/index.php">Tugas</a>
            <a href="categories/index.php">Kategori</a>
            <a href="auth/logout.php">Logout</a>
        </nav>
    </div>

    <div class="container">
        <h2>Selamat datang, <?= htmlspecialchars($_SESSION['user_name']) ?>!</h2>
        <br>
        
        <div class="stats">
            <div class="stat-card total">
                <h3>Total Tugas</h3>
                <div class="number"><?= $total_tasks ?></div>
            </div>
            <div class="stat-card todo">
                <h3>To Do</h3>
                <div class="number"><?= $status_counts['todo'] ?></div>
            </div>
            <div class="stat-card progress">
                <h3>In Progress</h3>
                <div class="number"><?= $status_counts['progress'] ?></div>
            </div>
            <div class="stat-card done">
                <h3>Done</h3>
                <div class="number"><?= $status_counts['done'] ?></div>
            </div>
        </div>

        <div class="section">
            <h2>Tugas dengan Deadline Terdekat</h2>
            <?php if ($upcoming_tasks->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Deadline</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($task = $upcoming_tasks->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($task['title']) ?></td>
                                <td><?= htmlspecialchars($task['category_name'] ?? '-') ?></td>
                                <td><?= date('d M Y', strtotime($task['deadline'])) ?></td>
                                <td>
                                    <span class="badge badge-<?= $task['status'] ?>">
                                        <?= ucfirst($task['status']) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty">Tidak ada tugas yang perlu diselesaikan</div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
