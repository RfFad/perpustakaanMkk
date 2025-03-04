<?php 
require '../../vendor/autoload.php';
include '../../config.php';
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

use Picqer\Barcode\BarcodeGeneratorPNG;

$sukses = "";
$judul = $_POST['judul'];
$pengarang = $_POST['pengarang'];
$penerbit = $_POST['penerbit'];
$tahun_terbit = $_POST['tahun_terbit'];
$stok = $_POST['stok'];
$foto = $_FILES['foto']['name'];
$tmp_name = $_FILES['foto']['tmp_name'];

if (isset($_FILES['foto'])) {
    move_uploaded_file($tmp_name, '../../asset/buku/' . $foto);
} else {
    $_SESSION['error'] = "Foto tidak diupload dengan benar!";
    header('Location: insert.php');
    exit();
}

$barcodeValue = $_POST['barcode'];

$generator = new BarcodeGeneratorPNG();
$barcode = $generator->getBarcode($barcodeValue, $generator::TYPE_CODE_128);

$barcodeFilePath =  '../../asset/barcodes/' . $barcodeValue . '.png';
file_put_contents($barcodeFilePath, $barcode);

$checkQuery = $koneksi->prepare("SELECT COUNT(*) FROM buku WHERE barcode = ?");
$checkQuery->bind_param("s", $barcodeValue);
$checkQuery->execute();
$checkQuery->bind_result($count);
$checkQuery->fetch();
$checkQuery->close();

if ($count > 0) {
    $_SESSION['error'] = "Barcode sudah ada!";
    header('Location: insert.php');
    exit();
}


$query = $koneksi->prepare("INSERT INTO buku (judul, pengarang, penerbit, tahun_terbit, foto, barcode, stok) VALUES (?, ?, ?, ?, ?, ?, ?)");
$query->bind_param("ssssssi", $judul, $pengarang, $penerbit, $tahun_terbit, $foto, $barcodeValue, $stok);

if ($query->execute()) {
    $_SESSION['sukses'] = "Berhasil menambahkan buku!";
    header('Location: index.php');
    exit();
} else {
    $_SESSION['error'] = "Gagal menambahkan data!";
}
$query->close();
?>
