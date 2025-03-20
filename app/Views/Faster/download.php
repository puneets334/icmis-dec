<?php
?>
<section class="content " >
    <div class="row">
        <div class="col-sm-3"><h3 class="head text-left" style="padding: 0 !important; margin: 0 !important;">Download</h3></div>
        <div class="col-sm-9 form-grup">
            <label for="dated" class="col-sm-2 control-label text-primary">Dated: <?=convertTodmY($_SESSION['nextDate'])?></label>
            <label for="diaryNumber" class="col-sm-4 control-label text-primary"><?=$_SESSION['caseNumber']?></label>
            <label for="causeTitle" class="col-sm-6 control-label text-primary"><?=$_SESSION['causetitle']?></label>
        </div>
        <?php if(isset($_SESSION['warning_message']) && !empty($_SESSION['warning_message'])){ ?>
            <div class="col-sm-8">
                <div class="alert alert-warning" role="alert">
                    <?=$_SESSION['warning_message']?>
                </div>
            </div>
            <?php
            $_SESSION['warning_message']="";
            //unset($_SESSION['warning_message']);
        } ?>
    </div>
    <hr>
    <!-------------Result Section ------------>
    <form id="frmDownload" method="post" enctype="multipart/form-data" action="<?= base_url() ?>index.php/FasterController/downloadAll" target="_blank">
    <div class="form-group col-sm-6">
        <label><span >Download all attached Digitally Signed and Certified file in a ZIP file </span></label>
        <button type="button" id="btnShowPDF" class="btn btn-default form-cntrol" onclick="downloadAll();"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span>  Download</button>
    </div>
        <div class="form-group col-sm-6">
            <h4>History</h4>
            <hr>
            <table id="tbl_history" class="table table-striped table-bordered">
                <thead>
                <th>Sl.No.</th>
                <th>Userdetail</th>
                <th>Transaction Datetime</th>
                <th>From IP</th>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </form>
</section>
<script type="text/javascript">
    setTimeout(function(){ showtransactions(<?=DOWNLOAD?>); }, 100);
</script>
