
<?php if (!empty($record)): ?>
    <input type="hidden" value="<?php if(isset($state)) echo $state ?>" id="hd_state">
            <input type="hidden" value="<?php if(isset($enroll)) echo $enroll ?>" id="hd_enr">
            <input type="hidden" value="<?php if(isset($year)) echo $year ?>" id="hd_en_yr">
            <input type="hidden" value="<?php if(isset($aor)) echo $aor ?>" id="hd_aor">

        <div class="tab-content table-responsive">
        <table class="table table-bordered" style="/* border-collapse: collapse; */.: 2px;/* width: auto; */display: inline;" align="center" cellspacing="4" cellpadding="6">
        <!-- <table class="table table-bordered" style="border-collapse: collapse;border-width: 2px;" align="center" cellspacing='4' cellpadding='6' > -->
            <tr> 
                <td><h7>State:<span style="color:red">*</span></h7></td>
                <td>
                    <select id="adv_state" required>
                        <option value="0">Select</option>    
                        <?php foreach ($state_name as $state): ?>
                            <option value="<?= esc($state['id_no']) ?>" <?= $state['id_no'] == $record['state_id'] ? 'selected' : '' ?>>
                                <?= esc($state['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td><h7>AOR/NAOR:<span style="color:red">*</span></h7></td>
                <td>
                    <select style="width: 163px" id='adv_aor' disabled >
                        <option value="">Select</option>
                        <option value="Y" <?= $record['if_aor'] == 'Y' ? 'selected' : '' ?>>AOR</option>
                        <option value="N" <?= $record['if_aor'] == 'N' ? 'selected' : '' ?>>NAOR</option>
                    </select>
                </td>
            </tr>
            <tr id="row-aor-code" style="<?= $record['if_aor'] == 'Y' ? 'display:table-row' : 'display:none' ?>">
                <td><h7>AOR Code:<span style="color:red">*</span></h7></td>
                <td><input type="text" maxlength="5" id="adv_aor_code" value="<?= esc($record['aor_code']) ?>" disabled /></td>
            </tr>
            <tr>
                <td><h7>Enrollment No.<span style="color:red">*</span>:</h7></td>
                <td><input type="text" id="enrollment_no" value="<?= esc($record['enroll_no']) ?>" maxlength="20" required/></td>
                <td><h7>Enrollment Date:<span style="color:red">*</span></h7></td>
                <td>
                    <input type='text' id="enrollment_date" name="enrollment_date" onkeypress="return onlynumbersadv(event)" 
                    onkeyup="checkDate(this.value,this.id)" maxlength="10" placeholder="DD-MM-YYYY" 
                    value="<?= $record['enroll_date'] != '0000-00-00' ? date('d-m-Y', strtotime($record['enroll_date'])) : '' ?>" required/>
                </td>
            </tr>
            <tr>
            <td><h7>Title:<span style="color:red">*</span></h7></td>
            <td><select id="adv_title" style="width: 163px;" required><option value='0'>Select</option>
                    <option value='Mr.' <?php if($record['title']=='Mr.') echo "Selected"; ?>>Mr.</option>
                    <option value='Mrs.' <?php if($record['title']=='Mrs.') echo "Selected"; ?>>Mrs.</option>
                    <option value='Miss' <?php if($record['title']=='Miss') echo "Selected"; ?>>Miss</option>
                    <option value='M/S' <?php if($record['title']=='M/S') echo "Selected"; ?>>M/S</option>
                   <option value='DR' <?php if($record['title']=='DR') echo "Selected"; ?>>DR</option></select>
            </td>
            <td><h7>Name:<span style="color:red">*</span></h7></td>
            <td><input type="text" id="adv_name" size="20" onkeyup="upper(this.id)" onkeypress="return onlyalpha(event)" value="<?php echo $record['name'];?>" required/></td>
         </tr>
        <tr><td><h7>Father's/Husband's Name:</h7></td>
            <td><input type="text" id="adv_f_h_name" size="20" onkeyup="upper(this.id)" onkeypress="return onlyalpha(event)" value="<?php echo $record['fname'];?>" max="30"/></td>
            <td><h7>Relation:</h7></td>
            <td><select id="adv_relation" style="width: 163px;"><option value='0'>Select</option>
                    <option value='F' <?php if($record['rel']=='F') echo "Selected"; ?>>Father</option>
                    <option value='H' <?php if($record['rel']=='H') echo "Selected"; ?>>Husband</option></select>
            </td>
        </tr>
        <tr><td><h7>Mother's Name:</h7></td>
            <td><input type="text" id="adv_m_name" size="20" onkeyup="upper(this.id)" onkeypress="return onlyalpha(event)" value="<?php echo $record['mname'];?>" max="30"/></td>
            <td><h7>Gender:</h7></td>
            <td><select id="adv_sex" style="width: 163px;"><option value='0'>Select</option>
                    <option value='M' <?php if($record['sex']=='M') echo "Selected"; ?>>Male</option>
                    <option value='F' <?php if($record['sex']=='F') echo "Selected"; ?>>Female</option>
                </select>
            </td>
        </tr>
        <tr>
            <td><h7>Cast:</h7></td>
            <td><select id="adv_cast" style="width: 163px;"><option value='0'>Select</option>
                    <option value='GEN' <?php if($record['cast']=='GEN') echo "Selected"; ?>>GEN</option>
                    <option value='OBC' <?php if($record['cast']=='OBC') echo "Selected"; ?>>OBC</option>
                    <option value='ST' <?php if($record['cast']=='ST') echo "Selected"; ?>>ST</option>
                    <option value='SC' <?php if($record['cast']=='SC') echo "Selected"; ?>>SC</option>
                </select>
            </td>
            <td><h7>Passing Year <span style="color:red">*</span></h7></td>
            <td><input type='text' size='20' maxlength="4" id="adv_year" onkeypress="return onlynumbers(event)" value="<?php echo $record['passing_year'];?>" maxlength="4" required/></td>
        </tr>
        <tr>
            <td><h7>Date of Birth:</h7></td>
            <td><input type='text' id="adv_dob" name="adv_dob" onkeypress="return onlynumbersadv(event)" 
            onkeyup="checkDate(this.value,this.id)" maxlength="10" placeholder="DD-MM-YYYY" 
            value="<?php 
            if(!empty($record['dob']))
            echo date('d-m-Y',strtotime($record['dob']));?>"/></td>
            <td><h7>Practice City:<span style="color:red">*</span></h7></td>
            <td><input type='text' size='20' id="adv_p_p" onkeyup="upper(this.id)" value="<?php echo $record['pp'];?>" max="30" required/></td>
        </tr>
        <tr>
            <td><h7>Address:</h7></td>
            <td><input type='text' size='20' id="adv_address" onkeyup="upper(this.id)" value="<?php echo $record['caddress'];?>" max="30" required/></td>

            <td>City:</h7></td>
            <td><input type='text' size='20' id="adv_city" onkeyup="upper(this.id)" value="<?php echo $record['ccity'];?>" max="30" required/></td>
        </tr>
        <tr>
            <td><h7>Mobile No.<span style="color:red">*</span>:</h7></td>
            <td><input type='text' size='20' id="adv_mob" maxlength="10" value="<?php echo $record['mobile'];?>" max="10" required/></td>

            <td><h7>Email ID:</h7></td>
            <td><input type='text' size='20' id="adv_email" value="<?php echo $record['email'];?>" max="50"/></td>
        </tr>

            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>
                <div class="row" style="float: inline-end;">
                <div class="col">
                    <input type="button" value="Update" id='updateadvocate'/>
                    <input type="reset" value="Reset" onclick="window.location.reload()"/>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
<?php else: ?>
    <div class="sorry">SORRY, NO RECORD FOUND!!!</div>
<?php endif; ?>

<script>
    $(document).ready(function() {
        $('input[required], select[required], textarea[required]').each(function() {
            var id = $(this).attr('id');
            if (id) {
                $('label[for="' + id + '"]').addClass('required-field');
            }
        });
    });
</script>