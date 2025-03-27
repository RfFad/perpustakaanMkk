
<?php
define('BASE_URL', 'https://perpustakaan-neper-one.vercel.app/perpustakaanMkk');

// Koneksi ke database
$host = 'bebodljqmcdzswxl1o6m-mysql.services.clever-cloud.com';    // Host database
$user = 'uzg3tuvtphtnnhxb';         // Username database
$pass = 'mmdZfLIzvlhfCfBRKrvB';             // Password database (kosong untuk XAMPP)
$db   = 'bebodljqmcdzswxl1o6m';    // Nama database Anda

$conn = new mysqli($host, $user, $pass, $db);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
