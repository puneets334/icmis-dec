<?php
//var_dump($documentsInICMIS);
?>
<section class="content " >
    <div class="row">
        <div class="col-sm-3"><h3 class="head text-left" style="padding: 0 !important; margin: 0 !important;">Attach Document(s)</h3></div>
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
    <?php
    $usercode = $_SESSION['login']['usercode'];
    if(is_array($caseDetails) ) {

        ?>
        <form id="frmAddDocument" enctype="multipart/form-data" action="<?= base_url() ?>/Faster/FasterController/attachDocument" method="post">
        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" id="csrf_token" />
            <input type="hidden" name="usercode" id="usercode" value="<?=$usercode?>"/>

            <div class="col-sm-6">
                <div class="col-sm-12">
                    <h4 class="text-info">Document(s) available in ICMIS</h4>
                    <table id="tblICMISDocuments" class="table table-striped">
                        <thead>
                        <tr>
                            <th>Sl.No.</th>
                            <th>Document Name</th>
                            <th>Dated</th>
                            <th>Action</th>
                        </tr></thead>
                        <tbody>
                        <?php
                        foreach ($documentsInICMIS as $index=>$document){
                            $slno=$index+1;
                            ?>
                            <tr>
                                <td><?=$slno?></td>
                                <td><?=$document['docname']?></td>
                                <td><?=!empty($document['dated'])?date("d-m-Y", strtotime($document['dated'])):''?></td>
                                <td><button type="button" onclick="showPDF(<?=$document['doctype']?>,'<?=$document['path']?>','<?=$document['dated']?>')"><i class="glyphicon glyphicon-eye-open"></i></button></td>
                            </tr>

                      <?php  }
                        ?>
                        </tbody>
                    </table>

                </div>
                <div class="col-sm-12"><hr/></div>
                <div class="col-sm-4 form-group">
                    <label for="docType">Document Type</label>
                    <select class="form-control" id="docType" name="docType" placeholder="docType" onchange="return getDates(this);" >
                        <option value="0">Select Document Type</option>
                        <?php
                        foreach ($noticeTypes as $noticeType){
                            echo '<option value="'.$noticeType['id'].'">'.$noticeType['name'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-4 form-group" id="divDocDated">
                    <label for="docType">Dated</label>
                    <select class="form-control" id="docDate" name="docDate" placeholder="docDate" >
                    </select>
                    <input type="hidden" id="hiddenDocDate" name="hiddenDocDate">
                </div>
                <div class="form-group col-sm-4">
                    <label>&nbsp;</label>
                    <button type="button" id="btnShowPDF" class="btn btn-primary form-control" onclick="showPDF();">View</button>
                </div>
                <div class="col-sm-12"><hr/></div>
                <div class="col-sm-12" id="divMessage"></div>
                <div class="col-sm-12">
                    <h4>Attached Document(s)</h4>
                    <table id="tblSharedDocuments" class="table table-striped">
                        <thead>
                        <tr>
                            <th>Document Name (Dated)</th>
                            <th>Uploaded On</th>
                            <th>Action</th>
                        </tr></thead>
                        <tbody>
                        </tbody>
                    </table>

                </div>
                <!-- <div class="col-sm-12">
                     <label>Download zip file</label>
                     <button class="btn btn-default form-control"><i class="glyphicon glyphicon-download">Download</button>

                 </div>-->

            </div>
            <div class="form-group col-sm-6">
                <div class="form-group col-sm-12" id="divBtnAttachPdf" style="display: none">
                    <label>&nbsp;</label>
                    <button type="button" id="btnAttachPdf" class="btn btn-success form-control" onclick="return confirmBeforeAdd();">Attach PDF</button>
                </div>
                <div classs="form-group col-sm-12" id="divShowPdf">

                </div>

            </div>

            <br/>

        </form>

    <?php }
    else
    {
        // echo "No data found";
    }
    ?>
</section>
<script>
    setTimeout(function(){ showAttachedDocumentsList(); }, 100);
</script>