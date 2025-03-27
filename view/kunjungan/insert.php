
<?php 

$title = "Insert Kunjungan";
include '../../koneksi.php';
include '../../layout/header.php';

if(!isset($_SESSION['username'])){
    $url = BASE_URL . "/auth/login.php";
    echo '<script language="javascript">alert("Harap login terlebih dahulu"); document.location="'. $url .'"</script>';
    exit;
  }
  $allowed_role = ['admin', 'operator'];
  if(!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowed_role)){
      session_destroy();
      echo "<script>alert('Akses ditolak! Anda tidak memiliki izin.'); window.location.href='../../auth/login.php';</script>";
      exit();
  }
  

?>
<style>
     canvas {
            border: 1px solid #000;
        }
    .form-control{
        border-color: #000;
    }
    label{
        color: #000;
    }
</style>
<div class="container-fluid">
    <div class="d-flex justify-content-center vh-50">
        <div class="card shadow mb-4" style="width:1500px">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Insert Kunjungan</h6>
            </div>
            <form action="proses_input.php" id="simpanData" method="post" id="simpan-form" enctype="multipart/form-data">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="">Pilih Jenis Pengunjung: </label>
                    <select name="jenis_pengunjung" id="pilihPengunjung" class="form-control">
                        <option value="">-- Pilih --</option>
                        <option value="siswa">Siswa</option>
                        <option value="anggota">Anggota</option>
                        <option value="baru">Anggota Baru</option>
                    </select>
                </div>

                <!-- Untuk Siswa -->
                <div class="siswa_data" style="display: none;">
                    <div class="form-group">
                        <label for="">Pilih Siswa</label>
                        <select name="id_siswa" class="form-control selectpicker" data-live-search="true">
                            <option disabled selected>-- Pilih Data --</option>
                            <?php 
                            include '../../koneksi.php';
                            $querySiswa = $koneksi->prepare("SELECT s.*, k.nama_kelas, k.tingkat, CONCAT(k.tingkat, ' ', j.singkatan, ' ', k.nama_kelas) AS nama_kelas FROM siswa s JOIN kelas k ON s.id_kelas = k.id_kelas JOIN jurusan j ON s.id_jurusan = j.id_jurusan  order by nis ASC");
                            $querySiswa->execute();
                            $resultSiswa = $querySiswa->get_result();
                            while ($rowSiswa = $resultSiswa->fetch_array()) { ?>
                                <option value="<?= $rowSiswa['id_siswa'] ?>"><?= $rowSiswa['nis'] ?> - <?= $rowSiswa['nama'] ?> - <?= $rowSiswa['nama_kelas'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <!-- Untuk Anggota -->
                <div class="anggota_data" style="display: none;">
                    <div class="form-group">
                        <label for="">Pilih Anggota</label>
                        <select name="id_anggota" class="form-control selectpicker" data-live-search="true">
                            <option disabled selected>-- Pilih Data --</option>
                            <?php 
                            $queryAnggota = $koneksi->prepare("SELECT * FROM data_keanggotaan");
                            $queryAnggota->execute();
                            $resultAnggota = $queryAnggota->get_result();
                            while ($rowAnggota = $resultAnggota->fetch_array()) { ?>
                                <option value="<?= $rowAnggota['id_anggota'] ?>"><?= $rowAnggota['nip'] ?> - <?= $rowAnggota['nama_anggota'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <!-- Anggota Baru -->
                 <div class="anggota_baru" style="display:none;">
                    <div class="form-group">
                        <label for="">Nip</label>
                        <input type="text" name="nip" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Nama Anggota</label>
                        <input type="text" name="nama_anggota" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Alamat</label>
                        <textarea name="alamat" class="form-control"> </textarea>
                    </div>
                 </div>
                <!-- End Anggota Baru -->

                <!-- Keperluan Kunjungan -->
                <div class="form-group">
                    <label for="">Keperluan Kunjungan</label>
                    <input type="text" name="keperluan_kunjungan" class="form-control" placeholder="contoh (belajar, membaca buku, dll)">
                </div>

                <!-- Waktu Masuk & Keluar -->
                <div class="form-group">
                    <label for="">Waktu Masuk</label>
                    <input type="time" name="waktu_masuk" class="form-control">
                </div>
                <div class="form-group">
                    <label for="">Waktu Keluar</label>
                    <input type="time" name="waktu_keluar" class="form-control">
                </div>

                <!-- Tanda Tangan -->
                <label>Tanda Tangan:</label>
                <div class="form-group">
                    <div class="draw d-flex">
                        <canvas id="signatureCanvas" width="500" height="300" style="border:1px solid #000;"></canvas>
                        <input type="hidden" name="ttd" id="ttd_data">
                    </div>
                    <br>
                    <button type="button" class="btn btn-danger btn-sm" id="clearButton">Bersihkan</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card-footer">
        <a href="<?= BASE_URL ?>/view/kunjungan/index.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <button type="submit" name="simpan" class="btn btn-primary">
            <i class="fas fa-save"></i> Simpan
        </button>
    </div>
</form>
                            </div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        $("#pilihPengunjung").on('change', function () {
            $('.siswa_data, .anggota_baru, .anggota_data',).hide();
            var dataSelect = $(this).val();
            if (dataSelect === 'siswa') {
                $('.siswa_data').show();
            } else if(dataSelect === 'baru'){
                $('.anggota_baru').show("");
            } else if(dataSelect === 'anggota'){
                $('.anggota_data').show();
            }
        });

        // Inisialisasi Canvas untuk Tanda Tangan
        var canvas = document.getElementById('signatureCanvas');
        var ctx = canvas.getContext('2d');
        var drawing = false;

        canvas.addEventListener('mousedown', function (e) {
            drawing = true;
            ctx.beginPath();
            ctx.moveTo(e.offsetX, e.offsetY);
        });

        canvas.addEventListener('mousemove', function (e) {
            if (drawing) {
                ctx.lineTo(e.offsetX, e.offsetY);
                ctx.stroke();
            }
        });

        canvas.addEventListener('mouseup', function () {
            drawing = false;
            ctx.closePath();
        });

        // Tombol Bersihkan Tanda Tangan
        $('#clearButton').click(function () {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            $('#ttd_data').val(''); // Kosongkan input hidden
        });

        // Tombol Simpan Tanda Tangan (Mengubah ke Base64)
        $('#simpanData').submit(function () {
            var dataURL = canvas.toDataURL('image/png');
            $('#ttd_data').val(dataURL); // Simpan ke input hidden
        });
    });
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