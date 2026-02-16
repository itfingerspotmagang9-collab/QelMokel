<?php
$conn = mysqli_connect("localhost", "root", "", "task_management");

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}