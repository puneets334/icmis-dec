<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?php echo base_url('images/scilogo.png'); ?>" type="image/x-icon">
    <title>Supreme Court of India</title>
    <!-- Google Font: Source Sans Pro -->
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
    <script src="<?=base_url('js/app.min.js')?>"></script>
    <script src="<?=base_url('js/angular.min.js')?>"></script>
</head>

<body class="hold-transition sidebar-mini">
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
        <div class="sidebar">
            <!-- Sidebar Menu -->
            <nav class="mt-2" id="nav_menu">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <?php $uri = current_url(true); ?>
                    <li class="nav-item menu-open">
                        <a href="#" class="nav-link">
                            <i class="nav-icon  fas fa-fill"></i>
                            <p>
                                Filing
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="<?= base_url('Caveat/Generation'); ?>"
                                   class="nav-link <?=($uri->getSegment(1)=='Caveat')?'active':'';?>">
                                    <i class="nav-icon fas fa-address-book"></i>
                                    <p>Caveat Generation</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?= base_url('Filing/Diary/search'); ?>"
                                   class="nav-link <?=($uri->getSegment(3)=='search')?'active':'';?>">
                                    <i class="nav-icon fas fa-address-book"></i>
                                    <p>Diary Search</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?= base_url('Filing/Diary'); ?>" class="nav-link <?=($uri->getSegment(2)=='Diary') && ($uri->getSegment(3) !='search')?'active':'';?>">
                                    <i class="far fa-address-book nav-icon"></i>
                                    <p>Diary Entry</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('Filing/Master/District_master'); ?>" class="nav-link  <?=($uri->getSegment(2)=='Master')?'active':'';?>">
                                    <i class="fas fa-fill nav-icon"></i>
                                    <p>District Masters</p>
                                    </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('Reports/SCLSC/Report'); ?>" class="nav-link <?=($uri->getSegment(2)=='SCLSC')?'active':'';?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>SCLSC Report</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('Filing/Sensitive_info'); ?>" class="nav-link <?=($uri->getSegment(2)=='Sensitive_info')?'active':'';?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Sensitive Case</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('Filing/Statistical_report/data_generation'); ?>" target="_blank" class="nav-link <?=($uri->getSegment(3)=='data_generation')?'active':'';?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Statistical Report</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('Filing/Statistical_report/send_mail'); ?>" target="_blank" class="nav-link <?=($uri->getSegment(3)=='send_mail')?'active':'';?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Statistical Report Send Mail</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('Filing/Efiling/check_documents'); ?>" class="nav-link <?=($uri->getSegment(2)=='Efiling')?'active':'';?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>E-Filing Admin</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item menu-open">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-circle"></i>
                            <p>
                                PIL(E)
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url('PIL/PilController/addToPilGroupShow'); ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>PIL Entry</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('PIL/PilController/queryPilData'); ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Reports</p>
                                </a>
                            </li>


                        </ul>
                    </li>
                    <li class="nav-item menu-open">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-circle"></i>
                            <p>
                               R & I
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url('RI/DispatchController/showCreateLetterGroup'); ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Dispatch</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('PIL/PilController/queryPilData'); ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Dispatch Reports</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('PIL/PilController/queryPilData'); ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>E-copy</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('PIL/PilController/queryPilData'); ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Notices</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('RI/ReceiptController/index'); ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Receipt</p>
                                </a>
                            </li>


                        </ul>
                    </li>

                    <li class="nav-item menu-open">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-chart-pie"></i>
                            <p>
                                Query Builder Reports
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="<?= base_url('Reports/Filing/Report'); ?>"
                                   class="nav-link <?=($uri->getSegment(2)=='Filing' && $uri->getSegment(1)=='Reports')?'active':'';?>">
                                    <i class="nav-icon  fas fa-fill"></i>
                                    <p>Filing Reports</p>
                                </a>

                            </li>

                            <li class="nav-item">
                                <a href="<?= base_url('Reports/Court/Report'); ?>"
                                   class="nav-link <?=($uri->getSegment(2)=='Court')?'active':'';?>">
                                    <i class="fas fa-solid fa-gavel nav-icon"></i>
                                    <p>Court Reports</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('Reports/Judicial/Report'); ?>"
                                   class="nav-link <?=($uri->getSegment(2)=='Judicial')?'active':'';?>">
                                    <i class="fas fa-solid fa-gavel nav-icon"></i>

                                    <p>Judicial Reports</p>
                                </a>
                            </li>


                            <li class="nav-item">
                                <a href="<?= base_url('Reports/Copying/Report'); ?>"
                                   class="nav-link <?=($uri->getSegment(2)=='Copying' && $uri->getSegment(1)=='Reports')?'active':'';?>">
                                    <i class="fas fa-copyright"></i>
                                    <p>Copying Reports</p>

                                </a>
                            </li>



                        </ul>
                    </li>

                    <li class="nav-item menu-open">
                        <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-circle"></i>
                            <p>
                                Editorial
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url('Editorial/ESCR/show_count'); ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Report</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('Editorial/ESCR/index'); ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Updated Note</p>
                                </a>
                            </li>
                            <li class="nav-item">
                            <a href="<?= base_url('WebCasting/Home'); ?>" class="nav-link">
                                <i class="nav-icon fas fa-circle"></i>
                                <p> Web Casting </p>
                            </a>
                            </li>


                        </ul>
                    </li>



                    <li class="nav-item menu-open">
                        <a href="#" class="nav-link">
                            <i class="nav-icon  fas fa-fill"></i>
                            <p>
                                Copying
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="<?= base_url('Copying/Copying/orders'); ?>"
                                   class="nav-link <?=($uri->getSegment(3)=='orders')?'active':'';?>">
                                    <i class="nav-icon fas fa-address-book"></i>
                                    <p>Download Orders</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?= base_url('copying/Copying/application_search'); ?>"
                                   class="nav-link <?=($uri->getSegment(3)=='application_search')?'active':'';?>">
                                    <i class="nav-icon fas fa-address-book"></i>
                                    <p>E-Copying</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?= base_url('Copying/Copying/reason_rejection_add'); ?>"
                                   class="nav-link <?=($uri->getSegment(3)=='reason_rejection_add')?'active':'';?>">
                                    <i class="far fa-address-book nav-icon"></i>
                                    <p>Master</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?= base_url('copying/Copying/application'); ?>"
                                   class="nav-link  <?=($uri->getSegment(3)=='application')?'active':'';?>">
                                    <i class="far fa-address-book nav-icon"></i>
                                    <p>Registration</p>
                                </a>
                            </li>

                        </ul>
                    </li>

                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <?= $this->renderSection('content') ?>
    </div>
    <!-- /.content-wrapper -->
    <!-- FOOTER: DEBUG INFO + COPYRIGHTS -->
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <div class="float-right d-none d-sm-block">
            <b>Version</b> 2.0
        </div>
        <strong><b>Version</b> 2.0 Copyright &copy; <?= date('Y'); ?> <a href="#<?/*= base_url() */?>">Supreme Court of India</a>.</strong>
    </footer>
    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">  </aside>
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

</script>
</body>

</html>