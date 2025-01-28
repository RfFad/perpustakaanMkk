<?php
define('BASE_URL', 'http://localhost/perpustakaanMkk');

// Koneksi ke database
$host = 'localhost';    // Host database
$user = 'root';         // Username database
$pass = '';             // Password database (kosong untuk XAMPP)
$db   = 'wb_perpus';    // Nama database Anda

$conn = new mysqli($host, $user, $pass, $db);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
