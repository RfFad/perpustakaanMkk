<?php
include '../../layout/header.php';

// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'wb_perpus';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<div class="container-fluid">

<!-- Page Heading -->

<!-- DataTales Example -->
<div class="d-flex justify-content-center vh-50">

<div class="card shadow mb-4 w-50">
    <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Insert Jurusan</h6>
    </div>
    <div class="card-body">
        <!-- Form handling -->
        <?php
        $message = "";
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama_jurusan = $_POST['nama_jurusan'] ?? '';
            $singkatan = $_POST['singkatan'] ?? '';

            if (!empty($nama_jurusan) && !empty($singkatan)) {
                // Prepare SQL statement
                $stmt = $conn->prepare("INSERT INTO jurusan (nama_jurusan, singkatan) VALUES (?, ?)");
                $stmt->bind_param("ss", $nama_jurusan, $singkatan);

                if ($stmt->execute()) {
                    $message = "<div class='alert alert-success'>Data berhasil disimpan!</div>";
                } else {
                    $message = "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
                }

                $stmt->close();
            } else {
                $message = "<div class='alert alert-warning'>Mohon isi semua field!</div>";
            }
        }
        echo $message;
        ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="nama_jurusan">Nama Jurusan</label>
                <input type="text" class="form-control" id="nama_jurusan" name="nama_jurusan" placeholder="Masukkan Nama Jurusan">
            </div>
            <div class="form-group">
                <label for="singkatan">Singkatan</label>
                <input type="text" class="form-control" id="singkatan" name="singkatan" placeholder="Masukkan Singkatan">
            </div>
    </div>
    <div class="card-footer">
        <a href="<?= BASE_URL ?>/view/jurusan/index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
        </form>
    </div>
</div>
</div>

</div>
<!-- /.container-fluid -->
<?php
$conn->close();
include '../../layout/footer.php';
?>
