<?php
$title = 'Data Siswa';

include '../../layout/header.php';
include '../../koneksi.php';
if(!isset($_SESSION['username'])){
    $url = BASE_URL . "/auth/login.php";
    echo '<script language="javascript">alert("Harap anda login terlebih dahulu"); document.location="'. $url .'"</script>';
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
    $query = "DELETE FROM siswa WHERE id_siswa = ?";
    $sql = $koneksi->prepare($query);
    $sql->bind_param("i", $id);
    if($sql->execute()){
        $url = BASE_URL . "/view/siswa/index.php";
        $_SESSION['sukses'] = "Berhasil menghapus data!";
        echo '<script language="javascript">document.location="'. $url .'"</script>';
        exit;

    }else {
        $_SESSION['error'] = "Gagal menghapus data!";
        echo '<script language="javascript">document.location="'. $url .'"</script>';
        exit;
    }
    if(!$id){
        $_SESSION['error'] = "Data tidak ditemukan!";
        echo '<script language="javascript">document.location="'. $url .'"</script>';
        exit;
    }
    $sql->close();
}
?>

<!-- DataTales Example -->
<!-- Begin Page Content -->
<div class="container-fluid">

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Data Siswa</h1>
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <a href="<?= BASE_URL ?>/view/siswa/insert.php" class="btn btn-sm btn-success"><i class="fas fa-plus"></i> tambah data</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered tabelData" id="example1" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th style="width:1%;" class="text-center">No</th>
                        <th>Nis</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>No Telepon</th>
                        <th>Alamat</th>
                        <th style="width:20%">Action</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                    <th style="width:1%;" class="text-center">No</th>
                        <th>Nis</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>No Telepon</th>
                        <th>Alamat</th>
                        <th style="width:20%">Action</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php 
                    $query = "SELECT s.*, k.nama_kelas, k.tingkat, j.nama_jurusan, j.singkatan  FROM siswa s JOIN kelas k ON s.id_kelas = k.id_kelas JOIN jurusan j ON s.id_jurusan = j.id_jurusan  order by nis ASC";
                    $sql = mysqli_query($koneksi, $query);
                    $no = 1;
                    while($row = mysqli_fetch_array($sql)){
                     ?>
                      <tr>
                        <td><?= $no ++ ?></td>
                        <td><?= $row['nis'] ?></td>
                        <td><?= $row['nama'] ?></td>
                        <td><?= $row['tingkat'] ?> <?= $row['singkatan'] ?> <?= $row['nama_kelas'] ?></td>
                        <td><?= $row['telepon'] ?></td>
                        <td><?= $row['alamat'] ?></td>
                        <td>
                            <a href="#" data-toggle="modal" onclick="showModalUpdate('<?= $row['nis'] ?>', '<?= $row['nama'] ?>', '<?= $row['id_kelas'] ?>', '<?= $row['id_jurusan'] ?>', '<?= $row['telepon'] ?>', '<?= $row['alamat'] ?>', '<?= $row['foto'] ?>', '<?= $row['id_siswa'] ?>', '<?= $row['barcode'] ?>')" class="btn btn-primary"><i class="fas fa-edit"></i> edit</a>
                            <a href="#"  class="btn btn-danger hapus-btn" data-idhapus = "<?= $row['id_siswa'] ?>"><i class="fas fa-trash"></i> hapus</a>
                        </td>
                    </tr>   
                    <?php } $sql->close(); ?>
                   
                   
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
                <h5 class="modal-title" id="updateModalLabel">Update Data Siswa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="update.php" enctype= "multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-2 d-flex flex-column align-items-center">
                            <div style="border: 2px dashed #ccc; width: 180px; height: 200px; display: flex; justify-content: center; align-items: center;">
                                <img id="preview" class="img-thumbnail" alt="Preview Foto" style="width: 180px; height: 200px; object-fit: cover;">
                            </div>
                            <div class="custom-file mt-2">
                                <input type="file" accept="image/*" id="foto" name="foto" class="custom-file-input">
                                <label class="custom-file-label" for="foto">Pilih Foto</label>
                            </div>
                            <img src="" style="width : 100%" id="coverBar" alt="Barcode" class="img-thumbnail mt-3">
                            <h6 class="text-dark mt-2" style="font-weight : bold" id="codeBar"></h6>
                        </div>
                        <div class="col-md-5">
                            <input type="text" style="display:none" name="id_siswa" id="id_siswa" class="form-control">
                            <div class="form-group">
                                <label for="nis">Nis</label>
                                <input type="text" id="nis" name="nis" class="form-control" placeholder="Masukkan Nis" required>
                            </div>
                            <div class="form-group">
                                <label for="nama">Nama</label>
                                <input type="text" id="nama" name="nama" class="form-control" placeholder="Masukkan Nama" required>
                            </div>  
                            <div class="form-group">
                                <label for="Kelas">Tingkat</label>
                                <select name="id_kelas" id="kelas" class="form-control">
                                    <option selected disabled>Pilih Tingkat</option>
                                    <?php
                                    $query = $koneksi->prepare("SELECT * FROM kelas ORDER BY id_kelas");
                                    $query->execute();
                                    $result = $query->get_result();
                                    while($row = $result->fetch_array()) {
                                    ?>
                                    <option value="<?= $row['id_kelas'] ?>" data-tingkat="<?= $row['tingkat'] ?>" data-kelas="<?= $row['nama_kelas'] ?>"><?= $row['tingkat'] ?> - <?= $row['nama_kelas'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>    
                            <div class="form-group">
                                <label for="Jurusan">Jurusan</label>
                                <select name="id_jurusan" id="jurusan" class="form-control">
                                    <option selected disabled>Pilih Jurusan</option>
                                    <?php
                                    $query = $koneksi->prepare("SELECT * FROM jurusan ORDER BY id_jurusan");
                                    $query->execute();
                                    $result = $query->get_result();
                                    while($row = $result->fetch_array()) {
                                    ?>
                                    <option value="<?= $row['id_jurusan'] ?>" data-jurusan="<?= $row['nama_jurusan'] ?>"><?= $row['singkatan'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="">No Telepon</label>
                                <input type="number" placeholder="Masukkan No Telepon" id="telepon" name="telepon" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Alamat</label>
                                <textarea name="alamat" id="" class="form-control" style="height:125px"></textarea>
                            </div>
                            <div class="form-group" style="display: none;">
                                <label for="barcode">Kode Barcode</label>
                                <input type="text" name="barcode" id="barcode" class="form-control" placeholder="Masukkan kode barcode (contoh: 3456)">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
<script>
    $('#nis').on('input', function(){
        const nis = $(this).val();
        $('#barcode').val(nis)
    })

    
    function showModalUpdate(nis, nama, id_kelas, id_jurusan, telepon, alamat, foto, id_siswa, barcode){
        $('#nis').val(nis)
        $('#nama').val(nama)
        $('#kelas').val(id_kelas).attr('selected', true);
        $('#jurusan').val(id_jurusan).attr('selected', true);
        $('#telepon').val(telepon);
        $('#id_siswa').val(id_siswa);
        $('#barcode').val(barcode);
        $('#codeBar').text(barcode);
        $('#coverBar').attr('src', `<?= BASE_URL ?>/asset/barcode_siswa/${barcode}.png`);
        $('#preview').attr('src', `<?= BASE_URL ?>/asset/foto_siswa/${foto}`);
        $('textarea[name="alamat"]').val(alamat);
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
                    window.location.href = `<?= BASE_URL ?>/view/siswa/index.php?action=hapus&id=${idhapus}`;
                }
                });
  
        });
        $("#foto").on("change", function(e){
            const fotoInput = e.target 
            const preview = $("#preview")

            if(fotoInput.files && fotoInput.files[0]){
                const reader = new FileReader();

                reader.onload = function(e){
                    preview.attr('src', e.target.result);
                    preview.show()
                }
                reader.readAsDataURL(fotoInput.files[0])
            }else{
                preview.hide();
            }
        })
        
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

<script>
  document.querySelector('.custom-file-input').addEventListener('change', function (e) {
    // Dapatkan nama file yang dipilih
    var fileName = e.target.files[0].name;
    // Perbarui label dengan nama file
    e.target.nextElementSibling.innerHTML = fileName;
  });
</script>

<?php
include '../../layout/footer.php';
 ?>