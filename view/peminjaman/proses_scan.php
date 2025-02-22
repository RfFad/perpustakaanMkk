<?php 
header('Content-Type: application/json');
include '../../koneksi.php'; // Pastikan file koneksi database ada
session_start();
if(isset($_GET['action'])){
    $action = $_GET['action'];
}else{
    $action = "";
}

$allowed_role = ['admin', 'operator'];
if(!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowed_role)){
    session_destroy();
    echo "<script>alert('Akses ditolak! Anda tidak memiliki izin.'); window.location.href='../../auth/login.php';</script>";
    exit();
}

if($action === 'buku' && isset($_GET['barcode'])){
    $barcode = $_GET['barcode'];
    $query = $koneksi->prepare("SELECT * FROM buku WHERE barcode = ?");
    $query->bind_param("s", $barcode);
    $query->execute();
    $result = $query->get_result();
    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        echo json_encode([
            'success' => true,
            'id_buku' => $row['id_buku'],
            'judul' => $row['judul'],
            'pengarang' => $row['pengarang'],
            'penerbit' => $row['penerbit'],
            'tahun_terbit' => $row['tahun_terbit'],
            'foto' => $row['foto'],
            'barcode' => $row['barcode'],
        ]);
    }else {
        echo json_encode(['success' => false, 'message' => 'Siswa tidak ditemukan']);
    }
    exit;
    //$result->close();
}
if (isset($_GET['barcode'])) {
    $barcode = $_GET['barcode'];

    // Debugging: Menampilkan barcode yang diterima
    error_log("Barcode yang diterima: " . $barcode);

    // Query cari siswa berdasarkan barcode
    $sql = "SELECT * FROM siswa WHERE barcode = '$barcode'";
    $result = mysqli_query($koneksi, $sql);

    // Debugging: Memeriksa apakah query berhasil
    if (!$result) {
        echo json_encode(['success' => false, 'message' => 'Query Error: ' . mysqli_error($conn)]);
        exit;
    }

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode([
            'success' => true,
            'barcode' => $row['barcode'],
            'nis' => $row['nis'],
            'nama' => $row['nama'],
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Barcode tidak valid']);
}


?>