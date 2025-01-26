<?php 
include '../../koneksi.php';
$error=[];
$sukses = "";

if(isset($_POST['simpan'])){
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $query = $koneksi->prepare("SELECT username, email FROM admin WHERE username = ? OR email=?");
    $query->bind_param('ss', $username, $password);
    $query->execute();
    $result = $query->get_result();

    if($result->num_rows > 0){
        while($row=$result->fetch_assoc()){
            if($row['username'] === $username){
                $error['username'] = "Username sudah digunakan!";
            }
            if($row['email'] === $email){
                $error['email'] = "Email sudah digunakan";
            }
        }
    }
    if(empty($error)){
        $hashed = password_hash($password, PASSWORD_BCRYPT);

        $query = $koneksi->prepare("INSERT INTO admin(nama, username, email, password, role) VALUES (?, ?, ?, ?, ?)");
        $query->bind_param('sssss', $nama, $username, $email, $hashed, $role);

        if($query->execute()){
            $sukses = "Berhasil menambahkan data user!";
        }else{
            $error['general'] = 'Gagal menambahkan data user!';
        }
        $query->close();
    }
}
?>

<?php include '../../layout/header.php'; ?>
<style>
.is-invalid {
    border-color: red;
}
.invalid-feedback {
    display: block;
    color: red;
}
</style>

<div class="container-fluid">
    <div class="d-flex justify-content-center vh-50">
        <div class="card shadow mb-4" style="width:800px">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Insert Kelas</h6>
            </div>
            <form action="" method="post" id="simpan-form" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                    <label for="nama">Nama</label>
                                    <input type="text" name="nama" class="form-control" placeholder="Nama" require>
                            </div>
                            <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" name="username" class="form-control <?= isset($error['username']) ? 'is-invalid' : '' ?>" placeholder="Username" require>
                                    <?php if (isset($error['username'])): ?>
                                        <div class="invalid-feedback"><?= $error['username'] ?></div>
                                    <?php endif; ?>
                            </div>
                                <label for="">Email</label>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">@</div>
                                    </div>
                                    <input type="email" class="form-control <?= isset($error['username']) ? 'is-invalid' : '' ?>" id="inlineFormInputGroup" name="email" placeholder="Email">
                                    <?php if (isset($error['email'])): ?>
                                        <div class="invalid-feedback"><?= $error['email'] ?></div>
                                    <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Password</label>
                                <input type="password" name="password" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Role</label>
                                <select name="role" id="" class="form-control" require>
                                    <option disabled selected>Selected</option>
                                    <option value="admin">admin</option>
                                    <option value="operator">operator</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    
                </div>
                <div class="card-footer">
                    <a href="<?= BASE_URL ?>/view/user/index.php" class="btn btn-secondary">
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
<?php if ($sukses) { ?>
<script>
    $(document).ready(function () {
        Swal.fire({
            title: 'Berhasil!',
            text: <?= json_encode($sukses) ?>,
            icon: 'success',
            //timer: 2000,
            showConfirmButton: true
        }).then(() => {
            window.location.href = "<?= BASE_URL ?>/view/user/index.php";
        });
    });
</script>
<?php } ?>
<?php include '../../layout/footer.php'; ?>