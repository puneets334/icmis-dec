<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><h2 align="center">PIL Updation on <?=date('d-m-Y',strtotime($dated))?></h2></title>
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

    <script src="<?php echo base_url('assets/vendor/datatables-buttons/js/buttons.html5.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendor/datatables-buttons/js/buttons.print.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendor/datatables-buttons/js/buttons.colVis.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendor/datatables-buttons/js/buttons.colVis.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/vendor/datatables-buttons/js/buttons.colVis.min.js'); ?>"></script>
    <script src="<?php echo base_url('js/nav_link.js'); ?>"></script>
    <script src="<?php echo base_url('js/customize_style.js'); ?>"></script>
    <!--<script src="--><?php //echo base_url('js/adminlte.min.js'); ?><!--"></script>-->



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
<body >

<div class="content-fluid">
    <section class="content">
        <div id="printable" class="box box-danger">

            <h2 align="center">PIL Updation on <?=date('d-m-Y',strtotime($dated))?></h2>
            <?php
            if(isset($pil_result) && sizeof($pil_result)>0 ) {
                ?>

                <table id="reportTable1" class="table table-striped table-hover">
                    <!--    <table id="example1" class="table table-striped table-bordered">-->
                    <thead>
                    <tr>
                        <th width="4%">S.No.</th>
                        <th width="7%">Inward Number</th>
                        <th width="15%">Address To</th>
                        <th width="25%">Received From</th>
                        <th width="7%">Received On</th>
                        <th width="6%">Petition Date</th>
                        <th width="20%">Status</th>
                        <th width="16%">Updated By</th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 0;
                    foreach ($pil_result as $result) {
                        $i++;
                        ?>
                        <tr>
                            <td><?=$i?></td>
                            <td><?=$result['pil_diary_number'];?></td>
                            <td><?=$result['address_to'];?></td>
                            <td><?=$result['received_from'];?><br/><?=$result['address'];?>
                                <?php
                                if(!empty($result['state_name'])){
                                    echo " ,State: ".$result['state_name'];
                                }
                                if(!empty($result['email'])){
                                    echo "<br/> Email: ".$result['email'];
                                }
                                if(!empty($result['mobile'])){
                                    echo "<br/> Mobile: ".$result['mobile'];
                                }
                                ?>
                            </td>
                            <td><?=!empty($result['received_on'])?date("d-m-Y", strtotime($result['received_on'])):null?></td>
                            <td><?=!empty($result['petition_date'])?date("d-m-Y", strtotime($result['petition_date'])):null?></td>
                            <td><?php
                                if(!empty($result['action_taken']))
                                {
                                    switch (trim($result['action_taken'])){
                                        case "L":{
                                            $actionTakenText = "No Action Required"; break;
                                        }
                                        case "W":{
                                            $actionTakenText = "Written Letter to ".$result['written_to']. " on ".date('d-m-Y', strtotime($result['written_on'])) ; break;
                                        }
                                        case "R":{
                                            $actionTakenText = "Letter Returned to Sender on ".date('d-m-Y', strtotime($result['return_date'])) ; break;
                                        }
                                        case "S":{
                                            $actionTakenText = "Letter Sent To ".$result['sent_to']. " on ".date('d-m-Y', strtotime($result['sent_on'])); break;
                                        }
                                        case "T":{
                                            $actionTakenText = "Letter Transferred To ".$result['transfered_to']." on ".date('d-m-Y', strtotime($result['transfered_on'])); break;
                                        }
                                        case "I":{
                                            $actionTakenText = "Letter Converted To Writ"; break;
                                        }
                                        case "O":{
                                            $actionTakenText = "Other Remedy"; break;
                                        }
                                        default:{
                                            $actionTakenText = "UNDER PROCESS"; break;
                                        }
                                    }
                                    echo $actionTakenText;
                                }else{
                                    $actionTakenText = "UNDER PROCESS";
                                    echo $actionTakenText;
                                }
                                ?>
                            </td>
                            <td><?=$result['username'].'('.$result['empid'].')'?>
                                <br/> At: <?=date('d-m-Y h:i:s A', strtotime($result['updated_on']))?></td>
                        </tr>

                        <?php
                    }?>
                    </tbody>


                </table>

                <?php
            }

            ?>
        </div>

    </section>
    <!-- /.content -->
    <!--</div>-->
    <!-- /.container -->
</div>


<script>

    $(function() {
        //     $("#example1").DataTable({
        //         "responsive": true,
        //         "lengthChange": false,
        //         "autoWidth": false,
        //         "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        //     }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        //
        // });

        $(document).ready(function() {
            $('#reportTable1').DataTable( {
                dom: 'Bfrtip',
                "pageLength":15,
                buttons: [
                    'print','pageLength'
                ],
                lengthMenu: [
                    [ 10, 25, 50, -1 ],
                    [ '10 rows', '25 rows', '50 rows', 'Show all' ]
                ]
            } );
        } );

        $(document).ready(function() {
            // Setup - add a text input to each footer cell
            $('#reportTable1 thead tr').clone(true).appendTo( '#reportTable1 thead' );
            $('#reportTable1 thead tr:eq(1) th').each( function (i) {
                var title = $(this).text();
                var width = $(this).width();
                if(width>260){
                    width=width-80;
                }
                else if(width<100){
                    width=width+20;
                }
                $(this).html( '<input type="text" style="width: '+width+'px" placeholder="'+title+'" />' );

                $( 'input', this ).on( 'keyup change', function () {
                    if ( table.column(i).search() !== this.value ) {
                        table
                            .column(i)
                            .search( this.value )
                            .draw();
                    }
                } );
            } );

        });
</script>
</body>
</html>