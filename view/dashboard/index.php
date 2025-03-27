
<?php
$title = 'Dashboard';

include '../../koneksi.php';
include '../../layout/header.php';

if(!isset($_SESSION['username'])){
    $url = BASE_URL . "/auth/login.php";
    echo '<script language="javascript">alert("Harap anda login terlebih dahulu"); document.location="'. $url .'"</script>';
    exit;
  }
$allowed_role = ['admin', 'operator'];
if(!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowed_role)){
      session_destroy();
      echo "<script>alert('Akses ditolak! Anda tidak memiliki izin.'); window.location.href='../../auth/login.php';</script>";
      exit();
}
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
//end kunjungan
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container-fluid">

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
            class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
</div>

<!-- Content Row -->
 
<div class="row d-flex justify-content-center">

    <!-- Earnings (Monthly) Card Example -->
    <a href="../buku/index.php" class="col-xl-3 col-md-6 mb-4 text-decoration-none" id="card">
    <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        DATA BUKU
                    </div>
                    <?php
                        $query = $koneksi->prepare("SELECT COUNT(*) AS count_buku FROM buku");
                        $query->execute();
                        $result = $query->get_result();
                        $getdata = $result->fetch_assoc();
                        ?>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?= $getdata['count_buku'] ?>
                    </div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-book fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</a>


    <!-- Earnings (Monthly) Card Example -->
    <a href="../siswa/index.php" class="col-xl-3 col-md-6 mb-4 text-decoration-none" id="card">
    <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        DATA SISWA
                    </div>
                    <?php
                        $querySiswa = $koneksi->prepare("SELECT COUNT(*) AS count_siswa FROM siswa");
                        $querySiswa->execute();
                        $resultSiswa = $querySiswa->get_result();
                        $getSiswa = $resultSiswa->fetch_assoc();
                        ?>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?= $getSiswa['count_siswa']  ?>
                    </div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-users fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</a>

    <!-- Earnings (Monthly) Card Example -->
    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin') { ?> 
    <a href="../jurusan/index.php" class="col-xl-3 col-md-6 mb-4 text-decoration-none" id="card">
    <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        DATA JURUSAN
                    </div>
                    <?php
                        $queryjurusan = $koneksi->prepare("SELECT COUNT(*) AS count_jurusan FROM jurusan");
                        $queryjurusan->execute();
                        $resultjurusan = $queryjurusan->get_result();
                        $getjurusan = $resultjurusan->fetch_assoc();
                        ?>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?= $getjurusan['count_jurusan'] ?>
                    </div>  
                </div>
                <div class="col-auto">
                    <i class="fas fa-wrench fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</a>

    <!-- Pending Requests Card Example -->
    <a href="../kelas/index.php" class="col-xl-3 col-md-6 mb-4 text-decoration-none" id="card">
    <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        DATA KELAS
                    </div>
                    <?php
                        $querykelas = $koneksi->prepare("SELECT COUNT(*) AS count_kelas FROM kelas");
                        $querykelas->execute();
                        $resultkelas = $querykelas->get_result();
                        $getkelas = $resultkelas->fetch_assoc();
                        ?>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?= $getkelas['count_kelas'] ?>
                    </div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-ruler-combined fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</a>
<?php } ?>
<!-- User -->
<a href="../user/index.php" class="col-xl-4 col-md-6 mb-4 text-decoration-none" id="card">
    <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                        DATA USER
                    </div>
                    <?php
                        $queryuser = $koneksi->prepare("SELECT COUNT(*) AS count_user FROM admin");
                        $queryuser->execute();
                        $resultuser = $queryuser->get_result();
                        $getuser = $resultuser->fetch_assoc();
                        ?>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?= $getuser['count_user'] ?>
                    </div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-user fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</a>
<!-- End User -->
  
<!-- peminjaman -->
<a href="../peminjaman/index.php" class="col-xl-4 col-md-6 mb-4 text-decoration-none" id="card">
    <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                        DATA peminjaman
                    </div>
                    <?php
                        $querypeminjaman = $koneksi->prepare("SELECT COUNT(*) AS count_peminjaman FROM peminjaman");
                        $querypeminjaman->execute();
                        $resultpeminjaman = $querypeminjaman->get_result();
                        $getpeminjaman = $resultpeminjaman->fetch_assoc();
                        ?>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?= $getpeminjaman['count_peminjaman'] ?>
                    </div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-address-book fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</a>
<!-- End peminjaman -->
<!-- kunjungan -->
<a href="../kunjungan/index.php" class="col-xl-4 col-md-6 mb-4 text-decoration-none" id="card">
    <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                        DATA kunjungan
                    </div>
                    <?php
                        $querykunjungan = $koneksi->prepare("SELECT COUNT(*) AS count_kunjungan FROM kunjungan");
                        $querykunjungan->execute();
                        $resultkunjungan = $querykunjungan->get_result();
                        $getkunjungan = $resultkunjungan->fetch_assoc();
                        ?>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        <?= $getkunjungan['count_kunjungan'] ?>
                    </div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-book-open fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</a>
<!-- End kunjungan -->
<div class="col-md-12 mb-3">
<div class="card">
    <div class="card-header">
    <h6 class="m-0 font-weight-bold text-primary">Grafik Pengunjung</h6>
    </div>
    <div class="card-body">
    <canvas id="myChart" style = "width: 100%; height: 400px;"></canvas>
    </div>
</div>
</div>
<div class="col-md-12 mb-3">
<div class="card">
    <div class="card-header">
    <h6 class="m-0 font-weight-bold text-primary">Grafik Peminjaman</h6>
    </div>
    <div class="card-body">
    <canvas id="myChartPeminjaman" style = "width: 100%; height: 400px;"></canvas>
    </div>
</div>
</div>

<!-- Content Row -->

</div>

</div>
<!-- /.container-fluid -->
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
<?php include '../../layout/footer.php'; ?>
