<?php 
include '../../koneksi.php';

// Grafik Kunjungan
$sql = $koneksi->prepare("SELECT DATE(tanggal_kunjungan) AS tanggal, COUNT(*) AS jumlah FROM kunjungan GROUP BY tanggal ORDER BY tanggal ASC");
$sql->execute();
$result = $sql->get_result();

$tanggal_kunjungan = [];
$jumlah_kunjungan = [];

while($row = $result->fetch_assoc()){
    $tanggal_kunjungan[] = $row['tanggal'];
    $jumlah_kunjungan[] = $row['jumlah'];
}
$result->close();

// Grafik Peminjaman
$sqlPeminjaman = $koneksi->prepare("SELECT DATE(tanggal_pinjam) AS tanggal, COUNT(*) AS jumlah FROM peminjaman GROUP BY tanggal ORDER BY tanggal ASC");
$sqlPeminjaman->execute();
$resultPinjam = $sqlPeminjaman->get_result();

$tanggal_peminjaman = [];
$jumlah_peminjaman = [];

while($row = $resultPinjam->fetch_assoc()){
    $tanggal_peminjaman[] = $row['tanggal'];
    $jumlah_peminjaman[] = $row['jumlah'];
}
$resultPinjam->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grafik Kunjungan dengan Chart.js</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Grafik Kunjungan BK Harian</h1>
    <canvas id="myChart" style="width: 100%; height: 400px;"></canvas>
    <br>
    <canvas id="myChartPeminjaman" style="width: 100%; height: 400px;"></canvas>
    
    <script>
        // Grafik Kunjungan
        const ctx = document.getElementById('myChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($tanggal_kunjungan) ?>,
        datasets: [{
            label: 'Jumlah Kunjungan',
            data: <?php echo json_encode($jumlah_kunjungan) ?>,
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1,  // Agar hanya menampilkan angka bulat
                    precision: 0  // Menghindari desimal
                }
            }
        }
    }
});

// Grafik Peminjaman
const ctxPinjam = document.getElementById('myChartPeminjaman').getContext('2d');
new Chart(ctxPinjam, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($tanggal_peminjaman) ?>,
        datasets: [{
            label: 'Jumlah Peminjaman',
            data: <?php echo json_encode($jumlah_peminjaman) ?>,
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgb(181, 7, 250)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1,
                    precision: 0
                }
            }
        }
    }
});

    </script>
</body>
</html>
