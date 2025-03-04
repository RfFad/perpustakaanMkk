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


$id_buku = $_POST['id_buku'];
$judul = $_POST['judul'];
$pengarang = $_POST['pengarang'];
$penerbit = $_POST['penerbit'];
$tahun_terbit = $_POST['tahun_terbit'];
$stok = $_POST['stok'];
$barcodeValue = $_POST['barcode'];
$fotoBaru = $_FILES['foto']['name'];
$tmp_name = $_FILES['foto']['tmp_name'];

$checkQuery = $koneksi->prepare("SELECT COUNT(*) FROM buku WHERE barcode = ? AND id_buku != ?");
$checkQuery->bind_param("si", $barcodeValue, $id_buku);
$checkQuery->execute();
$checkQuery->bind_result($count);
$checkQuery->fetch();
$checkQuery->close();

if ($count > 0) {
    $_SESSION['error'] = "Barcode sudah digunakan oleh buku lain!";
    header('Location: index.php');
    exit();
}

$query = $koneksi->prepare("SELECT foto, barcode FROM buku WHERE id_buku = ?");
$query->bind_param('i', $id_buku);
$query->execute();
$result = $query->get_result();
$data = $result->fetch_assoc();
$fotoLama = $data['foto'];
$barcodeLama = $data['barcode'];

if (!empty($fotoBaru)) {
    if (file_exists('../../asset/buku/' . $fotoLama)) {
        unlink('../../asset/buku/' . $fotoLama);
    }
    move_uploaded_file($tmp_name, '../../asset/buku/' . $fotoBaru);
    $foto = $fotoBaru;
} else {
    $foto = $fotoLama;
}

use Picqer\Barcode\BarcodeGeneratorPNG;
$generator = new BarcodeGeneratorPNG();

if ($barcodeValue !== $barcodeLama) {
    $barcodeFilePathLama = '../../asset/barcodes/' . $barcodeLama . '.png';
    if (file_exists($barcodeFilePathLama)) {
        unlink($barcodeFilePathLama);
    }
}

$barcode = $generator->getBarcode($barcodeValue, $generator::TYPE_CODE_128);
$barcodeFilePathBaru = '../../asset/barcodes/' . $barcodeValue . '.png';
file_put_contents($barcodeFilePathBaru, $barcode);

$query = $koneksi->prepare("UPDATE buku SET judul = ?, pengarang = ?, penerbit = ?, tahun_terbit = ?, foto = ?, barcode = ?, stok=? WHERE id_buku = ?");
$query->bind_param("ssssssii", $judul, $pengarang, $penerbit, $tahun_terbit, $foto, $barcodeValue, $stok, $id_buku);

if ($query->execute()) {
    $_SESSION['sukses'] = "Berhasil mengupdate data buku!";
    header('Location: index.php');
    exit();
} else {
    $_SESSION['error'] = "Gagal mengupdate data!";
    header('Location: index.php');
    exit();
}

$query->close();
?>
