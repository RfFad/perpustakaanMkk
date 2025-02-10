
<?php
$title = 'Dashboard';


include '../../layout/header.php';

if(!isset($_SESSION['username'])){
    $url = BASE_URL . "/auth/login.php";
    echo '<script language="javascript">alert("Harap anda login terlebih dahulu"); document.location="'. $url .'"</script>';
    exit;
  }
?>


<div class="container-fluid">

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
            class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
</div>

<!-- Content Row -->
<div class="row">

    <!-- Earnings (Monthly) Card Example -->
    <a href="../buku/index.php" class="col-xl-3 col-md-6 mb-4 text-decoration-none" id="card">
    <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        DATA BUKU
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        $40,000
                    </div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-book fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</a>


    <!-- Earnings (Monthly) Card Example -->
    <a href="../siswa/index.php" class="col-xl-3 col-md-6 mb-4 text-decoration-none" id="card">
    <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        DATA SISWA
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        $40,000
                    </div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-users fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</a>

    <!-- Earnings (Monthly) Card Example -->
    <a href="../jurusan/index.php" class="col-xl-3 col-md-6 mb-4 text-decoration-none" id="card">
    <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        DATA JURUSAN
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        $40,000
                    </div>  
                </div>
                <div class="col-auto">
                    <i class="fas fa-wrench fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</a>

    <!-- Pending Requests Card Example -->
    <a href="../kelas/index.php" class="col-xl-3 col-md-6 mb-4 text-decoration-none" id="card">
    <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        DATA KELAS
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        $40,000
                    </div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-ruler-combined fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</a>
<!-- Content Row -->

</div>
<a href="../guru/index.php" class="col-xl-3 col-md-6 mb-4 text-decoration-none" id="card">
    <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                        DATA USER
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        $40,000
                    </div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-user fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</a>
</div>
<!-- /.container-fluid -->
<?php include '../../layout/footer.php'; ?>
