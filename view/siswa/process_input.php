<?php 
require '../../vendor/autoload.php';
include '../../config.php';
include '../../koneksi.php';
session_start();

if(!isset($_SESSION['username'])){
    $url = BASE_URL . "/auth/login.php";
    echo '<script language="javascript">alert("Harap login terlebih dahulu"); document.location="'. $url .'"</script>';
    exit;
  }

if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    $urlBack = BASE_URL . "/view/dashboard/index.php";
    echo '<script language="javascript">alert("Anda tidak bisa mengakses halaman ini, karena anda bukan admin!"); document.location="' . $urlBack . '"</script>';
    exit;
}


use Picqer\Barcode\BarcodeGeneratorPNG;
$sukses = "";
$nama = $_POST['nama'];
$nis = $_POST['nis'];
$jk = $_POST['jk'];
$barcodeValue = $_POST['barcode'];
$id_kelas = $_POST['id_kelas'];
$id_jurusan = $_POST['id_jurusan'];
$alamat = $_POST['alamat'];
$telepon = $_POST['telepon'];
$foto = $_FILES['foto']['name'];
$tmp_name= $_FILES['foto']['tmp_name'];

if(isset($_FILES['foto'])){
    move_uploaded_file($tmp_name, '../../asset/foto_siswa/' . $foto);
}else{
    $_SESSION['error'] = "Foto tidak diupload dengan benar!";
    header('Location: insert.php');
    exit();
}
$generator = new BarcodeGeneratorPNG();
$barcode = $generator->getBarcode($barcodeValue, $generator::TYPE_CODE_128);

$barcodeFilePath =  '../../asset/barcode_siswa/' . $barcodeValue . '.png';
file_put_contents($barcodeFilePath, $barcode);

$checkQuery = $koneksi->prepare("SELECT COUNT(*) FROM siswa WHERE barcode = ?");
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

$query = $koneksi->prepare("INSERT INTO siswa (nama, nis, jk, barcode, id_kelas, id_jurusan, alamat, telepon, foto) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$query->bind_param("ssssiisss", $nama, $nis, $jk, $barcodeValue, $id_kelas, $id_jurusan, $alamat, $telepon, $foto);
if($query->execute()){
    $_SESSION['sukses'] = "Berhasil menambahkan data siswa!";
    header('Location: index.php');
    exit();
} else {
    $_SESSION['error'] = "Gagal menambahkan data!";
    header('Location: index.php');
    exit();
}

?>