<?= view('header') ?>

<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/menu_assign.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/style.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/all.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/fontawesome-free/css/all.min.css'); ?>">
<style> 
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">

                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-title">Master Management >> AOR Cases</h3>
                            </div>
                            <div class="col-sm-2"> </div>
                        </div>
                    </div>
                    <br /><br />
                    <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
                    <!--start menu programming-->
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12"> <!-- Right Part -->
                                <div class="form-div_">
                                    <div class="d-block text-center">

                                     <!-- Main content -->

                                    <form method="POST" action="<?=base_url();?>/MasterManagement/ReportsChamber/aor_detail">
                                            <?= csrf_field() ?>
                                            <div class="form-row">
                                                <div class="form-group col-md-3">
                                                <label for="category" ><h5>Enter AOR Code:</h5></label>
                                                <input type="text" id="aorCode" name="aorCode" class="form-control" value="<?php echo (isset($_POST['aorCode']) ? $_POST['aorCode'] : ''); ?>"  required="required">
                                            </div>

                                            <div class="form-group col-md-3">
                                                <label for="from_date" ><h5>Filing Date From:</h5></label>
                                                <input type="text" id="fromDate" name="fromDate" class="form-control datepick"  placeholder="From Date" value="<?php echo (isset($_POST['fromDate']) ? $_POST['fromDate'] : ''); ?>">
                                                </div>

                                            <div class="form-group col-md-3">
                                            <label for="to_date"><h5>Filing Date To:</h5></label>
                                            <input type="text" class="form-control datepick" id="toDate"  name="toDate" placeholder="To Date" value="<?php echo (isset($_POST['toDate']) ? $_POST['toDate'] : ''); ?>">
                                                </div>

                                        <div class="form-group col-md-1 text-end" style="margin-top: auto;">
                                        <button type="submit" id="view" name="view" onclick="check();" class="btn btn-primary custom-button">View</button>

                                            </div>

                                        </div>
                                    </form>                                               
                                    <!-- Report Div Start -->
                                        <?php
                                        if(is_array($reports))
                                        {
                                            ?>
                                            <div id="printable" class="table-responsive">
                                                <caption><h3 style="text-align: center;"><strong><?=$reports[0]['advName'];?>, AOR Code:<?=$reports[0]['aor_code'];?><br/></strong> List of Cases Filed between &nbsp;<strong><?=date('d-m-Y',strtotime($param[1]));?></strong>  to  <strong><?=date('d-m-Y',strtotime($param[2]));?></strong> </h3></caption>

                                                <?php
                                                if($app_name=='AOR CASES')
                                                {
                                                    ?>
                                                    <table id="reportTable1" class="table table-striped table-hover">
                                                        <thead>
                                                        <tr>
                                                            <th>S.No.</th>
                                                            <th>Diary No</th>
                                                            <th>CauseTitle</th>
                                                            <th>Registration Number</th>
                                                            <!-- th>Status</th -->
                                                            <th>Filing Date</th>
                                                            <th>Registration Date</th>
                                                            <!-- th>Disposal Date</th>
                                                            <th>Adv Entry Date</th>
                                                            <th>Main <br>or <br>Connected</th -->
                                                            <th>Pleaded for</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                        $s_no=1;
                                                        foreach ($reports as $result)
                                                        {
                                                            ?>
                                                            <tr>
                                                                <td><?=$s_no;?></td>
                                                                <td><?=$result['diary'];?></td>
                                                                <td><?=$result['title'];?></td>
                                                                <td><?=$result['registration_number_display'];?></td>
                                                                <td><?php echo date('d-m-Y',strtotime($result['filing_date']));?></td>
                                                                <td> <?php if($result['reg_date'] !='0') { ?><?php echo date('d-m-Y',strtotime($result['reg_date']));?> <?php } ?></td>
                                                                <td><?=$result['advocate_type'];?></td>
                                                            </tr>
                                                            <?php
                                                            $s_no++;
                                                        }   //for each
                                                        ?>
                                                        </tbody>
                                                    </table>
                                                <?php
                                                }?>

                                            </div>
                                        <?PHP
                                        }
                                        ?>
                                                
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>
<script src="<?= base_url('/Ajaxcalls/menu_assign/menu_assign.js') ?>"></script>
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
