<?php 
include '../../config.php'; 
include '../../koneksi.php' ;


$querySk = $koneksi->prepare("SELECT * FROM sekolah WHERE id_sekolah = 1");
$querySk->execute();
$resultSk = $querySk->get_result();
$rowSk = $resultSk->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cetak Semua Kartu Perpustakaan</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f4f4f4;
    }
    .container {
    display: grid;
    grid-template-columns: repeat(2, 1fr); /* 2 kartu per baris */
    gap: 10px;
    width: 100%;
    page-break-before: always;
  }

    .foldable-card {
    width: 900px;
    height: 250px;
    display: flex;
    border: 1px solid #000;
    border-radius: 10px;
    background: #fff;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }

  /* Bagian Tampilan Depan dan Belakang */
  .front, .back {
    width: 50%;
    padding: 20px;
    box-sizing: border-box;
    position: relative;
    border-right: 1px solid #ddd; /* Untuk .front */
    overflow: hidden;
  }

  .front:before, .back:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image: url('<?= BASE_URL ?>/asset/logo.png'); /* Path gambar watermark */
    background-size: 200px;
    background-position: center;
    background-repeat: no-repeat;
    opacity: 0.1; /* Transparansi gambar background */
    z-index: 0;
  }

  .front:after, .back:after {
    content: 'SMKN 1 CIREBON';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    font-size: 30px;
    font-weight: bold;
    color: rgba(0, 0, 0, 0.05); /* Transparansi teks */
    text-align: center;
    z-index: 1;
    display: flex;
    justify-content: center;
    align-items: center;
    background-image: repeating-linear-gradient(
        -45deg,
        rgba(0, 0, 0, 0.05) 0,
        rgba(0, 0, 0, 0.05) 20%,
        transparent 20%,
        transparent 40%
    ), repeating-linear-gradient(
        45deg,
        rgba(0, 0, 0, 0.05) 0,
        rgba(0, 0, 0, 0.05) 20%,
        transparent 20%,
        transparent 40%
    );
    background-size: 200px 200px;
    overflow: hidden;
  }


  .front > *, .back > * {
    position: relative;
    z-index: 2;
  }

  /* Header Sekolah */
  .header-section {
    display: flex;
    align-items: center;
  }

  .logo {
    width: 40px;
    height: 40px;
    margin-right: 50px;
    margin-top: -30px;
  }

  .school-info h2 {
    text-align: center;
    font-size: 10px;
    margin: 0;
  }

  .school-info p {
    text-align: center;
    font-size: 10px;
    margin: 2px 0;
  }

  /* Bagian Profil */
  .profile-section {
    display: flex;
    align-items: center;
    margin-top: 15px;
  }

  .profile-photo {
    width: 60px;
    height: 80px;
    border: 2px solid #ddd;
    margin-right: 15px;
    border-radius: 5px;
    object-fit: cover;
  }

  .member-info p {
    margin: 5px 0;
    font-size: 10px;
  }

  /* Bagian Tampilan Belakang */
  .barcode-section {
    margin: 10px 0;
  }

  .barcode {
    width: 110px;
    height: auto;
  }

  .back-info p, .footer-info p {
    font-size: 8px;
    margin: 5px 0;
  }

  .footer-info {
    margin-top: 20px;
    font-size: 12px;
    text-align: left;
  }
  @media print {
  body {
    background: none;
    margin: 0;
    padding: 0;
  }
  .container{
    display: block;
  }
  .foldable-card {
    width: 16cm; 
    height: 5cm; 
    border: none;
    box-shadow: none;
    page-break-inside: avoid;
    
    /* Pastikan background tidak hilang */
    background: url('background-image.jpg') no-repeat center center !important;
    background-size: cover !important;
    -webkit-print-color-adjust: exact; /* Untuk Safari & Chrome */
    print-color-adjust: exact; /* Untuk browser lain */
  }
}

</style>
</head>
<body>
    <div class="container">
<?php
          
          include '../../koneksi.php';
          $query = $koneksi->prepare("SELECT s.*, k.nama_kelas, k.tingkat, j.singkatan FROM siswa s JOIN kelas k ON s.id_kelas = k.id_kelas JOIN jurusan j ON s.id_jurusan = j.id_jurusan");
          $query->execute();
          $result = $query->get_result();

          while($row = $result->fetch_array()){ ?>
  <div class="foldable-card">
    <!-- Bagian Tampilan Depan -->
    <div class="front">
      <div class="header-section">
        <img src="<?= BASE_URL ?>/asset/<?= $rowSk['foto'] ?>" alt="Logo Sekolah" class="logo">
        <div class="school-info">
        <h2>Kartu Anggota Perpustakaan</h2>
        <h2><?= $rowSk['nama_sekolah'] ?></h2>
          <p><?= $rowSk['alamat_sekolah'] ?></p>
          <p>Website: <a href="<?= $rowSk['website_sekolah'] ?>"><?= $rowSk['website_sekolah'] ?></a></p>
          <p>Email: <?= $rowSk['email_sekolah'] ?></p>
        </div>
      </div>
      <div class="profile-section">
      
        <img src="<?= $row['foto'] ? BASE_URL . '/asset/foto_siswa/' . $row['foto'] : BASE_URL . '/asset/profile.jpg' ?>" alt="Foto Profil" class="profile-photo">
        <div class="member-info">
          
          <p><strong>ID Anggota:</strong> <?= $row['nis'] ?></p>
          <p><strong>Nama:</strong> <?= $row['nama'] ?></p>
          <p><strong>Kelas:</strong> <?= $row['tingkat'] ?> <?= $row['singkatan'] ?> <?= $row['nama_kelas'] ?></p>
          <p><strong>Alamat:</strong> <?= $row['alamat'] ?></p>
          <p><strong>Telepon:</strong> <?= $row['telepon'] ?></p>
         
        </div>
      </div>
    </div>

    <!-- Bagian Tampilan Belakang -->
    <div class="back">
      <div class="barcode-section">
        <img src="<?= BASE_URL ?>/asset/barcode_siswa/<?= $row['barcode'] ?>.png" alt="Barcode Anggota" class="barcode">
      </div>
      <div class="back-info">
        <p>Kartu ini diterbitkan oleh Perpustakaan SMK Negeri 1 Cirebon.</p>
        <p>Harap mengembalikan kartu ini kepada pemiliknya jika Anda menemukannya.</p>
      </div>
      <div class="footer-info">
        <p><strong>Berlaku Hingga:</strong> 2027-01-21</p>
        <p><strong>Penanggung Jawab:</strong> Dr. Hj. Weti Kurniawati, S.Pd., M.M.</p>
        <p><strong>NIP:</strong> 197004241993012005</p>
      </div>
    </div>
  </div>
  <?php
          }
          ?>
          </div>
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
