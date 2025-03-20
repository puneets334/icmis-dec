<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?php echo base_url('images/scilogo.png'); ?>" type="image/x-icon">
    <title>Supreme Court of India</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/googlefonticon.css'); ?>">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/fontawesome-free/css/all.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/daterangepicker/daterangepicker.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/icheck-bootstrap/icheck-bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/select2/css/select2.min.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css') ?>">    <!-- Bootstrap4 Duallistbox -->
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/bootstrap4-duallistbox/bootstrap-duallistbox.min.css') ?>">
    <!-- BS Stepper -->
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/bs-stepper/css/bs-stepper.min.css') ?>">
    <!-- dropzonejs -->
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/dropzone/min/dropzone.min.css') ?>">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/datatables-bs4/css/dataTables.bootstrap4.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/datatables-responsive/css/responsive.bootstrap4.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/vendor/datatables-buttons/css/buttons.bootstrap4.min.css'); ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url('assets/libs/css/admin.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('plugins/overlayScrollbars/css/OverlayScrollbars.min.css'); ?>">

    <!-- <link rel="stylesheet" href="<?php echo base_url('assets/libs/css/style.css'); ?>"> -->
    <link rel="stylesheet" href="<?php echo base_url('assets/libs/css/mystyle.css'); ?>">
    <script src="<?php echo base_url('assets/vendor/jquery/jquery.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendor/select2/js/select2.full.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendor/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendor/moment/moment.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendor/inputmask/jquery.inputmask.min.js'); ?>"></script>
    <!-- date-range-picker -->
    <script src="<?php echo base_url('assets/vendor/daterangepicker/daterangepicker.js'); ?>"></script>
    <!-- bootstrap color picker -->
    <script src="<?php echo base_url('assets/vendor/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendor/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js'); ?>"></script>
    <!-- Bootstrap Switch -->
    <script src="<?php echo base_url('assets/vendor/bootstrap-switch/js/bootstrap-switch.min.js'); ?>"></script>
    <!-- BS-Stepper -->
    <script src="<?php echo base_url('assets/vendor/bs-stepper/js/bs-stepper.min.js'); ?>"></script>
    <!-- dropzonejs -->
    <script src="<?php echo base_url('assets/vendor/dropzone/min/dropzone.min.js'); ?>"></script>
    <script src="<?php echo base_url('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js'); ?>"></script>

    <script src="<?=base_url('js/app.min.js')?>"></script>

    <script src="<?=base_url('js/angular.min.js')?>"></script>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- Messages Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <div class="image">
                        <!--                    <img src="--><?php //echo base_url('assets/images/user.png'); ?><!--" -->
                        <!--                         class="img-circle elevation-2" alt="User Image">  -->
                        <i class="fas fa-house-user" style="font-size:20px;color:steelblue"></i>

                    </div>
                    <span class="badge badge-danger navbar-badge"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <a href="#" class="dropdown-item">
                        <h3 class="dropdown-item-title">
                            <?=!empty(session()->get('login')['type_name']) ? ucfirst(strtolower((session()->get('login')['name']))) . ' [' . session()->get('login')['type_name'] . ']' : ucfirst(strtolower((session()->get('login')['name']))) ?></h3>
                        <a href="<?php echo base_url('Signout'); ?>"><span class="dropdown-item"> <i class="fas fa-sign-out-alt " style="font-size:20px;color:red"></i></span></a>
                        </h3>
                    </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                    <i class="fas fa-th-large"></i>
                </a>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="#" class="brand-link"><img src="<?php echo base_url('images/scilogo.png'); ?>" alt="SCI Logo" class="brand-image" ><span class="brand-text font-weight-light">Supreme Court of India</span>
        </a>

        <!-- Sidebar -->
        <?=view('templates/left_side_bar_menu');?>
        <!-- /.sidebar -->
    </aside>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <?= $this->renderSection('content') ?>
    </div>
    <!-- /.content-wrapper -->
    <!-- FOOTER: DEBUG INFO + COPYRIGHTS -->
    <!-- /.content-wrapper -->
    <!--<footer class="main-footer">
        <div class="float-right d-none d-sm-block">
            <b>Version</b> 2.0
        </div>
        <strong><b>Version</b> 2.0 Copyright &copy; <?php /*= date('Y'); */?> <a href="#<?php /*= base_url()*/?>">Supreme Court of India</a>.</strong>
    </footer>-->
    <!-- Control Sidebar -->
    <!--<aside class="control-sidebar control-sidebar-dark">  </aside>-->
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
<script src="<?php echo base_url('assets/vendor/datatables/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables-bs4/js/dataTables.bootstrap4.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables-responsive/js/dataTables.responsive.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables-responsive/js/responsive.bootstrap4.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables-buttons/js/dataTables.buttons.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables-buttons/js/buttons.bootstrap4.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/jszip/jszip.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/pdfmake/pdfmake.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/pdfmake/vfs_fonts.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables-buttons/js/buttons.html5.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables-buttons/js/buttons.print.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables-buttons/js/buttons.colVis.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables-buttons/js/buttons.colVis.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendor/datatables-buttons/js/buttons.colVis.min.js'); ?>"></script>
<script src="<?php echo base_url('js/nav_link.js'); ?>"></script>
<script src="<?php echo base_url('js/customize_style.js'); ?>"></script>
<!--<script src="--><?php //echo base_url('js/adminlte.min.js'); ?><!--"></script>-->




<script>
    function updateCSRFToken() {  $.getJSON("<?php echo base_url('Csrftoken'); ?>", function(result) { $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE); }); }
    $('.select2').select2();
    $('.select2bs4').select2({ theme: 'bootstrap4' });
    //Datemask dd/mm/yyyy
    $('.datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    //Datemask2 mm/dd/yyyy
    $('.datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    //Money Euro
    $('[data-mask]').inputmask()

    //Date picker
    $('.reservationdate').datetimepicker({
        format: 'L'
    });
    $('.duallistbox').bootstrapDualListbox();
    // Get the container element
    var base_url="<?php echo base_url('/'); ?>";

</script>
</body>

</html>