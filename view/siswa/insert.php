
<?php 

$title = "Insert siswa";
include '../../koneksi.php';
include '../../layout/header.php';

?>

<div class="container-fluid">
    <div class="d-flex justify-content-center vh-50">
        <div class="card shadow mb-4" style="width:1500px">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Insert Siswa</h6>
            </div>
            <form action="process_input.php" method="post" id="simpan-form" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                        <div style="border: 2px dashed #ccc; width: 180px; height: 200px; display: flex; justify-content: center; align-items: center;">
                            <img src="" id="preview" alt="Preview Foto" style="display: none; max-width: 100%; max-height: 100%;">
                        </div>
                        <div class="custom-file mt-2">
                            <input type="file" accept="image/*" id="foto" name="foto" class="custom-file-input">
                            <label class="custom-file-label" for="foto">Pilih Foto</label>
                        </div>
                            
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                    <label for="nis">Nis</label>
                                    <input type="text" id="nis" name="nis" class="form-control" placeholder="Masukkan Nis" require>
                            </div>
                            <div class="form-group">
                                    <label for="nama">Nama</label>
                                    <input type="text" name="nama" class="form-control" placeholder="Masukkan Nama" require>
                            </div>  
                            <label for="">Kelas</label>
                           <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" data-toggle="modal" data-target="#kelasModal" id="basic-addon1">Pilih</span>
                                </div>
                                <input type="text" id="kelas_jurusan" class="form-control" placeholder="Klik pilih untuk memilih kelas" aria-label="Username" aria-describedby="basic-addon1" required readonly>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="">No Telepon</label>
                                <input type="number" placeholder="Masukkan No Telepon" name="telepon" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Alamat</label>
                                <textarea name="alamat" id="" class="form-control" style="height:125px"></textarea>
                            </div>
                            <div class="form-group" style="display:none;">
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

                            <!-- MODAL  -->            
                                            <div class="modal fade" id="kelasModal" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="updateModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="updateModalLabel">Pilih tingkat dan jurusan</h5>
                                            <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                            <div class="modal-body">
                                                    <div class="form-group">
                                                            <label for="Kelas">Tingkat</label>
                                                            <select name="id_kelas" id="kelas" class=form-control>
                                                                <option selected disabled>Pilih Tingkat</option>
                                                                <?php
                                                                $query = $koneksi->prepare("SELECT * FROM kelas ORDER BY id_kelas");
                                                                $query->execute();
                                                                $result = $query->get_result();
                                                                while($row = $result->fetch_array()) {

                                                                ?>
                                                                <option value="<?= $row['id_kelas'] ?>" data-tingkat="<?= $row['tingkat'] ?>" data-kelas = "<?= $row['nama_kelas'] ?>"><?= $row['tingkat'] ?> - <?= $row['nama_kelas'] ?></option>
                                                            <?php } ?>
                                                            </select>
                                                    </div>    
                                                        <div class="form-group">
                                                            <label for="Jurusan">Jurusan</label>
                                                            <select name="id_jurusan" id="jurusan" class=form-control>
                                                                <option selected disabled>Pilih Jurusan</option>
                                                                <?php
                                                                $query = $koneksi->prepare("SELECT * FROM jurusan ORDER BY id_jurusan");
                                                                $query->execute();
                                                                $result = $query->get_result();
                                                                while($row = $result->fetch_array()) {

                                                                ?>
                                                                <option value="<?= $row['id_jurusan'] ?>"  data-jurusan="<?= $row['nama_jurusan'] ?>"><?= $row['nama_jurusan'] ?></option>
                                                            <?php } ?>
                                                            </select>
                                                    </div>  
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary close-btn" id="btn-oke" data-dismiss="modal">Oke</button>
                                            </div>
                                    </div>
                                </div>
                                            </div>
                             <!-- END MODAL -->
            </form>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
<script>
    // $('#generate-barcode').on('click', function(){
    //     const barcodeValue = $('#barcode').val();
    //     if(!barcodeValue){
    //         alert('Masukkan kode barcode terlebih dahulu!');
    //         return;
    //     }
    //     $.ajax({
    //         url : '<?= BASE_URL ?>/library/generate_barcode.php',
    //         type : 'POST',
    //         data : {barcode_value: barcodeValue},
    //         success : function(response){
    //             const data = JSON.parse(response);
    //             $('#barcode-display').html(data.barcode)
    //         },
    //         error : function(){
    //             alert('Gagal menghasilkan barcode')
    //         }
    //     })
    // })

    $('#nis').on('input', function(){
        const nis = $(this).val();
        $('#barcode').val(nis)
    })


    $('#jurusan, #kelas').on('change', function(){
        const nama_jurusan = $('#jurusan option:selected').data('jurusan');
        const tingkat = $('#kelas option:selected').data('tingkat');
        const kelas = $('#kelas option:selected').data('kelas');
        if(nama_jurusan && tingkat && kelas){
            $('#kelas_jurusan').val(tingkat + " " + nama_jurusan + " " + kelas);
            $('.close-btn').prop('disabled', false);
            $('#btn-oke').text("Oke");
        }else{
            $('#kelas_jurusan').val('')
            $('#btn-oke').text("Pilih Data Terlebih Dahulu");
            $('.close-btn').prop('disabled', true);
        }
    })
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
<?php include '../../layout/footer.php'?>