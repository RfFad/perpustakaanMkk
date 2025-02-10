
<?php
$title = "Scan";
include '../../koneksi.php';
include '../../layout/header.php';
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
        /* Styling untuk area pemindaian */
        #scanner-container {
            position: relative;
            width: 500px;
            height: 380px;
            margin: auto;
            border: 2px solid #000; /* Border untuk keseluruhan area pemindaian */
            box-sizing: border-box;
        }

        /* Border tambahan untuk area pemindaian barcode */
        .scan-area {
            position: absolute;
            top: 20%;
            left: 10%;
            width: 80%;
            height: 60%;
            border: 2px dashed rgba(0, 255, 0, 0.7); /* Garis putus-putus hijau untuk area pemindaian */
            box-sizing: border-box;
        }

        /* Membuat garis pemindaian yang bergerak */
        .scan-line {
            position: absolute;
            top: 50%;
            left: 0;
            width: 100%;
            height: 3px;
            background-color: rgba(0, 255, 0, 0.6); /* Garis hijau transparan */
            animation: scan-line-animation 2s infinite linear;
        }

        /* Animasi garis bergerak */
        @keyframes scan-line-animation {
            0% {
                top: 0;
            }
            50% {
                top: 50%;
            }
            100% {
                top: 100%;
            }
        }
    </style>
<div class="container-fluid">
    <div class="option-card">
<div class="row d-flex justify-content-center align-items-center" style="height:50vh;">
    <div class="col-12 text-center">
        <h3 class="font-weight-bold text-gray-800">Pilih siswa terlebih dahulu!</h3>
    </div>
    
    <!-- Earnings (Annual) Card Example -->
    <div class="col-xl-4 col-md-6 mb-3">
        <div class="card card-option border-left-success shadow h-100 py-3" id="manual" style="cursor: pointer;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-center text-success mb-1">
                            <h5>
                            Cari Manual 
                            </h5><i class="fas fa-search fa-3x"></i>
                        </div>
                    </div>
                    <div class="col-auto">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tasks Card Example -->
    <div class="col-xl-4 col-md-6 mb-3">
        <div class="card card-option border-left-info shadow h-100 py-3" id="scan">
            <div class="card-body">
                <div class="row no-gutters align-items-center" >
                    <div class="col mr-2">
                        <div class="text-xs text-center font-weight-bold text-info mb-1">
                            <h5>
                            Scan Barcode 
                            </h5><i class="fas fa-barcode fa-3x"></i>
                        </div>
                    </div>
                    <div class="col-auto">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 text-center">
        <button type="button" class="btn btn-primary" id="confirm">Confirm</button>
    </div>
</div>
</div>

<div class="manual-siswa d-none">
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h5>Data Siswa</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped tabelData" id="example2" width="100%" cellspacing="0">
                <thead class="thead-dark">
                    <tr>
                        <th style="width:1%;" class="text-center">No</th>
                        <th>Nis</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Kode Barcode</th>
                        <th style="width:20%">Action</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                    <th style="width:1%;" class="text-center">No</th>
                        <th>Nis</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Kode Barcode</th>
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
                        <td><?= $row['barcode'] ?></td>
                        <td>
                            <a href="#"  class="btn btn-primary"><i class="fas "></i> Select</a>
                        </td>
                    </tr>   
                    <?php } $sql->close(); ?>
                   
                   
                </tbody>
            </table>
            <button type="button" class="btn btn-secondary mt-3 back" id="back">Kembali</button>
        </div>
    </div>
</div>
</div>



    <div class="scanner d-none">
        
    <div id="scanner-container">
        <video id="video-webcam" style="width:100%; height:100%" autoplay></video>
        <div class="scan-area"></div> <!-- Area pemindaian dengan border -->
        <div class="scan-line"></div> <!-- Garis pemindaian yang bergerak -->
    </div>
    <input type="text" class="form-control" name="barcode" id="barcode">
    <button type="button" class="btn btn-secondary mt-3" id="kembali">Kembali</button>

</div>

</div>

<!-- Modal -->
<div class="modal fade" id="barcodeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Hasil Scan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="modalMessage"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" id="scanUlang">Scan Ulang</button>
        <button type="button" class="btn btn-primary" id="okeButton" disabled>Oke</button>
      </div>
    </div>
  </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function(){
        $('.card-option').on('click', function(){
            $(".card-option").removeClass("selected");
            $(this).addClass("selected");
        })
        $('#confirm').on('click', function(){
            let selected = $('.selected').attr('id');
            if(selected === "scan"){
                $('.option-card').slideUp(400, function(){ // Hilangkan dengan fade
                    $(".scanner").hide().removeClass("d-none").fadeIn(700); // Muncul dengan fade
                    startScanner();
                });
            }
            if(selected === "manual"){
                $('.option-card').slideUp(400, function(){ // Hilangkan dengan fade
                    $(".manual-siswa").hide().removeClass("d-none").fadeIn(700); // Muncul dengan fade
                });
            }
        })
        $('.back').on('click', function(){
            $(".manual-siswa").fadeOut(400, function() {
            $(".option-card").slideDown(400);
            $(".manual-siswa").addClass("d-none");
         });
        })

        $('#kembali').on('click', function(){
    if (Quagga) {
        Quagga.stop();
    }

    // Hentikan webcam
    let stream = $("#video-webcam")[0].srcObject;
    if (stream) {
        let tracks = stream.getTracks();
        tracks.forEach(track => track.stop()); // Matikan semua track video
    }

    
    $(".scanner").fadeOut(400, function() {
        $(".option-card").slideDown(400);
        $(".scanner").addClass("d-none");
    });
    
});

$('#scanUlang').on('click', function(){
        $("#barcodeModal").modal("hide"); 
        $("#barcode-result").text("Belum ada hasil");
        $("#nama-siswa").text("Belum ada data");
        $("#okeButton").prop("disabled", true);
        startScanner();
    });

    })
    function startScanner() {
    var video = document.querySelector("#video-webcam");

    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(function(stream) {
                video.srcObject = stream;

                Quagga.init({
                    inputStream: {
                        name: "Live",
                        type: "LiveStream",
                        target: video,
                        constraints: {
                            facingMode: "environment"
                        }
                    },
                    decoder: {
                        readers: ["code_128_reader", "ean_reader", "ean_8_reader"]
                    }
                }, function(err) {
                    if (err) {
                        console.error("Error inisialisasi QuaggaJS:", err);
                        return;
                    }
                    Quagga.start();
                });

                Quagga.onDetected(function(data) {
                    const barcode = data.codeResult.code;
                    console.log("Barcode terdeteksi:", barcode);
                    $("#barcode-result").text(barcode);
                    Quagga.stop();

                    $.ajax({
                        url: "proses_scan.php",
                        type: "GET",
                        dataType: "json",
                        data: { barcode: barcode },
                        success: function(response) {
                            $("#barcodeModal").modal("show");

                            if (response.success) {
                                $("#modalTitle").text("Data Ditemukan");
                                $("#modalMessage").html("<strong>Nama Siswa: </strong>" + response.nama);
                                $("#okeButton").prop("disabled", false);
                            } else {
                                $("#modalTitle").text("Error!");
                                $("#modalMessage").text("Kode Barcode :" + response.barcode + "Data tidak ditemukan. Silakan coba lagi.");
                                $("#okeButton").prop("disabled", true);
                            }
                        },
                        error: function() {
                            $("#barcodeModal").modal("show");
                            $("#modalTitle").text("Error!");
                            $("#modalMessage").text("Terjadi kesalahan saat mengambil data.");
                            $("#okeButton").prop("disabled", true);
                        }
                    });
                });
            })
            .catch(function(error) {
                console.error("Gagal mengakses webcam:", error);
                alert("Gagal mengakses webcam.");
            });
    } else {
        alert("Browser tidak mendukung akses webcam");
    }
}

function stopScanner() {
    if (Quagga) {
        Quagga.stop();
    }

    let stream = $("#video-webcam")[0].srcObject;
    if (stream) {
        let tracks = stream.getTracks();
        tracks.forEach(track => track.stop());
    }
}
</script>
<?php
include '../../layout/footer.php';
?>

