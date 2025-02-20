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