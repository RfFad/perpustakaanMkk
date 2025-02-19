<?php
include '../../koneksi.php';
$query = $koneksi->prepare("SELECT * FROM sekolah WHERE id_sekolah = 1");
    $query->execute();
    $result = $query->get_result();
    echo json_encode($result->fetch_assoc());
    $result->close();
    exit;
?>