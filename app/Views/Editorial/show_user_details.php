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
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
<!--                    <div class="card-header heading">-->
<!---->
<!--                        <div class="row">-->
<!--                            <div class="col-sm-10">-->
<!--                                <h3 class="card-title">Editorial >> Update Gist</h3>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
                    <div class="row">
                        <div class="col-md-12">

                            <div class="card-body">
                                <span class="alert-danger"><?= \Config\Services::validation()->listErrors() ?></span>

                                <?php if (session()->getFlashdata('message_error')) { ?>
                                    <div class="alert alert-danger">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        <strong> <?= session()->getFlashdata('message_error') ?></strong>
                                    </div>

                                <?php } ?>
                                <?php if (session()->getFlashdata('success_msg')) : ?>
                                    <div class="alert alert-success alert-dismissible">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        <strong> <?= session()->getFlashdata('success_msg') ?></strong>
                                    </div>
                                <?php endif; ?>


                    <?php

                    if (isset($uploaded_details) && !empty($uploaded_details)) {
                        foreach ($uploaded_details as $result) {
                            $name = $result['name'];
                            $empid = $result['empid'];
                            $desig = $result['type_name'];
                        }
                        if ($from_date != '' && $from_date != '1970-01-01' && $to_date != '' && $to_date != '1970-01-01')
                        {
                            $heading = "Matters in which Gist has been updated by " . $name . " [" . $empid . "] between " . date('d-m-Y', strtotime($from_date)) . " and " . date('d-m-Y', strtotime($to_date));
                        }
                        else{
                            $heading = "Matters in which Gist has been updated by " . $name . " [" . $empid . "] and are pending for verification";
                        }

                        ?>

<!--                    <div><button type="button" id="print" name="print" style="width:10%" onclick="printDiv('printable')" class="btn btn-block btn-warning">Print</button></div>-->
                        <?php
                        $attribute = array('class' => 'form-horizontal', 'method'=>'POST', 'name' => 'report_user_wise_verify_edit_delete', 'id' => 'report_user_wise_verify_edit_delete', 'autocomplete' => 'off');
                        echo form_open(base_url('#'), $attribute);
                        ?>
                        <!-- <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4 query_builder_wrapper"> -->
                        <br> <div style="text-align:center; font-weight:bold; font-size:24px;">  <?php echo $heading; ?> </div><br>
                        <div id="query_builder_wrapper" class="dataTables_wrapper dt-bootstrap4 query_builder_wrapper">
                        <table id="datewise_report" class="table table-bordered table-striped datatable_report">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Diary No.<br />Case No.</th>
                                    <th>Cause Title</th>
                                    <th>Judgment Date</th>
                                    <th>Gist</th>
                                    <th>Updated On</th>
                                    <?php
                                    if ($from_date != '' && $from_date != '1970-01-01' && $to_date != '' && $to_date != '1970-01-01')
                                    {
                                        ?>
                                        <th>Verification Status</th>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if (!empty($userrole) && $userrole == 2)
                                    {
                                        ?>
                                        <th>Action</th>
                                        <?php
                                    }
                                    ?>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $i = 0;
                                foreach ($uploaded_details as $result) {
                                    $i++;
                                    ?>
                                    <tr>
                                        <td width="2%"><?php echo $i; ?></td>
                                        <td width="5%"><?php echo substr($result['diary_no'], 0, strlen($result['diary_no']) - 4) . '/' . substr($result['diary_no'], -4); ?>
                                            <br /><?php echo $result['reg_no_display']; ?>
                                        </td>
                                        <td width="7%"><?php echo $result['pet_name'] . ' Vs ' . $result['res_name']; ?></td>
                                        <td width="5%"><?php echo date('d-m-Y', strtotime($result['orderdate'])); ?></td>
                                        <td width="50%"><?php echo $result['summary']; ?></td>
                                        <td width="5%"><?php echo date('d-m-Y h:i:s A', strtotime($result['updated_on'])) ?></td>
                                        <?php if ($from_date != '' && $from_date != '1970-01-01' && $to_date != '' && $to_date != '1970-01-01')
                                        {
                                            if ($result['is_verified'] == 't') { ?>
                                                <td width="10%">
                                                    <font color="green">VERIFIED</font><br /><?php echo $result['verified_name'] . ' [' . $result['verified_empid'] . '] On ' . date('d-m-Y h:i:s A', strtotime($result['verified_on'])); ?>
                                                </td>
                                            <?php } else if ($result['is_verified'] == 'f') { ?>
                                                <td>
                                                    <font color="red">NOT VERIFIED</font>
                                                </td>
                                                <?php
                                            }
                                        }
                                        if ($userrole == 2 && $result['is_verified'] == 'f')
                                        { 
                                            ?>
                                            <td width="10%"><a style="color:green;" onclick="verifyGist('<?php echo $result['id']; ?>','<?php echo $result['diary_no']; ?>','<?php echo date('d-m-Y', strtotime($result['orderdate'])); ?>')" id="verify">Verify</a>&nbsp;&nbsp;
                                                |&nbsp;&nbsp;<a style="color:blue;" href="<?php echo base_url() ?>/Editorial/ESCR/edit_gist?diary_no=<?php echo $result['diary_no']; ?>&judgment_date=<?php echo $result['orderdate']; ?>"  id="edit"> Edit</a> &nbsp;&nbsp;|&nbsp;&nbsp;
                                                <a style="color:red;" onclick="deleteGist('<?php echo $result['id']; ?>','<?php echo $result['diary_no']; ?>','<?php echo date('d-m-Y', strtotime($result['orderdate'])); ?>')"  id="delete"> Delete</a>
                                            </td>
                                        <?php } else{
                                            ?>
                                        <td></td>
                                        <?php
                                        }?>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <?php form_close(); ?>
                    <?php }
                    else {
                        echo "<center><span style='color:red;font-size:24px;'>Cases is not available for verification</span></center>";
                     }
                    ?>





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

    function printDiv(printable) {
        // alert(printable);return false;
        var printContents = document.getElementById('printable').innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }

    function updateCSRFToken() {
        $.getJSON("<?php echo base_url('Csrftoken'); ?>", function (result) {
            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
        });
    }

    //var validationError = false;
    function editGist(diaryno, orderdate){
    
       var dno = diaryno;
       var ordt = orderdate;
    
           var CSRF_TOKEN = 'CSRF_TOKEN';
           var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
           $.ajax({
               type: "POST",
               url: "<?php echo base_url('Editorial/ESCR/edit_gist'); ?>//",
               data: {
                   CSRF_TOKEN: CSRF_TOKEN_VALUE,
                   'diary_no': dno,
                   'judgment_date': ordt
    
               },
               success: function (data) {
                   updateCSRFToken();
                   // console.log(data);return false;
                   $("#page_id").html(data);
                   //if(data)
                   //{
                   //    window.location.href="<?////= base_url('Editorial/eSCREntry')?>////";
                   //}
    
               },
               error: function (data) {
                   updateCSRFToken();
                   alert(data);
    
               }
    
           });
           return false;
       
    }


    function verifyGist(id, diaryno, orderdate) {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        diary_no = diaryno.slice(0, -4) + "/" + diaryno.slice(-4);
        var a = confirm("Are you sure that you want to verify gist for diary number " + diary_no + " judgment dated " + orderdate + "?");
        if (a == true) {
            // alert("RWERWE");return false;
            $.ajax({
                type:"POST",
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    id:id
                },
                url: "<?php echo base_url('Editorial/ESCR/verify_gist'); ?>",
                success: function(data) {
                    //    alert(data);
                    updateCSRFToken();
                    if(data)
                    {
                        alert(data);
                        window.location.reload();
                    }else{
                        alert("There is some problem please contact computer cell");
                    }

                },
                error: function(data) {
                    alert(data);
                    updateCSRFToken();
                }
            });

        }

    }

    function deleteGist(id, diaryno, orderdate) {
        diary_no = diaryno.slice(0, -4) + "/" + diaryno.slice(-4);
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var a = confirm("Are you sure that you want to delete gist for diary number " + diary_no + " judgment dated " + orderdate + "?");
        if (a == true) {

            $.ajax({
                type:"POST",
                data: {
                    CSRF_TOKEN: CSRF_TOKEN_VALUE,
                    'id':id
                },
                url: "<?php echo base_url('Editorial/ESCR/delete_gist'); ?>",
                success: function(data) {
                    updateCSRFToken();
                    //    alert(data);
                    if(data)
                    {
                        alert(data);
                        window.location.reload();
                    }

                },
                error: function(data) {
                    alert(data);
                    updateCSRFToken();
                }
            });
        }

    }

    



</script>

<?=view('sci_main_footer') ?>
