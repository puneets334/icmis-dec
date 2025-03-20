<!DOCTYPE html>


<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> User wise Defect detail Report</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="<?=base_url()?>assets/css/bootstrap.min.css">
        <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">-->
    <link rel="stylesheet" href="<?=base_url()?>assets/css/AdminLTE.min.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/css/Reports.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/plugins/datepicker/datepicker3.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/plugins/datatables/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/plugins/datatables/buttons.dataTables.min.css">
    <style>
        @media print{
            @page {size:landscape;}
            html, body {
                height: 99%;
                width:98% !important;
            }
            table,h1,h2,body {position:relative;left:0.5cm;width:98% !important;}
            table,thead,tr,th {word-wrap: break-word;}

        }
    </style>
</head>
<body class="hold-transition skin-blue layout-top-nav">
<!-- Report Div Start -->

<div class="wrapper" >
    <div class="content-wrapper">
        <div class="container">
            <!-- Main content -->


            <div class="wrapper" >
                <div class="content-wrapper">

                    <div class="container">

                        <!-- Main content -->
                        <section class="content">

                            <?php
                            if(isset($case_result) && sizeof($case_result)>0 && is_array($case_result))  {
                            ?>
                            <div class="box-footer">
                                <form>
                                    <button type="submit"  style="width:15%;float:left" id="print" name="print"  onclick="printDiv('printable')" class="btn btn-block btn-warning">Print</button>
                                </form>
                            </div>
                            <div id="printable" class="box box-danger">

                                <table width="100%" id="reportTable" class="table table-striped table-hover" align="center">
                                    <thead>
                                    <?php $name1=str_replace('_',' ',$name);?>
                                    <h3 style="text-align: center;"> Defect entered by <?php echo $name1;?>  on  <?php echo date('d-m-Y', strtotime($on_date));?></h3>
                                    <tr>
                                        <th rowspan='2'>SNo.</th>
                                        <th rowspan='2'>Diary No.</th>
                                        <th rowspan='2'>Case Type</th>
                                        <th rowspan='2'>Cause Title</th>
                                        <th rowspan='2'>Filing Date</th>
                                        <th rowspan='2'>No. of Defects</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $i=0;
                                    $total_diary=0;
                                    foreach ($case_result as $result)
                                    {$i++;
                                        ?>
                                        <tr>
                                            <td><?php echo $i;?></td>
                                            <td><?php echo $result['diaryno'];?></td>
                                            <td><?php echo $result['casetype'];?></td>
                                            <td><?php echo $result['causetitle'];?></td>
                                            <td><?php echo date('d-m-Y', strtotime($result['filingdate']));?></td>
                                            <td><?php echo $result['no_of_defect'];?></td>

                                        </tr>
                                        <?php
                                        $total_diary+=$result['Total'];
                                    }
                                    ?>

                                    </tbody>
                                    <tfoot></tfoot>
                                </table>

                                <?php } ?>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
            <!-- Report Div End -->

            <script src="<?=base_url()?>assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
            <script src="<?=base_url()?>assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>
            <script src="<?=base_url()?>assets/js/bootstrap.min.js"></script>
            <script src="<?=base_url()?>assets/plugins/fastclick/fastclick.js"></script>
            <script src="<?=base_url()?>assets/js/app.min.js"></script>
            <script src="<?=base_url()?>assets/js/Reports.js"></script>
            <script src="<?=base_url()?>assets/jsAlert/dist/sweetalert.min.js"></script>
            <script src="<?=base_url()?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
            <script src="<?=base_url()?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
            <script src="<?=base_url()?>assets/plugins/datatables/dataTables.buttons.min.js"></script>
            <script src="<?=base_url()?>assets/plugins/datatables/buttons.print.min.js"></script>
            <script>
                $(function () {
                    $('.datepick').datepicker({
                        format: 'dd-mm-yyyy',
                        autoclose:true
                    });
                });



            </script>


</body>
</html>
