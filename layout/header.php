<?php include  '../../configPath.php' ?>
<?php include  BASE_PATH.'/config.php'; 
ob_start();
session_start();
session_regenerate_id(true);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Perpustakaan | <?= $title ?></title>

    <!-- Custom fonts for this template -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css">
    <link href="<?= BASE_URL ?>../public/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?= BASE_URL ?>../public/css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="<?= BASE_URL ?>../public/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <div class="d-flex flex-column align-items-center justify-content-center mt-3">
                <img src="<?= BASE_URL ?>/asset/logo.png" style="width: 80px; margin-bottom: -10px;" alt="">
                <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                    <div class="sidebar-brand-text mx-3">Perpustakaan Neper</div>
                </a>
            </div>


            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="<?= BASE_URL ?>/view/dashboard">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

                       <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Addons
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#peminjamanPages"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="fa fa-address-book ml-1"></i>
                    <span>Peminjaman</span>
                </a>
                <div id="peminjamanPages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="<?= BASE_URL ?>/view/peminjaman/index.php">Data Peminjaman</a>
                        <a class="collapse-item" href="<?= BASE_URL ?>/view/peminjaman/scan.php">Insert Peminjaman</a>
                    </div>
                </div>
          </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#bukuPages"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-book"></i>
                    <span>Buku</span>
                </a>
                <div id="bukuPages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="<?= BASE_URL ?>/view/buku/index.php">Data Buku</a>
                        <a class="collapse-item" href="<?= BASE_URL ?>/view/buku/insert.php">Insert Buku</a>
                    </div>
                </div>
          </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#userPages"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-user"></i>
                    <span>User</span>
                </a>
                <div id="userPages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="<?= BASE_URL ?>/view/user/index.php">Data User</a>
                        <a class="collapse-item" href="<?= BASE_URL ?>/view/user/insert.php">Insert User</a>
                    </div>
                </div>
          </li>

            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#siswaPages"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Data Siswa</span>
                </a>
                <div id="siswaPages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="<?= BASE_URL ?>/view/siswa/index.php">Data Siswa</a>
                        <a class="collapse-item" href="<?= BASE_URL ?>/view/siswa/insert.php">Insert Siswa</a>
                    </div>
                </div>
          </li>
<?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin') { ?> 
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#jurusanPages"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-wrench"></i>
                    <span>Data Jurusan</span>
                </a>
                <div id="jurusanPages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="<?= BASE_URL ?>/view/jurusan/index.php">Data Jurusan</a>
                        <a class="collapse-item" href="<?= BASE_URL ?>/view/jurusan/insert.php">Insert Jurusan</a>
                    </div>
                </div>
          </li>
          <li class="nav-item">
              <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
              aria-expanded="true" aria-controls="collapsePages">
              <i class="fas fa-ruler-combined"></i>
              <span>Kelas</span>
            </a>
            <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="<?= BASE_URL ?>/view/kelas/index.php">Data Kelas</a>
                    <a class="collapse-item" href="<?= BASE_URL ?>/view/kelas/insert.php">Insert Kelas</a>
                </div>
            </div>
        </li>
        <li class="nav-item">
              <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#DataPages"
              aria-expanded="true" aria-controls="collapsePages">
              <i class="fas fa-school"></i>
              <span>Data Sekolah</span>
            </a>
            <div id="DataPages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="<?= BASE_URL ?>/view/profile/profile.php">Data Sekolah</a>
                </div>
            </div>
        </li>
<?php }?>
        
            
            

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                  
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
                    
                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                           
                            <!-- Dropdown - Messages -->
                            
                        </li>

                        

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= isset($_SESSION['username']) ? $_SESSION['username'] : 'anonim' ?></span>
                                <img class="img-profile rounded-circle"
                                    src="<?= BASE_URL ?>/asset/pp.jpg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->
