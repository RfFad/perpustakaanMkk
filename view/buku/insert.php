<?php 
$title = "Insert buku";
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

?>

<div class="container-fluid">
    <div class="d-flex justify-content-center vh-50">
        <div class="card shadow mb-4" style="width:800px">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Insert Buku</h6>
            </div>
            <form action="process_input.php" method="post" id="simpan-form" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                    <label for="judul">Judul</label>
                                    <input type="text" name="judul" class="form-control" placeholder="Judul" require>
                            </div>
                            <div class="form-group">
                                    <label for="pengarang">Pengarang</label>
                                    <input type="text" name="pengarang" class="form-control" placeholder="Pengarang" require>
                            </div>
                            <label for="">Cover Buku</label>
                            <div class="custom-file">
                                <input type="file" accept="image/*" id="foto" name="foto" class="custom-file-input" id="customFile">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
                            <div class="form-group">
                                <img src="" id="preview" alt="" style="display: none; width: 150px; margin-top: 10px;">
                            </div>
                           
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Tahun terbit</label>
                                <input type="date" name="tahun_terbit" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Penerbit</label>
                                <input type="text" name="penerbit" class="form-control" placeholder="Penerbit" required>
                            </div>
                            <div class="form-group">
                                <label for="barcode">Kode Barcode</label>
                                <input type="text" name="barcode" id="barcode" class="form-control" placeholder="Masukkan kode barcode (contoh: 3456)" required>
                            </div>
                        </div>
                    </div>
                    
                    
                </div>
                <div class="card-footer">
                    <a href="<?= BASE_URL ?>/view/buku/index.php" class="btn btn-secondary">
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

    $(document).ready(function(){

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
<script>
  document.querySelector('.custom-file-input').addEventListener('change', function (e) {
    // Dapatkan nama file yang dipilih
    var fileName = e.target.files[0].name;
    // Perbarui label dengan nama file
    e.target.nextElementSibling.innerHTML = fileName;
  });
</script>
<?php include '../../layout/footer.php'?>