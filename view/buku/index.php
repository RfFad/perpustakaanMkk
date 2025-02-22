<?php 
$title = "Data buku";
include '../../koneksi.php';
$judul = !empty(($_GET['judul'])) ? $_GET['judul'] : null;
$penerbit =!empty(($_GET['penerbit'])) ? $_GET['penerbit'] : null;
$barcode =!empty(($_GET['barcode'])) ? $_GET['barcode'] : null;
$pengarang =!empty(($_GET['pengarang'])) ? $_GET['pengarang'] : null;
$tahun_terbit =!empty(($_GET['tahun_terbit'])) ? $_GET['tahun_terbit'] : null;




$queryParams = [];
if ($judul !== null) {
    $queryParams['judul'] = $judul;
}
if ($penerbit !== null) {
    $queryParams['penerbit'] = $penerbit;
}
if ($barcode !== null) {
    $queryParams['barcode'] = $barcode;
}
if ($pengarang !== null) {
    $queryParams['pengarang'] = $pengarang;
}
if ($tahun_terbit !== null) {
    $queryParams['tahun_terbit'] = $tahun_terbit;
}

// Redirect hanya jika query string yang dihasilkan berbeda dengan yang ada di URL saat ini
$currentQuery = $_SERVER['QUERY_STRING'];
$newQuery = http_build_query($queryParams);

if ($newQuery !== $currentQuery) {
    $newUrl = 'index.php?' . $newQuery;
    header("Location: $newUrl");
    exit;
}

include '../../layout/header.php'; 


if(!isset($_SESSION['username'])){
    $url = BASE_URL . "/auth/login.php";
    echo '<script language="javascript">alert("Harap anda login terlebih dahulu"); document.location="'. $url .'"</script>';
    exit;
  }

//query filter


//action
if(isset($_GET['action'])){
    $action = $_GET['action'];
}else {
    $action = "";
}
//filter search


//hapus

?>
<style>
    .detail-buku ul li{
        margin-bottom : 10px;
    }
</style>
<div class="container-fluid">
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="filter-form" style = "display:none;">
            <form action="" method="GET">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Judul</label>
                        <input type="text" name="judul" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Penerbit</label>
                        <input type="text" name="penerbit" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Tahun terbit</label>
                        <input type="number" name="tahun_terbit" class="form-control" min="1900" max="2099" step="1" placeholder="Masukkan tahun">

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Pengarang</label>
                        <input type="text" class="form-control" name="pengarang">
                    </div>
                    <div class="form-group">
                        <label for="">Barcode</label>
                        <input type="text" class="form-control" name="barcode">
                    </div>
                </div>
                <div class="col-md-6">
                    <button class="btn btn-secondary mb-5" type="submit">Search</button>
                    <a href="index.php" class="btn btn-secondary mb-5">Tampilkan Semua Buku</a>
                </div>
            </div>
        </form>
        </div>
        <div class = "head-button">
        <?php $query = $koneksi->prepare("SELECT COUNT(*) AS count FROM buku");
        $query->execute();
        $resultCount = $query->get_result();
        $rowCount = $resultCount->fetch_assoc();
        ?>
        <span class="btn btn-sm btn-primary">Jumlah Buku : <?= $rowCount['count'] ?></span>
        <button type="button" class = "btn btn-sm btn-warning" id="btn-filter">filter search</button>
        <?php if($_SESSION['role'] === 'admin') { ?> 
        <button id="import" class="btn btn-sm btn-secondary"><i class="fas fa-file-import"></i> import data</button>
        <?php } ?>
        <a href="<?= BASE_URL ?>/view/buku/print_all.php" target="_blank" onclick="print(event)" class="btn btn-sm btn-success"><i class="fas fa-print"></i> Print Barcode</a>
        </div>
    </div>
</div>


<div class="row">
    <?php
    $query = "SELECT * FROM buku WHERE 1=1";
    $params = [];
    $types = "";

    if ($judul !== null) {
        $query .= " AND judul LIKE ?";
        $params[] = '%' . $judul . '%';
        $types .= "s";
    }

    if ($penerbit !== null) {
        $query .= " AND penerbit LIKE ?";
        $params[] = '%' . $penerbit . '%';
        $types .= "s";
    }

    if ($barcode !== null) {
        $query .= " AND barcode LIKE ?";
        $params[] = '%' . $barcode . '%';
        $types .= "s";
    }

    if ($pengarang !== null) {
        $query .= " AND pengarang LIKE ?";
        $params[] = '%' . $pengarang . '%';
        $types .= "s";
    }

    if ($tahun_terbit !== null) {
        $query .= " AND tahun_terbit LIKE ?";
        $params[] = '%' . $tahun_terbit . '%';
        $types .= "s";
    }

    $query .= " ORDER BY id_buku DESC";
    $sql = $koneksi->prepare($query);

    if ($sql === false) {
        die("Error preparing the query: " . $koneksi->error);
    }

    if (!empty($params)) {
        $sql->bind_param($types, ...$params);
    }

    $sql->execute();
    $result = $sql->get_result();

    while ($row = $result->fetch_array()) { 
    ?>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="row no-gutters">
                    <div class="col-5">
                        <img src="<?= BASE_URL ?>/asset/buku/<?= htmlspecialchars($row['foto']) ?>" 
                             alt="Image" class="img-fluid" 
                             style="width: 100%; height: 200px; object-fit: cover;">
                    </div>
                    <div class="col-7">
                        <div class="card-body detail-buku">
                            <h5 class="card-title"><?= htmlspecialchars($row['judul']) ?></h5>
                            <ul style="list-style-type: none; padding-left: 0; font-size: 12px;">
                                <li><strong>Penerbit</strong>: <?= htmlspecialchars($row['penerbit']) ?></li>
                                <li><strong>Pengarang</strong>: <?= htmlspecialchars($row['pengarang']) ?></li>
                                <li><strong>Tahun Terbit</strong>: <?= htmlspecialchars($row['tahun_terbit']) ?></li>
                            </ul>
                            <button type="button" data-idhapus="<?= $row['id_buku'] ?>" 
                                    class="btn btn-sm btn-primary" 
                                    onclick="modalUpdate('<?= addslashes($row['judul']) ?>', 
                                                         '<?= addslashes($row['pengarang']) ?>', 
                                                         '<?= addslashes($row['penerbit']) ?>', 
                                                         '<?= addslashes($row['tahun_terbit']) ?>', 
                                                         '<?= isset($row['foto']) ? addslashes($row['foto']) : ''   ?>', 
                                                         '<?= addslashes($row['barcode']) ?>', 
                                                         '<?= addslashes($row['id_buku']) ?>')" 
                                    data-toggle="modal">
                                <i class="fas fa-edit"></i> Detail
                            </button>
                        </div>
                    </div>
                </div>    
            </div>
        </div>
    <?php 
    } 
    $result->close(); 
    ?>
</div>

</div>


<!-- Modal -->
<div class="modal fade" id="modalUpdate" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Detail Buku</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="update.php" method="post" enctype="multipart/form-data">
      <div class="modal-body">
      <div class="row">
    <!-- Kolom Gambar Cover -->
    <div class="col-md-3 d-flex flex-column align-items-center">
    <!-- Gambar Cover -->
    <img src="" alt="Cover" id="imgCover" class="img-fluid mb-3 img-thumbnail" style="height: 70%; object-fit: cover;">
    
    <!-- Barcode -->
    <img src="" style="width : 70%" id="coverBar" alt="Barcode" class="img-thumbnail">
    <h6 class="text-dark mt-2" style="font-weight : bold" id="codeBar"></h6>
</div>


    <!-- Kolom Input Form -->
    <div class="col-md-4">
        <input type="hidden" id="hapusid" name="id_buku">
        <input type="hidden" name="foto_lama">
        <div class="form-group">
            <label for="judul">Judul</label>
            <input type="text" id="judul" name="judul" class="form-control" placeholder="Judul" required>
        </div>
        <div class="form-group">
            <label for="pengarang">Pengarang</label>
            <input type="text" id="pengarang" name="pengarang" class="form-control" placeholder="Pengarang" required>
        </div>
        <div class="form-group">
            <label for="tahun_terbit">Tahun Terbit</label>
            <input type="date"  id= "tahun_terbit" name="tahun_terbit" class="form-control">
        </div>
    </div>

    <!-- Kolom Input Lainnya -->
    <div class="col-md-5">
    <div class="form-group">
            <label for="penerbit">Penerbit</label>
            <input type="text" name="penerbit" id="penerbit" class="form-control" placeholder="Penerbit" required>
        </div>
        <label for="foto">Cover Buku</label>
        <div class="custom-file mb-3">
            <input type="file" accept="image/*" id="foto" name="foto" class="custom-file-input">
            <label class="custom-file-label" for="foto">Choose file</label>
        </div>
        <div class="form-group">
            <label for="barcode">Kode Barcode</label>
            <input type="text" name="barcode" id="barcode" class="form-control" placeholder="Masukkan kode barcode (contoh: 3456)" required>
        </div>
    </div>

    <!-- Barcode Mendekat ke Form -->
    <div class="col-md-12 d-flex justify-content-start align-items-center mt-*">
        
    </div>
</div>
</div>


<div class="modal-footer d-flex justify-content-between">
    <!-- Tombol Close di kiri -->
    <button type="button" class="btn btn-secondary" data-dismiss="modal">
        <i class="fas fa-arrow-left"></i> Close
    </button>

    <!-- Grup tombol di kanan -->
    <div>
        <a href="" class="btn btn-success" id="print-barcode" target="_blank" onclick="print(event)">
        <i class="fas fa-print"></i> Print Barcode</a>
        <?php if($_SESSION['role'] === 'admin') { ?> 
        <a href="#" id="btn-hapus" class="btn btn-danger"><i class="fas fa-trash"></i> Hapus</a>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
        <?php } ?>
    </div>
</div>

    </form>
    </div>
  </div>
</div>
<!-- Modal import -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Import Data Buku</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="import.php" id="import-form" enctype= "multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Upload File Excel!</label>
                        <input type="file" name="file" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="import"  class="btn btn-primary">
                        <i class="fas fa-save"></i> import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
<script>
    function print(event) {
        event.preventDefault();
        var url = event.target.href;
        var width = 700;
        var height = 650;
        window.open(url, '_blank', `width=${width},height=${height},top=100,left=200`);
    }
    function modalUpdate(judul, pengarang, penerbit, tahun_terbit, foto, barcode, id_buku){
       
        $('#judul').val(judul)
        $('#pengarang').val(pengarang)
        $('#penerbit').val(penerbit)
        $('#tahun_terbit').val(tahun_terbit)
        $('#foto_lama').val(foto)
        $('#barcode').val(barcode)
        $('#imgCover').attr('src', `<?= BASE_URL ?>/asset/buku/${foto}`)
        $('#coverBar').attr('src', `<?= BASE_URL ?>/asset/barcodes/${barcode}.png`)
        $("#codeBar").text(barcode)
        $("#hapusid").val(id_buku)
        $("#print-barcode").attr('href', `<?= BASE_URL ?>/view/buku/print.php?id_buku=${id_buku}`);
        $('#modalUpdate').modal("show")
    }
   
        $('#btn-filter').on('click', function(){
            if($('.filter-form').is(':visible')){
                $('.filter-form').hide("");
                $('#btn-filter').text("filter search")
            }else{
                $('.filter-form').show("");
                $('#btn-filter').text('Tutup filter')
            }
        })
    
   $(document).on('click', '#btn-hapus', function(){
        const idhapus= $('#hapusid').val();
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
                    window.location.href = `<?= BASE_URL ?>/view/buku/hapus.php?id=${idhapus}`;
                }
                });
    })
     //import
     $('#import').on('click', function(){
            $('#importModal').modal("show");
        })
    $("#foto").on("change", function(e){
            const fotoInput = e.target 
            const preview = $("#imgCover")

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

<?php include '../../layout/footer.php'; ?>
