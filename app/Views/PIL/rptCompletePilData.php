<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>PIL Add/Edit</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
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


    <style>
        h1 {
            text-align:center;
            color:green;
        }
        body {
            width:70%;
        }
        .container .box {
            display : flex;
            flex-direction : row;

        }
        .container .box .box-cell.box1 {
            background:green;
            color:white;
            text-align:justify;
        }
        .container .box .box-cell.box2 {
            background:lightgreen;
            text-align:justify
        }
    </style>

</head>
<body class="hold-transition skin-blue layout-top-nav">
<!--<div class="wrapper" >-->
<?php
//include('../Copying/template/top_navigation.html');
// $this->load->view('Copying/template/top_navigation.html');
?>
<!-- Full Width Column -->
<div class="content-fluid">
    <!--<div class="container">-->
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <button class="btn btn-default" onclick="printDiv('DivIdToPrint')"><i class="fa fa-print"> Print</i></button>
        </div>
        <div class="row" id="DivIdToPrint">
            <div class="col-sm-12">
                <h4>PIL Status as on <?=date('d-m-Y h:i:s A')?></h4>
            </div>
            <br/><br/><br/>
            <div class="col-sm-12">

               <?php
               if(isset($pilCompleteDetail)){
//                   echo "<pre>";
//                   print_r($pilCompleteDetail);die;
                ?>

                    <div class="form-group row">

                        <p class="col-sm-8 control-label"><b>Inward Number : </b><?= $pilCompleteDetail['diary_number']."/".$pilCompleteDetail['diary_year'] ?></p>
                        <p class="col-sm-4 control-label"><b>Petition Date : </b>
                      <?php
                      echo date('d-m-Y', strtotime(($pilCompleteDetail['petition_date'] != '' && $pilCompleteDetail['petition_date'] != '30-11-0001') ? $pilCompleteDetail['petition_date'] : ''));
                      ?></p>
                      <p class="col-sm-8 control-label"><b>Received On : </b><?php echo !empty($date_formatter_received_on) ?$date_formatter_received_on:'';?></p>
                        <p class="col-sm-4 control-label"><b>Address To : </b><?= $pilCompleteDetail['address_to']?></p>
                    </div>
                    <div class="form-group row">

                        <p class="col-sm-8 control-label"><b>Received From : </b><?= $pilCompleteDetail['received_from'];   ?></p>
                        <p class="col-sm-4 control-label"><b>Address :</b><?= $pilCompleteDetail['address']?>
                            <?php
                            if(!empty($state)) {
                                foreach ($state as $st) {
                                    if ($pilCompleteDetail['ref_state_id'] == $st['state_code']) {
                                        echo ",State : " . $st['state_name'];
                                        break;
                                    }
                                }
                            }
                            ?>

                        </p>
                    </div>
                    <div class="form-group row">
                        <p class="col-sm-8 control-label"><b>Email Id : </b><?= $pilCompleteDetail['email'] ?></p>
                        <p class="col-sm-4 control-label"><b>Mobile Number : </b><?= $pilCompleteDetail['mobile'] ?></p>
                    </div>
                    <div class="form-group row">

                        <p class="col-sm-8 control-label"><b>Nature/Subject Matter :</b>
                            <?php
                            if(!empty($pilCategory)) {
                                foreach ($pilCategory as $pilcat) {
                                    if ($pilCompleteDetail['ref_pil_category_id'] == $pilcat['id']) {
                                        echo $pilcat['pil_category'];
                                        break;
                                    }
                                }
                            }
                            ?>

                        </p>
                        <p class="col-sm-4 control-label"><b>Other : </b><?= $pilCompleteDetail['other_text']; ?></p>


                        <p class="col-sm-8 control-label"><b>Group :</b>
                            <?php
                            if(!empty($pilGroup)) {
                                foreach ($pilGroup as $pilGrp) {
                                    if ($pilCompleteDetail['group_file_number'] == $pilGrp['id']) {
                                        echo $pilGrp['group_file_number'];
                                        break;
                                    }
                                }
                            }
                            ?>

                        </p>
                        <p class="col-sm-4 control-label"><b>Summary Of Request :</b><?= $pilCompleteDetail['request_summary']?> </p>

                    </div>
                    <div class="form-group row">

                        <p class="col-sm-8 control-label"><b>Action Taken : </b>
                            <?php echo !empty($actionTakenText)?$actionTakenText:''; ?>
                            <?= $pilCompleteDetail['pil_sub_action_code']?>-<? $pilCompleteDetail['sub_action_description']?>
                        </p>
                        <p class="col-sm-4 control-label"><b>Remarks : </b><?= $pilCompleteDetail['other_text'] ?></p>

                    </div>
                    <div class="form-group row">
                        <p class="col-sm-8 control-label"><b>Report Received Date :</b><?php echo !empty($date_formatter_report_received_date)?$date_formatter_report_received_date:''; ?> </p>
                        <p class="col-sm-4 control-label"><b>Report Received :</b>
                         <?php if($pilCompleteDetail['report_received']==1 && $pilCompleteDetail['report_received']!=""){
                                echo "Yes";
                            }
                            else{
                                echo "No";
                            }
                            ?>

                        </p>

                    </div>



                <!------------- PIL Deletion ------------>

                <div class="form-group row">
                       <p class="col-sm-8 control-label"><b>Destroy/Keep in Record Date : </b>
                           <?php
                           if(!empty($destroyOrKeepInDate))
                               echo $destroyOrKeepInDate;
                           ?></p>
                    <p class="col-sm-4 control-label"><b>Destroy/Keep In Record : </b>
                        <?php
                        if(!empty($destroyOrKeepIn))
                        {
                        if($destroyOrKeepIn=='Y'){
                            echo "Destroyed";
                        }
                        else if($destroyOrKeepIn=='N'){
                            echo "Keep In Record";
                        }
                        }else{

                        }
                        ?>

                    </p>
                    <p class="col-sm-8 control-label"><b>Destroy/Keep in Record remarks : </b><?= $pilCompleteDetail['remarks']?></p>

                </div>


                <!----------------- END PIL Deletion ---------->

            <?php
            }
           ?>


        </div>
    </section>
</div>

<script >
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
    }
</script>

</body>
</html>