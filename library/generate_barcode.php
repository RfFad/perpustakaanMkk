<?php
require '../vendor/autoload.php'; // Picqer autoload

use Picqer\Barcode\BarcodeGeneratorHTML;

if (isset($_POST['barcode_value'])) {
    $barcodeValue = $_POST['barcode_value']; // Ambil barcode dari form
    $generator = new BarcodeGeneratorHTML(); // Gunakan format HTML
    $barcode = $generator->getBarcode($barcodeValue, $generator::TYPE_CODE_128);

    echo json_encode([
        'barcode' => $barcode,
        'value' => $barcodeValue
    ]);
    exit;
}
?>
