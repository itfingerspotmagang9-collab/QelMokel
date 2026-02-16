<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require '../config/database.php';

$user_id = $_SESSION['user_id'];
$category_id = $_GET['id'] ?? 0;

// Hapus kategori (tasks akan terhapus otomatis karena ON DELETE CASCADE)
$stmt = $conn->prepare("DELETE FROM categories WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $category_id, $user_id);
$stmt->execute();
$stmt->close();

$conn->close();

header("Location: index.php");
exit();
