<?php
include '../../koneksi.php';
session_start();

if(isset($_POST['input_pinjam'])){
    $id_buku = $_POST['id_buku'];
    $id_siswa = $_POST['id_siswa'];
    $id_admin = $_POST['id_admin'];
    $tanggal_pinjam = date('Y-m-d');
    $tanggal_kembali = $_POST['tanggal_kembali'];
    $status = "Dipinjam";
    $query= $koneksi->prepare("INSERT INTO peminjaman(id_buku, id_siswa, id_admin, tanggal_pinjam, tanggal_kembali, status) VALUES (?, ?, ?, ?, ?, ?)");
    $query->bind_param("iiisss", $id_buku, $id_siswa, $id_admin, $tanggal_pinjam, $tanggal_kembali, $status);
    if($query->execute()){
        $_SESSION['sukses'] = "Berhasil meminjam buku!";
        header("Location: index.php");
    }else{
        $_SESSION['error'] = "Gagal meminjam buku!";
        header("Location: pinjam.php");
    }
}


?>