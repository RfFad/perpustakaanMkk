<?php
include '../../layout/header.php';
?>
<div class="container-fluid">

<!-- Page Heading -->

<!-- DataTales Example -->
<div class="d-flex justify-content-center vh-50">

<div class="card shadow mb-4 w-50">
    <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Insert Jurusan</h6>
    </div>
    <div class="card-body">
       <div class="form-group">
        <input type="text" class="form-control">
       </div>
    </div>
    <div class="card-footer">
        <a href="<?= BASE_URL ?>/view/jurusan/index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> kembali</a>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
    </div>
</div>
</div>

</div>
<!-- /.container-fluid -->
<?php
include '../../layout/footer.php';

?>