
<?php
$title = 'Data Kunjungan';

include '../../layout/header.php';
include '../../koneksi.php';
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
 <style>
        .modal-body {
            font-size: 16px;
        }
        .table-info td {
            padding: 10px;
            vertical-align: middle;
        }
        .signature {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 10px;
        }
        .signature img {
            border: 1px solid #ddd;
            padding: 5px;
            border-radius: 5px;
            max-width: 200px;
            height: auto;
        }
    </style>
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
            <table class="table table-striped tabelData table-responsive" id="example1" width="100%" cellspacing="0">
                <thead class="thead-dark">
                    <tr>
                    <th style="width:1%;">No</th>
                        <th>Nip/Nis</th>
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
                        <th>Nip/Nis</th>
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
                                COALESCE(s.alamat, a.alamat) AS alamat_pengunjung,
                                CONCAT(kl.tingkat, ' ', j.singkatan, ' ', kl.nama_kelas) AS kelas,
                                k.tanggal_kunjungan, 
                                k.keperluan_kunjungan, 
                                k.waktu_masuk, 
                                k.waktu_keluar, 
                                k.tujuan, 
                                k.ttd,
                                COALESCE(s.nis, a.nip) AS nip_nis
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
                                COALESCE(s.alamat, a.alamat) AS alamat_pengunjung,
                                CONCAT(kl.tingkat, ' ', j.singkatan, ' ', kl.nama_kelas) AS kelas,
                                k.tanggal_kunjungan, 
                                k.keperluan_kunjungan, 
                                k.waktu_masuk, 
                                k.waktu_keluar, 
                                k.tujuan, 
                                k.ttd,
                                 COALESCE(s.nis, a.nip) AS nip_nis
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
                        <td><?= $row['nip_nis']?></td>
                        <td><?= $row['nama_pengunjung']?></td>
                        <td><?= isset($row['kelas']) ? $row['kelas'] : 'Anggota' ?></td>
                        <td><?= date("Y-m-d", strtotime($row['tanggal_kunjungan'])); ?></td>
                        <td><?= $row['keperluan_kunjungan'] ?></td>
                        <td><?= date("H:i", strtotime($row['waktu_masuk'])) ?></td>
                        <td><?= date("H:i", strtotime($row['waktu_keluar'])) ?></td>
                       
                        <td>
                            <button type="button" class="btn btn-danger btn-sm hapus-btn" data-idhapus="<?= $row['id_kunjungan'] ?>">Hapus</button>
                            <button type="button" onclick="showModalUpdate('<?= $row['nip_nis'] ?>', '<?= $row['nama_pengunjung'] ?>', '<?= isset($row['kelas']) ? $row['kelas'] : 'Anggota' ?>', '<?= date('Y-m-d', strtotime($row['tanggal_kunjungan'])); ?>', '<?= $row['keperluan_kunjungan'] ?>', '<?= date('H:i', strtotime($row['waktu_masuk'])) ?>', '<?= date('H:i', strtotime($row['waktu_keluar'])) ?>', '<?= $row['alamat_pengunjung'] ?>')" class="btn btn-primary btn-sm">Detail</button>
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
<div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTitle">Informasi Pengunjung</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered ">
                    <tr>
                        <td><strong>Nis/Nip</strong></td>
                        <td id="nis-nip">122222</td>
                    </tr>
                    <tr>
                        <td><strong>Nama Pengunjung</strong></td>
                        <td id="nama_pengunjung">John Doe</td>
                    </tr>
                    <tr>
                        <td><strong>Kelas</strong></td>
                        <td id="kelas">XII IPA 1</td>
                    </tr>
                    <tr>
                        <td><strong>Alamat pengunjung</strong></td>
                        <td id="alamat"></td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Kunjungan</strong></td>
                        <td id="tanggal_kunjungan">28 Februari 2024</td>
                    </tr>
                    <tr>
                        <td><strong>Keperluan</strong></td>
                        <td id="keperluan">Membaca buku di perpustakaan</td>
                    </tr>
                    <tr>
                        <td><strong>Waktu Masuk</strong></td>
                        <td id="waktu_masuk">08:00</td>
                    </tr>
                    <tr>
                        <td><strong>Waktu Keluar</strong></td>
                        <td id="waktu_keluar">09:30</td>
                    </tr>
                    <tr>
                        <td><strong>Tanda Tangan</strong></td>
                        <td class="signature">
                            <img id="ttd" src="../../asset/ttd_kunjungan/ttd_1740737833.png" alt="Tanda Tangan">
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>   
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
<script>
    function showModalUpdate(nipNis, nama, kelas, tgl_kunjungan, keperluan, waktu_masuk, waktu_keluar, alamat){
        $('#nis-nip').text(nipNis)
        $('#nama_pengunjung').text(nama)
        $('#kelas').text(kelas)
        $('#tanggal_kunjungan').text(tgl_kunjungan)
        $('#keperluan').text(keperluan)
        $('#waktu_masuk').text(waktu_masuk)
        $('#waktu_keluar').text(waktu_keluar)
        $('#alamat').text(alamat)
        $('#infoModal').modal("show")
    }
    $(document).on('click', '.hapus-btn', function(){
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