<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require '../config/database.php';

$user_id = $_SESSION['user_id'];
$task_id = $_GET['id'] ?? 0;

$stmt = $conn->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $task_id, $user_id);
$stmt->execute();
$stmt->close();

$conn->close();

header("Location: index.php");
exit();
