<?php
include '../../koneksi.php';
session_start();
if (isset($_POST['simpan'])) {
    $id_siswa = $_POST['id_siswa'];
    $keperluan = $_POST['keperluan_kunjungan'];
    $waktu_masuk = $_POST['waktu_masuk'];
    $waktu_keluar = $_POST['waktu_keluar'];
    $ttd = $_POST['ttd']; // Gambar tanda tangan dalam format base64
    
    if($_POST['id_anggota']){
        $id_anggota = $_POST['id_anggota'];
    }else{
        $id_anggota = null;

    }
    //untuk anggota baru
    $nama_anggota = $_POST['nama_anggota'];
    $nip = $_POST['nip'];
    $alamat = $_POST['alamat'];

    if($nama_anggota){
        $queryBaru = $koneksi->prepare("INSERT INTO data_keanggotaan (nama_anggota, nip, alamat) VALUES (?, ?, ?)");
        $queryBaru->bind_param("sss", $nama_anggota, $nip, $alamat);

        if($queryBaru->execute()){
            $id_anggota= $koneksi->insert_id;
        }
    }

    // Bersihkan base64 untuk penyimpanan
    $ttd = str_replace('data:image/png;base64,', '', $ttd);
    $ttd = base64_decode($ttd);

    // Simpan ke file
    $nama_file = "ttd_" . time() . ".png";
    $file_path = "../../asset/ttd_kunjungan/" . $nama_file;
    file_put_contents($file_path, $ttd);

    // Simpan ke database
    $query = $koneksi->prepare("INSERT INTO kunjungan (id_siswa, id_anggota, keperluan_kunjungan, waktu_masuk, waktu_keluar, ttd) VALUES (?, ?, ?, ?, ?, ?)");
    $query->bind_param("iissss", $id_siswa, $id_anggota, $keperluan, $waktu_masuk, $waktu_keluar, $nama_file);

    if ($query->execute()) {
        $_SESSION['sukses'] = "Data berhasil disimpan!";
        header("Location: insert.php");
    } else {
        $_SESSION['error'] = "Gagal menyimpan data.";
        header("Location: insert.php");
    }
}
?>
