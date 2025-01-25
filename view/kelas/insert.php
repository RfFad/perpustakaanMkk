<?php
include '../../koneksi.php';
session_start();
$title = "Insert Kelas";
$nama_kelas = "";
$tingkat = "";
$sukses = "";
$error = "";

if (isset($_GET['action'])) {
    $action = $_GET['action'];
} else {
    $action = "";
}

if($action === 'edit'){
    $id = $_GET['id'];
    $query = "SELECT * FROM kelas WHERE id_kelas = ? ";
    $sql = $koneksi->prepare($query);
    $sql->bind_param("i", $id);
    $sql->execute();
    $result = $sql->get_result();
    $r = $result->fetch_assoc();

    $nama_kelas = $r['nama_kelas'];
    $tingkat = $r['tingkat'];
    if(!$nama_kelas){
        $error = "Data tidak ditemukan";
    }
    $result->close();

}

if (isset($_POST['simpan'])) {
    $nama_kelas = $_POST['nama_kelas'];
    $tingkat = $_POST['tingkat'];
    if ($nama_kelas) {
        if ($action === 'edit') {
            $id = $_GET['id'];
            $query = "UPDATE kelas SET nama_kelas = ?, tingkat= ? WHERE id_kelas = ?";
            $sql = $koneksi->prepare($query);
            $sql->bind_param("ssi", $nama_kelas, $tingkat, $id);
            if ($sql->execute()) {
                $sukses = "Berhasil memperbarui data!";
            } else {
                $error = "Gagal memperbarui!";
            }
            $sql->close();
        } else {
            $query = "INSERT INTO kelas (nama_kelas, tingkat) VALUES (?,?)";
            $sql = $koneksi->prepare($query);
            $sql->bind_param("ss", $nama_kelas, $tingkat);
            if ($sql->execute()) {
                $sukses = "Berhasil menambahkan data!";
            } else {
                $error = "Gagal menambahkan data!";
            }
            $sql->close();
        }
    } else {
        $error = "Pastikan semua form terisi!";
    }
}
?>

<?php
include '../../layout/header.php';
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
                        <input type="number" name="nama_kelas" class="form-control" value="<?= htmlspecialchars($nama_kelas) ?>" require>
                    </div>
                    <div class="form-group">
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
<?php if ($sukses) { ?>
<script>
    $(document).ready(function () {
        Swal.fire({
            title: 'Berhasil!',
            text: <?= json_encode($sukses) ?>,
            icon: 'success',
            timer: 2000,
            showConfirmButton: false
        }).then(() => {
            window.location.href = "<?= BASE_URL ?>/view/kelas/index.php";
        });
    });
</script>
<?php } ?>

<?php if ($error) { ?>
<script>
    $(document).ready(function () {
        Swal.fire({
            title: 'Error!',
            text: <?= json_encode($error) ?>,
            icon: 'error',
            showConfirmButton: true
        }).then(()=>{
            window.location.href = "<?= BASE_URL ?>/view/kelas/insert.php";
        })
    });
</script>
<?php } ?>
<script>
    
        // $('#simpan-form').on('submit', function () {
        //     $('[name="simpan"]').attr('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Proses...');
        // });
    
</script>

<?php
include '../../layout/footer.php';
?>
