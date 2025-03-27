<?php 
include '../../koneksi.php';
session_start();
if(!isset($_SESSION['username'])){
    $url = BASE_URL . "/auth/login.php";
    echo '<script language="javascript">alert("Harap anda login terlebih dahulu"); document.location="'. $url .'"</script>';
    exit;
  }
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    $urlBack = BASE_URL . "/view/dashboard/index.php";
    echo '<script language="javascript">alert("Anda tidak bisa mengakses halaman ini, karena anda bukan admin!"); document.location="' . $urlBack . '"</script>';
    exit;
}
    $id = $_GET['id'];

    $querySelect = $koneksi->prepare("SELECT barcode FROM buku WHERE id_buku = ?");
    $querySelect->bind_param("i", $id);
    $querySelect->execute();
    $result = $querySelect->get_result();
    $row = $result->fetch_assoc();
    $barcodeFile = "../../asset/barcodes/" . htmlspecialchars($row['barcode']) . ".png";

    $query = $koneksi->prepare("DELETE FROM buku WHERE id_buku = ?");
    $query->bind_param("i", $id);
    if($query->execute()){
        if (file_exists($barcodeFile)) {
            unlink($barcodeFile); // Fungsi PHP untuk menghapus file
        }
        $_SESSION['sukses'] = "Berhasil menghapus data buku!";
        header('Location: index.php');
        exit();
    }else{
        $_SESSION['error'] = "Gagal menghapus data!";
    }
    $query->close();
    $koneksi->close();

?>