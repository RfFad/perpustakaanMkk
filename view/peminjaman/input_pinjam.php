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
    $query->close();
}
if(isset($_POST['update_pinjam'])){
    $id = $_POST['id'];
    $id_buku = $_POST['id_buku'];
    $tanggal_kembali = $_POST['tanggal_kembali'];
    $status = $_POST['status'];
    $query = $koneksi->prepare("UPDATE peminjaman SET id_buku = ?, tanggal_kembali = ?, status = ? WHERE id_peminjaman = ?");
    $query->bind_param("issi", $id_buku, $tanggal_kembali, $status, $id);
    if($query->execute()){
        $_SESSION['sukses'] = "Berhasil mengupdate data peminjaman!";
        header("Location: index.php");
    }else{
        $_SESSION['error'] = "Gagal mengupdate data peminjaman!";
        header("Location: index.php");
    }
    $query->close();
}
?>