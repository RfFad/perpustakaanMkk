
<?php
$title = "Insert Kelas";
include '../../layout/header.php';

include '../../koneksi.php';

if(!isset($_SESSION['username'])){
    $url = BASE_URL . "/auth/login.php";
    echo '<script language="javascript">alert("Harap anda login terlebih dahulu"); document.location="'. $url .'"</script>';
    exit;
  }
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    $urlBack = BASE_URL . "/view/dashboard/index.php";
    echo '<script language="javascript">alert("Anda tidak bisa mengakses halaman ini, karena anda bukan admin!"); document.location="' . $urlBack . '"</script>';
    exit;
}

$nama_kelas = "";
$tingkat = "";
$sukses = "";
$error = "";

if (isset($_GET['action'])) {
    $action = $_GET['action'];
} else {
    $action = "";
}



if (isset($_POST['simpan'])) {
    $nama_kelas = $_POST['nama_kelas'];
    $tingkat = $_POST['tingkat'];
    if ($nama_kelas) {
            $query = "INSERT INTO kelas (nama_kelas, tingkat) VALUES (?,?)";
            $sql = $koneksi->prepare($query);
            $sql->bind_param("ss", $nama_kelas, $tingkat);
            if ($sql->execute()) {
                header('Location: index.php');
                return $_SESSION['sukses'] = "Berhasil menambahkan data!";
            } else {
                header('Location: index.php');
                return $_SESSION['error'] = "Gagal menambahkan data!";
            }
            $sql->close();
        
    } else {
        $error = "Pastikan semua form terisi!";
    }
}
?>

<div class="container-fluid">
    <div class="d-flex justify-content-center vh-50">
        <div class="card shadow mb-4 w-50">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Insert Kelas</h6>
            </div>
            <form action="" method="post" id="simpan-form" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="form-group">
                        <label for="nama_kelas">Nama kelas</label>
                        <input type="number" name="nama_kelas" class="form-control" value="<?= htmlspecialchars($nama_kelas) ?>" require>
                    </div>
                    <div class="form-group">
                        <label for="">Tingkatan</label>
                        <select name="tingkat" id="" class="form-control" require>
                            <option disabled selected>Selected</option>
                            <option value="X" <?= htmlspecialchars($tingkat) === 'X' ? 'selected' : '' ?>>X</option>
                            <option value="XI" <?= htmlspecialchars($tingkat) === 'XI' ? 'selected' : '' ?>>XI</option>
                            <option value="XII" <?= htmlspecialchars($tingkat) === 'XII' ? 'selected' : '' ?>>XII</option>
                          
                        </select>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="<?= BASE_URL ?>/view/kelas/index.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" name="simpan" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if (!empty($_SESSION['sukses'])) { ?>
    <script>
        $(document).ready(function () {
            Swal.fire({
                title: 'Berhasil!',
                text: <?= json_encode($_SESSION['sukses']) ?>,
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            })
        });
    </script>
    <?php unset($_SESSION['sukses']); // Hapus pesan sukses dari session ?>
<?php } ?>
<?php if (!empty($_SESSION['error'])) { ?>
    <script>
        $(document).ready(function () {
            Swal.fire({
                title: 'Gagal!',
                text: <?= json_encode($_SESSION['error']) ?>,
                icon: 'error',
                showConfirmButton: true
            })
        });
    </script>
    <?php unset($_SESSION['error']); // Hapus pesan sukses dari session ?>
<?php } ?>
<script>
    
        // $('#simpan-form').on('submit', function () {
        //     $('[name="simpan"]').attr('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Proses...');
        // });
    
</script>

<?php
include '../../layout/footer.php';
?>
