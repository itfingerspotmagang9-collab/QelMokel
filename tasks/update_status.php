<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require '../config/database.php';

$user_id = $_SESSION['user_id'];
$task_id = $_GET['id'] ?? 0;
$current_status = $_GET['status'] ?? 'todo';

// Tentukan status berikutnya
$next_status = [
    'todo' => 'progress',
    'progress' => 'done',
    'done' => 'todo'
];

$new_status = $next_status[$current_status] ?? 'todo';

$stmt = $conn->prepare("UPDATE tasks SET status = ? WHERE id = ? AND user_id = ?");
$stmt->bind_param("sii", $new_status, $task_id, $user_id);
$stmt->execute();
$stmt->close();

$conn->close();

header("Location: index.php");
exit();
