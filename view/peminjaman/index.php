<?php
$title = 'Kelas';

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

if(isset($_GET['status'])){
    $status = $_GET['status'];
}else{
    $status = "";
}

if($action === 'hapus'){
    $url = BASE_URL . "/view/peminjaman/index.php";
    $id = $_GET['id'];
    $query = "DELETE FROM peminjaman WHERE id_peminjaman = ?";
    $sql = $koneksi->prepare($query);
    $sql->bind_param("i", $id);
    if($sql->execute()){
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
                header('Location: index.php');
                return $_SESSION['sukses'] = "Berhasil memperbarui data!";
            } else {
                header('Location: index.php');
                return $_SESSION['gagal'] = "Gagal memperbarui!";
            }
           
            $sql->close();
}
//update status
$query = $koneksi->prepare("UPDATE peminjaman SET status = 'Melebihi waktu' WHERE tanggal_kembali < CURDATE() AND status='Dipinjam'");
$query->execute();
$queryStatus = $koneksi->prepare("UPDATE peminjaman SET status = 'Dipinjam' WHERE tanggal_kembali > CURDATE() AND status='Melebihi waktu'");
$queryStatus->execute();
?>
<style>
  .text-center.profile-photo {
  display: flex;
  justify-content: center;
  align-items: center;
}

.profile-status-border {
  width: 110px;
  height: 110px;
  display: flex;
  justify-content: center;
  align-items: center;
  border-radius: 50%;
  padding: 5px;
  position: relative;
}

.profile-user-img {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid #fff;
}



</style>
<!-- DataTales Example -->
<!-- Begin Page Content -->
<div class="container-fluid">

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Data Peminjaman</h1>
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between">
        <a href="<?= BASE_URL ?>/view/peminjaman/scan.php" class="btn btn-sm btn-success d-flex align-items-center"><i class="fas fa-plus mr-2"></i> tambah data</a>
        <div class="button-data">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link <?= $status === '' ? 'active' : '' ?> " href="index.php">Hari ini</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $status === 'semua' ? 'active' : '' ?>" href="index.php?status=semua">Semua</a>
                </li>
                <li class="nav-item dropdown d-flex align-items-center ">
                    <a class="nav-link dropdown-toggle <?= $status === 'dipinjam' || $status === 'dikembalikan' || $status === 'melebihiwaktu'  ? 'active' : '' ?>" data-toggle="dropdown" href="#" role="button" aria-expanded="false">Status</a>
                    <div class="dropdown-menu">
                    <a class="dropdown-item" href="index.php?status=dipinjam">Dipinjam</a>
                    <a class="dropdown-item" href="index.php?status=dikembalikan">Dikembalikan</a>
                    <a class="dropdown-item" href="index.php?status=melebihiwaktu">Melebihi waktu</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered   table-striped tabelData" id="example1" width="100%" cellspacing="0">
                <thead class= "thead-dark">
                    
                    <tr class="text-center" >
                    <th style="width:1%;">No</th>
                        <th>Nama Peminjam</th>
                        <th>No Tlp</th>
                        <th>Buku</th>
                        <th>Tanggal Peminjaman</th>
                        <th>Tanggal Pengembalian</th>
                        <th>Status</th>
                        <th style="width:10%">Action</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th style="width:1%;">No</th>
                        <th>Nama Peminjam</th>
                        <th>No Tlp</th>
                        <th>Buku</th>
                        <th>Tanggal Peminjaman</th>
                        <th>Tanggal Pengembalian</th>
                        <th>Status</th>
                        <th style="width:17%">Action</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php 
                    $query = "SELECT pj.*, b.judul AS nama_buku, b.barcode AS barcode_buku, s.nama AS nama_siswa, s.telepon, s.id_siswa, s.foto AS foto_siswa, s.nis, s.telepon, CONCAT(k.tingkat, ' ', j.singkatan, ' ', k.nama_kelas) AS nama_kelas FROM peminjaman pj JOIN buku b ON pj.id_buku = b.id_buku JOIN siswa s ON pj.id_siswa = s.id_siswa JOIN kelas k ON s.id_kelas = k.id_kelas JOIN jurusan j ON s.id_jurusan = j.id_jurusan  order by pj.id_peminjaman DESC";
                    if(!isset($_GET['status'])){
                        $queryData = "SELECT pj.*, b.judul AS nama_buku, b.barcode AS barcode_buku, s.nama AS nama_siswa, s.telepon, s.id_siswa, s.foto AS foto_siswa, s.nis, s.telepon, CONCAT(k.tingkat, ' ', j.singkatan, ' ', k.nama_kelas) AS nama_kelas FROM peminjaman pj JOIN buku b ON pj.id_buku = b.id_buku JOIN siswa s ON pj.id_siswa = s.id_siswa JOIN kelas k ON s.id_kelas = k.id_kelas JOIN jurusan j ON s.id_jurusan = j.id_jurusan WHERE DATE(pj.tanggal_pinjam) = CURDATE() OR DATE(pj.tanggal_kembali) = CURDATE() order by pj.id_peminjaman DESC";
                    }else if($_GET['status'] === 'dipinjam'){
                        $queryData = "SELECT pj.*, b.judul AS nama_buku, b.barcode AS barcode_buku, s.nama AS nama_siswa, s.telepon, s.id_siswa, s.foto AS foto_siswa, s.nis, s.telepon, CONCAT(k.tingkat, ' ', j.singkatan, ' ', k.nama_kelas) AS nama_kelas FROM peminjaman pj JOIN buku b ON pj.id_buku = b.id_buku JOIN siswa s ON pj.id_siswa = s.id_siswa JOIN kelas k ON s.id_kelas = k.id_kelas JOIN jurusan j ON s.id_jurusan = j.id_jurusan WHERE pj.status = 'Dipinjam'  order by pj.id_peminjaman DESC";
                    }else if($_GET['status'] === 'dikembalikan'){
                        $queryData = "SELECT pj.*, b.judul AS nama_buku, b.barcode AS barcode_buku, s.nama AS nama_siswa, s.telepon, s.id_siswa, s.foto AS foto_siswa, s.nis, s.telepon, CONCAT(k.tingkat, ' ', j.singkatan, ' ', k.nama_kelas) AS nama_kelas FROM peminjaman pj JOIN buku b ON pj.id_buku = b.id_buku JOIN siswa s ON pj.id_siswa = s.id_siswa JOIN kelas k ON s.id_kelas = k.id_kelas JOIN jurusan j ON s.id_jurusan = j.id_jurusan WHERE pj.status = 'Dikembalikan'  order by pj.id_peminjaman DESC";
                    }else if($_GET['status'] === 'melebihiwaktu'){
                        $queryData = "SELECT pj.*, b.judul AS nama_buku, b.barcode AS barcode_buku, s.nama AS nama_siswa, s.telepon, s.id_siswa, s.foto AS foto_siswa, s.nis, s.telepon, CONCAT(k.tingkat, ' ', j.singkatan, ' ', k.nama_kelas) AS nama_kelas FROM peminjaman pj JOIN buku b ON pj.id_buku = b.id_buku JOIN siswa s ON pj.id_siswa = s.id_siswa JOIN kelas k ON s.id_kelas = k.id_kelas JOIN jurusan j ON s.id_jurusan = j.id_jurusan WHERE pj.status = 'Melebihi waktu'  order by pj.id_peminjaman DESC";
                    }else if($_GET['status'] === 'semua'){
                        $queryData = $query;
                    }

                    $queryKonek = $koneksi->prepare($queryData);
                    $queryKonek->execute();
                    $result = $queryKonek->get_result();
                    $no = 1;
                    while($row = $result->fetch_array()){
                     ?>
                      <tr>
                        <td><?= $no ++ ?></td>
                        <td><?= htmlspecialchars($row['nama_siswa']) ?></td>
                        <td><?= htmlspecialchars($row['telepon']) ?></td>
                        <td><?= htmlspecialchars($row['nama_buku']) ?></td>
                        <td><?= htmlspecialchars($row['tanggal_pinjam']) ?></td>
                        <td><?= htmlspecialchars($row['tanggal_kembali']) ?></td>
                        <td><span class="badge <?= $row['status'] === 'Dipinjam' ? 'badge-warning' : ($row['status'] === 'Dikembalikan' ? 'badge-success' : 'badge-danger') ?>">
                            <?= $row['status'] ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="#" data-toggle="modal" onclick="showModalUpdate('<?= $row['id_siswa'] ?>', '<?= $row['id_peminjaman'] ?>', '<?= $row['nis'] ?>', '<?= $row['telepon'] ?>', '<?= $row['nama_siswa'] ?>', '<?= $row['foto_siswa'] ?>', '<?= $row['nama_kelas'] ?>', '<?= $row['barcode_buku'] ?>', '<?= $row['tanggal_kembali'] ?>', '<?= $row['status'] ?>')" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i> edit</a>
                            <a href="#"  class="btn btn-danger btn-sm hapus-btn" data-idhapus = "<?= $row['id_peminjaman'] ?>"><i class="fas fa-trash"></i> hapus</a>
                        </td>
                    </tr>   
                    <?php } $result->close() ?>
                   
                   
                </tbody>
            </table>
        </div>
    </div>
</div>

</div>
<!-- /.container-fluid -->  
<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Update Data Peminjaman</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="input_pinjam.php">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card border-bottom-primary">
                                <div class="card-body box-profile">
                                    <div class="text-center profile-photo">
                                        <div class="profile-status-border bg-secondary">
                                            <img class="profile-user-img img-fluid rounded-circle" src="profile.jpg" id="foto_siswa" alt="User profile picture">
                                        </div>
                                    </div>
                                    <h4 class="profile-username text-center" id="nama_siswa"></h4>
                                    <p class="text-muted text-center" id="nis"></p>
                                    <ul class="list-group list-group-unbordered mb-3">
                                        <li class="list-group-item">
                                            <b>Kelas</b> <a class="float-right" id="nama_kelas"></a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Tlp</b> <a class="float-right" id="telepon"></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="card shadow card-primary border-bottom-primary card-outline">
                                <div class="card-body">
                                    <div class="pencarian">
                                        <div class="form-group">
                                            <label for="">Pilih buku yang di pinjam :</label>
                                            <select class="selectpicker form-control" id="barcodeSelected" data-live-search="true">
                                                <option disabled selected>-- Pilih Buku Yang Akan dipinjam --</option>
                                                <?php 
                                                    $queryBuku = $koneksi->prepare("SELECT * FROM buku");
                                                    $queryBuku->execute(); 
                                                    $resultBuku = $queryBuku->get_result();
                                                    while($row = $resultBuku->fetch_array()) { 
                                                ?>
                                                    <option data-idBuku="<?= $row['id_buku'] ?>" value="<?= $row['barcode'] ?>">
                                                        <?= $row['judul'] ?> - <?= $row['barcode'] ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="confirm">
                                            <button type="button" class="btn btn-primary" id="confirmButton">Confirm</button>
                                        </div>
                                    </div>
                                    <div class="bukuData d-none">
                                        <div class="row">
                                            <div class="col-md-3 text-center">
                                                <img class="profile-user-img img-fluid rounded shadow" src="" id="fotoBuku" alt="User profile picture" style="width: 100%; height: 200px; object-fit: cover;">
                                            </div>
                                            <div class="col-md-6">
                                                <h3 class="card-title fw-bold" id="judul">Judul Buku</h3>
                                                <ul class="list-unstyled fs-5">
                                                    <li class="d-flex">
                                                        <strong class="me-2" style="min-width: 100px;">Penerbit</strong>: 
                                                        <span id="penerbit" class="ml-2">-</span>
                                                    </li>
                                                    <hr>
                                                    <li class="d-flex">
                                                        <strong class="me-2" style="min-width: 100px;">Pengarang</strong>: 
                                                        <span id="pengarang" class="ml-2">-</span>
                                                    </li>
                                                    <hr>
                                                    <li class="d-flex">
                                                        <strong class="me-2" style="min-width: 100px;">Tahun Terbit</strong>: 
                                                        <span id="tahunTerbit" class="ml-2">-</span>
                                                    </li>
                                                    <hr>
                                                </ul>
                                            </div>
                                            <div class="col-md-12">
                                                <input type="hidden" name="id_buku" class="form-control">
                                                <input type="hidden" name="id" class="form-control">
                                                <input type="hidden" name="id_admin" class="form-control" value="<?= $_SESSION['id_admin'] ?>">
                                                <div class="row">
                                                  <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">Tanggal Pengembalian :</label>
                                                    <input type="date" name="tanggal_kembali" class="form-control">
                                                </div>
                                                  </div>
                                                  <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="">Status</label>
                                                    <select name="status" id="status" class="form-control">
                                                        <option disabled selected>-- Select Status --</option>
                                                        <option value="Dipinjam">Dipinjam</option>
                                                        <option value="Dikembalikan">Dikembalikan</option>
                                                        <option value="Melebihi waktu">Melebihi waktu</option>
                                                    </select>
                                                </div>
                                                   </div>
                                                </div>
                                                <button type="button" id="gantiBuku" class="btn btn-secondary">Ganti Buku</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="update_pinjam" class="btn btn-primary"><i class="fas fa-save"></i> Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
<script>
    function dataBuku(barcode_buku){
        //const barcode = $("#barcodeSelected").val();
      $.ajax({
        url: `proses_scan.php?action=buku&barcode=${barcode_buku}`,
        method: "GET",
        dataType: "json",
        success: function (response) {
          if (response.success) {
            $(".pencarian").slideDown(400, function () {
              $(".bukuData").hide().removeClass("d-none").fadeIn(400);
              $(".pencarian").addClass("d-none");
            });
            $("#judul").text(response.judul);
            $("#penerbit").text(response.penerbit);
            $("#pengarang").text(response.pengarang);
            $("#tahunTerbit").text(response.tahun_terbit);
            $("#fotoBuku").attr('src', `<?= BASE_URL ?>/asset/buku/${response.foto}`);
          } else {
          }
        },
      });
    }
    function showModalUpdate(id_kelas, id_peminjaman, nis, telepon, nama_siswa, foto_siswa, kelas, barcode_buku, tanggal_kembali, status){
        $('#nis').text(nis);
        $('#telepon').text(telepon);
        $("input[name='id']").val(id_peminjaman);
        $('#nama_siswa').text(nama_siswa);
        $('#nama_kelas').text(kelas);
        $('#status').val(status).trigger('change')
        $("input[name='tanggal_kembali']").val(tanggal_kembali);
        $("#barcodeSelected").val(barcode_buku).trigger('change');
        dataBuku(barcode_buku)
        if(foto_siswa){
            $("#foto_siswa").attr('src', `<?= BASE_URL ?>/asset/foto_siswa/${foto_siswa}`)
        }else{
            $("#foto_siswa").attr('src', `<?= BASE_URL ?>/asset/profile.jpg`)
        }
        $("#updateModal").modal("show")
    }
    $(document).ready(function(){
     
    // $.ajax({
    //     url: 'update_status.php',
    //     type : 'GET', 
    //     dataType : 'json'
    // })
        $('#gantiBuku').on('click', function(){
      $('.bukuData').slideDown(400, function(){
        $('.bukuData').addClass('d-none')
        $('.pencarian').hide().removeClass('d-none').fadeIn(400);
      })
    })
    $("#barcodeSelected").on('change', function(){
      const idBuku = $(this).find(':selected').data('idbuku');     
      $("input[name ='id_buku']").val(idBuku)
    })

    $("#confirmButton").on("click", function () {
      const barcode = $("#barcodeSelected").val();
      $.ajax({
        url: `proses_scan.php?action=buku&barcode=${barcode}`,
        method: "GET",
        dataType: "json",
        success: function (response) {
          if (response.success) {
            $(".pencarian").slideDown(400, function () {
              $(".bukuData").hide().removeClass("d-none").fadeIn(400);
              $(".pencarian").addClass("d-none");
            });
            $("#judul").text(response.judul);
            $("#penerbit").text(response.penerbit);
            $("#pengarang").text(response.pengarang);
            $("#tahunTerbit").text(response.tahun_terbit);
            $("#fotoBuku").attr('src', `<?= BASE_URL ?>/asset/buku/${response.foto}`);
          } else {
          }
        },
      });
    });


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
                    window.location.href = `<?= BASE_URL ?>/view/peminjaman/index.php?action=hapus&id=${idhapus}`;
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