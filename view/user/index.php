<?php
session_start();
include '../../koneksi.php';
$error = [];
$sukses = "";

if(isset($_GET['action'])){
    $action = $_GET['action'];
}else{
    $action = "";
}


if($action === 'hapus'){
    $id = $_GET['id'];
    $query = $koneksi->prepare("DELETE FROM admin WHERE id_admin = ?");
    $query->bind_param('i', $id);
    if($query->execute()){
        $_SESSION['sukses'] = "Berhasil menghapus data!";
    }else{
        $_SESSION['error']['general'] = "Gagal menghapus data!";
    }
    $query->close();
}

if (isset($_POST['update'])) {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $id_admin = $_POST['id_admin'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $query = $koneksi->prepare("SELECT username, email, id_admin FROM admin WHERE (username = ? OR email = ?) AND id_admin != ?");
    $query->bind_param('ssi', $username, $email, $id_admin);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if ($row['username'] === $username) {
                $_SESSION['error']['username'] = "Username sudah digunakan!";
            }
            if ($row['email'] === $email) {
                $_SESSION['error']['email'] = "Email sudah digunakan!";
            }
        }
    }
    if (empty($_SESSION['error'])) {
        if (empty($password)) {
            $query = $koneksi->prepare("SELECT password FROM admin WHERE id_admin = ?");
            $query->bind_param('i', $id_admin);
            $query->execute();
            $result = $query->get_result();
            $data = $result->fetch_assoc();
            $password = $data['password'];
        } else {
            $password = password_hash($password, PASSWORD_BCRYPT);
        }
        $update = $koneksi->prepare("UPDATE admin SET nama = ?, username = ?, email = ?, password = ?, role = ? WHERE id_admin = ?");
        $update->bind_param('sssssi', $nama, $username, $email, $password, $role, $id_admin);
        if ($update->execute()) {
            $_SESSION['sukses'] = "Data berhasil diupdate!";
        } else {
            $_SESSION['error']['general'] = "Gagal memperbarui data!";
        }
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}
?>

<?php
$title = 'User';

include '../../layout/header.php';

?>

<!-- DataTales Example -->
<!-- Begin Page Content -->
<div class="container-fluid">

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Data User</h1>
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <a href="<?= BASE_URL ?>/view/user/insert.php" class="btn btn-sm btn-success"><i class="fas fa-plus"></i> tambah data</a>
    </div>
    <div class="card-body">

    <?php if (!empty($_SESSION['error'])) { ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php if (!empty($_SESSION['error']['username'])) { ?>
            <strong>Username Error!</strong> <?= $_SESSION['error']['username'] ?><br>
        <?php } ?>
        <?php if (!empty($_SESSION['error']['email'])) { ?>
            <strong>Email Error!</strong> <?= $_SESSION['error']['email'] ?>
        <?php } ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php unset($_SESSION['error']); // Hapus pesan error dari session ?>
<?php } ?>

        <div class="table-responsive">
        <table class="table table-bordered tabelData" id="example1" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $query = $koneksi->prepare("SELECT * FROM admin ORDER BY id_admin DESC");
        $query->execute();
        $result = $query->get_result();
        $no = 1;
        while ($row = $result->fetch_array()) {
        ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['role']) ?></td>
                <td>
                    <a href="#" data-toggle="modal" onclick="showModalUpdate('<?= addslashes($row['nama']) ?>', '<?= addslashes($row['username']) ?>', '<?= addslashes($row['email']) ?>', '<?= addslashes($row['role']) ?>', '<?= addslashes($row['id_admin']) ?>')" class="btn btn-primary"><i class="fas fa-edit"> Edit</i></a>
                    <a href="#" class="btn btn-danger btn-hapus" data-idhapus = "<?= htmlspecialchars($row['id_admin']) ?>"><i class="fas fa-trash"> Hapus</i></a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
        </div>
    </div>
</div>

</div>


<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Update Data Jurusan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" id="id_admin" name="id_admin">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                    <label for="nama">Nama</label>
                                    <input type="text" id="nama" name="nama" class="form-control" placeholder="Nama" require>
                            </div>
                            <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" id="username" name="username" class="form-control" placeholder="Username" require>
                            </div>
                                <label for="">Email</label>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">@</div>
                                    </div>
                                    <input type="email" id="email" class="form-control" id="inlineFormInputGroup" name="email" placeholder="Email">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Password</label>
                                <input type="password" name="password" placeholder="(Kosongkan jika tidak diubah)" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Role</label>
                                <select name="role" id="role" class="form-control" require>
                                    <option disabled selected>Selected</option>
                                    <option value="admin">admin</option>
                                    <option value="operator">operator</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="update" class="btn btn-primary"><i class="fas fa-save"></i> Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>    
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
<script>
    function showModalUpdate(nama, username, email, role, id_admin){
        $('#nama').val(nama)
        $('#username').val(username)
        $('#email').val(email)
        $('#role').val(role).attr('selected', true);
        $('#id_admin').val(id_admin)
        $('#updateModal').modal("show")
    }
    $(document).ready(function() {
        $('.btn-hapus').on('click', function(){
            const idhapus = $(this).data('idhapus');
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
                    window.location.href = `<?= BASE_URL ?>/view/user/index.php?action=hapus&id=${idhapus}`;
                }
                });
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
            }).then(() => {
                window.location.href = "<?= BASE_URL ?>/view/user/index.php";
            });
        });
    </script>
    <?php unset($_SESSION['sukses']); // Hapus pesan sukses dari session ?>
<?php } ?>
<?php
include '../../layout/footer.php';
 ?>