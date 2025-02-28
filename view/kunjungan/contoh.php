<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Kunjungan</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h2>Form Kunjungan</h2>
    <form action="proses_kunjungan.php" method="post" enctype="multipart/form-data">
        <label>Pilih Jenis Pengunjung:</label>
        <select id="jenis_pengunjung" name="jenis_pengunjung" required>
            <option value="">-- Pilih --</option>
            <option value="siswa">Siswa</option>
            <option value="anggota">Anggota</option>
            <option value="baru">Anggota Baru</option>
        </select>
        <br><br>

        <div id="siswa_section" style="display: none;">
            <label for="id_siswa">Pilih Siswa:</label>
            <select name="id_siswa">
                <option value="">-- Pilih Siswa --</option>
                <?php
                include '../../koneksi.php';
                $query = mysqli_query($koneksi, "SELECT id_siswa, nama FROM siswa");
                while ($row = mysqli_fetch_assoc($query)) {
                    echo "<option value='{$row['id_siswa']}'>{$row['nama']}</option>";
                }
                ?>
            </select>
            <br><br>
        </div>

        <div id="anggota_section" style="display: none;">
            <label for="id_anggota">Pilih Anggota:</label>
            <select name="id_anggota">
                <option value="">-- Pilih Anggota --</option>
                <?php
                $query = mysqli_query($koneksi, "SELECT id_anggota, nama_anggota FROM data_keanggotaan");
                while ($row = mysqli_fetch_assoc($query)) {
                    echo "<option value='{$row['id_anggota']}'>{$row['nama_anggota']}</option>";
                }
                ?>
            </select>
            <br><br>
        </div>

        <div id="baru_section" style="display: none;">
            <label>Nama Anggota Baru:</label>
            <input type="text" name="nama_baru">
            <br><br>
        </div>

        <label>Keperluan Kunjungan:</label>
        <input type="text" name="keperluan_kunjungan" required>
        <br><br>

        <label>Tujuan Kunjungan:</label>
        <textarea name="tujuan" required></textarea>
        <br><br>

        <label>Waktu Masuk:</label>
        <input type="datetime-local" name="waktu_masuk" required>
        <br><br>

        <label>Waktu Keluar:</label>
        <input type="datetime-local" name="waktu_keluar">
        <br><br>

        <label>Tanda Tangan:</label>
        <input type="file" name="ttd" accept="image/*">
        <br><br>

        <button type="submit">Simpan</button>
    </form>

    <script>
        $(document).ready(function(){
            $('#jenis_pengunjung').change(function(){
                var jenis = $(this).val();
                $('#siswa_section, #anggota_section, #baru_section').hide();

                if (jenis == 'siswa') {
                    $('#siswa_section').show();
                } else if (jenis == 'anggota') {
                    $('#anggota_section').show();
                } else if (jenis == 'baru') {
                    $('#baru_section').show();
                }
            });
        });
    </script>
</body>
</html>
