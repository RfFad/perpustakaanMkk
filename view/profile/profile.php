<?php 
$title = "Profile Sekolah";
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

if (isset($_GET['action'])) {
    $action = $_GET['action'];
} else {
    $action = "";
}

if ($action === 'update') {
    $nama_sekolah = $_POST['nama_sekolah'];
    $email_sekolah = $_POST['email_sekolah'];
    $notelp_sekolah = $_POST['notelp_sekolah'];
    $alamat_sekolah = $_POST['alamat_sekolah'];
    $website_sekolah = $_POST['website_sekolah'];
    $fotoBaru = $_FILES['foto']['name'];
    $tmp_name = $_FILES['foto']['tmp_name'];

    //untk mengambil data foto lama jika foto tidak diubah
     $queryFoto = $koneksi->prepare("SELECT foto FROM sekolah WHERE id_sekolah = 1");
    $queryFoto->execute();
    $result = $queryFoto->get_result();
    $data = $result->fetch_assoc();
    $fotoLama = $data['foto'];


    if(!empty($fotoBaru)){
        if(file_exists('../../asset/' . $fotoLama)){
            unlink('../../asset/' . $fotoLama);
        }
    move_uploaded_file($tmp_name, '../../asset/' . $fotoBaru);
    $foto = $fotoBaru;
    }else{
        $foto = $fotoLama;
    }

    //Query untuk update
    $queryUpdate = $koneksi->prepare("UPDATE sekolah SET nama_sekolah = ?, email_sekolah= ?, notelp_sekolah= ?, alamat_sekolah = ?, website_sekolah = ?, foto = ? WHERE id_sekolah = 1 ");
    $queryUpdate->bind_param("ssssss", $nama_sekolah, $email_sekolah, $notelp_sekolah, $alamat_sekolah, $website_sekolah, $foto);
    if ($queryUpdate->execute()) {
        $_SESSION['sukses'] = "Berhasil mengupdate data";
    }else{
        $_SESSION['error'] = "Gagal Memperbarui data";
    }
}


?>
    <style>

        .container {
            width: 100%;
            margin: auto;
            overflow: hidden;
        }
        header {
            display: block;
            background: #35424a;
            color: #ffffff;
            padding: 20px 0;
            text-align: center;
        }
        .profile, .contact {
            background: #ffffff;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #35424a;
        }
        .profile ul {
            list-style-type: disc;
            margin-left: 20px;
        }
        .image-conteiner{
            display : inline-block;
            background-color: white;
            padding: 10px;
            border-radius: 100%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
    <div class="container-fluid">
    
    </div>

   <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Update Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">                   
                <div class="row">
                    <div class="col-md-6">
                        <form id="Update" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="nama_sekolah">Nama Sekolah</label>
                                <input type="text" class="form-control" name="nama_sekolah" required>
                            </div>
                            <div class="form-group">
                                <label for="">Email</label>
                                <input type="email" class="form-control" name="email_sekolah" required>
                            </div>
                            <div class="form-group">
                                <label for="">No. Telp</label>
                                <input type="text" class="form-control" name="notelp_sekolah" required>
                            </div>
                            <div class="form-group">
                                <label for="">Website</label>
                                <input type="text" class="form-control" name="website_sekolah" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Logo Sekolah</label>
                                <input type="file" name="foto" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Alamat</label>
                                <textarea style="height: 120px" name="alamat_sekolah" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" id="btn-update" class="btn btn-primary">Save changes</button>
                </div>
                </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
<script>
    $(document).ready(function(){
        data();
        $("#Update").on('submit', function(e){
            e.preventDefault()
            $("#btn-update").attr('disabled', true).text("Loading...")
            $.ajax({
                url : 'profile.php?action=update',
                type : 'POST',
                data : new FormData(this),
                processData: false, // Jangan memproses data secara default (karena FormData sudah menanganinya)
                contentType: false, // Jangan set tipe konten secara manual (FormData menangani otomatis)
                cache: false,
                success : function(response){
                    Swal.fire({
                    title: 'Berhasil!',
                    text: 'Data berhasil diperbarui!',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
                $("#updateModal").modal('hide');
                $("#btn-update").attr('disabled', false).text("Save change")
                    data();
                }
            })
        })

    })
    function data(){
        let html = "";
        $.ajax({
            url : 'data.php',
            type : 'GET',
            dataType: 'json',
            success: function(data){
                html += `<div class="container">
                    <header>
                        <div class="image-conteiner bg-shadow mb-2" style="object-fit:cover;">
                        <img src="../../asset/${data.foto}" style="height: 90px; " alt="">
                        </div>
                        <h3>${data.nama_sekolah}</h3>
                    </header>
                    <section class="profile d-none">
                        <h2>Tentang Sekolah</h2>
                        <p>Sekolah XYZ didirikan pada tahun 2000 dan telah berkomitmen untuk memberikan pendidikan berkualitas kepada siswa-siswi.</p>
                        <h3>Visi</h3>
                        <p>Menjadi sekolah unggulan yang mencetak generasi yang berakhlak mulia dan berprestasi.</p>
                        <h3>Misi</h3>
                        <ul>
                            <li>Menyediakan pendidikan yang berkualitas.</li>
                            <li>Mendorong siswa untuk berprestasi di bidang akademik dan non-akademik.</li>
                            <li>Membangun karakter siswa yang baik.</li>
                        </ul>
                    </section>
                    <section class="contact p-4 bg-white shadow-sm rounded">
                <h2 class="text-center text-primary mb-4">Kontak</h2>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card shadow-sm mb-3">
                            <div class="card-body d-flex align-items-center">
                                <div>
                                    <i class="fas fa-envelope text-danger fs-4 me-3"></i>
                                    <strong>Email:</strong>
                                    <p class="mb-0 text-dark">${data.email_sekolah}</p>
                                </div>
                            </div>
                        </div>
                        <div class="card shadow-sm mb-3">
                            <div class="card-body d-flex align-items-center">
                                <div>
                                    <i class="fas fa-map-marker-alt text-success fs-4 me-3"></i>
                                    <strong>Alamat:</strong>
                                    <p class="mb-0 text-dark">${data.alamat_sekolah}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-sm mb-3">
                            <div class="card-body d-flex align-items-center">
                                <div>
                                    <i class="fas fa-phone text-primary fs-4 me-3"></i>
                                    <strong>No Tlp:</strong>
                                    <p class="mb-0 text-dark">${data.notelp_sekolah}</p>
                                </div>
                            </div>
                        </div>
                        <div class="card shadow-sm mb-3">
                            <div class="card-body d-flex align-items-center">
                                <div>
                                    <i class="fas fa-globe text-info fs-4 me-3"></i>
                                    <strong style="min-width: 100px;">Website:</strong>
                                    <p class="mb-0"><a href="https://${data.website_sekolah}">${data.website_sekolah}</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if($_SESSION['role'] === 'admin') { ?> 
                <button class="btn btn-primary" data-toggle="modal" data-target="#updateModal">Edit</button>
                <?php } ?>
            </section>

            <!-- Tambahkan FontAwesome jika belum ada -->
                </div>`;

                $(".container-fluid").html(html);
                $("input[name='nama_sekolah']").val(data.nama_sekolah)
                $("input[name='email_sekolah']").val(data.email_sekolah)
                $("input[name='notelp_sekolah']").val(data.notelp_sekolah)
                $("input[name='website_sekolah']").val(data.website_sekolah)
                $("textarea[name='alamat_sekolah']").val(data.alamat_sekolah)
            }
        })
        

    }
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
    <?php include '../../layout/footer.php' ?>