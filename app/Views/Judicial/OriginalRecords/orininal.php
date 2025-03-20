<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Original Records Upload</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap.min.css">
      <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">-->
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/AdminLTE.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datepicker/datepicker3.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/jsAlert/dist/sweetalert.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/Reports.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/datatables/jquery.dataTables.min.css">
</head>
<body>
<div class="container-fluid">
    <h2 class="page-header">Upload Original Records</h2>
    <div class="row">
        <div class="row">
            <?=$this->session->flashdata('msg'); ?>
        </div>
        <form id="frmUploadOriginalRecords" enctype="multipart/form-data" action="<?= base_url() ?>index.php/CourtMasterController/uploadROP" method="post">
            <!-- <form id="frmUploadRop" enctype="multipart/form-data">-->
            <input type="hidden" name="usercode" id="usercode" value="<?php echo $_SESSION["dcmis_user_idd"]; ?>"/>

            <div class="form-group col-sm-12">
                <label for="causelistDate">Search Option :</label>
                <div class="input-group">
                    <label class="radio-inline"><input type="radio" name="optradio" value="1" checked>Case Type</label>
                    <label class="radio-inline"><input type="radio" name="optradio" value="2">Diary No.</label>
                </div>
            </div>

            <div class="form-group col-sm-12">
                <label for="lodgementDate" class="col-sm-2 control-label">Case Type</label>
                <div class="col-sm-2">
                    <select  class="form-control" name="caseType" tabindex="1" id="caseType" required>
                        <option value="">Select</option>
                        <?php
                        foreach($caseTypes as $caseType){
                            echo '<option value="' . $caseType['casecode'] . '">'. $caseType['casename'] .'&nbsp;:&nbsp;' .$caseType['skey']. '</option>';
                        }
                        ?>
                    </select>
                </div>
                <label for="caseNo" class="col-sm-2 control-label">Case No.</label>
                <div class="col-sm-2">
                    <input class="form-control" id="caseNo" name="caseNo" placeholder="Case Number" type="number" maxlength="10" required="required">
                </div>
                <label for="caseYear" class="col-sm-2 control-label">Year</label>
                <div class="col-sm-2">
                    <select class="form-control" id="caseYear" name="caseYear" >
                        <?php
                        for($year=date('Y'); $year>=1950; $year--)
                            echo '<option value="'.$year.'">'.$year.'</option>';
                        ?>
                    </select>
                </div>
                <label for="diaryNumber" class="col-sm-2 control-label">Diary No</label>
                <div class="col-sm-2">
                    <input class="form-control" id="diaryNumber" name="diaryNumber" placeholder="Diary Number" type="number" maxlength="20" required="required">
                </div>

                <label for="diaryYear" class="col-sm-2 control-label">Year</label>
                <div class="col-sm-2">
                    <select class="form-control" id="diaryYear" name="diaryYear" required="required" >
                        <?php
                        for($year=date('Y'); $year>=1950; $year--)
                            echo '<option value="'.$year.'">'.$year.'</option>';
                        ?>
                    </select>
                </div>
            </div>

            <div class="col-sm-2">
                <label>&nbsp;</label>
                <button type="button" id="btnGetCases" class="btn btn-info form-control"
                        onclick="getCasesForUploading();">Get Cases
                </button>
            </div>
            <div id="divCasesForUploading" class="col-sm-12">

            </div>
        </form>
    </div>
</div>
<!-- SlimScroll -->
<script src="<?= base_url() ?>assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap.min.js"></script>
<script>
    $(function () {

        $("#causelistDate").datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });
    });

</script>
<!--<script src="<? /*=base_url()*/ ?>assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>-->

<script src="<?= base_url() ?>assets/plugins/fastclick/fastclick.js"></script>
<script src="<?= base_url() ?>assets/js/app.min.js"></script>
<script src="<?= base_url() ?>assets/js/courtmaster.js"></script>

<script src="<?= base_url() ?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?= base_url() ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript">

    function getCasesForUploading() {
        //alert("1");
        var causelistDate = $('#causelistDate').val();
        var pJudge = $('#pJudge').val();
        var causelistType = $('#causelistType').val();
        var bench = $('#bench').val();
        var usercode=$('#usercode').val();
        if(causelistDate == ""){
            alert("Please Select Causelist Date..");
            $('#causelistDate').focus();
            return false;
        }
        if (causelistDate != "" ){
            //alert("2");
            $.get("<?=base_url()?>index.php/CourtMasterController/getCasesForUploading", {causelistDate: causelistDate,usercode:usercode},function(result){

                //alert(usercode);
                $("#divCasesForUploading").html(result);
                $('#tblCasesForUploading').DataTable({
                    "bSort": false,
                    "bPaginate": false,
                    "bLengthChange": false,
                    "bInfo": false
                } );
            });
        }
    }
</script>
</body>
</html>
