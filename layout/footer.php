</div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="<?= BASE_URL ?>/auth/logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

<!-- Profile -->
    <style>
  .text-center.profile-photo {
  display: flex;
  justify-content: center;
  align-items: center;
}

.profile-status-border {
  width: 110px;
  height: 110px;
  display: flex;
  justify-content: center;
  align-items: center;
  border-radius: 50%;
  padding: 5px;
  position: relative;
}

.profile-user-img {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid #fff;
}
</style>
                <div class="modal fade" id="profileModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Profile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                <div class="card border-bottom-primary">
                                <div class="card-body box-profile">
                                    <div class="text-center profile-photo">
                                        <div class="profile-status-border bg-secondary">
                                            <img class="profile-user-img img-fluid rounded-circle" src="profile.jpg" id="foto_siswa" alt="User profile picture">
                                        </div>
                                    </div>
                                    <h4 class="profile-username text-center" id="username_profile"><?= $_SESSION['nama'] ?></h4>
                                    <p class="text-muted text-center" id="nis"></p>
                                    <ul class="list-group list-group-unbordered mb-3">
                                        <li class="list-group-item">
                                            <b>Username</b> <a class="float-right" id="nama_profile"><?= $_SESSION['username'] ?></a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Email</b> <a class="float-right" id="email_profile"><?= $_SESSION['email'] ?></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- End Profile -->
    <!-- Bootstrap core JavaScript-->
    <script src="<?= BASE_URL ?>../public/vendor/jquery/jquery.min.js"></script>
    <script src="<?= BASE_URL ?>../public/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= BASE_URL ?>../public/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= BASE_URL ?>../public/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="<?= BASE_URL ?>../public/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= BASE_URL ?>../public/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="<?= BASE_URL ?>../public/vendor/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?= BASE_URL ?>../public/vendor/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <!-- Page level custom scripts -->
    <script src="<?= BASE_URL ?>../public/js/demo/datatables-demo.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

     <!-- Buttons Extension JS -->

    <script>
    $('.selectpicker').selectpicker();
    
        $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "lengthChange": true,
      "lengthMenu": [5, 10, 25, 50, 100],
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    $("#example2").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "lengthChange": true,
      "lengthMenu": [5, 10, 25, 50, 100],
    });
    </script>
<?php ob_end_flush(); ?>
</body>

</html>