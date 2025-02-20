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


$id_siswa = $_POST['id_siswa'];
$nis = $_POST['nis'];
$nama = $_POST['nama'];
$id_kelas = $_POST['id_kelas'];
$id_jurusan = $_POST['id_jurusan'];
$telepon = $_POST['telepon'];
$alamat = $_POST['alamat'];
$fotoBaru = $_FILES['foto']['name'];
$tmp_name = $_FILES['foto']['tmp_name'];
$barcodeValue = $_POST['barcode'];

$checkBarcode = $koneksi->prepare("SELECT COUNT(*) FROM siswa WHERE barcode = ? AND id_siswa != ? ");
$checkBarcode->bind_param("si", $barcodeValue, $id_siswa);
$checkBarcode->execute();
$checkBarcode->bind_result($count);
$checkBarcode->fetch();
$checkBarcode->close();

if($count > 0){
    $_SESSION['error'] = "Barcode sudah digunakan oleh buku lain!";
    header('Location: index.php');
    exit();
}
$query = $koneksi->prepare("SELECT foto, barcode FROM siswa WHERE id_siswa = ?");
$query->bind_param("i", $id_siswa);
$query->execute();
$result = $query->get_result();
$data = $result->fetch_assoc();
$fotoLama = $data['foto'];
$barcodeLama = $data['barcode'];

if (!empty($fotoBaru)) {
    if (file_exists('../../asset/foto_siswa/' . $fotoLama)) {
        unlink('../../asset/foto_siswa/' . $fotoLama);
    }
    move_uploaded_file($tmp_name, '../../asset/foto_siswa/' . $fotoBaru);
    $foto = $fotoBaru;
} else {
    $foto = $fotoLama;
}

use Picqer\Barcode\BarcodeGeneratorPNG;
$generator = new BarcodeGeneratorPNG();

if ($barcodeValue !== $barcodeLama) {
    $barcodeFilePathLama = '../../asset/barcode_siswa/' . $barcodeLama . '.png';
    if (file_exists($barcodeFilePathLama)) {
        unlink($barcodeFilePathLama);
    }
}

$barcode = $generator->getBarcode($barcodeValue, $generator::TYPE_CODE_128);
$barcodeFilePathBaru = '../../asset/barcode_siswa/' . $barcodeValue . '.png';
file_put_contents($barcodeFilePathBaru, $barcode);

$query = $koneksi->prepare("UPDATE siswa SET nis= ?, nama= ?, id_kelas= ?, id_jurusan= ?, telepon= ?, alamat= ?, foto= ?, barcode= ? WHERE id_siswa = ?");
$query->bind_param("ssiissssi", $nis, $nama, $id_kelas, $id_jurusan, $telepon, $alamat, $foto, $barcodeValue, $id_siswa);
if($query->execute()){
    $_SESSION['sukses'] = "Berhasil mengupdate data siswa!";
    header('Location: index.php');
    exit();
}else {
    $_SESSION['error'] = "Gagal mengupdate data!";
    header('Location: index.php');
    exit();
}
$query->close();

?>