<?php
    if(!empty($result)){ $row = $result[0];?>
        <table class="tblmid" style="margin-left: auto;margin-right: auto;" border="1" cellspacing="2" cellpadding="4">
            <tr><td><b>Document No</b></td>
                <td><input type="text" id="docnum" value="<?php echo $row['docnum']; ?>" size="5" maxlength="7" disabled/> /
                    <input type="text" id="docyr" value="<?php echo $row['docyear']; ?>" size="4" maxlength="4" disabled/>
                    <?php echo ' - '.$row['kntgrp'];//$row['docnum'].'/'.$row['docyear'].' - '.$row['kntgrp']; ?></td></tr>
            <tr><td><b>Description</b></td>
                <td>
                    <select name="m_doc" id="m_doc" disabled>
                        <option value="0">Select</option>
                        <?php
                        foreach ($docmaster as $row1){ ?>
                            <option value="<?php echo $row1['doccode']; ?>" <?php if($row1['doccode']==$row['doccode']) echo "Selected";?>>
                                <?php echo $row1['doccode'].' - '.$row1['docdesc']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <input type="text" id="m_doc1" name="m_doc1" style="width: 450px" value="<?php echo $row['docdesc']; ?> "
                        <?php if($row['doccode1']!=8) echo "disabled";?> disabled/>
                    <input type="hidden" id="hd_doc_type1" value="<?php echo $row['doccode1']; ?>" disabled/>
                    <br>
                    Description
                    <input type="text" size="50" name="m_desc" id="m_desc" maxlength="50" onBlur="replace_amp(this.id)" value="<?php echo $row['other1'];?>"
                        <?php if($row['doccode1']!=19) echo "disabled";?> />
                    </td>
            </tr>
            <tr><td><b>Remark</b></td><td><input type="text" value="<?php echo $row['remark']; ?>" id="remark_ld" maxlength="50" onBlur="replace_amp(this.id)" disabled/></td></tr>
            <tr><td><b>No of Copies</b></td><td><input type="text" value="<?php echo $row['no_of_copy']; ?>" id="noc" size="4" disabled/></td></tr>
            <tr><td><b>Filed By</b></td><td><input type="text" value="<?php echo $row['filedby']; ?>" id="fb" maxlength="50" disabled/></td></tr>
            <tr><td><b>Fee</b></td><td><input type="text" value="<?php echo $row['docfee']; ?>" id="df" size="5" disabled/></td></tr>
            <tr><td><b>Party / For Res</b></td>
                <td><?php
                    if($row['doccode'] == 7){
                        ?>
                        <input type="text" value="<?php echo $row['forresp']; ?>" id="fr" disabled/>
                        <?php
                    }
                    else
                        echo $row['party'];
                    ?></td>
            </tr>
            <tr><td><b>Advocate</b></td><td><input type="text" value="<?php echo $row['aor_code'];?>" id="aor_code" size="4" onblur="getAdvocateAOR()" disabled/>
                    <span id="adv_name"><?php echo $row['advname']; ?></span></td></tr>
            <tr><td><b>Filing Date</b></td><td><?=!empty($row['ent_dt']) ? date('d-m-Y h:i:s A',strtotime($row['ent_dt'])):''; ?>
                    &nbsp;
                    New Filing Date <input type="date" size="8" maxlength="10" class="dtp" id="new_filing_date" />
                </td></tr>
            <tr><td><b>Taken By</b></td><td><?php echo $row['entryuser'];//.' on '.date('d-M-Y h:i:s A',  strtotime($row['ent_dt'])); ?></td></tr>
            <tr><td><b>Status</b></td>
                <td><select id="ia_status" disabled><option value="P" <?php if($row['iastat']=='P') echo 'selected';?>>P</option>
                        <option value="D" <?php if($row['iastat']=='D') echo 'selected';?>>D</option></select>
                </td></tr>
            <tr><td colspan="10"><center><input type="button" value="UPDATE" onclick="updateFunct(this.id)" id="<?php echo $docd_id; ?>"/>
                    &nbsp;
                    <input type="button" value="CANCEL" onclick="calcelFunct()" /></b></center>
                </td>
            </tr>
        </table>
<br/>
        <?php
    }
    else{
        echo "SORRY, RECORD NOT FOUND";
    }

