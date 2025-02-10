<?php
$title = "Scan";
include '../../koneksi.php';
include '../../layout/header.php';

$action = isset($_GET['action']) ? $_GET['action'] : "";

?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>

<style>
  .card-option:hover {
    transform: scale(1.07);
    box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
  }
  .selected {
    border: 2px solid #007bff;
    background-color: #e3f2fd;
  }
</style>

<div class="container-fluid">
  <form action="input_pinjam.php" method="POST">
  <div class="peminjaman">
    <div class="row">
      <?php 
if ($action === "pinjam" && isset($_GET['nis'])) {
    $nis = $_GET['nis'];
    $query = $koneksi->prepare("SELECT siswa.*, kelas.nama_kelas, kelas.tingkat,
      jurusan.singkatan FROM siswa JOIN kelas ON siswa.id_kelas = kelas.id_kelas
      JOIN jurusan ON siswa.id_jurusan = jurusan.id_jurusan WHERE nis = ?");
      $query->bind_param("s", $nis); $query->execute(); $result_siswa =
      $query->get_result(); if ($result_siswa->num_rows > 0) { $row =
      $result_siswa->fetch_assoc(); ?>

      <div class="col-md-3">
        <div class="card shadow border-bottom-primary">
          <div class="card-body box-profile">
            <div
              class="text-center"
              style="width: 100%; height: 220px; overflow: hidden"
            >
              <img
                class="profile-user-img img-fluid"
                src="<?= $row['foto'] ? BASE_URL . '/asset/foto_siswa/' . $row['foto'] : BASE_URL . '/asset/profile.jpg' ?>"
                alt="User profile picture"
                style="width: 100%; height: 100%; object-fit: cover"
              />
            </div>
            <button type="button" class="btn btn-primary btn-block mt-3">
              <?= htmlspecialchars($row['nama']) ?>
            </button>
            <button type="button" class="btn btn-primary btn-block mt-3">
              <?= htmlspecialchars($row['tingkat']) ?>
              <?= htmlspecialchars($row['singkatan']) ?>
              <?= htmlspecialchars($row['nama_kelas']) ?>
            </button>
            <input type="hidden" name="id_siswa" class="form-control" value="<?= htmlspecialchars($row['id_siswa']) ?>">
          </div>
        </div>
      </div>

      <?php 
    } else {
        echo "<div class='col-md-12'><div class='alert alert-danger'>Siswa tidak ditemukan!</div></div>";} } ?>

    <div class="col-md-9">
      <div class="card shadow card-primary border-bottom-primary card-outline">
        <div class="card-body">
          <div class="pencarian">
            <div class="form-group">
              <label for="">Pilih buku yang di pinjam :</label>
              <select
                class="selectpicker form-control"
                id="barcodeSelected"
                data-live-search="true"
              >
                <option disabled selected>
                  -- Pilih Buku Yang Akan dipinjam
                </option>
                <?php 
            $queryBuku = $koneksi->prepare("SELECT * FROM buku");
                $queryBuku->execute(); $resultBuku = $queryBuku->get_result();
                while($row = $resultBuku->fetch_array()){ ?>
                <option data-idBuku="<?= $row['id_buku'] ?>" value="<?= $row['barcode'] ?>">
                 <?= $row['judul'] ?> - <?= $row['barcode'] ?>
                </option>
                <?php } ?>
              </select>
            </div>
            <div class="confirm">
              <button type="button" class="btn btn-primary" id="confirmButton">
                confirm
              </button>
            </div>
          </div>
          <div class="bukuData d-none">
          <div class="row">
             <!-- Kolom Gambar Buku -->
            <div class="col-md-3 text-center">
              <img
                class="profile-user-img img-fluid rounded shadow"
                src=""
                id="fotoBuku"
                alt="User profile picture"
                style="width: 100%; height: 200px; object-fit: cover;"
              />
            </div>

            <!-- Kolom Detail Buku -->
            <div class="col-md-6">
              <h3 class="card-title fw-bold" id="judul">Judul Buku</h3>
              <ul class="list-unstyled fs-5">
                <li class="d-flex">
                  <strong class="me-2" style="min-width: 100px;">Penerbit</strong>: 
                  <span id="penerbit" class="ml-2">-</span>
                </li><hr>
                <li class="d-flex">
                  <strong class="me-2" style="min-width: 100px;">Pengarang</strong>: 
                  <span id="pengarang" class="ml-2">-</span>
                </li><hr>
                <li class="d-flex">
                  <strong class="me-2" style="min-width: 100px;">Tahun Terbit</strong>: 
                  <span id="tahunTerbit" class="ml-2">-</span>
                </li><hr>
              </ul>
            </div>

            <div class="col-md-12">
              <input type="hidden" name="id_buku" class="form-control">
              <input type="hidden" name="id_admin" class="form-control" value="<?= $_SESSION['id_admin'] ?>">
              <div class="form-group">
                <label for="">Tanggal Pengembalian : </label>
                <input type="date" name="tanggal_kembali" class="form-control">
              </div>
              <button type="button" id="gantiBuku" class="btn btn-secondary">Ganti Buku</button>
              <button type="submit" name="input_pinjam" class="btn btn-primary">Simpan</button>
            </div>
          </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</form>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function () {
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
   
  });
</script>
<?php
include '../../layout/footer.php';
?>
