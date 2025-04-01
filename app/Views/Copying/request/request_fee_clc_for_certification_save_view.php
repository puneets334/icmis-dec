<?php 
if($fee_clc_for_certification_no_doc > 0 || $fee_clc_for_uncertification_no_doc > 0){ $submit_type='Modify'; }else{$submit_type= 'Save';};
$nm_s=  exec ('pdftk '.GET_SERVER_IP.'/'.$fee_clc_path. ' dump_data | grep NumberOfPages');
         $total_path_pages = str_replace('NumberOfPages: ','', $nm_s);
?>
<form id="fee_clc_for_certificationForm" name="fee_clc_for_certificationForm" autocomplete="off">
    <div class="form-row align-items-center">

        <input type="hidden" class="form-control " name="fee_clc_request_form_status" id="fee_clc_request_form_status" value="<?php echo $submit_type;?>" readonly>
        <input type="hidden" class="form-control " name="fee_clc_application_id" id="fee_clc_application_id" value="<?php echo $application_id;?>" readonly>
        <input type="hidden" class="form-control " name="fee_clc_request_status" id="fee_clc_request_status" value="<?php echo $fee_clc_request_status;?>" readonly>
        <input type="hidden" class="form-control " name="total_path_pages" id="total_path_pages" value="<?php echo $total_path_pages;?>" readonly>

        <div class="col-6" style="margin-left:15%" >
            <div class="col-auto py-2"><u>Total Pages in uploaded document</u> : <span class="text-success font-weight-bold"><?= $total_path_pages ?></span></div>
            <div class="col-auto" >
                <span>Number of Certification Documents:
                <input type="text" class="form-control mb-2" name="certification_no_doc" id="certification_no_doc"  placeholder="No of Certification Duc" value="<?=$fee_clc_for_certification_no_doc > 0 ? $fee_clc_for_certification_no_doc : 0?>" required  onkeyup="this.value=this.value.replace(/[^0-9]/g,'');">
                </span>
            </div>
            <div class="col-auto" >
                <span>Number of Certification Page:
                <input type="text" class="form-control mb-2" name="certification_pages" id="certification_pages"  placeholder="No of Certification Page" value="<?=$fee_clc_for_certification_pages > 0 ? $fee_clc_for_certification_pages : 0?>" required  onkeyup="this.value=this.value.replace(/[^0-9]/g,'');">
                </span>
            </div>
            <div class="col-auto" >
                <span>Number of Uncertification Documents:
                <input type="text" class="form-control mb-2" name="uncertification_no_doc" id="uncertification_no_doc"  placeholder="no of Certification Duc" value="<?=$fee_clc_for_uncertification_no_doc > 0 ? $fee_clc_for_uncertification_no_doc : 0 ?>" required  onkeyup="this.value=this.value.replace(/[^0-9]/g,'');">
                </span>
            </div>

            <div class="col-auto" >
                <span>Number of Uncertification Pages:
                <input type="text" class="form-control mb-2" name="uncertification_pages" id="uncertification_pages"  placeholder="No of Uncertification Page" value="<?=$fee_clc_for_uncertification_pages > 0 ? $fee_clc_for_uncertification_pages : 0 ?>" required  onkeyup="this.value=this.value.replace(/[^0-9]/g,'');">
                 </span>
            </div>

            <br/>
            <center>
                <div class="col-auto">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success " id="fee_clc_for_certificationSubmit"><?=($fee_clc_for_certification_no_doc > 0 || $fee_clc_for_uncertification_no_doc > 0) ? 'Modify' : 'Save'?></button>
                </div>
            </center>
            <br/>


                </div>
            </div>

        </form>