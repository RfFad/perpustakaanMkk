
<?php
$title = 'Data Kunjungan';

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
$sukses = "";
$error = "";


if (isset($_GET['action'])) {
    $action = $_GET['action'];
} else {
    $action = "";
}


if($action === 'hapus'){
    $id = $_GET['id'];
    //untuk menghapus dan mengecek
    $queryData = $koneksi->prepare("SELECT * FROM kunjungan WHERE id_kunjungan = ? ");
    $queryData->bind_param("i", $id);
    $queryData->execute();
    $resultData = $queryData->get_result();
    $rowData = $resultData->fetch_assoc();

    //query delete
    $query = "DELETE FROM kunjungan WHERE id_kunjungan = ?";
    $sql = $koneksi->prepare($query);
    $sql->bind_param("i", $id);

    if($sql->execute()){
        unlink('../../asset/ttd_kunjungan/' . $rowData['ttd']);
        header('Location: index.php');
        return $_SESSION['sukses'] = "Berhasil menghapus data!";

    }else {
        header('Location: index.php');
        return $_SESSION['error']= "Gagal menghapus data!";
    }
    if(!$id){
        header('Location: index.php');
        return $_SESSION['error'] = "Data tidak ditemukan!";
    }
    $sql->close();
}

if(isset($_POST['update'])){
    $nama_kelas = $_POST['nama_kelas'];
    $tingkat = $_POST['tingkat'];
    $id_kelas = $_POST['id_kelas'];
            $query = "UPDATE kelas SET nama_kelas = ?, tingkat= ? WHERE id_kelas = ?";
            $sql = $koneksi->prepare($query);
            $sql->bind_param("ssi", $nama_kelas, $tingkat, $id_kelas);
            if ($sql->execute()) {
                $_SESSION['sukses'] = "Berhasil memperbarui data!";
            } else {
                $_SESSION['gagal'] = "Gagal memperbarui!";
            }
            $sql->close();
}
?>

<!-- DataTales Example -->
<!-- Begin Page Content -->
<div class="container-fluid">

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Data Pengunjung</h1>
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between">
        <a href="<?= BASE_URL ?>/view/kunjungan/insert.php" class="btn btn-sm btn-success d-flex align-items-center"><i class="fas fa-plus mr-2"></i> tambah data</a>

        <nav class="nav nav-pills nav-fill">
        <a class="nav-link <?= isset($_GET['d']) ? '' : 'active' ?>" href="index.php">Hari ini</a>
        <a class="nav-link <?= isset($_GET['d']) ? 'active' : '' ?>" href="index.php?d=semua">Semua</a>
        </nav>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped tabelData" id="example1" width="100%" cellspacing="0">
                <thead class="thead-dark">
                    <tr>
                    <th style="width:1%;">No</th>
                        <th>Nama Pengunjung</th>
                        <th>Kelas</th>
                        <th>Tanggal Kunjungan</th>
                        <th>Keperluan/Tujuan Kunjungan</th>
                        <th>Waktu Masuk</th>
                        <th>Waktu Keluar</th>
                        <th style="width:15%">Action</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th style="width:1%;">No</th>
                        <th>Nama Pengunjung</th>
                        <th>Kelas</th>
                        <th>Tanggal Kunjungan</th>
                        <th>Keperluan/Tujuan Kunjungan</th>
                        <th>Waktu Masuk</th>
                        <th>Waktu Keluar</th>
                        <th style="width:15%">Action</th>
                    </tr>
                </tfoot>
                <tbody>
                <?php 
                    $query = "";

                    if(isset($_GET['d'])){
                        $query = "SELECT 
                                k.id_kunjungan, 
                                COALESCE(s.nama, a.nama_anggota) AS nama_pengunjung,
                                CONCAT(kl.tingkat, ' ', j.singkatan, ' ', kl.nama_kelas) AS kelas,
                                k.tanggal_kunjungan, 
                                k.keperluan_kunjungan, 
                                k.waktu_masuk, 
                                k.waktu_keluar, 
                                k.tujuan, 
                                k.ttd
                            FROM kunjungan k
                            LEFT JOIN siswa s ON k.id_siswa = s.id_siswa
                            LEFT JOIN data_keanggotaan a ON k.id_anggota = a.id_anggota
                            LEFT JOIN kelas kl ON s.id_kelas = kl.id_kelas
                            LEFT JOIN jurusan j ON s.id_jurusan = j.id_jurusan ORDER BY k.id_kunjungan DESC
                            ";
                    }else{
                        $query = "SELECT 
                                k.id_kunjungan, 
                                COALESCE(s.nama, a.nama_anggota) AS nama_pengunjung,
                                CONCAT(kl.tingkat, ' ', j.singkatan, ' ', kl.nama_kelas) AS kelas,
                                k.tanggal_kunjungan, 
                                k.keperluan_kunjungan, 
                                k.waktu_masuk, 
                                k.waktu_keluar, 
                                k.tujuan, 
                                k.ttd
                            FROM kunjungan k
                            LEFT JOIN siswa s ON k.id_siswa = s.id_siswa
                            LEFT JOIN data_keanggotaan a ON k.id_anggota = a.id_anggota
                            LEFT JOIN kelas kl ON s.id_kelas = kl.id_kelas
                            LEFT JOIN jurusan j ON s.id_jurusan = j.id_jurusan 
                            WHERE DATE(k.tanggal_kunjungan) = CURDATE() ORDER BY k.id_kunjungan DESC
                            ";
                    }
                    $sql = mysqli_query($koneksi, $query);
                    $no = 1;
                    while($row = mysqli_fetch_array($sql)){
                     ?>
                      <tr>
                        <td><?= $no ++ ?></td>
                        <td><?= $row['nama_pengunjung']?></td>
                        <td><?= isset($row['kelas']) ? $row['kelas'] : 'Anggota' ?></td>
                        <td><?= date("Y-m-d", strtotime($row['tanggal_kunjungan'])); ?></td>
                        <td><?= $row['keperluan_kunjungan'] ?></td>
                        <td><?= date("H:i", strtotime($row['waktu_masuk'])) ?></td>
                        <td><?= date("H:i", strtotime($row['waktu_keluar'])) ?></td>
                       
                        <td>
                            <button type="button" class="btn btn-danger btn-sm hapus-btn" data-idhapus="<?= $row['id_kunjungan'] ?>">Hapus</button>
                            <button type="button" class="btn btn-primary btn-sm">Detail</button>
                        </td>
                    </tr>   
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</div>
<!-- /.container-fluid -->  
<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Update Data Jurusan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" id="id_kelas" name="id_kelas">
                    <div class="form-group">
                        <label for="nama_kelas">Nama kelas</label>
                        <input type="number" class="form-control" id="nama_kelas" name="nama_kelas" required>
                    </div>
                    <div class="form-group">
                        <label for="tingkatan">tingkatan</label>
                        <select name="tingkat" id="tingkat" class="form-control" require>
                            <option disabled selected>Selected</option>
                            <option value="X">X</option>
                            <option value="XI">XI</option>
                            <option value="XII">XII</option>
                          
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="update" class="btn btn-primary"><i class="fas fa-save"></i> Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>    
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
<script>
    function showModalUpdate(nama_kelas, tingkat, id_kelas){
        $('#nama_kelas').val(nama_kelas)
        $('#tingkat').val(tingkat).attr('selected', true);
        $('#id_kelas').val(id_kelas)
        $('#updateModal').modal("show")
    }
    $(document).ready(function(){
        
        $('.hapus-btn').on('click', function(){
            const idhapus = $(this).data('idhapus')
                        Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda tidak akan dapat mengembalikan data ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
                }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `<?= BASE_URL ?>/view/kunjungan/index.php?action=hapus&id=${idhapus}`;
                }
                });
  
        });
        
    })
</script>
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
<?php
include '../../layout/footer.php';
 ?>