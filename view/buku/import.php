<?php 

require '../../vendor/autoload.php';
include '../../koneksi.php';

session_start();

if(!isset($_SESSION['username'])){
    $url = BASE_URL . "/auth/login.php";
    echo '<script language="javascript">alert("Harap login terlebih dahulu"); document.location="'. $url .'"</script>';
    exit;
  }

  use PhpOffice\PhpSpreadsheet\IOFactory;
  use Picqer\Barcode\BarcodeGeneratorPNG;

  //UNTUK FORMAT TANGGAL
  function convertDate($date) {
    $formats = ['m/d/Y', 'd/m/Y', 'Y-m-d', 'd-M-Y']; 
    foreach ($formats as $format) {
        $dateTime = DateTime::createFromFormat($format, $date);
        if ($dateTime) {
            return $dateTime->format('Y-m-d');
        }
    }
    return false; 
}

  if(isset($_POST['import'])){
    $file = $_FILES['file']['tmp_name'];
    $spreadsheet = IOFactory::load($file);
    $worksheet = $spreadsheet->getActiveSheet();
    $rows = $worksheet->toArray();
    
    foreach($rows as $index => $row){
        if($index == 0) continue;
        $judul = $row[0];
        $pengarang = $row[1];
        $penerbit = $row[2];
        $tahun_terbit = $row[3];
        $barcodeValue = $row[4];

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
        
        $tanggal_fix = convertDate($tahun_terbit);

        //untuk menginput data 
        $insertQuery = $koneksi->prepare("INSERT INTO buku (judul, pengarang, penerbit, tahun_terbit, barcode)VALUES(?, ?, ?, ?, ?)");
        $insertQuery->bind_param("sssss", $judul, $pengarang, $penerbit, $tanggal_fix, $barcodeValue);
        $insertQuery->execute();
        $insertQuery->close();
    }
    header("Location: index.php");
    return $_SESSION['sukses'] = "Data Berhasil diImport!";

  }
?>