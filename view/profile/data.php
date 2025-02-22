<?php
include '../../koneksi.php';
session_start();
if(!isset($_SESSION['username'])){
    $url = BASE_URL . "/auth/login.php";
    echo '<script language="javascript">alert("Harap anda login terlebih dahulu"); document.location="'. $url .'"</script>';
    exit;
  }
$query = $koneksi->prepare("SELECT * FROM sekolah WHERE id_sekolah = 1");
    $query->execute();
    $result = $query->get_result();
    echo json_encode($result->fetch_assoc());
    $result->close();
    exit;
?>