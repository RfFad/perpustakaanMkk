<?php 

include '../config.php';
session_start();

if(empty($_SESSION['csrf_token'])){
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if(isset($_SESSION['username'])){
        header('Location:../view/dashboard/index.php');
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']){
        die("CSRF token tidak valid!");
    }
}


if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = $conn->prepare("SELECT * FROM admin WHERE username = ?");
    $query->bind_param("s", $username);
    $query->execute();
    $result = $query->get_result();

    if($result->num_rows > 0){
        $user = $result->fetch_assoc();
        
        if(password_verify($password,$user['password'])){
            session_regenerate_id(true);

            $_SESSION['id_admin'] = $user['id_admin'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

          
            if ($user['role'] === 'admin') {
                header("Location: ../view/dashboard/index.php");
            } elseif ($user['role'] === 'operator') {
                header("Location: ../view/dashboard/index.php");
            } else {
                echo "Role tidak dikenal.";
            }
            exit();


        }else{
            $error = "Password Salah";
        }
        
    }else{
        $error = "Username Salah";
    }
}

$queryData = $conn->prepare("SELECT * FROM sekolah");
$queryData->execute();
$resultData = $queryData->get_result();
$rowData = $resultData->fetch_assoc();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Login</title>

    <!-- Custom fonts for this template-->
    <link href="<?= BASE_URL ?>/public/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= BASE_URL ?>/public/css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .bg-login-image {
            background-image: url('<?= BASE_URL ?>/asset/background/perpustakaan.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100%;
            min-height: 60vh;
        }
    </style>
</head>

<body class="bg-gradient-white">
    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Selamat Datang di Perpustakaan <br> <?= $rowData['nama_sekolah'] ?>!</h1>
                                    </div>
                                    <?php if (isset($error)): ?>
                                        <div class="alert alert-danger d-flex align-items-center" style="height: 35px; font-size: 15px;" role="alert">
                                            <?= $error ?>
                                        </div>
                                    <?php endif; ?>

                                    <form class="user" method="POST" action="">
                                        <input type="hidden" name="csrf_token" value = "<?= $_SESSION['csrf_token'] ?>" >
                                        <div class="form-group">
                                            <input type="text" name="username" class="form-control form-control-user" aria-describedby="emailHelp" placeholder="Enter Username..." required>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Password" required>
                                        </div>
                                        <button type="submit" name="login" class="btn btn-primary btn-user btn-block">
                                            Login
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= BASE_URL ?>/public/vendor/jquery/jquery.min.js"></script>
    <script src="<?= BASE_URL ?>/public/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= BASE_URL ?>/public/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= BASE_URL ?>/public/js/sb-admin-2.min.js"></script>
</body>

</html>
