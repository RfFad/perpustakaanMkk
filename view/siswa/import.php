<?php
require '../../vendor/autoload.php';
include '../../koneksi.php'; // Koneksi ke database
session_start();
use PhpOffice\PhpSpreadsheet\IOFactory;
use Picqer\Barcode\BarcodeGeneratorPNG;
if(isset($_POST['import'])){
    $file = $_FILES['file']['tmp_name'];
    $spreadsheet = IOFactory::load($file);
    $worksheet = $spreadsheet->getActiveSheet();
    $rows = $worksheet->toArray();

    foreach ($rows as $index => $row){
        if($index == 0) continue;
        $nis = $row[0];
        $nama = $row[1];
        $kelas = $row[2];
        $telepon = $row[3];
        $alamat = $row[4];

        $generator = new BarcodeGeneratorPNG();
            $barcode = $generator->getBarcode($nis, $generator::TYPE_CODE_128);

            $barcodeFilePath =  '../../asset/barcode_siswa/' . $nis . '.png';
            file_put_contents($barcodeFilePath, $barcode);

            $checkQuery = $koneksi->prepare("SELECT COUNT(*) FROM siswa WHERE barcode = ?");
            $checkQuery->bind_param("s", $nis);
            $checkQuery->execute();
            $checkQuery->bind_result($count);
            $checkQuery->fetch();
            $checkQuery->close();

            if ($count > 0) {
                $_SESSION['error'] = "Barcode sudah ada!";
                header('Location: insert.php');
                exit();
            }
        
        $kelas_pisah = explode(" ", $kelas);
        if (count($kelas_pisah) < 3) {
            echo "Format kelas tidak valid untuk '$kelas'!<br>";
            continue;
        }

        $tingkatan = $kelas_pisah[0];
        $nama_jurusan = $kelas_pisah[1];
        $nama_kelas = $kelas_pisah[2];

        $query_jurusan =$koneksi->prepare("SELECT id_jurusan FROM jurusan WHERE singkatan = ?");
        $query_jurusan->bind_param("s", $nama_jurusan);
        $query_jurusan->execute();
        $query_jurusan->bind_result($id_jurusan);
        $query_jurusan->fetch();
        $query_jurusan->close();
        
        if(!$id_jurusan){
            echo "Jurusan '$nama_jurusan' tidak ditemukan!<br>";
            continue;
        }

        $query_kelas = $koneksi->prepare("SELECT id_kelas FROM kelas WHERE tingkat = ? AND nama_kelas = ? ");
        $query_kelas->bind_param("ss", $tingkatan, $nama_kelas);
        $query_kelas->execute();
        $query_kelas->bind_result($id_kelas);
        $query_kelas->fetch();
        $query_kelas->close();

        if (!$id_kelas) {
            echo "Kelas '$tingkatan $nama_kelas' tidak ditemukan!<br>";
            continue;
        }

        $insertQuery = $koneksi->prepare("INSERT INTO siswa (nama, nis, id_kelas, id_jurusan, alamat, telepon, barcode)VALUES(?, ?, ?, ?, ?, ?, ?)");
        $insertQuery->bind_param("ssiisss", $nama, $nis, $id_kelas, $id_jurusan, $alamat, $telepon, $nis);
        $insertQuery->execute();
        $insertQuery->close();
    }
    header("Location: index.php");
    return $_SESSION['sukses'] = "Data Berhasil diImport!";
}

?>
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="file" required>
    <button type="submit" name="import">Import Excel</button>
</form>