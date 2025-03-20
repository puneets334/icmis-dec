<hr/>
<div class="row">
    <div class="col-sm-4">
        <div class="form-group row">
            <label  class="col-sm-5 col-form-label">Judgement Date :</label>
            <div class="col-sm-7"><span id="sp_judgement_dt"><?=$lct_dec_dt['lct_dec_dt'];?></span></div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group row">
            <label  class="col-sm-5 col-form-label">Sentence imposed :</label>
            <div class="col-sm-7">
                <select name="m_sent2" id="m_sent2" class="form-control">
                    <option value="">Select Year</option>
                    <?php for ($index = 1; $index <= 20; $index++) {  ?>
                        <option value="<?php echo $index; ?>" <?php if(!empty($get_details) && $get_details['sentence_yr']==$index) { ?> selected="selected" <?php } ?>><?php echo $index; ?> Year</option>
                        <?php } ?>
                    <option value="99" <?php if(!empty($get_details) && $get_details['sentence_yr']==99) { ?> selected="selected" <?php } ?>>Life Imprisonment</option>
                    <option value="100" <?php if(!empty($get_details) && $get_details['sentence_yr']==100) { ?> selected="selected" <?php } ?>>Death Sentence</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group row">
            <label  class="col-sm-5 col-form-label">Select Month :</label>
            <div class="col-sm-7">
                <select name="m_sent2_mon" id="m_sent2_mon" class="form-control">
                    <option value="">Select Month</option>
                    <?php for ($index = 0; $index <= 11; $index++) { ?>
                        <option value="<?php echo $index; ?>" <?php if(!empty($get_details) && $get_details['sentence_mth']==$index) { ?> selected="selected" <?php } ?>><?php echo $index; ?> Month</option>
                    <?php } ?>
                </select>

            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group row">
            <label  class="col-sm-5 col-form-label">Current Status :</label>
            <div class="col-sm-7">
                <select name="m_status" id="m_status" class="form-control" onchange="check(this.value)">
                    <option value="">Select</option>
                    <option value="U" >U-Under Trial</option>
                    <option value="C" >C-Custody</option>
                    <option value="B" >B-Bail Out</option>
                    <option value="A" >A-Absconding</option>
                    <option value="P" >P-Parole</option>
                    <option value="F" >F-Furlough</option>
                    <option value="M" >M-Approximation</option>
                    <option value="O" >O-Others</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group row">
            <label  class="col-sm-5 col-form-label">From Date :</label>
            <div class="col-sm-7">
                <input type="date" name="txt_frm_dt" id="txt_frm_dt" class="form-control" size="9" maxlength="10" value="<?php if($res_max_to_dt!=''){ echo $res_max_to_dt;}?>"/>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="form-group row">
            <label  class="col-sm-5 col-form-label">To Date :</label>
            <div class="col-sm-7">
                <input type="date" name="txt_to_dt" id="txt_to_dt" class="form-control" size="9" maxlength="10"/>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="form-group row">
            <label  class="col-sm-1 col-form-label">remarks :</label>
            <div class="col-sm-7">
                <textarea type="text" name="remarks" id="remarks" size="10" class="form-control" placeholder="Enter remarks" style="margin-left: 8%;width: 90%;"></textarea>
            </div>
        </div>
    </div>
 </div>

<br/>

<center><div class="btn btn-success" onclick="btn_add();">Add</div></center>
