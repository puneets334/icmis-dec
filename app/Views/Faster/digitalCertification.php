<?php
?>
<section class="content " >
    <div class="row">
        <div class="col-sm-3"><h3 class="head text-left" style="padding: 0 !important; margin: 0 !important;">Digital Certification</h3></div>
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
            $_SESSION['warning_message'] = "";
            //unset($_SESSION['warning_message']);
        } ?>
    </div>
    <hr>

    <!-------------Result Section ------------>
    <!--<div class="form-group col-sm-12">
        <label><span style="color: red">Note: Rest of the page will be developed once institutional signature token procured.</span></label>
        <button type="button" id="btnShowPDF" class="btn btn-success form-control" onclick="skipNext();">Skip & Next</button>
    </div>-->
    <div class="col-sm-6">

        <div class="col-sm-12">
            <h4>Attached Document(s)</h4>
            <table id="tblDigiCertify" class="table table-striped">
                <thead>
                <tr>
                    <th>Document Name (Dated)</th>
                    <th>Attached On</th>
                    <th>Action</th>
                </tr></thead><tbody>

                </tbody>
            </table>

        </div>
    </div>
    <div class="rightDiv form-group col-sm-6">
        <div class="form-group col-sm-12" id="actionDigitalCertify" >

        </div>
        <div classs="form-group col-sm-12" id="divShowPdf">

        </div>

    </div>

</section>
<script type="text/javascript">
    setTimeout(function(){ showDocumentsListForCertification(); }, 100);
</script>
