<?php if(count($caseList)>0) {
    //var_dump($data);?>
        <input type="hidden" id="cmaDol" value="<?=$cmaDol?>">
        <input type="hidden" id="cmaU" value="<?=$cmaU?>">
        <input type="hidden" id="cmaUn" value="<?=$cmaUn->name?>">
        <input type="hidden" id="cmaUUrl" value="">
        <span class="image-inline-upward"></span>
<?php }?>
<hr/>

<!-- div class="form-group col-sm-2">
    <label for="fileROPList">Select Digitally Signed Files</label>
    <input type="file" name="fileROPList[]" id="fileROPList" multiple> -->

   <!-- <p class="help-block">Example block-level help text here.</p>-->
<!-- </div> -->
<div class="col-md-12">
    <div class="col-md-4 diary_section">
        <div class="row">
            <label for="fileROPList" class="col-sm-6 col-form-label">Select Digitally Signed Files</label>
            <div class="col-sm-6">
                <input type="file" name="fileROPList[]" id="fileROPList" multiple required>
            </div>
        </div>
    </div>

    <div class="col-2 pl-4 mb-3">
        <button type="submit" id="btnUploadROP" class="btn btn-success"><i class="fa fa-fw fa-upload"></i>&nbsp;Upload</button>
    </div>
</div>

<!-- <div class="form-group col-sm-3">
    <label>&nbsp;</label>
<button type="submit" id="btnUploadROP" class="btn btn-success"><i class="fa fa-fw fa-upload"></i>&nbsp;Upload
</button>
</div> -->

<table id="tblCasesForUploading" class="table table-striped table-hover">
    <thead>
    <tr>
        <th width="5%">S.No.</th>
        <th width="5%">Court No</th>
        <th width="5%">Item No</th>
        <th width="25%">Case Number</th>
        <th width="20%">Causetitle</th>
        <th width="10%">Upload Date</th>
        <th width="20%">Uploaded By</th>
        <th width="10%">Uploaded Status</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $s_no=1;
    foreach ($caseList as $case)
    {
        ?>
        <tr>
            <td>
                <?php echo $s_no; ?>
            </td>
            <td>
                <?php echo $case['court_number']; ?>
            </td>
            <td>
                <?php echo $case['item_number']; ?>
            </td>
            <?php
            $diarynumber=$case['diary_no'];
            $diarynumber="DIary No. ".substr($diarynumber, 0, -4)."/".substr($diarynumber, -4);
            ?>

            <td style="word-wrap:break-word !important;">
                <?php echo $diarynumber."<br/>".$case['registration_number_desc'];?>
            </td>
            <td>
                <?php
                echo $case['petitioner_name']."<br/><centre>Vs.</centre><br/> ".$case['respondent_name'];
                ?>
            </td>
            <td>
                <?php
                if($case['upload_date_time']!=null && $case['upload_date_time']!=""){
                    $date=date_create($case['upload_date_time']);
                    echo date_format($date,"d-m-Y H:i:s A");
                }

                ?>
            </td>
            <td>
                <?php
                echo $case['username'];
                ?>
            </td>
            <td>
                <?php
                if($case['upload_flag']==1) {
                    ?>
                    <span class="label label-success">Uploaded</span>
                    <?php
                } else { ?>
                    <span class="label label-warning">Pending</span>
                <?php
                }?>

            </td>

        </tr>
        <?php
        $s_no++;
    }   //for each
    ?>
    </tbody>
</table>
<hr/>

<!--<div class="col-sm-12">
    <button type="submit" id="btnDownloadROPBottom" name="btnDownloadROP" class="btn btn-success pull-left btn-block generateROP" onclick="return generateAndDownloadROP();" ><i class="fa fa-fw fa-download"></i>&nbsp;Generate ROP</button>
</div>-->
<br/>
<br/>



<script src="<?=base_url()?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript">
function uploadROP() {
alert("1");
var causelistDate = $('#causelistDate').val();
var fileROPList = $('#fileROPList').val();
var usercode=$('#usercode').val();
if(causelistDate == ""){
alert("Please Select Causelist Date..");
$('#causelistDate').focus();
return false;
}
if(fileROPList == ""){
alert("Please Select at least one digitally signed file.");
$('#fileROPList').focus();
return false;
}
alert(2);

//if (causelistDate != "" && fileROPList!=null){
//alert("2");
$.post("uploadROP", $("#frmUploadRop").serialize(),function(result){

//alert(usercode);
//$("#divCasesForGeneration").html(result);
alert(result);
});
//}
}
</script>


<!--<script>
    $(function() {
        alert(1);
        if ($('#cmaU')['length'] && $('#cmaUn')['length'] && $('#cmaDol')['length']) {
            alert('123');
            $('.image-inline-upward')['append']('<APPLET CODE="in/nic/sci/courtmaster/UploadApplet.class" ARCHIVE="CMAppletSP.jar,commons-io-2.0.1.jar,commons-lang-2.1.jar,java-json.jar,zxing-core-3.2.1.jar" WIDTH=250 HEIGHT=30><param name="user" value="' + $('#cmaU')['val']() + '"><param name="appletdate" value="' + $('#cmaDol')['val']() + '"><param name="username" value="' + $('#cmaUn')['val']() + '"><param name="uploadUrl" value="' + $('#cmaUUrl')['val']() + '"></APPLET>')
            //$('.image-inline-upward')['append']('<APPLET CODE="in/nic/sci/courtmaster/UploadApplet.class" ARCHIVE="CMAppletSP.jar,commons-io-2.0.1.jar,commons-lang-2.1.jar,java-json.jar,zxing-core-3.2.1.jar" WIDTH=250 HEIGHT=30><param name="user" value="' + $('#cmaU')['val']() + '"><param name="appletdate" value="' + $('#cmaDol')['val']() + '"><param name="username" value="' + $('#cmaUn')['val']() + '"><param name="uploadUrl" value="' + $('#cmaUUrl')['val']() + '"></APPLET>')
        }
    });
</script>-->
<!--</form>-->
