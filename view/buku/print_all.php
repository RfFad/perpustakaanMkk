<?php include '../../config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body{
            font-family: Arial, sans-serif;
            margin:0;
            padding:0;
        }
        .barcode {
        width: 60mm; /* Lebar area barcode */
        border: 1px solid #000; /* Opsi: beri garis untuk mempermudah pemotongan */
        

    }
    .barcode-footer p {
        font-size: 10pt;
        letter-spacing: 3px;
        margin-top: 10px;
        font-weight : bold;
    }
        
    @media print {
        @page {
        size: A4; /* Atur ukuran dan orientasi kertas */
        margin: 5mm; /* Atur margin sesuai kebutuhan */
    }
    body{
            font-family: Arial, sans-serif;
            margin:0;
            padding:0;
        }
}


        
        
    </style>
</head>
<body>
<!-- container -->
<div class="container">
<!-- row -->
  <div class="row">
    <!-- col -->
    <?php 
        include '../../koneksi.php';
        $query = $koneksi->prepare("SELECT * FROM buku");
        $query->execute();
        $result = $query->get_result();
        while($row = $result->fetch_array()) { ?>
    <div class="col">

<div class="barcode mb-3">
    <div class= "barcode-header text-center mt-3">
            <h6><?= $row['judul'] ?></h6>
    </div>
        <div class="barcode-body text-center">
            <img src="<?= BASE_URL ?>/asset/barcodes/<?= $row['barcode'] ?>.png" alt="">
        </div>
    <div class="barcode-footer text-center">
            <P><?= $row['barcode'] ?></p>
    </div>
</div>    

    </div>
    <?php } ?>
<!-- /col -->
  </div>
<!-- /row -->
</div>
<!-- /container -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
       window.onload = function(){
        window.print();
        setTimeout(function(){
            window.close();
        }, 2000)
       }
    </script>
</body>
</html>