<?php
// data.php
header('Content-Type: application/json');

// Contoh data kunjungan, Anda bisa menggantinya dengan data dari database
$data = [
    'labels' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
    'values' => [50, 75, 100, 80, 90, 120, 60] // Jumlah kunjungan per hari
];

echo json_encode($data);
?>