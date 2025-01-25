<?php 
include '../../koneksi.php';
session_start();
$sukses = "";
$error = "";

if (isset($_GET['action'])) {
    $action = $_GET['action'];
} else {
    $action = "";
}


if($action === 'hapus'){
    $id = $_GET['id'];
    $query = "DELETE FROM kelas WHERE id_kelas = ?";
    $sql = $koneksi->prepare($query);
    $sql->bind_param("i", $id);
    if($sql->execute()){
        $sukses = "Berhasil menghapus data!";

    }else {
        $error = "Gagal menghapus data!";
    }
    if(!$id){
        $error = "Data tidak ditemukan!";
    }
    $sql->close();
}
?>


<?php
$title = 'Kelas';

include '../../layout/header.php';

?>

<!-- DataTales Example -->
<!-- Begin Page Content -->
<div class="container-fluid">

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Data Kelas</h1>
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <a href="<?= BASE_URL ?>/view/kelas/insert.php" class="btn btn-sm btn-success"><i class="fas fa-plus"></i> tambah data</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered tabelData" id="example1" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th style="width:1%;" class="text-center">No</th>
                        <th>Nama Kelas</th>
                        <th style="width:10%">Action</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th style="width:1%;">No</th>
                        <th>Nama Kelas</th>
                        <th style="width:10%">Action</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php 
                    $query = "SELECT * FROM kelas order by id_kelas DESC";
                    $sql = mysqli_query($koneksi, $query);
                    $no = 1;
                    while($row = mysqli_fetch_array($sql)){
                     ?>
                      <tr>
                        <td><?= $no ++ ?></td>
                        <td><?= $row['tingkat']?> <?= $row['nama_kelas'] ?></td>
                        <td>
                            <a href="<?= BASE_URL ?>/view/kelas/insert.php?action=edit&id=<?= $row['id_kelas'] ?>" class="btn btn-circle btn-warning"><i class="far fa-edit"></i></a>
                            <a href="#"  class="btn btn-circle btn-danger hapus-btn" data-idhapus = "<?= $row['id_kelas'] ?>"><i class="fas fa-trash"></i></a>
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
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
<script>
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
                    window.location.href = `<?= BASE_URL ?>/view/kelas/index.php?action=hapus&id=${idhapus}`;
                }
                });
  
        });
        
    })
</script>
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
<?php
include '../../layout/footer.php';
 ?>