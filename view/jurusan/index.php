<?php
$title = 'Jurusan';

include '../../layout/header.php';

// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'wb_perpus';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

// Handle Update Request
if (isset($_POST['update'])) {
    $id_jurusan = $_POST['id_jurusan'];
    $nama_jurusan = $_POST['nama_jurusan'];
    $singkatan = $_POST['singkatan'];

    $stmt = $conn->prepare("UPDATE jurusan SET nama_jurusan = ?, singkatan = ? WHERE id_jurusan = ?");
    $stmt->bind_param("ssi", $nama_jurusan, $singkatan, $id_jurusan);

    if ($stmt->execute()) {
        echo "<script>Swal.fire({title: 'Berhasil!', text: 'Data berhasil diperbarui.', icon: 'success'});</script>";
    } else {
        echo "<script>Swal.fire({title: 'Gagal!', text: 'Terjadi kesalahan.', icon: 'error'});</script>";
    }

    $stmt->close();
}

// Handle Delete Request
if (isset($_POST['delete'])) {
    $id_jurusan = $_POST['id_jurusan'];

    $stmt = $conn->prepare("DELETE FROM jurusan WHERE id_jurusan = ?");
    $stmt->bind_param("i", $id_jurusan);

    if ($stmt->execute()) {
        echo "<script>Swal.fire({title: 'Berhasil!', text: 'Data berhasil dihapus.', icon: 'success'});</script>";
    } else {
        echo "<script>Swal.fire({title: 'Gagal!', text: 'Terjadi kesalahan saat menghapus data.', icon: 'error'});</script>";
    }

    $stmt->close();
}

?>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid">

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Data Jurusan</h1>
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <a href="<?= BASE_URL ?>/view/jurusan/insert.php" class="btn btn-sm btn-success"><i class="fas fa-plus"></i> tambah data</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered tabelData" id="example1" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Jurusan</th>
                        <th>Singkatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Nama Jurusan</th>
                        <th>Singkatan</th>
                        <th>Aksi</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM jurusan";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        $no = 1;
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $no++ . "</td>";
                            echo "<td>" . htmlspecialchars($row['nama_jurusan']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['singkatan']) . "</td>";
                            echo "<td>";
                            echo "<button class='btn btn-sm btn-primary' onclick='showUpdateModal(" . $row['id_jurusan'] . ", \"" . htmlspecialchars($row['nama_jurusan']) . "\", \"" . htmlspecialchars($row['singkatan']) . "\")'><i class='fas fa-edit'></i> Update</button> ";
                            echo "<button class='btn btn-sm btn-danger' onclick='confirmDelete(" . $row['id_jurusan'] . ")'><i class='fas fa-trash'></i> Delete</button>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' class='text-center'>Tidak ada data</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</div>

<!-- Update Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Update Data Jurusan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" id="id_jurusan" name="id_jurusan">
                    <div class="form-group">
                        <label for="nama_jurusan">Nama Jurusan</label>
                        <input type="text" class="form-control" id="nama_jurusan" name="nama_jurusan" required>
                    </div>
                    <div class="form-group">
                        <label for="singkatan">Singkatan</label>
                        <input type="text" class="form-control" id="singkatan" name="singkatan" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="update" class="btn btn-primary"><i class="fas fa-save"></i> Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showUpdateModal(id, nama, singkatan) {
    document.getElementById('id_jurusan').value = id;
    document.getElementById('nama_jurusan').value = nama;
    document.getElementById('singkatan').value = singkatan;
    $('#updateModal').modal('show');
}

function confirmDelete(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: 'Data yang dihapus tidak dapat dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '';

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'delete';
            input.value = true;

            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'id_jurusan';
            idInput.value = id;

            form.appendChild(input);
            form.appendChild(idInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>

<?php
$conn->close();
include '../../layout/footer.php';
?>
