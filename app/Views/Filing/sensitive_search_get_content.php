<?php
$diary_details=$case_title=$user_section=$reg_no_display=$reason=$case_info='';
if (!empty($case_detail)) {
    $diary_details = $case_detail['diary_no'] . '/' . $case_detail['diary_year'] . ' filed on:' . $case_detail['diary_date'];
    $case_title=$case_detail['case_title'];
    $case_info=(!empty($case_detail['display']) && $case_detail['display']=='Y') && (!empty($case_detail['reason']) && $case_detail['reason'] !=null) ? $case_detail['reason'] : '';
    //$user_section="<span class='center text-success'><b>".$user_section_data."</b></span>";
    $user_section=$case_detail['user_section'];
    $reg_no_display=$case_detail['reg_no_display'];
    $senstiveCaseInfo=$reason=$case_detail['reason'];
     if ($case_detail['c_status'] =='D'){
    ?>
         <center><span class="text-danger text-center" id="sensitiveStatus"> The searched case is Disposed! You cannot add information</span></center>
<?php }else{ ?>
             <?php if (!empty($senstiveCaseInfo) && $senstiveCaseInfo !=null) { ?>
         <center><p style="color: Red;font-size:20px;font-weight: bold;text-align=center;">Case is Already Added as Sensitive Case On: <?=$case_detail['updated_on'];?>  [Updated By : <?=$case_detail['updated_by'];?>]</p></center>
             <?php } ?>
<div class="row">
    <input type="hidden" class="form-control" id="case_diaryno" name="case_diaryno" value="<?=$case_detail['case_diary'];?>" readonly>
    <div class="col-md-6">
        <div class="form-group row">
            <div class="col-md-12">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Diary Number :</span>
                    </div>

                    <input type="text" class="form-control" id="case_diary" name="case_diary" value="<?=$diary_details;?>" readonly>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group row">
            <div class="col-md-12">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Cause Title :</span>
                    </div>
                    <textarea class= "form-control" rows="1"  id="case_title" name="case_title" readonly><?=$case_title;?></textarea>
                </div>
            </div>
        </div>
    </div>


    <div class="col-md-6">
        <div class="form-group row">
            <div class="col-md-12">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Section : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    </div>
                    <input type="text" class="form-control text-success" id="section" name="section" style="font-size:15px; font-weight:bold;" value="<?=$user_section;?>" readonly>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group row">
            <div class="col-md-12">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Case No.: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    </div>
                    <input type="text" class="form-control" id="caseNo" name="caseNo" value="<?=$reg_no_display;?>" readonly>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group row">
            <div class="col-md-12">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Information : &nbsp;&nbsp;&nbsp;</span>
                    </div>
                    <textarea class= "form-control" rows="3"  name="case_info" id="case_info"><?=$case_info;?></textarea>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-md-5"></div>
    <div class="col-md-3">
        <button type="button" id="btn-update" class="btn bg-olive btn-flat pull-right" onclick="update_case();"><i class="fa fa-save"></i> Update Case </button>
    </div>
</div>
         <!--<center>
         <span class="alert alert-error" style="display: none; color: red;">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        <span class="form-response"> </span>
                                    </span>
             <span id="loader"></span> </center>-->
 <script>

     function update_casewwwww()
     {
         var validateFlag = false;
         var case_diary='<?=$case_detail['case_diary'];?>';
         var case_info=$('#case_info').val();
         alert('case_diary='+case_diary + 'case_info='+case_info)
         if(case_diary.length != 0) {
             if(case_info.length == 0)
             {
                 alert("Enter Case Sensitive Information");
                 $('#case_info').focus();
                 validationError = false;
                 return false;
             }else{
                  validateFlag = true;
                 var CSRF_TOKEN = 'CSRF_TOKEN';
                 var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                 alert('CSRF_TOKEN='+CSRF_TOKEN + 'CSRF_TOKEN_VALUE='+CSRF_TOKEN_VALUE)
                 $.post("<?=base_url('Filing/Sensitive_info/update_case');?>", {
                     CSRF_TOKEN: CSRF_TOKEN_VALUE,
                     case_diary: case_diary,
                     case_info:case_info
                 }, function (result) {
                     updateCSRFToken();
                     if(!alert(result))
                     {
                         //location.reload();
                     }
                 });
             }
         }
         else {
             if (!alert("Enter Case Details"))
             {
                 //$('#btn-restore').prop('disabled', true);
                 // location.reload();
                 $('#case_type').focus();
             }
         }
     }

 </script>

<?php } }else{ ?>
    <center><span class="text-danger text-center">The searched case is not found</span></center>
<?php } ?>