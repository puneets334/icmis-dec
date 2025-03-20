 
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>
        
    </title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="<?=base_url()?>/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?=base_url()?>/assets/css/AdminLTE.min.css">
    <link rel="stylesheet" href="<?=base_url()?>/assets/css/Reports.css">
    <link rel="stylesheet" href="<?=base_url()?>/assets/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="<?=base_url()?>/assets/plugins/datepicker/datepicker3.css">
    <link rel="stylesheet" href="<?=base_url()?>/assets/plugins/datatables/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?=base_url()?>/assets/plugins/datatables/buttons.dataTables.min.css">
    <link rel="stylesheet" href="<?=base_url()?>/assets/plugins/select2/select2.min.css">
    <style>
        #reportTable1_wrapper{
            margin-top: 20px;

        }
    </style>

</head>
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper" >
    <div class="content-wrapper">
        <div class="container-fluid">
            <!-- Main content -->
            <section class="content">
                <div class="card">
                <div class="card-header">
                    <div class="box box-info">
                        <form  method="POST" action="<?=base_url();?>/MasterManagement/AORCase" class="form-horizontal" id="push-form">
                        <?= csrf_field() ?>
                            <div class="box-body">
                                <div class="form-group" >
                                    <div class="col-sm-12">
                                        <div class="col-sm-3">
                                            <label for="category" >Enter AOR Code:</label>
                                            <input type="text" id="aorCode" name="aorCode" class="form-control"   required="required">
                                        </div>
                                        <div  class="col-sm-3">
                                            <button type="submit" style="width:25%;float:left;margin-top: 25px;" id="view" name="view" class="form-control btn btn-block btn-primary">View</button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
            <?php
            if(isset($sub_reports)){
            ?>

                
                <div class="row">
                    <div class="col-lg-4 col-xs-6">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3><?=$sub_reports[0]['unreg']?></h3>
                                <p>Un-Registered</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-bag"></i>
                            </div>
                            <form method="post" action="<?=base_url();?>/MasterManagement/AORCase">
                              <?= csrf_field() ?>
                                <input type="hidden" name="aorCode" value="<?=$_POST['aorCode']?>">
                                <input type="hidden" name="type" value="1">
                                <button type="submit" class="small-box-footer text-success">More info</button>
                            </form>
                        </div>
                    </div>

                    <div class="col-lg-4 col-xs-6">
                        <div class="small-box bg-green">
                            <div class="inner">
                                <h3><?=$sub_reports[0]['reg']?><sup style="font-size: 20px"></sup></h3>
                                <p>Registered</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                            <form method="post" action="<?=base_url();?>/MasterManagement/AORCase">
                               <?= csrf_field() ?>
                                <input type="hidden" name="aorCode" value="<?=$_POST['aorCode']?>">
                                <input type="hidden" name="type" value="2">
                                <button type="submit" class="small-box-footer btn btn-primary">More info</button>
                            </form>
                        </div>
                    </div>

                    <div class="col-lg-4 col-xs-6">
                        <div class="small-box bg-yellow">
                            <div class="inner">
                                <h3><?=$sub_reports[0]['disposed_cases']?></h3>
                                <p>Disposed</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add"></i>
                            </div>
                            <form method="post" action="<?=base_url();?>/MasterManagement/AORCase">
                              <?= csrf_field() ?>
                                <input type="hidden" name="aorCode" value="<?=$_POST['aorCode']?>">
                                <input type="hidden" name="type" value="3">
                                <button type="submit" class="small-box-footer text-success">More info</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>

            <!-- Report Div Start -->
            <div class="wrapper" >
                <div class="content-wrapper">
                    <div class="container-fluid">
                        <section class="content">
                            <?php
                            if(isset($reports))
                            {
                                ?>
                                <div id="printable" class="table-responsive">
                                    <?php
                                    if($app_name=='AOR CASES')
                                    {
                                        ?>
                                        <div class="table-responsive">
                                        <table id="reportTable1" class="table table-striped table-hover table-bordered">
                                            <thead>
                                            <tr>
                                                <th>S.No.</th>
                                                <th>Diary No</th>
                                                <th>CauseTitle</th>
                                                <th>Registration Number</th>
                                                <th>Status</th>
                                                <th>Filing Date</th>
                                                <th>Registration Date</th>
                                                <th>Disposal Date</th>
                                                <th>Main <br>or <br>Connected</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $s_no=1;
                                            if(is_array($reports) && !empty($reports)){
                                            foreach ($reports as $result)
                                            {
                                                ?>
                                                <tr>
                                                    <td><?=$s_no;?></td>
                                                    <td><?=substr($result['diary_no'], 0, (strlen($result['diary_no'])-4)).'/'.substr($result['diary_no'], (strlen($result['diary_no'])-4));?></td>
                                                    <td><?=$result['pet_name'].' <i>Vs</i>. '.$result['res_name'];?></td>
                                                    <td><?=$result['reg_no_display'];?></td>
                                                    <td><?=$result['c_status'];?></td>
                                                    <td><?php echo date('d-m-Y',strtotime($result['filing_date']));?></td>
                                                    <td> <?php if(!empty($result['reg_date'])&& $result['reg_date'] !='0') { ?><?php echo date('d-m-Y',strtotime($result['reg_date']));?> <?php } ?></td>
                                                    <td><?php if(is_null($result['disposal_dt']) || $result['disposal_dt']=='' || $result['disposal_dt']=='0') echo ''; else echo date('d-m-Y', strtotime($result['disposal_dt']));?></td>
                                                    <td><?=$result['mainorconn'];?></td>
                                                </tr>
                                                <?php
                                                $s_no++;
                                            }   //for each
                                        }
                                            ?>
                                            </tbody>
                                        </table>
                                        </div>
                                    <?php
                                    }?>

                                </div>
                            <?PHP
                            }
                            ?>
                        </section>
                        <!-- Report Div End -->
                    </div>
                </div>
            </div>
            </div>
           </section>
            <script src="//code.jquery.com/jquery-1.12.4.js"></script>
            <script src="<?=base_url()?>/assets/plugins/datatables/pdfmake.min.js"></script>
            <script src="<?=base_url()?>/assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>
            <script src="<?=base_url()?>/assets/js/bootstrap.min.js"></script>
            <script src="<?=base_url()?>/assets/plugins/fastclick/fastclick.js"></script>
            <script src="<?=base_url()?>/assets/plugins/select2/select2.full.min.js"></script>
            <script src="<?=base_url()?>/assets/js/app.min.js"></script>
            <script src="<?=base_url()?>/assets/js/Reports.js"></script>
            <script src="<?=base_url()?>/assets/jsAlert/dist/sweetalert.min.js"></script>
            <script src="<?=base_url()?>/assets/plugins/datepicker/bootstrap-datepicker.js"></script>
            <script src="<?=base_url()?>/assets/plugins/datatables/jquery.dataTables.min.js"></script>
            <script src="<?=base_url()?>/assets/plugins/datatables/dataTables.buttons.min.js"></script>
            <script src="<?=base_url()?>/assets/plugins/datatables/buttons.print.min.js"></script>
            <script src="<?=base_url()?>/assets/plugins/datatables/pdfmake.min.js"></script>
            <script src="<?=base_url()?>/assets/plugins/datatables/buttons.html5.min.js"></script>
            <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/1.4.2/js/dataTables.buttons.min.js"></script>
            
            <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
            <script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
            <script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
            <script src="//cdn.datatables.net/buttons/1.4.2/js/buttons.html5.min.js"></script>

            <script>




                $(document).ready(function() {

                    $(function () {
                        $('.datepick').datepicker({
                            format: 'dd-mm-yyyy',
                            autoclose:true
                        });
                    });

                    $('#reportTable1').DataTable( {
                       /* dom: 'Bfrtip',
                        buttons: [
                            'excelHtml5',
                            'pdfHtml5'
                        ]*/

                        "bProcessing"   :   true,
                        dom: 'Bfrtip',
                        buttons: [
                            'excelHtml5',
                            {
                                extend: 'pdfHtml5',
                                pageSize: 'A3',
                                customize: function ( doc ) {
                                    doc.content.splice( 0, 0, {
                                        margin: [ 0, 0, 0, 5 ],
                                        alignment: 'center',
                                    });
                                    doc.watermark = {text: 'SUPREME COURT OF INDIA', color: 'blue', opacity: 0.05}
                                }
                            }

                        ]

                    });
                });


                function report(adv_code, type){
                    $.ajax({
                        type: 'POST',
                        url: window.location.href,
                        data: {
                            aorCode: adv_code,
                            type: type
                        },
                        complete: function () {
                            window.location.reload(true);
                        }
                    });
                }

            </script>


</body>
</html>
