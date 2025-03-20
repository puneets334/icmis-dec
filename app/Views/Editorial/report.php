<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/googlefonticon.css'); ?>">
    <!-- BS Stepper -->
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


    <script src="<?php echo base_url('assets/vendor/moment/moment.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendor/inputmask/jquery.inputmask.min.js'); ?>"></script>
    <!-- date-range-picker -->

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




    <style>
        .box.box-danger {
            border-top-color: #dd4b39;
        }
        .box {
            position: relative;
            border-radius: 3px;
            background: #ffffff;
            border-top: 3px solid #d2d6de;
            margin-bottom: 20px;
            width: 100%;
            box-shadow: 0 1px 1px rgba(0,0,0,0.1);
        }
        .row {
            margin-right: 15px;
            margin-left: 15px;
        }
    </style>
</head>
 
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                              <?php

                        // echo "<pre>";
                        // print_r($list);
                        // die;


                        if($from_date == $to_date)
                        {
                            ?>
                            <h2 class="page-header" align="center"><b><u>List of Matters in which Gist has been updated on
                                        <?php  echo date('d-m-Y',strtotime($from_date));  ?></u></b></h2>
                            <?php
                        }
                        else{
                            ?>

                            <h2 class="page-header" align="center"><b><u>List of Matters in which Gist has been updated between
                                        <?php  echo date('d-m-Y',strtotime($from_date))." and ". date('d-m-Y',strtotime($to_date));?></u></b></h2>
                        <?php }
                        if (isset($list) && sizeof($list) > 0) { ?>

<!--                            <div id="disp">-->
                                <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4 query_builder_wrapper">
                                <table id="datewise_report" class="table table-bordered table-striped datatable_report">

                                    <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Diary No. <br/>Case No.</th>
                                        <th>Cause Title</th>
                                        <th>Judgment Date</th>
                                        <th>Gist</th>
                                        <th>Updated By</th>
                                        <th>IP Address</th>
                                        <th>Verification Status</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $i=0;
                                    foreach($list as $result)
                                    {$i++;
                                        ?>
                                        <tr>
                                            <td width="2%"><?php echo $i;?></td>
                                            <td width="5%"><?php echo substr($result['diary_no'],0,strlen($result['diary_no'])-4).'/'.substr($result['diary_no'], -4 ).'<br/>'.$result['reg_no_display'];?></td>
                                            <td width="3%"><?php echo $result['pet_name'].' Vs '.$result['res_name'];?></td>
                                            <td width="5%"><?php echo date('d-m-Y',strtotime($result['orderdate']));?></td>
                                            <td width="25%"><?php echo $result['summary'];?></td>
                                            <td width="5%"><?php echo $result['name'].'['.$result['empid'].']';?></td>
                                            <td width="5%"><?php echo $result['updated_by_ip'];?></td>
                                            <?php if($result['is_verified']=='t'){?>
                                                <td width="15%"><?php echo $result['ver_name'].'['.$result['ver_id'].']'."<br/>".
                                                        date('d-m-Y h:i:s A',strtotime($result['verified_on']))."<br/>".
                                                        $result['verified_by_ip'];?></td>
                                            <?php } else if($result['is_verified']=='f'){?>

                                                <td ><font color="red">NOT VERIFIED</font></td>
                                            <?php } ?>

                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php
                        }
                        else
                        {
                            ?>
                            <label class="text-danger" style="margin-left:40%; margin-top: 5%;">&nbsp;No Record Found!!</label>
                            <?php
                        }
                        ?>



                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    <script>
        // $(function () {
        //     $(".datatable_report").DataTable({
        //         "responsive": true, "lengthChange": false, "autoWidth": false,
        //         "buttons": ["copy", "csv", "excel",{extend: 'pdfHtml5',orientation: 'landscape',pageSize: 'LEGAL' },
        //             { extend: 'colvis',text: 'Show/Hide'}],"bProcessing": true,"extend": 'colvis',"text": 'Show/Hide'
        //     }).buttons().container().appendTo('.query_builder_wrapper .col-md-6:eq(0)');

        // });
        $(function () {
    $(".datatable_report").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "buttons": [
            "copy",
            "csv",
            {
                extend: 'excelHtml5',
                text: 'Excel', // Changed to XLSX
                filename: function() { // Added filename function
                    var d = new Date();
                    var n = d.toISOString().slice(0,10);
                    return 'Data_export_' + n;
                }
            },
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                text: 'PDF' // Explicitly setting the text here
            },
            {
                extend: 'print',
                text: 'Print' // Explicitly setting the text here
            },
            {
                extend: 'colvis',
                text: 'Show/Hide'
            }
        ],
        "bProcessing": true,
        "extend": 'colvis', // this line is redundant, it is already in the buttons array.
        "text": 'Show/Hide' // this line is redundant, it is already in the buttons array.
    }).buttons().container().appendTo('.query_builder_wrapper .col-md-6:eq(0)');
});
    </script>

    <script>
        function edit_gist(diaryno, orderdate) {
            $.post("<?= base_url(); ?>index.php/ESCR/edit_gist", {
                diary_no: diaryno,
                judgment_date: orderdate
            }, function(result) {
                //alert(result);
                //location.reload();
            });
        }


        function verify_gist(id, diaryno, orderdate) {
            diary_no = diaryno.slice(0, -4) + "/" + diaryno.slice(-4);
            var a = confirm("Are you sure that you want to verify gist for diary number " + diary_no + " judgment dated " + orderdate + "?");
            if (a == true) {
                $.post("<?= base_url(); ?>index.php/ESCR/verify_gist", {
                    id: id
                }, function(result) {
                    alert(result);
                    location.reload();
                });
            }

        }

        function delete_gist(id, diaryno, orderdate) {
            diary_no = diaryno.slice(0, -4) + "/" + diaryno.slice(-4);
            var a = confirm("Are you sure that you want to delete gist for diary number " + diary_no + " judgment dated " + orderdate + "?");
            if (a == true) {
                $.post("<?= base_url(); ?>index.php/ESCR/delete_gist", {
                    id: id
                }, function(result) {
                    alert(result);
                    location.reload();
                });
            }
        }

    </script>

 <?=view('sci_main_footer') ?>