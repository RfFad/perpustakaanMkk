<?php
include '../../koneksi.php';
$query = $koneksi->prepare("UPDATE peminjaman SET status = 'Melebihi waktu' WHERE tanggal_kembali < CURDATE() AND status='Dipinjam'");
if($query->execute()){
    echo json_encode(["status" => "success", "message" => "Status diperbarui"]);
} else {
    echo json_encode(["status" => "error", "message" => "Gagal memperbarui status"]);
}
?>